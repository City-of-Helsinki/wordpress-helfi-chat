<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\WPHelfiCookieConsent\Cookies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CityOfHelsinki\WordPress\CookieConsent\Features\Interfaces\Known_Cookie_Data;

final class J5XKjqJt_ChatClient_MaxObjectId implements Known_Cookie_Data
{
	public function issuer(): string
	{
		return 'wds.ace.teliacompany.com';
	}

	public function name(): string
	{
		return 'J5XKjqJt_chatClient_maxObjectId';
	}

	public function label(): string
	{
		return 'J5XKjqJt_chatClient_maxObjectId';
	}

	public function descriptionTranslations(): array
	{
		return array(
			'fi' => 'Tallentaa chat-asiakkaan suurimman objektin tunnisteen.',
			'sv' => 'Lagrar chattklientens högsta objekt-ID.',
			'en' => 'Stores chat client’s highest object ID.',
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
