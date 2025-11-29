<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\WPHelfiCookieConsent\Cookies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CityOfHelsinki\WordPress\CookieConsent\Features\Interfaces\Known_Cookie_Data;

final class ARRAffinity implements Known_Cookie_Data
{
	public function issuer(): string
	{
		return '.hel.humany.net';
	}

	public function name(): string
	{
		return 'ARRAffinity';
	}

	public function label(): string
	{
		return 'ARRAffinity';
	}

	public function descriptionTranslations(): array
	{
		return array(
			'fi' => 'Käytetään Humany-chatin palvelinistunnon kuormantasaukseen.',
			'sv' => 'Används för lastbalansering av Humany-chattens server-session.',
			'en' => 'Used for load balancing of the Humany chat server session.',
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
		return 'cookie';
	}

	public function category(): string
	{
		return 'functional';
	}
}
