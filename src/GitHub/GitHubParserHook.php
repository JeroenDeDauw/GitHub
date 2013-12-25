<?php

namespace GitHub;

use dflydev\markdown\MarkdownExtraParser;
use FileFetcher\FileFetcher;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GitHubParserHook {

	protected $fileFetcher;
	protected $gitHubUrl;
	protected $defaultGitHubRepo;

	protected $fileName;
	protected $repoName;
	protected $branchName;

	/**
	 * @param FileFetcher $fileFetcher
	 * @param string $defaultGitHubRepo
	 * @param string $gitHubUrl
	 */
	public function __construct( FileFetcher $fileFetcher, $defaultGitHubRepo, $gitHubUrl = 'https://raw.github.com' ) {
		$this->fileFetcher = $fileFetcher;
		$this->gitHubUrl = $gitHubUrl;
		$this->defaultGitHubRepo = $defaultGitHubRepo;
	}

	public function renderWithParser( \Parser $parser, $fileName = '', $repoName = '', $branchName = '' ) {
		return $this->render( $fileName, $repoName, $branchName );
	}

	public function render( $fileName = '', $repoName = '', $branchName = '' ) {
		$this->fileName = $fileName === '' ? 'README.md' : $fileName;
		$this->branchName = $branchName === '' ? 'master' : $branchName;
		$this->repoName = $repoName === '' ? $this->defaultGitHubRepo : $repoName;

		return $this->getTransformedContent();
	}

	protected function getTransformedContent() {
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
