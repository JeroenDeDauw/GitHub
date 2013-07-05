<?php

namespace GitHub;

use FileFetcher\CachingFileFetcher;
use SimpleCache\Cache\CombinatoryCache;
use SimpleCache\Cache\MediaWikiCache;
use SimpleCache\Cache\SimpleInMemoryCache;

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
	protected $defaultGitHubRepo = 'JeroenDeDauw/GitHub';

	public function __construct( &$globals, $rootDirectory ) {
		$this->globals =& $globals;
		$this->rootDirectory = $rootDirectory;
	}

	public function run() {
		$this->registerExtensionCredits();
		$this->registerMessageFiles();
		$this->registerParserHookHandler();
		$this->loadSettings();
	}

	protected function registerExtensionCredits() {
		$this->globals['wgExtensionCredits']['other'][] = array(
			'path' => $this->rootDirectory . '/GitHub.php',
			'name' => 'GitHub',
			'version' => GitHub_VERSION,
			'author' => array(
				'[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]',
			),
			'url' => 'https://github.com/JeroenDeDauw/GitHub',
			'descriptionmsg' => 'github-desc'
		);
	}

	protected function registerMessageFiles() {
		$this->globals['wgExtensionMessagesFiles']['GitHub'] = $this->rootDirectory . '/GitHub.i18n.php';
		$this->globals['wgExtensionMessagesFiles']['GitHubMagic'] = $this->rootDirectory . '/GitHub.i18n.magic.php';
	}

	protected function loadSettings() {
		if ( array_key_exists( 'egGitHubDefaultRepo', $this->globals ) ) {
			$this->defaultGitHubRepo = $this->globals['egGitHubDefaultRepo'];
		}
	}

	protected function registerParserHookHandler() {
		$fileFetcherFactory = array( $this, 'newFileFetcher' );
		$defaultGitHubRepo = $this->defaultGitHubRepo;

		$this->globals['wgHooks']['ParserFirstCallInit'][] = function( \Parser &$parser ) use ( $fileFetcherFactory, $defaultGitHubRepo ) {
			$hookHandler = new GitHubParserHook(
				call_user_func( $fileFetcherFactory ),
				$defaultGitHubRepo
			);

			$parser->setFunctionHook( 'github', array( $hookHandler, 'renderWithParser' ) );
			return true;
		};
	}

	protected function newFileFetcher() {
		return new CachingFileFetcher(
			new MediaWikiFileFetcher(),
			new CombinatoryCache( array(
				new SimpleInMemoryCache(),
				new MediaWikiCache( wfGetMainCache() )
			) )
		);
	}

}