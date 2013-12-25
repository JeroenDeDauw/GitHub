<?php

namespace GitHub\Tests\System;

use ParserOptions;
use Title;
use User;

class PageReader {

	public function getContentOf( Title $title ) {
		$page = new \WikiPage( $title );

		if ( method_exists( $page, 'getContent' ) ) {
			$page->prepareContentForEdit( $page->getContent() );
			return $page->getContent()->getParserOutput( $title )->getText();
		}

		return $page->getParserOutput(
			ParserOptions::newFromUserAndLang( new User(), $GLOBALS['wgContLang'] )
		)->getText();
	}

}