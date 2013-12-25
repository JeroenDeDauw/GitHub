<?php

namespace GitHub\Tests\System;

use Title;

class PageCreator {

	public function createPage( Title $title, $wikiText ) {
		$page = new \WikiPage( $title );

		$editMessage = 'GitHub system test: create page';

		if ( class_exists( 'WikitextContent' ) ) {
			$page->doEditContent(
				new \WikitextContent( $wikiText ),
				$editMessage
			);
		}
		else {
			$page->doEdit( $wikiText, $editMessage );
		}
	}

}