<?php

declare(strict_types = 1);

namespace CityOfHelsinki\WordPress\Chat\Integrations\WPHelfiCookieConsent;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

\add_filter( 'wordpress_helfi_cookie_consent_known_cookies', __NAMESPACE__ . '\\provide_cookies' );
function provide_cookies( array $cookies ): array {
	if ( should_load_cookies() ) {
		$path = \plugin_dir_path( __FILE__ );

		foreach( cookie_classes_and_files() as $class_name => $file ) {
			require_once $path . 'cookies' . DIRECTORY_SEPARATOR . $file;

			$cookies[] = __NAMESPACE__ . '\\Cookies\\' . $class_name;
		}
	}

	return $cookies;
}

function should_load_cookies(): bool {
	$settings = \get_option( 'helsinki-chat-settings', array() );

	return ! empty( $settings['chat-selection'] )
		&& 'disabled' !== $settings['chat-selection'];
}

function cookie_classes_and_files(): array {
	return array(
		'Aiap_Chat_App_V1_State' => 'class-aiap-chat-app-v1-state.php',
		'Aiap_User' => 'class-aiap-user.php',
		'Aiap_Wbc_Chat_App_Button_Options' => 'class-aiap-wbc-chat-app-button-options.php',
		'Aiap_Wbc_Chat_App_Button_State' => 'class-aiap-wbc-chat-app-button-state.php',
		'ARRAffinity' => 'class-arraffinity.php',
		'ARRAffinitySameSite' => 'class-arraffinitysamesite.php',
		'CallGuide_Config_Services_J5XKjqJt' => 'class-callguide-config-services-j5xkjqjt.php',
		'CallGuide_Language' => 'class-callguide-language.php',
		'Conversion_Token' => 'class-conversion-token.php',
		'Genesys_Widgets_Infocus' => 'class-genesys-widgets-infocus.php',
		'Humany_Dash_Local_Storage' => 'class-humany-dash-local-storage.php',
		'Humany_Dash_Session_Storage' => 'class-humany-dash-session-storage.php',
		'Humany_Local_Storage' => 'class-humany-local-storage.php',
		'Humany_Session_Storage' => 'class-humany-session-storage.php',
		'J5XKjqJt_ACEChatState_ActiveClient' => 'class-j5xkjqjt-acechatstate-activeclient.php',
		'J5XKjqJt_CGWebSDK_VideoClients' => 'class-j5xkjqjt-cgwebsdk-videoclients.php',
		'J5XKjqJt_CGWebSDK_VideoShower' => 'class-j5xkjqjt-cgwebsdk-videoshower.php',
		'J5XKjqJt_CGWebSDK_WindowGUID' => 'class-j5xkjqjt-cgwebsdk-windowguid.php',
		'J5XKjqJt_ChatClient_MaxObjectId' => 'class-j5xkjqjt-chatclient-maxobjectid.php',
		'J5XKjqJt_ChatEntrance' => 'class-j5xkjqjt-chatentrance.php',
		'User' => 'class-user.php',
	);
}
