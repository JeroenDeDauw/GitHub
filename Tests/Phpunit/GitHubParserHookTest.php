<?php

namespace GitHub\Tests\Phpunit;

use GitHub\GitHubParserHook;

/**
 * @covers GitHub\GitHubParserHook
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
class GitHubParserHookTest extends \PHPUnit_Framework_TestCase {

	public function testRender() {
		$fileContents = 'foo bar baz';

		$fileFetcher = $this->getMock( 'FileFetcher\FileFetcher' );

		$fileFetcher->expects( $this->once() )
			->method( 'fetchFile' )
			->with( 'https://raw.github.com/JeroenDeDauw/GitHub/master/README.md' )
			->will( $this->returnValue( $fileContents ) );

		$parserHook = new GitHubParserHook( $fileFetcher, 'JeroenDeDauw/GitHub' );

		$renderResult = $parserHook->render();

		$this->assertEquals( $fileContents, $renderResult );
	}

}
