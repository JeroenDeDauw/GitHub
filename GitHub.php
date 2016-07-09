<?php

/**
 * Entry point of the GitHub extension.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

if ( defined( 'GitHub_VERSION' ) ) {
	// Do not initialize more than once.
	return 1;
}

define( 'GitHub_VERSION', '1.0.3' );

if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	include_once( __DIR__ . '/vendor/autoload.php' );
}

if ( defined( 'MEDIAWIKI' ) ) {
	$GLOBALS['wgExtensionFunctions'][] = function() {
		$setup = new \GitHub\Setup( $GLOBALS, __DIR__ );
		$setup->run();
	};
}
