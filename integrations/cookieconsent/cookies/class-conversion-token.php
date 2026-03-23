<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\CookieConsent\Cookies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CityOfHelsinki\WordPress\CookieConsent\Features\Interfaces\Known_Cookie_Data;

final class Conversion_Token implements Known_Cookie_Data
{
	public function issuer(): string
	{
		return 'coh-chat-app-prod.ow6i4n9pdzm.eu-de.codeengine.appdomain.cloud';
	}

	public function name(): string
	{
		return 'conversationToken';
	}

	public function label(): string
	{
		return 'conversationToken';
	}

	public function descriptionTranslations(): array
	{
		return array(
			'fi' => 'Käytetään chat-sovellustoiminnallisuutta varten. Säilyttää chat-sovelluksen keskustelutunnisteen istunnnon tunnistamista ja tietojen hakemista varten.',
			'sv' => 'Används för chattapplikationens funktionalitet. Lagrar konversationstoken för autentisering och åtkomst till dataändamål.',
			'en' => 'Used for chat app functionality. Stores chat app conversation token for authentication and data access purposes.',
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
