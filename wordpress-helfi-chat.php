<?php

/**
  * Plugin Name: Helsinki Chat
  * Description: Adds chat integrations for various chat services.
  * Version: 2.0.0
  * License: GPLv3
  * Requires at least: 6.7
  * Requires PHP:      8.2
  * Author: Broomu Digitals
  * Author URI: https://www.broomudigitals.fi
  * Text Domain: helsinki-chat
  * Domain Path: /languages
  */

namespace CityOfHelsinki\WordPress\Chat;

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

function define_constants( string $file ) : void {
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

define_constants( __FILE__ );

add_action( 'plugins_loaded', __NAMESPACE__ . '\\init', 100 );
function init() {


	/**
	  * Plugin parts
	  */
	require_once 'functions.php';
	require_once 'settings/settings.php';
	require_once 'integrations/wp-helfi-cookie-consent.php';

	/**
	 * Assets
	 */
	require_once 'class/assets.php';

	//spl_autoload_register( __NAMESPACE__ . '\\autoloader' );

	/**
	  * Actions & filters
	  */
	add_action( 'init', __NAMESPACE__ . '\\textdomain' );

	/**
	  * Plugin ready
	  */
	do_action( 'helsinki_chat_init' );
}
