<?php

namespace GitHub;

/**
 * @file
 * @since 0.1
 * @ingroup GitHub
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GitHubParserHook {

	protected $fileFetcher;
	protected $gitHubUrl;
	protected $defaultGitHubRepo;

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

	public function render( $fileName = '', $repoName = '', $branchName = '' ) {
		$fileUrl = $this->getFileUrl( $fileName, $repoName, $branchName );
		return $this->fileFetcher->fetchFile( $fileUrl );
	}

	public function renderWithParser( \Parser $parser, $fileName = '', $repoName = '', $branchName = '' ) {
		return $this->render( $fileName, $repoName, $branchName );
	}

	protected function getFileUrl( $fileName, $repoName, $branchName ) {
		return sprintf(
			'%s/%s/%s/%s',
			$this->gitHubUrl,
			$repoName === '' ? $this->defaultGitHubRepo : $repoName,
			$branchName === '' ? 'master' : $branchName,
			$fileName === '' ? 'README.md' : $fileName
		);
	}

}
