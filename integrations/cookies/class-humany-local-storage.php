<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\WPHelfiCookieConsent\Cookies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CityOfHelsinki\WordPress\CookieConsent\Features\Interfaces\Known_Cookie_Data;

final class Humany_Local_Storage implements Known_Cookie_Data
{
	public function issuer(): string
	{
		return 'Helsinki Chat';
	}

	public function name(): string
	{
		return 'humany_*';
	}

	public function label(): string
	{
		return 'humany_*';
	}

	public function descriptionTranslations(): array
	{
		return array(
			'fi' => 'Humany-chatin käyttämä paikallinen tallennus.',
			'sv' => 'Lokal lagring som används av Humany-chat.',
			'en' => 'Local storage used by the Humany chat.',
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
