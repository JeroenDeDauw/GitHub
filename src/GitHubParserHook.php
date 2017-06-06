<?php

namespace GitHub;

use ParamProcessor\ProcessingResult;
use Parser;
use ParserHooks\HookHandler;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GitHubParserHook implements HookHandler {

	private $gitHubFetcher;

	public function __construct( GitHubFetcher $gitHubFetcher ) {
		$this->gitHubFetcher = $gitHubFetcher;
	}

	public function handle( Parser $parser, ProcessingResult $result ): string {
		$params = $result->getParameters();

		$content = $this->gitHubFetcher->getFileContent(
			$params['repo']->getValue(),
			$params['branch']->getValue(),
			$params['file']->getValue()
		);

		if ( $params['lang']->getValue() === '' ) {
			return ( new NormalRenderer() )->getRenderedContent( $content, $params['file']->getValue() );
		}

		$syntaxRenderer = new SyntaxRenderer(
			function( string $syntaxContent ) use ( $parser ) {
				return $parser->recursiveTagParse( $syntaxContent, null );
			},
			$params['lang']->getValue(),
			$params['line']->getValue(),
			$params['start']->getValue(),
			$params['highlight']->getValue(),
			$params['inline']->getValue()
		);

		return $syntaxRenderer->getRenderedContent( $content );
	}

}
