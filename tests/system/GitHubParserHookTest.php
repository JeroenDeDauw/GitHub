<?php

namespace GitHub\Tests\System;

use FileFetcher\FileFetcher;
use GitHub\GitHubFetcher;
use GitHub\GitHubParserHook;
use GitHub\Tests\MediaWikiBoundTestCase;
use ParamProcessor\ProcessedParam;
use ParamProcessor\ProcessingResult;
use PHPUnit\Framework\TestCase;

/**
 * @covers GitHub\GitHubParserHook
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GitHubParserHookTest extends MediaWikiBoundTestCase {

	private $file;
	private $repo;
	private $branch;
	private $lang;

	public function setUp() {
		$this->file = 'README.md';
		$this->repo = 'JeroenDeDauw/GitHub';
		$this->branch = 'master';
		$this->lang = '';
	}

	public function testUrlGetsBuildCorrectly() {
		$fileFetcher = $this->createMock( FileFetcher::class );

		$fileFetcher->expects( $this->once() )
			->method( 'fetchFile' )
			->with( 'https://cdn.rawgit.com/JeroenDeDauw/GitHub/master/README.md' );

		$this->runHookWithFileFetcher( $fileFetcher );
	}

	private function runHookWithFileFetcher( FileFetcher $fileFetcher ) {
		$parserHook = new GitHubParserHook( new GitHubFetcher( $fileFetcher, 'https://cdn.rawgit.com', [] ) );

		$parser = $this->createMock( 'Parser' );
		$params = $this->newParams();

		return $parserHook->handle( $parser, $params );
	}

	private function newParams() {
		return new ProcessingResult( array(
			'file' => new ProcessedParam( 'file', $this->file, false ),
			'repo' => new ProcessedParam( 'repo', $this->repo, false ),
			'branch' => new ProcessedParam( 'branch', $this->branch, false ),
			'lang' => new ProcessedParam( 'lang', $this->lang, false ),
			'line' => new ProcessedParam( 'line', false, true ),
			'start' => new ProcessedParam( 'start', 1, true ),
			'highlight' => new ProcessedParam( 'highlight', '', true ),
			'inline' => new ProcessedParam( 'inline', false, true ),
		) );
	}

	/**
	 * @dataProvider makrdownProvider
	 */
	public function testRenderWithMakrkdownFile( $markdown, $html ) {
		$this->assertFileContentRendersAs( $markdown, $html );
	}

	public function makrdownProvider() {
		return array(
			array(
				'# Ohai there!',
				"<h1>Ohai there!</h1>\n"
			),
			array(
				'foo bar baz',
				"<p>foo bar baz</p>\n"
			),
			array(
				'foo bar baz<script>alert(\'Greetings from github\')</script>',
				"<p>foo bar baz</p>\n"
			)
		);
	}

	private function assertFileContentRendersAs( $fileContent, $expectedRenderedResult ) {
		$fileFetcher = $this->createMock( FileFetcher::class );

		$fileFetcher->expects( $this->once() )
			->method( 'fetchFile' )
			->will( $this->returnValue( $fileContent ) );

		$renderResult = $this->runHookWithFileFetcher( $fileFetcher );

		$this->assertSame( $expectedRenderedResult, $renderResult );
	}

	public function nonMdProvider() {
		return array(
			array(
				'foo bar baz',
				'Foo.php',
			),
			array(
				'# Ohai there!',
				'README.wikitext',
			),
			array(
				'{ "you": { "can": "haz", "a": "json!" } }',
				'composer.json',
			),
			array(
				'{ "you": { "can": "haz", "a": "json!" } }',
				'someFileWithoutExtension',
			),
		);
	}

	/**
	 * @dataProvider nonMdProvider
	 */
	public function testRenderingWithNonMdFileAsIs( $notMd, $fileName ) {
		$this->file = $fileName;
		$this->assertFileContentRendersAs( $notMd, $notMd );
	}

	public function testNonMdContentIsPurified() {
		$this->file = 'Hello.html';

		$this->assertFileContentRendersAs(
			'<script>alert("Greetings from github")</script>foo<script>alert(\'Greetings from github\')</script>',
			'foo'
		);
	}

	public function testRenderingWithLangBash() {
		$this->file = 'hi.sh';
		$this->lang = 'bash';

		$fileFetcher = $this->createMock( FileFetcher::class );

		$fileFetcher->expects( $this->once() )
			->method( 'fetchFile' )
			->will( $this->returnValue( '# Ohai there!' ) );

		$parserHook = new GitHubParserHook( new GitHubFetcher( $fileFetcher, 'https://cdn.rawgit.com', [] ) );

		$parser = $this->createMock( 'Parser' );

		$parser->expects( $this->once() )
			->method( 'recursiveTagParse' )
			->with( $this->equalTo( '<syntaxhighlight lang="bash" start="1"># Ohai there!</syntaxhighlight>' ) )
			->willReturn( null );

		$this->assertSame( '', $parserHook->handle( $parser, $this->newParams() ) );
	}

	// TODO: syntaxhighlight: prevent content from terminating syntaxhighlight and embedding evil stuff

}
