<?php

namespace CityOfHelsinki\WordPress\Chat\Features\Providers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Chat_Provider_Factory
{
	private array $providers;

	public function __construct()
	{
		$this->providers = array(
			'telia-ace' => Telia_Ace_Chat::class,
		);
	}

	public function from_settings( array $settings ): ?Chat_Provider
	{
		if ( ! $this->is_chat_visible( $settings ) ) {
			return null;
		}

		$chat = $settings['chat-selection'] ?? '';
		if ( ! isset( $this->providers[$chat] ) ) {
			return null;
		}

		$chat = $this->providers[$chat];

		return new $chat( $settings );
	}

	private function is_chat_visible( array $settings ): bool
	{
		$chat = $settings['chat-selection'] ?? '';
		if ( ! $chat || 'disabled' === $chat ) {
			return false;
		}

		$visibility = $settings['chat-visibility'] ?? 'all';

		if ( 'selected' === $visibility ) {
			$pages = ! empty( $settings['chat-pages'] )
				? explode( ',', $settings['chat-pages'] )
				: array();

			return in_array( $this->current_page_id(), $pages );
		}

		return true;
	}

	private function current_page_id(): int
	{
		return \is_front_page()
			? (int) \get_option( 'page_on_front' )
			: \get_the_ID();
	}
}
