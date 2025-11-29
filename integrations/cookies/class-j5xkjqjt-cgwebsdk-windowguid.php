<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\WPHelfiCookieConsent\Cookies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CityOfHelsinki\WordPress\CookieConsent\Features\Interfaces\Known_Cookie_Data;

final class J5XKjqJt_CGWebSDK_WindowGUID implements Known_Cookie_Data
{
	public function issuer(): string
	{
		return 'wds.ace.teliacompany.com';
	}

	public function name(): string
	{
		return 'J5XKjqJt_CGWebSDK_windowGUID';
	}

	public function label(): string
	{
		return 'J5XKjqJt_CGWebSDK_windowGUID';
	}

	public function descriptionTranslations(): array
	{
		return array(
			'fi' => 'Käytetään chat-sovelluksen toimintaan. Säilyttää chat-sovelluksen istunnon tunnisteen.',
			'sv' => 'Används för chattapplikationens funktion. Sparar chatapplikationens sessionidentifierare.',
			'en' => 'Used for chat application functionality. Stores chat application session identifier.',
		);
	}

	public function retentionTranslations(): array
	{
		return array(
			'fi' => 'Istunto',
			'sv' => 'Session',
			'en' => 'Session'
		);
	}

	public function type(): string
	{
		return 'sessionstorage';
	}

	public function category(): string
	{
		return 'functional';
	}
}
