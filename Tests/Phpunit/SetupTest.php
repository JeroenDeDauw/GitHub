<?php

namespace GitHub\Tests\Phpunit;

use GitHub\Setup;

/**
 * @covers GitHub\Setup
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
class SetupTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {
		$inputGlobals = array();

		$setup = new Setup( $inputGlobals, __DIR__ . '/..' );
		$setup->run();


	}

}
