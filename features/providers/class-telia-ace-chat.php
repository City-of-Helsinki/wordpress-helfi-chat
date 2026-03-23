<?php

namespace CityOfHelsinki\WordPress\Chat\Features\Providers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Telia_Ace_Chat implements Chat_Provider
{
	private string $chat_service;
	private string $chat_name;
	private string $localization;

	public function __construct( array $settings )
	{
		$this->setup_chat_config( $settings );
	}

	public function setup(): void
	{
		if ( $this->has_valid_config() ) {
			\wp_enqueue_script(
				'telia-ace',
				sprintf(
					'https://wds.ace.teliacompany.com/wds/instances/%s/ACEWebSDK.min.js',
					$this->chat_service
				),
				\apply_filters( 'telia_ace_scripts_dependencies', array( 'jquery' ) ),
				\apply_filters( 'helsinki_chat_asset_version', '' ),
				array(
					'strategy' => 'defer',
					'in_footer' => false,
				)
			);

			\wp_register_style( 'telia-ace', false );
			\wp_enqueue_style( 'telia-ace' );
			\wp_add_inline_style( 'telia-ace', $this->inline_styles() );

			\add_action( 'wp_footer', function() {
				printf(
					'<a class="telia-ace-chat-button" href="%s">%s</a>',
					\esc_url( $this->localization ),
					\esc_html( $this->chat_name )
				);
			} );
		}
	}

	private function inline_styles(): string
	{
		return '.telia-ace-chat-button {box-sizing: content-box; display: none;} .telia-ace-chat-button:focus{outline-offset: 2px;}';
	}

	private function has_valid_config(): bool
	{
		return $this->chat_service
			&& $this->chat_name
			&& $this->localization;
	}

	private function setup_chat_config( array $settings ): void
	{
		$this->chat_service = '';
		$this->chat_name = '';
		$this->localization = '';

		if ( ! empty( $settings['chat-telia-ace-service'] ) ) {
			$lang = \apply_filters( 'helsinki_chat_current_language', 'en' );

			if ( in_array( $lang, array( 'fi', 'en', 'sv' ) ) ) {
				$this->chat_service = $settings['chat-telia-ace-service'];
				$this->chat_name = $settings['chat-telia-ace-name-' . $lang] ?? '';
				$this->localization = $settings['chat-telia-ace-localization-' . $lang] ?? '';
			}
		}
	}
}
