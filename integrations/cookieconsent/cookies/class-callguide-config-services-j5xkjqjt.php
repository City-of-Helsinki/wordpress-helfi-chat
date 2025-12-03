<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\CookieConsent\Cookies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CityOfHelsinki\WordPress\CookieConsent\Features\Interfaces\Known_Cookie_Data;

final class CallGuide_Config_Services_J5XKjqJt implements Known_Cookie_Data
{
	public function issuer(): string
	{
		return 'Helsinki Chat';
	}

	public function name(): string
	{
		return 'CallGuide.config_services__J5XKjqJt';
	}

	public function label(): string
	{
		return 'CallGuide.config_services__J5XKjqJt';
	}

	public function descriptionTranslations(): array
	{
		return array(
			'fi' => 'Tallentaa Humany-chatin palvelukonfiguraation istunnon ajaksi.',
			'sv' => 'Lagrar tjänstekonfigurationen för Humany-chatten under sessionen.',
			'en' => 'Stores the service configuration for the Humany chat during the session.',
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
