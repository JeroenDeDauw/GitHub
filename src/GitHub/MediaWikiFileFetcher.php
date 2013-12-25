<?php

namespace GitHub;

use FileFetcher\FileFetcher;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MediaWikiFileFetcher implements FileFetcher {

	public function fetchFile( $fileUrl ) {
		return \Http::get( $fileUrl );
	}

}
