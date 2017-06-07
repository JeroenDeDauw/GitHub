<?php

namespace GitHub;

use FileFetcher\FileFetcher;
use FileFetcher\FileFetchingException;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GitHubFetcher {

	private $fileFetcher;
	private $gitHubUrl;
	private $repositoryWhitelist;

	/**
	 * @param FileFetcher $fileFetcher
	 * @param string $gitHubUrl
	 * @param string[] $repositoryWhitelist Empty for no restrictions
	 */
	public function __construct( FileFetcher $fileFetcher, string $gitHubUrl, array $repositoryWhitelist ) {
		$this->fileFetcher = $fileFetcher;
		$this->gitHubUrl = $gitHubUrl;
		$this->repositoryWhitelist = $repositoryWhitelist;
	}

	public function getFileContent( string $repoName, string $branchName, string $fileName ): string {
		if ( !$this->repoIsAllowed( $repoName ) ) {
			return '';
		}

		$url = $this->getFileUrl( $repoName, $branchName, $fileName );

		try {
			return $this->fileFetcher->fetchFile( $url );
		}
		catch ( FileFetchingException $ex ) {
			return '';
		}
	}

	private function repoIsAllowed( string $repoName ): bool {
		return $this->repositoryWhitelist === []
			|| in_array( $repoName, $this->repositoryWhitelist );
	}

	private function getFileUrl( string $repoName, string $branchName, string $fileName ): string {
		return sprintf(
			'%s/%s/%s/%s',
			$this->gitHubUrl,
			$repoName,
			$branchName,
			$fileName
		);
	}

}
