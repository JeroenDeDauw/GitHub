<?php

namespace GitHub;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SyntaxRenderer {

	private $recursiveTagParseFunction;

	// Parameters for SyntaxHighlight extension (formerly SyntaxHighlight_GeSHi)
	// https://www.mediawiki.org/wiki/Extension:SyntaxHighlight
	private $language;
	private $enableLineNumbers;
	private $startingLineNumber;
	private $highlightedLines;
	private $inlineSource;

	public function __construct( callable $recursiveTagParseFunction, string $language,
		bool $enableLineNumbers, int $startingLineNumber, string $highlightedLines, bool $inlineSource ) {

		$this->recursiveTagParseFunction = $recursiveTagParseFunction;
		$this->language = $language;
		$this->enableLineNumbers = $enableLineNumbers;
		$this->startingLineNumber = $startingLineNumber;
		$this->highlightedLines = $highlightedLines;
		$this->inlineSource = $inlineSource;
	}

	public function getRenderedContent( string $content ): string {
		$parsed = ($this->recursiveTagParseFunction)( $this->buildSyntaxTag( $content ) );

		if ( is_string( $parsed ) ) {
			return $parsed;
		}

		return '';
	}

	private function buildSyntaxTag( $content ): string {
		return \Html::rawElement(
			'syntaxhighlight',
			$this->getTagAttributes(),
			$content
		);
	}

	private function getTagAttributes(): array {
		$attributes = [
			'lang' => $this->language,
			'start' => $this->startingLineNumber,
		];

		if ( $this->enableLineNumbers ) {
			$attributes['line'] = true;
		}

		if ( $this->highlightedLines !== '' ) {
			$attributes['highlight'] = $this->highlightedLines;
		}

		if ( $this->inlineSource ) {
			$attributes['inline'] = true;
		}

		return $attributes;
	}

}
