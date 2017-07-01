<?php

namespace GitHub;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ContentPurifier {

	/**
	 * @var HTMLPurifier
	 */
	private $purifier;

	const ALLOWED_HTML_TAGS = '
		h1,h2,h3,h4,h5,h6,
		p,
		br,hr,
		ul,ol,li,
		span,b,i,u,strong,em,
		a[href|target],
		img[src|alt],
		table[class],thead,tbody,tr,th[scope],td[scope],
		code,pre
	';

	public function __construct() {
		$config = HTMLPurifier_Config::createDefault();

		$config->set( 'HTML.Allowed', self::ALLOWED_HTML_TAGS );
		$config->set( 'Attr.AllowedFrameTargets', [ '_blank' ] ); // allow target="_blank" hrefs

		$this->purifier = new HTMLPurifier( $config );
	}

	public function purify( string $html ): string {
		return $this->purifier->purify( $html );
	}

}
