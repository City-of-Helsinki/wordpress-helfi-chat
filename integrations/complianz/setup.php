<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\Complianz;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

\add_action( 'helsinki_chat_setup', __NAMESPACE__ . '\\setup' );
function setup(): void {
	\add_filter( 'cmplz_integrations', __NAMESPACE__ . '\\provide_cmplz_integration' );
	\add_filter( 'cmplz_integration_path', __NAMESPACE__ . '\\provide_cmplz_integration_path', 10, 2 );
}

function cmplz_integration_name(): string {
	return 'helsinki_chat';
}

function provide_cmplz_integration( array $integrations ): array {
	$integrations[cmplz_integration_name()] = array(
		'constant_or_function' => __NAMESPACE__ . '\\cmplz_integration_name',
		'label'                => __( 'Helsinki Chat', 'helsinki-chat' ),
		'firstparty_marketing' => false,
	);

	return $integrations;
}

function provide_cmplz_integration_path( string $path, string $integration ): string {
	if ( cmplz_integration_name() === $integration ) {
		return \apply_filters(
			'helsinki_chat_path_to_php_file',
			array( 'integrations', 'complianz', 'complianz' )
		);
	}

	return $path;
}
