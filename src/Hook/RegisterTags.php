<?php

namespace BlueSpice\Player\Hook;

use BlueSpice\Player\Tag\Showtime;
use MediaWiki\Config\ConfigFactory;
use MWStake\MediaWiki\Component\GenericTagHandler\Hook\MWStakeGenericTagHandlerInitTagsHook;

class RegisterTags implements MWStakeGenericTagHandlerInitTagsHook {

	public function __construct(
		private readonly ConfigFactory $configFactory
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function onMWStakeGenericTagHandlerInitTags( array &$tags ) {
		$tags[] = new Showtime( $this->configFactory );
	}
}
