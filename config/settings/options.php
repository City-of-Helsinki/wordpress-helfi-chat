<?php
return array(
	'general' => array(
        array(
            'id' => 'chat-general',
            'name' => __('General', 'helsinki-chat'),
            'page' => 'helsinki-chat-settings',
            'description' => __('', 'helsinki-chat'),
            'options' => array(
                array(
                    'id' => 'chat-selection',
                    'name' => __('Chat', 'helsinki-chat'),
                    'type' => 'select',
                    'description' => __('', 'helsinki-chat'),
                    'default' => 'on',
                    'values' => array(
                        array(
                            'name' => 'Genesys v9',
                            'value' => 'genesys-v9'
                        )
                    )
                ),
            ),
        ),
	),
	'genesys-v9' => array(
        array(
            'id' => 'chat-genesys-v9',
            'name' => __('Genesys v9 Settings', 'helsinki-chat'),
            'page' => 'helsinki-chat-settings',
            'description' => __('Configure Genesys v9 parameters.', 'helsinki-chat'),
            'options' => array(
                array(
                    'id' => 'chat-genesys-v9-service',
                    'name' => __('Service string', 'helsinki-chat'),
                    'type' => 'text'
                ),
                array(
                    'id' => 'chat-genesys-v9-data-url',
                    'name' => __('Data URL', 'helsinki-chat'),
                    'type' => 'text'
                ),
                array(
                    'id' => 'chat-genesys-v9-localization',
                    'name' => __('Localization', 'helsinki-chat'),
                    'type' => 'multiple-text',
                    'fields' => array(
                        'fi' => __('Finnish', 'helsinki-chat'),
                        'en' => __('English', 'helsinki-chat'),
                        'sv' => __('Swedish', 'helsinki-chat'),
                    )
                ),
                array(
                    'id' => 'chat-genesys-v9-enable-other-languages',
                    'name' => __('Enable chat for other languages', 'helsinki-chat'),
                    'type' => 'checkbox',
                    'description' => __('English chat will be displayed for other languages. Localization must be set for English.', 'helsinki-chat'),
                    'default' => 'on',
                ),
            ),
        ),
	),
);
