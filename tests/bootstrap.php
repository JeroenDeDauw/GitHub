<?php

/**
 * PHPUnit test bootstrap file for the GitHub extension.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

if ( php_sapi_name() !== 'cli' ) {
	die( 'Not an entry point' );
}

$pwd = getcwd();
chdir( __DIR__ . '/..' );
passthru( 'composer update' );
chdir( $pwd );

require_once( __DIR__ . '/evilMediaWikiBootstrap.php' );
