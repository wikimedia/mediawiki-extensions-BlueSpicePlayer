<?php

namespace BlueSpice\Player\Hook\GetPreferences;

use BlueSpice\Hook\GetPreferences;

class Autostart extends GetPreferences {
	protected function doProcess() {
		$this->preferences['bs-showtime-pref-autostart'] = [
			'type' => 'check',
			'label-message' => 'bs-showtime-pref-Autostart',
			'section' => 'rendering/showtime',
		];
		return true;
	}
}
