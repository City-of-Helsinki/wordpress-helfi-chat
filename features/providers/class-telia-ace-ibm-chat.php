<?php

namespace CityOfHelsinki\WordPress\Chat\Features\Providers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Telia_Ace_IBM_Chat implements Chat_Provider
{
	private string $hostname;
	private string $engagementId;
	private string $tenantId;
	private string $assistantId;

	public function __construct( array $settings )
	{
		$this->setup_chat_config( $settings );
	}

	public function setup(): void
	{
		if ( $this->has_valid_config() ) {
			\wp_register_style( 'telia-ace-ibm', false );
			\wp_enqueue_style( 'telia-ace-ibm' );
			\wp_add_inline_style( 'telia-ace-ibm', $this->inline_styles() );

			\wp_enqueue_script(
				'telia-ace-ibm',
				sprintf(
					'%s/get-widget-button?tenantId=%s&assistantId=%s&engagementId=%s',
					$this->hostname,
					$this->tenantId,
					$this->assistantId,
					$this->engagementId
				),
				\apply_filters( 'genesys_watson_scripts_dependencies', array( 'jquery' ) ),
				\apply_filters( 'helsinki_chat_asset_version', '' ),
				false
			);
		}
	}

	private function inline_styles(): string
	{
		return '#aca-wbc-chat-app-button #chat-app-button-wrapper{max-width:calc(100vw - 24px);bottom:16px;right:16px;z-index:5!important;}
		@media only screen and (min-width:320px){#aca-wbc-chat-app-button #chat-app-button-wrapper{bottom:24px;}}
		@media only screen and (min-width:576px){#aca-wbc-chat-app-button #chat-app-button-wrapper{right:24px;}}
		@media only screen and (min-width:768px){#aca-wbc-chat-app-button #chat-app-button-wrapper{bottom:32px;right:24px;}}
		#aca-wbc-chat-app-button #chat-app-button-wrapper #chat-app-button:focus{outline:#1a1a1a solid 3px;}';
	}

	private function has_valid_config(): bool
	{
		return $this->hostname
			&& $this->engagementId
			&& $this->tenantId
			&& $this->assistantId;
	}

	private function setup_chat_config( array $settings ): void
	{
		$this->hostname = '';
		$this->engagementId = '';
		$this->tenantId = '';
		$this->assistantId = '';

		$fields = array(
			'hostname',
			'engagementId',
			'tenantId',
			'assistantId',
		);

		if ( ! $this->populate_chat_config( 'telia-ace-ibm', $fields, $settings ) ) {
			// Backwards compatibility
			$this->populate_chat_config( 'genesys-watson', $fields, $settings );
		}
	}

	private function populate_chat_config( string $provider, array $fields, array $settings ): bool
	{
		$populated = 0;

		foreach ( $fields as $field ) {
			$key = sprintf( 'chat-%s-identifier-%s', $provider, $field );

			if ( ! empty( $settings[$key] ) ) {
				$this->$field = $settings[$key];
				$populated++;
			}
		}

		return count( $fields ) === $populated;
	}
}
