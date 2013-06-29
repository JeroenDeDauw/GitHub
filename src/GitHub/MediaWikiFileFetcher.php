<?php

namespace GitHub;

class MediaWikiFileFetcher implements FileFetcher {

	public function fetchFile( $fileUrl ) {
		return \Http::get( $fileUrl );
	}

}
