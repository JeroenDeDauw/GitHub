<?php

namespace GitHub\Tests;

use PHPUnit\Framework\TestCase;

abstract class MediaWikiBoundTestCase extends TestCase {

	public static function setUpBeforeClass(): void {
		if ( !class_exists( 'Parser' ) ) {
			self::markTestSkipped( 'MediaWiki is not available' );
		}
	}

}
