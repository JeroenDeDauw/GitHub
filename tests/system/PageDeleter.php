<?php

namespace GitHub\Tests\System;

use Title;

class PageDeleter {

	public function deletePage( Title $title ) {
		$page = new \WikiPage( $title );
		$page->doDeleteArticle( 'GitHub system test: delete page' );
	}

}