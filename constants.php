<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
  * Condifugration
  */
function define_constants(string $file) : void {
	if ( ! function_exists('get_plugin_data') ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	$pluginData = \get_plugin_data( $file, false, false );
	$dirname = dirname( $file );
	$basename = basename( $file );
	$dirbasename = basename( $dirname );

	define( __NAMESPACE__ . '\\PLUGIN_VERSION', $pluginData[ 'Version' ] );
	define( __NAMESPACE__ . '\\PLUGIN_MAIN_FILE', $file );
	define( __NAMESPACE__ . '\\PLUGIN_PATH', $dirname . DIRECTORY_SEPARATOR );
	define( __NAMESPACE__ . '\\PLUGIN_DIRNAME', $dirbasename );
	define( __NAMESPACE__ . '\\PLUGIN_BASENAME', $basename );
	define( __NAMESPACE__ . '\\PLUGIN_SLUG', str_replace( '-', '_', PLUGIN_DIRNAME ) );
	define( __NAMESPACE__ . '\\PLUGIN_NAME', $dirbasename . DIRECTORY_SEPARATOR . $basename );
	define( __NAMESPACE__ . '\\PLUGIN_URL', \plugin_dir_url( $file ) );
}

/**
  * Helpers
  */
function plugin_version() : string {
	return PLUGIN_VERSION;
}

function plugin_main_file() : string {
	return PLUGIN_MAIN_FILE;
}

function plugin_path() : string {
	return PLUGIN_PATH;
}

function plugin_dirname() : string {
	return PLUGIN_DIRNAME;
}

function plugin_basename() : string {
	return PLUGIN_BASENAME;
}

function plugin_slug() : string {
	return PLUGIN_SLUG;
}

function plugin_name() : string {
	return PLUGIN_NAME;
}

function plugin_url() : string {
	return PLUGIN_URL;
}

function is_debug() : bool {
	return defined( 'WP_DEBUG' ) && WP_DEBUG;
}

function asset_version() : string {
	return is_debug() ? (string) time() : plugin_version();
}

function assets_url() : string {
	return PLUGIN_URL . 'assets/';
}

function script_url() : string {
	return assets_url() . 'js/';
}

function style_url() : string {
	return assets_url() . 'css/';
}
