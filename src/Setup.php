<?php

namespace GitHub;

use FileFetcher\CachingFileFetcher;
use FileFetcher\ErrorLoggingFileFetcher;
use FileFetcher\FileFetcher;
use FileFetcher\SimpleFileFetcher;
use MediaWiki\Logger\LegacyLogger;
use ParserHooks\FunctionRunner;
use ParserHooks\HookDefinition;
use ParserHooks\HookHandler;
use ParserHooks\HookRegistrant;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
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
	private $gitHubCache = 'full';
	private $repositoryWhitelist = [];

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
		$this->globals['wgExtensionCredits']['other'][] = [
			'path' => $this->rootDirectory . '/GitHub.php',
			'name' => 'GitHub',
			'version' => GitHub_VERSION,
			'author' => [
				'[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]',
			],
			'url' => 'https://github.com/JeroenDeDauw/GitHub',
			'descriptionmsg' => 'github-desc',
			'license-name' => 'GPL-2.0+'
		];
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

		if ( array_key_exists( 'egGitHubCache', $this->globals ) ) {
			$this->gitHubCache = $this->globals['egGitHubCache'];
		}

		if ( array_key_exists( 'egGitHubRepositoryWhitelist', $this->globals ) ) {
			$this->repositoryWhitelist = $this->globals['egGitHubRepositoryWhitelist'];
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

	public function getGitHubHookDefinition(): HookDefinition {
		return new HookDefinition(
			'github',
			[
				'file' => [
					'default' => 'README.md',
					'aliases' => 'filename',
					'message' => 'github-par-filename',
				],
				'repo' => [
					'default' => $this->defaultGitHubRepo,
					'aliases' => 'reponame',
					'message' => 'github-par-reponame',
				],
				'branch' => [
					'default' => 'master',
					'aliases' => 'branchname',
					'message' => 'github-par-branchname',
				],
				'lang' => [
					'default' => '',
					'message' => 'github-par-lang',
				],
				'line' => [
					'default' => false,
					'message' => 'github-par-line',
					'type'    => 'boolean',
				],
				'start' => [
					'default' => 1,
					'message' => 'github-par-start',
					'type'    => 'integer',
				],
				'highlight' => [
					'default' => '',
					'message' => 'github-par-highlight',
				],
				'inline' => [
					'default' => false,
					'message' => 'github-par-inline',
					'type'    => 'boolean',
				],
			),
			[
				'file',
				'repo',
				'branch',
				'lang'
			]
		];
	}

	public function getGitHubHookHandler(): HookHandler {
		return new GitHubParserHook(
			new GitHubFetcher(
				$this->newFileFetcher(),
				$this->gitHubUrl,
				$this->repositoryWhitelist
			)
		);
	}

	private function newFileFetcher(): FileFetcher {
		return $this->newCachingFileFetcher(
			$this->newLoggingFileFetcher(
				$this->gitHubFetcher === 'mediawiki' ? new MediaWikiFileFetcher() : new SimpleFileFetcher()
			)
		);
	}

	private function newLoggingFileFetcher( FileFetcher $fileFetcher ): FileFetcher {
		return new ErrorLoggingFileFetcher(
			$fileFetcher,
			$this->newLogger()
		);
	}

	private function newLogger(): LoggerInterface {
		return new LegacyLogger( 'GitHub-extension' );
	}

	private function newCachingFileFetcher( FileFetcher $fileFetcher ): FileFetcher {
		if ( $this->gitHubCache === 'full' ) {
			return new CachingFileFetcher(
				$fileFetcher,
				new CombinatoryCache( array(
					new SimpleInMemoryCache(),
					new MediaWikiCache( wfGetMainCache(), $this->cacheTime )
				) )
			);
		}

		return $fileFetcher;
	}

}
