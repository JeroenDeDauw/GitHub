<?php

namespace GitHub;

use FileFetcher\FileFetcher;
use FileFetcher\FileFetchingException;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MediaWikiFileFetcher implements FileFetcher {

	public function fetchFile( string $fileUrl ): string {
		$result = \Http::get( $fileUrl );

		if ( !is_string( $result ) ) {
			throw new FileFetchingException( $fileUrl );
		}

		return $result;
	}

}
