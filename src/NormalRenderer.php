<?php

namespace GitHub;

use Michelf\Markdown;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class NormalRenderer {

	public function getRenderedContent( string $content, string $fileName ): string {
		return ( new ContentPurifier() )
			->purify( $this->getUnpurifiedRenderedContent( $content, $fileName ) );
	}

	private function getUnpurifiedRenderedContent( string $content, string $fileName ): string {
		if ( $this->isMarkdownFile( $fileName ) ) {
			return $this->renderAsMarkdown( $content );
		}

		return $content;
	}

	private function isMarkdownFile( string $fileName ): bool {
		return $this->fileHasExtension( $fileName, '.md' )
			|| $this->fileHasExtension( $fileName,'.markdown' );
	}

	private function fileHasExtension( string $fileName, string $extension ): bool {
		return substr( $fileName, -strlen( $extension ) ) === $extension;
	}

	private function renderAsMarkdown( string $content ): string {
		return Markdown::defaultTransform( $content );
	}

}
