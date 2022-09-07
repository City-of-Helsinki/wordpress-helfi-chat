<?php

/**
  * Plugin Name: Helsinki Chat
  * Description: Adds chat integrations for various chat services.
  * Version: 1.0.0
  * License: MIT
  * Requires at least: 5.7
  * Requires PHP:      7.1
  * Author: Broomu Digitals
  * Author URI: https://www.broomudigitals.fi
  * Text Domain: helsinki-chat
  * Domain Path: /languages
  */

namespace CityOfHelsinki\WordPress\Chat;

/**
 * Constants
*/
define( __NAMESPACE__ . '\\PLUGIN_VERSION', '1.0.0' );
define( __NAMESPACE__ . '\\PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( __NAMESPACE__ . '\\PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( __NAMESPACE__ . '\\PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
  

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\\init', 100 );
function init() {


	/**
	  * Plugin parts
	  */
	require_once 'functions.php';
	require_once 'settings/settings.php';

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
