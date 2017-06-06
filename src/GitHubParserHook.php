<?php

namespace GitHub;

use FileFetcher\FileFetcher;
use FileFetcher\FileFetchingException;
use Michelf\Markdown;
use ParamProcessor\ProcessingResult;
use Parser;
use ParserHooks\HookHandler;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GitHubParserHook implements HookHandler {

	private $gitHubFetcher;

	/**
	 * @var Parser
	 */
	private $parser;

	// Parameters for SyntaxHighlight extension (formerly SyntaxHighlight_GeSHi)
	// https://www.mediawiki.org/wiki/Extension:SyntaxHighlight
	private $syntaxHighlightLanguage;
	private $syntaxHighlightEnableLineNumbers;
	private $syntaxHighlightStartingLineNumber;
	private $syntaxHighlightHighlightedLines;
	private $syntaxHighlightInlineSource;

	public function __construct( GitHubFetcher $gitHubFetcher ) {
		$this->gitHubFetcher = $gitHubFetcher;
	}

	public function handle( Parser $parser, ProcessingResult $result ): string {
		$this->parser = $parser;

		$params = $result->getParameters();
		$this->setFields( $params );

		$content = $this->gitHubFetcher->getFileContent(
			$params['repo']->getValue(),
			$params['branch']->getValue(),
			$params['file']->getValue()
		);

		return $this->getRenderedContent( $content, $params['file']->getValue() );
	}

	private function setFields( array $params ) {
		$this->syntaxHighlightLanguage = $params['lang']->getValue();
		$this->syntaxHighlightEnableLineNumbers = $params['line']->getValue();
		$this->syntaxHighlightStartingLineNumber = $params['start']->getValue();
		$this->syntaxHighlightHighlightedLines = $params['highlight']->getValue();
		$this->syntaxHighlightInlineSource = $params['inline']->getValue();
	}

	private function getRenderedContent( string $content, string $fileName ): string {
		if ( $this->syntaxHighlightLanguage === '' ) {
			return ( new ContentPurifier() )
				->purify( $this->getRenderedNonSyntaxContent( $content, $fileName ) );
		}

		return $this->getRenderedSyntaxContent( $content );
	}

	private function getRenderedNonSyntaxContent( string $content, string $fileName ): string {
		if ( $this->isMarkdownFile( $fileName ) ) {
			return $this->renderAsMarkdown( $content );
		}

		return $content;
	}

	private function getRenderedSyntaxContent( string $content ): string {
		$syntax_highlight = "<syntaxhighlight lang=\"". $this->syntaxHighlightLanguage ."\"";
		$syntax_highlight .= " start=\"". $this->syntaxHighlightStartingLineNumber ."\"";

		if ( $this->syntaxHighlightEnableLineNumbers === true ) {
			$syntax_highlight .= " line";
		}

		if ( $this->syntaxHighlightHighlightedLines !== "" ) {
			$syntax_highlight .= " highlight=\"". $this->syntaxHighlightHighlightedLines ."\"";
		}

		if ( $this->syntaxHighlightInlineSource === true ) {
			$syntax_highlight .= " inline";
		}

		$syntax_highlight .= ">$content</syntaxhighlight>";
		$parsed = $this->parser->recursiveTagParse( $syntax_highlight, null );

		if ( is_string( $parsed ) ) {
			return $parsed;
		}

		return '';
	}

	private function isMarkdownFile( string $fileName ): bool {
		return $this->fileHasExtension( $fileName, 'md' )
			   || $this->fileHasExtension( $fileName,'markdown' );
	}

	private function fileHasExtension( string $fileName, string $extension ): bool {
		$fullExtension = '.' . $extension;
		return substr( $fileName, -strlen( $fullExtension ) ) === $fullExtension;
	}

	private function renderAsMarkdown( string $content ): string {
		return Markdown::defaultTransform( $content );
	}

}
