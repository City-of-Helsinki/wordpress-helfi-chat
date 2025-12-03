<?php
/**
  * Plugin Name: Helsinki Chat
  * Description: Adds chat integrations for various chat services.
  * Version: 3.0.0
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

/**
  * Setup
  */
require_once \plugin_dir_path( __FILE__ ) . 'constants.php';
define_constants( __FILE__ );

require_once \plugin_dir_path( __FILE__ ) . 'functions.php';
load_includes();

spl_autoload_register( __NAMESPACE__ . '\\class_loader' );

\add_action( 'helsinki_chat_setup', __NAMESPACE__ . '\\setup_filters', 0 );
\add_action( 'helsinki_chat_setup', __NAMESPACE__ . '\\load_features', 1 );
\add_action( 'helsinki_chat_setup', __NAMESPACE__ . '\\load_integrations', 1 );

\add_action( 'plugins_loaded', __NAMESPACE__ . '\\setup', 1 );
\add_action( 'plugins_loaded', __NAMESPACE__ . '\\loaded', 10 );

/**
  * Init
  */
\add_action( 'init', __NAMESPACE__ . '\\textdomain' );
\add_action( 'init', __NAMESPACE__ . '\\init', 100 );

/**
  * Activation
  */
// \register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate' );

/**
  * Deactivation
  */
// \register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate' );

/**
  * Uninstall
  */
// \register_uninstall_hook( __FILE__, __NAMESPACE__ . '\\uninstall' );
