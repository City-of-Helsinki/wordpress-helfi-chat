<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\WPHelfiCookieConsent\Cookies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CityOfHelsinki\WordPress\CookieConsent\Features\Interfaces\Known_Cookie_Data;

final class Aiap_Wbc_Chat_App_Button_State implements Known_Cookie_Data
{
	public function issuer(): string
	{
		return 'Helsinki Chat';
	}

	public function name(): string
	{
		return 'aiap-wbc-chat-app-button-state';
	}

	public function label(): string
	{
		return 'aiap-wbc-chat-app-button-state';
	}

	public function descriptionTranslations(): array
	{
		return array(
			'fi' => 'Käytetään chat-sovelluksen painikkeen toiminnassa. Säilyttää asetukset, jotka vaikuttavat painikkeen toimintaan ja ulkoasuun.',
			'sv' => 'Används för chattapplikationsknappens funktion. Sparar de inställningar som påverkar knappens funktion och utseende.',
			'en' => 'Used for chat application button functionality. Stores chat app button settings and configuration data to control chat application button behaviour and appearance.',
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
