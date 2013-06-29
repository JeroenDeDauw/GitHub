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

	public function testGetFileWhenNotCached() {
		$fileUrl = 'foo://bar';
		$fileContents = 'NyanData across the sky!';

		$fileFetcher = $this->getMock( 'GitHub\FileFetcher' );

		$fileFetcher->expects( $this->once() )
			->method( 'fetchFile' )
			->with( $fileUrl )
			->will( $this->returnValue( $fileContents ) );

		$cache = $this->getMock( 'SimpleCache\Cache\Cache' );

		$cache->expects( $this->once() )
			->method( 'get' )
			->with( $fileUrl )
			->will( $this->returnValue( null ) );

		$cache->expects( $this->once() )
			->method( 'set' )
			->with( $fileUrl );

		$cachingFetcher = new CachingFileFetcher( $fileFetcher, $cache );
		$cachingFetcher->fetchFile( $fileUrl );
	}

}
