<?php

namespace GitHub;

class GitHubParserHook {

	public function render() {
		$egGitwebRoot = 'https://gerrit.wikimedia.org/r/gitweb';
		$egGitwebFile = 'README';
		$egGitwebRepo = 'mediawiki/core';
		$egGitwebCacheTtl = 60;

		$wgHooks['ParserFirstCallInit'][] = function( \Parser &$parser ) {
			$parser->setFunctionHook( 'gitweb', function( \Parser $parser, $file = '', $repo = '' ) {
				$sourceUrl = str_replace(
					array( '$1', '$2' ),
					array(
						$repo === '' ? $GLOBALS['egGitwebRepo'] : $repo,
						$file === '' ? $GLOBALS['egGitwebFile'] : $file,
					),
					$GLOBALS['egGitwebRoot'] . '?p=$1.git;a=blob_plain;f=$2;hb=HEAD'
				);

				$cacheKey = wfMemcKey( __METHOD__, $sourceUrl );
				$cachedValue = wfGetMainCache()->get( $cacheKey );

				if ( is_string( $cachedValue ) ) {
					return $cachedValue;
				}
				else {
					$fetchedValue = \Http::get( $sourceUrl );
					wfGetMainCache()->set( $cacheKey, $fetchedValue, $GLOBALS['egGitwebCacheTtl'] );
					return $fetchedValue;
				}
			} );
			return true;
		};
	}

}
