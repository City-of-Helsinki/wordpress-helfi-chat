<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function setup() : void {
	if ( ! \did_action( 'helsinki_chat_setup' ) ) {
		\do_action( 'helsinki_chat_setup' );
	}
}

function loaded() : void {
	if ( ! \did_action( 'helsinki_chat_loaded' ) ) {
		\do_action( 'helsinki_chat_loaded' );
	}
}

function init() : void {
	if ( ! \did_action( 'helsinki_chat_init' ) ) {
		\do_action( 'helsinki_chat_init' );
	}
}

function activate() : void {
	setup();

	\do_action( 'helsinki_chat_activate' );
}

function deactivate() : void {
	setup();

	\do_action( 'helsinki_chat_deactivate' );
}

function uninstall() : void {
	setup();

	\do_action( 'helsinki_chat_uninstall' );
}
