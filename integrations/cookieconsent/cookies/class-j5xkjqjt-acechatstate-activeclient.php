<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\CookieConsent\Cookies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CityOfHelsinki\WordPress\CookieConsent\Features\Interfaces\Known_Cookie_Data;

final class J5XKjqJt_ACEChatState_ActiveClient implements Known_Cookie_Data
{
	public function issuer(): string
	{
		return 'wds.ace.teliacompany.com';
	}

	public function name(): string
	{
		return 'J5XKjqJt_ACEChatState_activeClient';
	}

	public function label(): string
	{
		return 'J5XKjqJt_ACEChatState_activeClient';
	}

	public function descriptionTranslations(): array
	{
		return array(
			'fi' => 'Käytetään chat-asiakkaan tilan tallentamiseen.',
			'sv' => 'Används för att lagra chattklientens tillstånd.',
			'en' => 'Used to store chat client state.',
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
