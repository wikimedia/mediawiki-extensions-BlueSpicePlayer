<?php

namespace BlueSpice\Player\Tag;

use File;
use MediaWiki\Context\RequestContext;
use MediaWiki\Html\Html;
use MediaWiki\Message\Message;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\PPFrame;
use MediaWiki\Title\TitleFactory;
use MWStake\MediaWiki\Component\GenericTagHandler\ITagHandler;
use OOUI\MessageWidget;
use RepoGroup;
use RuntimeException;

class ShowtimeHandler implements ITagHandler {

	/**
	 * @var array|string[]
	 */
	private $allowedFileExtensions = [ 'flv', 'mp4', 'ogv', 'ogg', 'webm' ];

	/**
	 * @param TitleFactory $titleFactory
	 * @param RepoGroup $repoGroup
	 */
	public function __construct(
		private readonly TitleFactory $titleFactory,
		private readonly RepoGroup $repoGroup,
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function getRenderedContent( string $input, array $params, Parser $parser, PPFrame $frame ): string {
		try {
			$file = $this->initFileFromInput( $input );
			$this->validateFileExtension( $file );
		} catch ( RuntimeException $e ) {
			RequestContext::getMain()->getOutput()->enableOOUI();
			return new MessageWidget( [
				'label' => $e->getMessage(),
				'type' => 'error'
			] );
		}

		$attributes = [];
		$attributes['class'] = 'bs-video';
		$attributes['style'] = "height:" . $params['height'] . "px;";
		// We are not using native HTML5 attributes like 'autoplay' and 'loop'
		// because the browser would start playing the video before the javascript
		// player and its controls are ready.
		$playrOptions = [
			'autoplay' => $params['autostart'],
			'muted' => $params['autostart'],
			'loop' => [
				'active' => $params['repeat']
			]
		];
		$attributes['data-plyr-config'] = json_encode( $playrOptions );

		$html = Html::openElement( 'div', [
			'style' =>
				"width: "
				. $params['width'] .
				"px;"
		] );

		$html .= Html::rawElement( 'video', $attributes,
			Html::element( 'source', [
				"src" => $file->getViewURL( false ),
				"type" => $file->getMimeType()
			] )
		);
		$html .= Html::closeElement( 'div' );

		return $html;
	}

	/**
	 * Check if file exist
	 */
	private function initFileFromInput( string $input ): File {
		$fileTitle = $this->titleFactory->makeTitle( NS_FILE, $input );
		$file = $this->repoGroup->findFile( $fileTitle );
		if ( $file instanceof File === false ) {
			throw new RuntimeException( Message::newFromKey( 'bs-player-file-does-not-exist' )->text() );
		}
		return $file;
	}

	/**
	 * Validate Allowed file Extensions
	 */
	private function validateFileExtension( File $file ) {
		$lcFileExt = strtolower( $file->getExtension() );
		if ( !in_array( $lcFileExt, $this->allowedFileExtensions ) ) {
			throw new RuntimeException( Message::newFromKey( 'bs-player-unsupported-type' )->text() );
		}
	}
}
