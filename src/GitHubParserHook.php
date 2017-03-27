<?php

namespace GitHub;

use ExtensionRegistry;
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
	private $lang;
	private $line;
	private $start;
	private $highlight;
	private $inline;

	private static $syntaxHighlightFields = array('lang', 'line', 'start', 'highlight', 'inline');

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

		return $this->getRenderedContent($parser);
	}

	private function setFields( ProcessingResult $result ) {
		$params = $result->getParameters();

		$this->fileName = $params['file']->getValue();
		$this->repoName = $params['repo']->getValue();
		$this->branchName = $params['branch']->getValue();

		foreach ( self::$syntaxHighlightFields as $val ) {
			if ( isset( $params[$val] ) ) {
				$this->$val = $this->cleanField( $params[$val]->getValue() );
			}
			else {
				$this->$val = null;
			}
		}
	}

	private function cleanField( $val ) {
		if ( $val !== null ) {
			$val = trim( $val, "'\"" );
		}
		return $val;
	}

	private function getRenderedContent(Parser $parser) {
		$content = $this->getFileContent();

		if ( $this->isMarkdownFile() ) {
			$content = $this->renderAsMarkdown( $content );
		}
		else if ($this->lang !== "") {
			if ( ExtensionRegistry::getInstance()->isLoaded( 'SyntaxHighlight' ) ) {
				// Use SyntaxHighlight specifically
				$tag = "syntaxhighlight";
			}
			else {
				// Some other extensions also watch for this
				$tag = "source";
			}

			$syntax_highlight = "<$tag lang=\"". $this->lang ."\"";
			$syntax_highlight .= " start=\"". $this->start ."\"";

			if ( $this->line !== null ) {
				$syntax_highlight .= " line";
			}

			if ( $this->highlight !== "" ) {
				$syntax_highlight .= " highlight=\"". $this->highlight ."\"";
			}

			if ( $this->inline !== null ) {
				$syntax_highlight .= " inline";
			}

			$syntax_highlight .= ">$content</$tag>";
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
