<?php

namespace BlueSpice\Player\Tag;

use MediaWiki\Config\ConfigFactory;
use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\GenericTagHandler\ClientTagSpecification;
use MWStake\MediaWiki\Component\GenericTagHandler\GenericTag;
use MWStake\MediaWiki\Component\GenericTagHandler\ITagHandler;
use MWStake\MediaWiki\Component\InputProcessor\Processor\BooleanValue;
use MWStake\MediaWiki\Component\InputProcessor\Processor\IntValue;

class Showtime extends GenericTag {

	public function __construct(
		private readonly ConfigFactory $configFactory
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function getTagNames(): array {
		return [ 'showtime', 'bs:showtime' ];
	}

	/**
	 * @return bool
	 */
	public function hasContent(): bool {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getHandler( MediaWikiServices $services ): ITagHandler {
		return new ShowtimeHandler(
			$services->getTitleFactory(),
			$services->getRepoGroup()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getResourceLoaderModules(): ?array {
		return [ 'ext.bluespice.showtime' ];
	}

	/**
	 * @inheritDoc
	 */
	public function getParamDefinition(): ?array {
		$config = $this->configFactory->makeConfig( 'bsg' );
		$width = ( new IntValue() )
			->setDefaultValue( $config->get( 'ShowtimePrefWidth' ) )
			->setMin( 1 );
		$height = ( new IntValue() )
			->setDefaultValue( $config->get( 'ShowtimePrefHeight' ) )
			->setMin( 1 );
		$autostart = ( new BooleanValue() )
			->setDefaultValue( false );
		$repeat = ( new BooleanValue() )
			->setDefaultValue( false );

		return [
			'width' => $width,
			'height' => $height,
			'autostart' => $autostart,
			'repeat' => $repeat
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getClientTagSpecification(): ClientTagSpecification|null {
		return null;
	}
}
