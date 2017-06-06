<?php

namespace GitHub\Tests\System;

use FileFetcher\FileFetcher;
use GitHub\GitHubParserHook;
use ParamProcessor\ProcessedParam;
use ParamProcessor\ProcessingResult;
use PHPUnit\Framework\TestCase;

/**
 * @covers GitHub\GitHubParserHook
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GitHubParserHookTest extends TestCase {

	private $file;
	private $repo;
	private $branch;

	public function setUp() {
		$this->file = 'README.md';
		$this->repo = 'JeroenDeDauw/GitHub';
		$this->branch = 'master';
	}

	public function testUrlGetsBuildCorrectly() {
		$fileFetcher = $this->createMock( FileFetcher::class );

		$fileFetcher->expects( $this->once() )
			->method( 'fetchFile' )
			->with( 'https://cdn.rawgit.com/JeroenDeDauw/GitHub/master/README.md' );

		$this->runHookWithFileFetcher( $fileFetcher );
	}

	private function runHookWithFileFetcher( FileFetcher $fileFetcher ) {
		$parserHook = new GitHubParserHook( $fileFetcher, 'https://cdn.rawgit.com' );

		$parser = $this->createMock( 'Parser' );
		$params = $this->newParams();

		return $parserHook->handle( $parser, $params );
	}

	private function newParams() {
		return new ProcessingResult( array(
			'file' => new ProcessedParam( 'file', $this->file, false ),
			'repo' => new ProcessedParam( 'repo', $this->repo, false ),
			'branch' => new ProcessedParam( 'branch', $this->branch, true ),
			'lang' => new ProcessedParam( 'lang', '', true ),
			'line' => new ProcessedParam( 'line', false, true ),
			'start' => new ProcessedParam( 'start', 1, true ),
			'highlight' => new ProcessedParam( 'highlight', '', true ),
			'inline' => new ProcessedParam( 'inline', false, true ),
		) );
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
			)
		);
	}

	/**
	 * @dataProvider makrdownProvider
	 */
	public function testRenderWithMakrkdownFile( $markdown, $html ) {
		$this->assertFileContentRendersAs( $markdown, $html );
	}

	private function assertFileContentRendersAs( $fileContent, $expectedRenderedResult ) {
		$fileFetcher = $this->createMock( FileFetcher::class );

		$fileFetcher->expects( $this->once() )
			->method( 'fetchFile' )
			->will( $this->returnValue( $fileContent ) );

		$renderResult = $this->runHookWithFileFetcher( $fileFetcher );

		$this->assertEquals( $expectedRenderedResult, $renderResult );
	}

	/**
	 * @dataProvider nonMdProvider
	 */
	public function testRenderingWithNonMdFileAsIs( $notMd, $fileName ) {
		$this->file = $fileName;
		$this->assertFileContentRendersAs( $notMd, $notMd );
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

}
