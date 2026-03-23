<?php

namespace CityOfHelsinki\WordPress\Chat\Features\Providers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

\add_action( 'helsinki_chat_loaded', __NAMESPACE__ . '\\loaded' );
function loaded(): void {
	\add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\setup_chat', 1 );
}

function setup_chat(): void {
	$chat = (new Chat_Provider_Factory())->from_settings(
		\apply_filters( 'helsinki_chat_settings', array() )
	);

	if ( $chat ) {
		$chat->setup();
	}
}
