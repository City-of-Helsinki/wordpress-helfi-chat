<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\WPHelfiCookieConsent\Cookies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CityOfHelsinki\WordPress\CookieConsent\Features\Interfaces\Known_Cookie_Data;

final class J5XKjqJt_ChatEntrance implements Known_Cookie_Data
{
	public function issuer(): string
	{
		return 'wds.ace.teliacompany.com';
	}

	public function name(): string
	{
		return 'J5XKjqJt_chatEntrance';
	}

	public function label(): string
	{
		return 'J5XKjqJt_chatEntrance';
	}

	public function descriptionTranslations(): array
	{
		return array(
			'fi' => 'Tallentaa chatin sisäänkäyntitiedot.',
			'sv' => 'Lagrar inmatningsinformation för chatten.',
			'en' => 'Stores chat entrance information.',
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
