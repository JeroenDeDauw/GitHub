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
class MediaWikiFileFetcher implements FileFetcher {

	public function fetchFile( $fileUrl ) {
		return \Http::get( $fileUrl );
	}

}
