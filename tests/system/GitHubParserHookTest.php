<?php

namespace GitHub\Tests\System;

use FileFetcher\FileFetcher;
use GitHub\GitHubParserHook;
use ParamProcessor\ProcessedParam;
use ParamProcessor\ProcessingResult;

/**
 * @covers GitHub\GitHubParserHook
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GitHubParserHookTest extends \PHPUnit_Framework_TestCase {

	protected $file;
	protected $repo;
	protected $branch;

	public function setUp() {
		$this->file = 'README.md';
		$this->repo = 'JeroenDeDauw/GitHub';
		$this->branch = 'master';
	}

	public function testUrlGetsBuildCorrectly() {
		$fileFetcher = $this->getMock( 'FileFetcher\FileFetcher' );

		$fileFetcher->expects( $this->once() )
			->method( 'fetchFile' )
			->with( 'https://raw.githubusercontent.com/JeroenDeDauw/GitHub/master/README.md' );

		$this->runHookWithFileFetcher( $fileFetcher );
	}

	protected function runHookWithFileFetcher( FileFetcher $fileFetcher ) {
		$parserHook = new GitHubParserHook( $fileFetcher );

		$parser = $this->getMock( 'Parser' );
		$params = $this->newParams();

		return $renderResult = $parserHook->handle( $parser, $params );
	}

	protected function newParams() {
		return $params = new ProcessingResult( array(
			'file' => new ProcessedParam( 'file', $this->file, false ),
			'repo' => new ProcessedParam( 'repo', $this->repo, false ),
			'branch' => new ProcessedParam( 'branch', $this->branch, true ),
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

	protected function assertFileContentRendersAs( $fileContent, $expectedRenderedResult ) {
		$fileFetcher = $this->getMock( 'FileFetcher\FileFetcher' );

		$fileFetcher->expects( $this->once() )
			->method( 'fetchFile' )
			->will( $this->returnValue( $fileContent ) );

		$renderResult = $this->runHookWithFileFetcher( $fileFetcher );

		$this->assertEquals( $expectedRenderedResult, $renderResult );
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

}
