<?php

namespace GitHub\Tests\Phpunit;

use GitHub\Setup;
use PHPUnit\Framework\TestCase;

/**
 * @covers GitHub\Setup
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SetupTest extends TestCase {

	public function testCanConstruct() {
		$inputGlobals = array(
			'wgExtensionCredits' => array( 'other' => array() ),
			'wgHooks' => array( 'ParserFirstCallInit' => array() ),
		);

		$setup = new Setup( $inputGlobals, __DIR__ . '/..' );
		$setup->run();

		$this->assertCount( 1, $inputGlobals['wgExtensionCredits']['other'], 'credits where set' );
		$this->assertCount( 1, $inputGlobals['wgHooks']['ParserFirstCallInit'], 'parser hook was registered' );

		$setup->getGitHubHookHandler();
	}

}
