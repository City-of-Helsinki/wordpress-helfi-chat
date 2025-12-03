<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function textdomain(): void {
	\load_plugin_textdomain(
		'helsinki-chat',
		false,
		\apply_filters( 'helsinki_chat_plugin_dirname', '' ) . '/languages'
	);
}

function current_language(): string {
	return substr( \get_locale(), 0, 2 );
}
