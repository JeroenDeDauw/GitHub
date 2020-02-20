<?php

namespace GitHub\Tests\Phpunit;

use GitHub\SyntaxRenderer;
use GitHub\Tests\MediaWikiBoundTestCase;

/**
 * @covers \GitHub\SyntaxRenderer
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SyntaxRendererTest extends MediaWikiBoundTestCase {

	private $language = 'bash';
	private $enableLineNumbers = false;
	private $startingLineNumber = 1;
	private $highlightedLines = '';
	private $inlineSource = false;

	private function newRenderer(): SyntaxRenderer {
		 return new SyntaxRenderer(
			function( string $a ): string {
				return $a;
			},
			$this->language,
			$this->enableLineNumbers,
			$this->startingLineNumber,
			$this->highlightedLines,
			$this->inlineSource
		);
	}

	public function testBasicCase() {
		$this->assertSame(
			'<syntaxhighlight lang="bash" start="1">some nice bash script</syntaxhighlight>',
			$this->newRenderer()->getRenderedContent( 'some nice bash script' )
		);
	}

	public function testEnableLineNumbers() {
		$this->enableLineNumbers = true;

		$this->assertSame(
			'<syntaxhighlight lang="bash" start="1" line="1">some nice bash script</syntaxhighlight>',
			$this->newRenderer()->getRenderedContent( 'some nice bash script' )
		);
	}

	public function testInlineSource() {
		$this->inlineSource = true;

		$this->assertSame(
			'<syntaxhighlight lang="bash" start="1" inline="1">some nice bash script</syntaxhighlight>',
			$this->newRenderer()->getRenderedContent( 'some nice bash script' )
		);
	}

	public function testAlternativeStartingLineNumber() {
		$this->startingLineNumber = 42;

		$this->assertSame(
			'<syntaxhighlight lang="bash" start="42">some nice bash script</syntaxhighlight>',
			$this->newRenderer()->getRenderedContent( 'some nice bash script' )
		);
	}

	public function testHighlightedLines() {
		$this->highlightedLines = '1,2,1337';

		$this->assertSame(
			'<syntaxhighlight lang="bash" start="1" highlight="1,2,1337">some nice bash script</syntaxhighlight>',
			$this->newRenderer()->getRenderedContent( 'some nice bash script' )
		);
	}

	public function testHtmlContent() {
		$this->assertSame(
			'<syntaxhighlight lang="bash" start="1"><h1><a href="http://test">hi</a></h1></syntaxhighlight>',
			$this->newRenderer()->getRenderedContent( '<h1><a href="http://test">hi</a></h1>' )
		);
	}

	public function testEvilAttributes() {
		$this->language = '"><script>alert("hi");</script>';

		$this->assertSame(
			'<syntaxhighlight lang="&quot;&gt;&lt;script&gt;alert(&quot;hi&quot;);&lt;/script&gt;" start="1">some script</syntaxhighlight>',
			$this->newRenderer()->getRenderedContent( 'some script' )
		);
	}

	public function testEvilContent() {
		// It is fine that the content can break out of the tag. The effect will be the same as placing
		// it on the wiki directly as the syntaxhighlight tag still goes though the MediaWiki parser.
		$this->assertSame(
			'<syntaxhighlight lang="bash" start="1"></syntaxhighlight><script>alert("hi");</script><syntaxhighlight></syntaxhighlight>',
			$this->newRenderer()->getRenderedContent( '</syntaxhighlight><script>alert("hi");</script><syntaxhighlight>' )
		);
	}

}
