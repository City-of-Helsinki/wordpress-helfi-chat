<?php

namespace CityOfHelsinki\WordPress\Chat\Integrations\Complianz;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

\add_filter( 'cmplz_known_script_tags', __NAMESPACE__ . '\\provide_known_script_tag' );
function provide_known_script_tag( array $tags ): array {
	$tags[] = array(
		'name' => cmplz_integration_name(),
		'category' => 'functional',
		'urls' => array(
			'https://apps.mypurecloud.ie/widgets/9.0/plugins/',
			'https://apps.mypurecloud.ie/widgets/9.0/cxbus.min.js',
			'https://wds.ace.teliacompany.com/wds/instances/%s/ACEWebSDK.min.js',
		),
	);

	return $tags;
}
