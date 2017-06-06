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

	public function __construct( FileFetcher $fileFetcher, string $gitHubUrl ) {
		$this->fileFetcher = $fileFetcher;
		$this->gitHubUrl = $gitHubUrl;
	}

	public function getFileContent( string $repoName, string $branchName, string $fileName ): string {
		$url = $this->getFileUrl( $repoName, $branchName, $fileName );

		try {
			return $this->fileFetcher->fetchFile( $url );
		}
		catch ( FileFetchingException $ex ) {
			return '';
		}
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
