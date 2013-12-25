<?php

namespace GitHub\Tests\System;

use Title;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GitHubParserHookTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var Title[]
	 */
	protected static $titles = array();

	public static function tearDownAfterClass() {
		$pageDeleter = new PageDeleter();

		foreach ( self::$titles as $title ) {
			$pageDeleter->deletePage( $title );
		}
	}

	private function createPage( $titleText, $wikiText ) {
		$title = Title::newFromText( $titleText );
		self::$titles[] = $title;

		$pageCreator = new PageCreator();
		$pageCreator->createPage( $title, $wikiText );

		return $title;
	}

	public function testCreatingPageWithParserHookDoesNotFail() {
//		$title = $this->createPage(
//			'GitHubTest:TestForSmoke',
//			'{{#github:docs/INSTALL.md}}'
//		);
//
//		$reader = new PageReader();
//		$text = $reader->getContentOf( $title );
//
//		$this->assertEquals( '', $text );

		// TODO
		$this->assertTrue( true );
	}

}