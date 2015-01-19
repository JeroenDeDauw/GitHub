<?php

namespace GitHub;

use FileFetcher\CachingFileFetcher;
use ParserHooks\FunctionRunner;
use ParserHooks\HookDefinition;
use ParserHooks\HookRegistrant;
use SimpleCache\Cache\CombinatoryCache;
use SimpleCache\Cache\MediaWikiCache;
use SimpleCache\Cache\SimpleInMemoryCache;

/**
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
		$this->loadSettings();

		$this->registerExtensionCredits();
		$this->registerMessageFiles();
		$this->registerParserHookHandler();
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
		$self = $this;

		$this->globals['wgHooks']['ParserFirstCallInit'][] = function( \Parser &$parser ) use ( $self ) {
			$hookRegistrant = new HookRegistrant( $parser );

			$hookRegistrant->registerFunction(
				new FunctionRunner(
					$self->getGitHubHookDefinition(),
					$self->getGitHubHookHandler(),
					array(
						FunctionRunner::OPT_DO_PARSE => false
					)
				)
			);

			return true;
		};
	}

	public function newFileFetcher() {
		return new CachingFileFetcher(
			new MediaWikiFileFetcher(),
			new CombinatoryCache( array(
				new SimpleInMemoryCache(),
				new MediaWikiCache( wfGetMainCache() )
			) )
		);
	}

	/**
	 * @since 1.0
	 *
	 * @return HookDefinition
	 */
	public function getGitHubHookDefinition() {
		return new HookDefinition(
			'github',
			array(
				'file' => array(
					'default' => 'README.md',
					'aliases' => 'filename',
					'message' => 'github-par-filename',
				),
				'repo' => array(
					'default' => $this->defaultGitHubRepo,
					'aliases' => 'reponame',
					'message' => 'github-par-reponame',
				),
				'branch' => array(
					'default' => 'master',
					'aliases' => 'branchname',
					'message' => 'github-par-branchname',
				),
			),
			array( 'file', 'repo', 'branch' )
		);
	}

	public function getGitHubHookHandler() {
		return new GitHubParserHook(
			$this->newFileFetcher()
		);
	}

}