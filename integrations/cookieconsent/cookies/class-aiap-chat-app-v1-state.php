<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\CookieConsent\Cookies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CityOfHelsinki\WordPress\CookieConsent\Features\Interfaces\Known_Cookie_Data;

final class Aiap_Chat_App_V1_State implements Known_Cookie_Data
{
	public function issuer(): string
	{
		return 'Helsinki Chat';
	}

	public function name(): string
	{
		return 'aiap-chat-app-v1-state';
	}

	public function label(): string
	{
		return 'aiap-chat-app-v1-state';
	}

	public function descriptionTranslations(): array
	{
		return array(
			'fi' => 'Käytetään chat-sovelluksen toimintaan. Säilyttää chat-sovelluksen asetukset, jotka vaikuttavat sovelluksen toimintaan ja ulkoasuun.',
			'sv' => 'Används för chattapplikationens funktion. Sparar chattapplikationens inställningar som påverkar applikationens funktion och utseende.',
			'en' => 'Used for chat application functionality. Stores chat application settings and configuration data to control chat application behaviour and appearance.',
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
