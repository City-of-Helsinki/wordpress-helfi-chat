<?php

namespace CityOfHelsinki\WordPress\Chat\Features\Assets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

\add_action( 'helsinki_chat_loaded', __NAMESPACE__ . '\\loaded' );
function loaded(): void {
	$assets = new Public_Assets(
		\apply_filters( 'helsinki_chat_plugin_path', '' ),
		\apply_filters( 'helsinki_chat_assets_url', '' ),
		\apply_filters( 'helsinki_chat_asset_version', '' ),
		\apply_filters( 'helsinki_chat_is_debug', false ),
		array( 'fi', 'en', 'sv' )
	);

	\add_action( 'wp_enqueue_scripts', array( $assets, 'publicStyles' ), 2 );
	\add_action( 'wp_enqueue_scripts', array( $assets, 'chatScripts' ), 1 );
	\add_filter( 'script_loader_tag', array( $assets, 'filterScript' ), 10, 3 );
}
