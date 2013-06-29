<?php

namespace GitHub;

class GitHubParserHook {

	protected $fileFetcher;

	public function __construct( FileFetcher $fileFetcher ) {
		$this->fileFetcher = $fileFetcher;
	}

	public function render( \Parser $parser, $file = '', $repo = '' ) {
		$egGitwebRoot = 'https://gerrit.wikimedia.org/r/gitweb';
		$egGitwebFile = 'README';
		$egGitwebRepo = 'mediawiki/core';
		$egGitwebCacheTtl = 60;

		$fileUrl = str_replace(
			array( '$1', '$2' ),
			array(
				$repo === '' ? $GLOBALS['egGitwebRepo'] : $repo,
				$file === '' ? $GLOBALS['egGitwebFile'] : $file,
			),
			$GLOBALS['egGitwebRoot'] . '?p=$1.git;a=blob_plain;f=$2;hb=HEAD'
		);

		return $this->fileFetcher->fetchFile( $fileUrl );
	}

}
