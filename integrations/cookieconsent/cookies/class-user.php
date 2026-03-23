<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\CookieConsent\Cookies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CityOfHelsinki\WordPress\CookieConsent\Features\Interfaces\Known_Cookie_Data;

final class User implements Known_Cookie_Data
{
	public function issuer(): string
	{
		return 'coh-chat-app-prod.ow6i4n9pdzm.eu-de.codeengine.appdomain.cloud';
	}

	public function name(): string
	{
		return 'user';
	}

	public function label(): string
	{
		return 'user';
	}

	public function descriptionTranslations(): array
	{
		return array(
			'fi' => 'Käytetään chat-sovelluksen toimintaan. Muistaa istunnon tiedot, joita tarvitaan chatkeskustelussa.',
			'sv' => 'Används för chattapplikationens funktion. Kommer ihåg de uppgifter om sessionen som behövs i chattdiskussionen.',
			'en' => 'Used for chat application functionality. Stores information about chat session that is used in the dialogue during the conversation. ',
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
