<?php


/**
 * This documentation group collects source code files belonging to the GitHub extension.
 *
 * @defgroup GitHub GitHub
 */

if ( defined( 'GitHub_VERSION' ) ) {
	// Do not initialize more then once.
	return;
}

define( 'GitHub_VERSION', '0.1' );

// @codeCoverageIgnoreStart
spl_autoload_register( function ( $className ) {
	$className = ltrim( $className, '\\' );
	$fileName = '';
	$namespace = '';

	if ( $lastNsPos = strripos( $className, '\\') ) {
		$namespace = substr( $className, 0, $lastNsPos );
		$className = substr( $className, $lastNsPos + 1 );
		$fileName  = str_replace( '\\', '/', $namespace ) . '/';
	}

	$fileName .= str_replace( '_', '/', $className ) . '.php';

	$namespaceSegments = explode( '\\', $namespace );

	if ( $namespaceSegments[0] === 'GitHub' ) {
		if ( count( $namespaceSegments ) === 1 || $namespaceSegments[1] !== 'Tests' ) {
			require_once __DIR__ . '/src/' . $fileName;
		}
	}
} );
// @codeCoverageIgnoreEnd

if ( defined( 'MEDIAWIKI' ) ) {
	$setup = new \GitHub\Setup( $GLOBALS, __DIR__ );
	$setup->run();
}