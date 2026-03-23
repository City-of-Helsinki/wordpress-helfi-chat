<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\CookieConsent\Cookies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CityOfHelsinki\WordPress\CookieConsent\Features\Interfaces\Known_Cookie_Data;

final class Genesys_Widgets_Infocus implements Known_Cookie_Data
{
	public function issuer(): string
	{
		return 'Helsinki Chat';
	}

	public function name(): string
	{
		return '_genesys.widgets.inFocus';
	}

	public function label(): string
	{
		return '_genesys.widgets.inFocus';
	}

	public function descriptionTranslations(): array
	{
		return array(
			'fi' => 'Käytetään chat-sovelluksen toimintaan. Säilyttää tiedon siitä, onko chat-sovellus käyttäjän näkökentässä.',
			'sv' => 'Används för chattapplikationens funktion. Sparar information om chattapplikationen är i användarens fokus.',
			'en' => 'Used for chat application functionality. Stores information about whether the chat application is in the user\'s focus.',
		);
	}

	public function retentionTranslations(): array
	{
		return array(
			'fi' => '-',
			'sv' => '-',
			'en' => '-'
		);
	}

	public function type(): string
	{
		return 'localstorage';
	}

	public function category(): string
	{
		return 'functional';
	}
}
