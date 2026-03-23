<?php

namespace CityOfHelsinki\WordPress\Chat\Features\Assets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use ArtCloud\Helsinki\Plugin\HDS\Svg;
use WP_Query;

final class Public_Assets
{
	public function __construct(
		private string $plugin_path,
		private string $assets_url,
		private string $assets_version,
		private bool $is_debug,
		private array $allowed_languages
	) {}

	public function chatScripts(): void
	{
		$settings = \apply_filters( 'helsinki_chat_settings', array() );

		if ( ! $this->isChatVisible( $settings ) ) {
			return;
		}

		$chat = $settings['chat-selection'] ?? '';
		switch ( $chat ) {

			case 'telia-ace':
				$this->chatTeliaAce( $settings );
				break;
		}
	}

	public function publicStyles(): void
	{
		\wp_enqueue_style(
			'chat-wp-styles',
			$this->assets_url . '/public/css/styles' . ( $this->is_debug ? '.min.css' : '.css' ),
			\apply_filters(
				'chat_styles_dependencies',
				array( 'wp-block-library' )
			),
			$this->assets_version,
			'all'
		);
	}

	protected function createIcon( string $group, string $name ): string
	{
		return class_exists(Svg::class) ? Svg::icon( $group, $name ) : '';
	}

	protected function chatTeliaAce( array $settings ): void
	{
		if ( empty( $settings['chat-telia-ace-service'] ) ) {
			return;
		}

		$current_lang = $this->currentLanguage();

		if ( $this->isLanguageAllowed( $current_lang ) ) {
			$chat_name = $settings['chat-telia-ace-name-' . $current_lang] ?? '';
			$localization = $settings['chat-telia-ace-localization-' . $current_lang] ?? '';
		} else {
			$chat_name = '';
			$localization = '';
		}

		if ( ! $localization ) {
			return;
		}

		\wp_enqueue_script(
			'telia-ace',
			sprintf(
				'https://wds.ace.teliacompany.com/wds/instances/%s/ACEWebSDK.min.js',
				$settings['chat-telia-ace-service']
			),
			\apply_filters(
				'telia_ace_scripts_dependencies',
				array('jquery')
			),
			$this->assets_version,
			array(
				'strategy' => 'defer',
				'in_footer' => false,
			)
		);

		\add_action('wp_footer', function() use ($chat_name, $localization) {
			printf(
				'<a class="telia-ace-chat-button" href="%s">%s</a>',
				\esc_url( $localization ),
				\esc_html( $chat_name )
			);
		});
	}

	protected function isChatVisible( array $settings ): bool
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

			return in_array( $this->currentPageId(), $pages );
		}

		return true;
	}

	protected function currentPageId(): int
	{
		return \is_front_page() ? (int) \get_option( 'page_on_front' ) : \get_the_ID();
	}

	protected function isLanguageAllowed( string $lang ): bool
	{
		return in_array( $lang, $this->allowed_languages );
	}

	protected function currentLanguage(): string
	{
		return \apply_filters( 'helsinki_chat_current_language', 'en' );
	}
}
