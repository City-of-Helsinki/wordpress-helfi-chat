<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\CookieConsent\Cookies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CityOfHelsinki\WordPress\CookieConsent\Features\Interfaces\Known_Cookie_Data;

final class CallGuide_Language implements Known_Cookie_Data
{
	public function issuer(): string
	{
		return 'Helsinki Chat';
	}

	public function name(): string
	{
		return 'CallGuide.language';
	}

	public function label(): string
	{
		return 'CallGuide.language';
	}

	public function descriptionTranslations(): array
	{
		return array(
			'fi' => 'Tallentaa Humany-chatin kielivalinnan istunnon ajaksi.',
			'sv' => 'Lagrar språkvalet för Humany-chatten under sessionen.',
			'en' => 'Stores the language selection for the Humany chat during the session.',
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
