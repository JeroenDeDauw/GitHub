<?php

/**
 * Entry point of the GitHub extension.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

if ( defined( 'GitHub_VERSION' ) ) {
	// Do not initialize more than once.
	return ;
}

define( 'GitHub_VERSION', '1.0' );

if ( !defined( 'FileFetcher_VERSION' ) && is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	include_once( __DIR__ . '/vendor/autoload.php' );
}

if ( !defined( 'FileFetcher_VERSION' ) && is_readable( __DIR__ . '/../FileFetcher/FileFetcher.php' ) ) {
	include_once( __DIR__ . '/../FileFetcher/FileFetcher.php' );
}

if ( !defined( 'FileFetcher_VERSION' ) ) {
	throw new Exception( 'You need to have the FileFetcher library loaded in order to use GitHub' );
}

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
	$wgExtensionFunctions[] = function() {
		$setup = new \GitHub\Setup( $GLOBALS, __DIR__ );
		$setup->run();
	};
}