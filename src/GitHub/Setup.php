<?php

namespace GitHub;

/**
 * @file
 * @since 0.1
 * @ingroup GitHub
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Setup {

	protected $globals;
	protected $rootDirectory;

	public function __construct( &$globals, $rootDirectory ) {
		$this->globals =& $globals;
		$this->rootDirectory = $rootDirectory;
	}

	public function run() {
		$this->registerExtensionCredits();
		$this->registerMessageFiles();
		$this->registerParserHookHandler();
	}

	protected function registerExtensionCredits() {
		$this->globals['wgExtensionCredits']['other'][] = array(
			'path' => __FILE__,
			'name' => 'GitHub',
			'version' => GitHub_VERSION,
			'author' => array(
				'[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]',
			),
			'url' => 'https://www.mediawiki.org/wiki/Extension:GitHub',
			'descriptionmsg' => 'github-desc'
		);
	}

	protected function registerMessageFiles() {
		$this->globals['wgExtensionMessagesFiles']['GitHub'] = $this->rootDirectory . '/GitHub.i18n.php';
		$this->globals['wgExtensionMessagesFiles']['GitHubMagic'] = $this->rootDirectory . '/GitHub.i18n.magic.php';
	}

	protected function registerParserHookHandler() {
		$fileFetcher = $this->newFileFetcher();

		$this->globals['wgHooks']['ParserFirstCallInit'][] = function( \Parser &$parser ) use ( $fileFetcher ) {
			$hookHandler = new GitHubParserHook( $fileFetcher );
			$parser->setFunctionHook( 'github', array( $hookHandler, 'renderWithParser' ) );
			return true;
		};
	}

	protected function newFileFetcher() {
		return new MediaWikiFileFetcher();
	}

}