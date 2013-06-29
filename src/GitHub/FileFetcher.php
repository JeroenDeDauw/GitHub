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
interface FileFetcher {

	public function fetchFile( $fileUrl );

}
