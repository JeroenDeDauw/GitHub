<?php

namespace GitHub;

use dflydev\markdown\MarkdownExtraParser;
use FileFetcher\FileFetcher;
use ParamProcessor\ProcessingResult;
use Parser;
use ParserHooks\HookHandler;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GitHubParserHook implements HookHandler {

	protected $fileFetcher;
	protected $gitHubUrl;

	protected $fileName;
	protected $repoName;
	protected $branchName;

	/**
	 * @param FileFetcher $fileFetcher
	 * @param string $gitHubUrl
	 */
	public function __construct( FileFetcher $fileFetcher, $gitHubUrl = 'https://raw.githubusercontent.com' ) {
		$this->fileFetcher = $fileFetcher;
		$this->gitHubUrl = $gitHubUrl;
	}

	public function handle( Parser $parser, ProcessingResult $result ) {
		$this->setFields( $result );

		return $this->getRenderedContent();
	}

	protected function setFields( ProcessingResult $result ) {
		$params = $result->getParameters();

		$this->fileName = $params['file']->getValue();
		$this->repoName = $params['repo']->getValue();
		$this->branchName = $params['branch']->getValue();
	}

	protected function getRenderedContent() {
		$content = $this->getFileContent();

		if ( $this->isMarkdownFile() ) {
			$content = $this->renderAsMarkdown( $content );
		}

		return $content;
	}

	protected function getFileContent() {
		return $this->fileFetcher->fetchFile( $this->getFileUrl() );
	}

	protected function getFileUrl() {
		return sprintf(
			'%s/%s/%s/%s',
			$this->gitHubUrl,
			$this->repoName,
			$this->branchName,
			$this->fileName
		);
	}

	protected function isMarkdownFile() {
		return $this->fileHasExtension( 'md' ) || $this->fileHasExtension( 'markdown' );
	}

	protected function fileHasExtension( $extension ) {
		$fullExtension = '.' . $extension;
		return substr( $this->fileName, -strlen( $fullExtension ) ) === $fullExtension;
	}

	protected function renderAsMarkdown( $content ) {
		$markdownParser = new MarkdownExtraParser();
		return $markdownParser->transform( $content );
	}

}
