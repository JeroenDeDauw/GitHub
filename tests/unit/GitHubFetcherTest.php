<?php

namespace GitHub\Tests\Phpunit;

use FileFetcher\StubFileFetcher;
use FileFetcher\ThrowingFileFetcher;
use GitHub\GitHubFetcher;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GitHub\GitHubFetcher
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GitHubFetcherTest extends TestCase {

	public function testWhenFileFetcherThrowsException_emptyStringIsReturned() {
		$this->assertSame(
			'',
			( new GitHubFetcher( new ThrowingFileFetcher(), '', [] ) )
				->getFileContent( 'JeroenDeDauw/GitHub', 'master', 'README.md' )
		);
	}

	public function testWhenRepoNotInWhitelist_emptyStringIsReturned() {
		$this->assertSame(
			'',
			( new GitHubFetcher( new StubFileFetcher( 'kittens' ), '', [ 'JeroenDeDauw/Maps' ] ) )
				->getFileContent( 'JeroenDeDauw/GitHub', 'master', 'README.md' )
		);
	}

}
