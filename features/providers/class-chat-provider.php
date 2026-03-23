<?php

namespace CityOfHelsinki\WordPress\Chat\Features\Providers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Chat_Provider
{
	public function setup(): void;
}
