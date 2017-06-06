<?php

namespace GitHub;

use FileFetcher\CachingFileFetcher;
use FileFetcher\FileFetcher;
use FileFetcher\SimpleFileFetcher;
use ParserHooks\FunctionRunner;
use ParserHooks\HookDefinition;
use ParserHooks\HookHandler;
use ParserHooks\HookRegistrant;
use SimpleCache\Cache\CombinatoryCache;
use SimpleCache\Cache\MediaWikiCache;
use SimpleCache\Cache\SimpleInMemoryCache;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Setup {

	private $globals;
	private $rootDirectory;
	private $defaultGitHubRepo = 'JeroenDeDauw/GitHub';
	private $cacheTime = 600;
	private $gitHubUrl = 'https://cdn.rawgit.com';
	private $gitHubFetcher = 'simple';

	public function __construct( &$globals, string $rootDirectory ) {
		$this->globals =& $globals;
		$this->rootDirectory = $rootDirectory;
	}

	public function run() {
		$this->loadSettings();

		$this->registerExtensionCredits();
		$this->registerParserHookHandler();
	}

	private function registerExtensionCredits() {
		$this->globals['wgExtensionCredits']['other'][] = array(
			'path' => $this->rootDirectory . '/GitHub.php',
			'name' => 'GitHub',
			'version' => GitHub_VERSION,
			'author' => array(
				'[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]',
			),
			'url' => 'https://github.com/JeroenDeDauw/GitHub',
			'descriptionmsg' => 'github-desc',
			'license-name' => 'GPL-2.0+'
		);
	}

	private function loadSettings() {
		if ( array_key_exists( 'egGitHubDefaultRepo', $this->globals ) ) {
			$this->defaultGitHubRepo = $this->globals['egGitHubDefaultRepo'];
		}

		if ( array_key_exists( 'egGitHubCacheTime', $this->globals ) ) {
			$this->cacheTime = $this->globals['egGitHubCacheTime'];
		}

		if ( array_key_exists( 'egGitHubUrl', $this->globals ) ) {
			$this->gitHubUrl = $this->globals['egGitHubUrl'];
		}

		if ( array_key_exists( 'egGitHubFetcher', $this->globals ) ) {
			$this->gitHubFetcher = $this->globals['egGitHubFetcher'];
		}
	}

	private function registerParserHookHandler() {
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

	public function newFileFetcher(): FileFetcher {
		return new CachingFileFetcher(
			$this->gitHubFetcher === 'mediawiki' ? new MediaWikiFileFetcher() : new SimpleFileFetcher(),
			new CombinatoryCache( array(
				new SimpleInMemoryCache(),
				new MediaWikiCache( wfGetMainCache(), $this->cacheTime )
			) )
		);
	}

	public function getGitHubHookDefinition(): HookDefinition {
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
				'lang' => array(
					'default' => '',
					'message' => 'github-par-lang',
				),
				'line' => array(
					'default' => false,
					'message' => 'github-par-line',
					'type'    => 'boolean',
				),
				'start' => array(
					'default' => 1,
					'message' => 'github-par-start',
					'type'    => 'integer',
				),
				'highlight' => array(
					'default' => '',
					'message' => 'github-par-highlight',
				),
				'inline' => array(
					'default' => false,
					'message' => 'github-par-inline',
					'type'    => 'boolean',
				),
			),
			array( 'file', 'repo', 'branch', 'lang' )
		);
	}

	public function getGitHubHookHandler(): HookHandler {
		return new GitHubParserHook(
			$this->newFileFetcher(),
			$this->gitHubUrl
		);
	}

}
