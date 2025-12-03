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
			case 'genesys-v9':
				$this->chatGenesysV9( $settings );
				break;

			case 'genesys-watson':
				$this->chatGenesysWatson( $settings );
				break;

			case 'telia-ace':
				$this->chatTeliaAce( $settings );
				break;
		}
	}

	public function filterScript( $tag, $handle, $source ): string
	{
		if ( 'genesys-v9-base' === $handle ) {
			$tag = '<script type="text/javascript" src="' . $source . '" id="' . $handle . '" onload="javascript:CXBus.configure({pluginsPath:\'https://apps.mypurecloud.ie/widgets/9.0/plugins/\'}); CXBus.loadPlugin(\'widgets-core\');"></script>';
		}

		return $tag;
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

	protected function chatGenesysV9( array $settings ): void
	{
		if ( empty( $settings['chat-genesys-v9-service'] ) ) {
			return;
		}

		if ( empty( $settings['chat-genesys-v9-data-url'] ) ) {
			return;
		}

		$current_lang = $this->currentLanguage();

		if ( $this->isLanguageAllowed( $current_lang ) ) {
			$chat_name = $settings['chat-genesys-v9-name-' . $current_lang] ?? '';
			$localization = $settings['chat-genesys-v9-localization-' . $current_lang] ?? '';
		} else {
			$chat_name = $settings['chat-genesys-v9-name-en'] ?? '';
			$localization = $settings['chat-genesys-v9-localization-en'] ?? '';
		}

		if ( ! $localization ) {
			return;
		}

		$other_langs_enabled = isset($settings['chat-genesys-v9-enable-other-languages']) && $settings['chat-genesys-v9-enable-other-languages'] === 'on' ? true : false;
		$language_ok = $other_langs_enabled || $this->isLanguageAllowed( $current_lang );
		if ( ! $language_ok ) {
			return;
		}

		\wp_enqueue_script(
			'genesys-v9-base',
			'https://apps.mypurecloud.ie/widgets/9.0/cxbus.min.js',
			\apply_filters(
				'genesys_v9_scripts_dependencies',
				array('jquery')
			),
			$this->assets_version,
			false
		);

		\wp_enqueue_script(
			'genesys-v9',
			$this->assets_url . '/chat/genesys-v9/js/chat-genesys-gui-customization.js',
			\apply_filters(
				'genesys_v9_scripts_dependencies',
				array('jquery')
			),
			$this->assets_version,
			false
		);

		\wp_localize_script('genesys-v9', 'genesys_settings', array(
			'chat_name' => $chat_name ?: __('Start chat', 'helsinki-chat'),
			'chat_aria_label' => __('Start chat', 'helsinki-chat'),
			'service_string' => $settings['chat-genesys-v9-service'],
			'dataURL' => $settings['chat-genesys-v9-data-url'],
			'localization' => $localization,
			'language_code' => $this->isLanguageAllowed( $current_lang ) ? $current_lang : 'en',
			'chat_icon' => $this->createIcon( 'forms-data', 'speechbubble-text' ),
			'chat_arrow_icon' => $this->createIcon( 'arrows-operators', 'angle-up' ),
		) );

		\wp_enqueue_style(
			'genesys-v9-style',
			$this->assets_url . '/chat/genesys-v9/css/chat-genesys-gui-customization.css',
			\apply_filters(
				'genesys_style_dependencies',
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

	protected function chatGenesysWatson( array $settings ): void
	{
		if ( empty( $settings['chat-genesys-watson-identifier-hostname'] ) ) {
			return;
		}

		if ( empty( $settings['chat-genesys-watson-identifier-engagementId'] ) ) {
			return;
		}

		if ( empty( $settings['chat-genesys-watson-identifier-tenantId'] ) ) {
			return;
		}

		if ( empty( $settings['chat-genesys-watson-identifier-assistantId'] ) ) {
			return;
		}

		\wp_enqueue_script(
			'genesys-watson',
			sprintf(
				'%s/get-widget-button?tenantId=%s&assistantId=%s&engagementId=%s',
				$settings['chat-genesys-watson-identifier-hostname'],
				$settings['chat-genesys-watson-identifier-tenantId'],
				$settings['chat-genesys-watson-identifier-assistantId'],
				$settings['chat-genesys-watson-identifier-engagementId']
			),
			\apply_filters( 'genesys_watson_scripts_dependencies', array( 'jquery' ) ),
			$this->assets_version,
			false
		);
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
