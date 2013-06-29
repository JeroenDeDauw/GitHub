<?php

namespace GitHub\Tests\Phpunit;

use GitHub\CachingFileFetcher;

/**
 * @covers GitHub\CachingFileFetcher
 *
 * @file
 * @since 0.1
 *
 * @ingroup GitHub
 * @group GitHub
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class CachingFileFetcherTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {
		$fileFetcher = $this->getMock( 'GitHub\FileFetcher' );
		$cache = $this->getMock( 'SimpleCache\Cache\Cache' );

		new CachingFileFetcher( $fileFetcher, $cache );

		$this->assertTrue( true );
	}

}
