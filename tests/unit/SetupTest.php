<?php

namespace GitHub\Tests\Phpunit;

use GitHub\Setup;
use GitHub\Tests\MediaWikiBoundTestCase;

/**
 * @covers \GitHub\Setup
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SetupTest extends MediaWikiBoundTestCase {

	public function testCanConstruct() {
		$inputGlobals = [
			'wgExtensionCredits' => [ 'other' => [] ],
			'wgHooks' => [ 'ParserFirstCallInit' => [] ],
		];

		$setup = new Setup( $inputGlobals, __DIR__ . '/..' );
		$setup->run();

		$this->assertCount( 1, $inputGlobals['wgExtensionCredits']['other'], 'credits where set' );
		$this->assertCount( 1, $inputGlobals['wgHooks']['ParserFirstCallInit'], 'parser hook was registered' );

		$setup->getGitHubHookHandler();
	}

}
