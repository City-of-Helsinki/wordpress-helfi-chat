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
                    'values' => array(
                        array(
                            'name' => __('Disabled', 'helsinki-chat'),
                            'value' => 'disabled'
                        ),
                        array(
                            'name' => 'Telia Ace',
                            'value' => 'telia-ace'
                        )
                    )
                ),
                array(
                    'id' => 'chat-visibility',
                    'name' => __('Chat visibility', 'helsinki-chat'),
                    'type' => 'select',
                    'description' => __('', 'helsinki-chat'),
                    'values' => array(
                        array(
                            'name' => __('Show on all pages', 'helsinki-chat'),
                            'value' => 'all'
                        ),
                        array(
                            'name' => __('Show on selected pages', 'helsinki-chat'),
                            'value' => 'selected'
                        ),
                    )
                ),
                array(
                    'id' => 'chat-pages',
                    'name' => __('Selected pages', 'helsinki-chat'),
                    'type' => 'combobox',
                    'class' => 'helsinki-chat-settings-chat-pages',
                    'description' => __('Search for a page by name', 'helsinki-chat'),
                ),
            ),
        ),
	),
    'telia-ace' => array(
        array(
            'id' => 'chat-telia-ace',
            'name' => __('Settings', 'helsinki-chat'),
            'page' => 'helsinki-chat-settings',
            'description' => __('Configure the chat service\'s parameters. Please request the chat-service provider for the information to the fields below.', 'helsinki-chat'),
            'options' => array(
                array(
                    'id' => 'chat-telia-ace-name',
                    'name' => __('Chat name', 'helsinki-chat'),
                    'type' => 'multiple-text',
                    'description' => __('Chat name will be displayed on the button to start the chat.', 'helsinki-chat'),
                    'fields' => array(
                        'fi' => __('Finnish', 'helsinki-chat'),
                        'en' => __('English', 'helsinki-chat'),
                        'sv' => __('Swedish', 'helsinki-chat'),
                    )
                ),
                array(
                    'id' => 'chat-telia-ace-service',
                    'name' => __('Service string', 'helsinki-chat'),
                    'type' => 'text'
                ),
                array(
                    'id' => 'chat-telia-ace-localization',
                    'name' => __('Localization', 'helsinki-chat'),
                    'type' => 'multiple-text',
                    'description' => __('Localisation determines the chat\'s text-contents and language. Chat will only be displayed for languages which have their localisation provided.', 'helsinki-chat'),
                    'fields' => array(
                        'fi' => __('Finnish', 'helsinki-chat'),
                        'en' => __('English', 'helsinki-chat'),
                        'sv' => __('Swedish', 'helsinki-chat'),
                    )
                ),
            ),
        )
    )
);
