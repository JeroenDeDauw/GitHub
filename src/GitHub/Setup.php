<?php

namespace GitHub;

class Setup {

	protected $globals;
	protected $rootDirectory;

	public function __construct( $globals, $rootDirectory ) {
		$this->globals = $globals;
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
		$this->globals['wgHooks']['ParserFirstCallInit'][] = function( \Parser &$parser ) {
			$hookHandler = new GitHubParserHook();
			$parser->setFunctionHook( 'github', array( $hookHandler, 'render' ) );
		};
	}

}