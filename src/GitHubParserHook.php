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

	private $fileFetcher;
	private $gitHubUrl;

	private $fileName;
	private $repoName;
	private $branchName;

	// Parameters for SyntaxHighlight extension (formerly SyntaxHighlight_GeSHi)
	// https://www.mediawiki.org/wiki/Extension:SyntaxHighlight
	private $syntaxHighlightLanguage;
	private $syntaxHighlightEnableLineNumbers;
	private $syntaxHighlightStartingLineNumber;
	private $syntaxHighlightHighlightedLines;
	private $syntaxHighlightInlineSource;

	/**
	 * @param FileFetcher $fileFetcher
	 * @param string $gitHubUrl
	 */
	public function __construct( FileFetcher $fileFetcher, $gitHubUrl ) {
		$this->fileFetcher = $fileFetcher;
		$this->gitHubUrl = $gitHubUrl;
	}

	public function handle( Parser $parser, ProcessingResult $result ) {
		$this->setFields( $result );

		return $this->getRenderedContent( $parser );
	}

	private function setFields( ProcessingResult $result ) {
		$params = $result->getParameters();

		$this->fileName = $params['file']->getValue();
		$this->repoName = $params['repo']->getValue();
		$this->branchName = $params['branch']->getValue();

		$this->syntaxHighlightLanguage = $params['lang']->getValue();
		$this->syntaxHighlightEnableLineNumbers = $params['line']->getValue();
		$this->syntaxHighlightStartingLineNumber = $params['start']->getValue();
		$this->syntaxHighlightHighlightedLines = $params['highlight']->getValue();
		$this->syntaxHighlightInlineSource = $params['inline']->getValue();
	}

	private function getRenderedContent( Parser $parser ) {
		$content = $this->getFileContent();

		if ( $this->isMarkdownFile() ) {
			$content = $this->renderAsMarkdown( $content );
		}
		else if ( $this->syntaxHighlightLanguage !== "" ) {
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
			$content = $parser->recursiveTagParse( $syntax_highlight, null );
		}

		return $content;
	}

	private function getFileContent() {
		try {
			return $this->fileFetcher->fetchFile( $this->getFileUrl() );
		}
		catch ( FileFetchingException $ex ) {
			return '';
		}
	}

	private function getFileUrl() {
		return sprintf(
			'%s/%s/%s/%s',
			$this->gitHubUrl,
			$this->repoName,
			$this->branchName,
			$this->fileName
		);
	}

	private function isMarkdownFile() {
		return $this->fileHasExtension( 'md' ) || $this->fileHasExtension( 'markdown' );
	}

	private function fileHasExtension( $extension ) {
		$fullExtension = '.' . $extension;
		return substr( $this->fileName, -strlen( $fullExtension ) ) === $fullExtension;
	}

	private function renderAsMarkdown( $content ) {
		return Markdown::defaultTransform( $content );
	}

}
