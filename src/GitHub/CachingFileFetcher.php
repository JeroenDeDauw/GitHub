<?php

namespace GitHub;

use SimpleCache\Cache\Cache;

class CachingFileFetcher implements FileFetcher {

	protected $fileFetcher;
	protected $cache;

	public function __construct( FileFetcher $fileFetcher, Cache $cache ) {
		$this->fileFetcher = $fileFetcher;
		$this->cache = $cache;
	}

	public function fetchFile( $fileUrl ) {
		$fileContents = $this->cache->get( $fileUrl );

		if ( $fileContents === null ) {
			return $this->retrieveAndCacheFile( $fileUrl );
		}

		return $fileContents;
	}

	protected function retrieveAndCacheFile( $fileUrl ) {
		$fileContents = $this->fileFetcher->fetchFile( $fileUrl );

		$this->cache->set( $fileUrl, $fileContents );

		return $fileContents;
	}

}
