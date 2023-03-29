<?php

namespace CityOfHelsinki\WordPress\Chat;

define(__NAMESPACE__ . '\\PAGE_SLUG', 'helsinki-chat-settings');

add_action( 'admin_init', __NAMESPACE__ . '\\chat_register_settings');
add_action( 'admin_menu', __NAMESPACE__ . '\\chat_settings_page' );
add_action( 'helsinki_chat_settings_tab_panel', __NAMESPACE__ . '\\chat_renderTabPanel' );
add_action( 'helsinki_chat_init', __NAMESPACE__ . '\\chat_settings_defaults');
//add_action( 'helsinki_chat_init', __NAMESPACE__ . '\\chat_register_polylang_strings');

$tabs = array();

function chat_settings_page() {
    add_menu_page(
        __('Chat', 'helsinki-chat'),
        __('Chat', 'helsinki-chat'),
        apply_filters(
            'helsinki_chat_settings_page_capability_requirement',
            'manage_options'
        ),
        PAGE_SLUG,
        __NAMESPACE__ . '\\chat_settings_renderpage',
        'dashicons-format-chat',
        null
    );
}

function chat_settings_renderpage() {
    $tabs = include PLUGIN_PATH . 'config/settings/tabs.php';

    include_once PLUGIN_PATH . 'views/settings/page.php';
}

function chat_renderTabPanel( string $tab ) {
    $tabs = include PLUGIN_PATH . 'config/settings/tabs.php';
    if ( ! isset( $tabs[$tab] ) ) {
        return;
    }

    $settingsConfig = chat_settingsConfig( $tab );
    $page = $settingsConfig['page'];
    $section = $settingsConfig['section'];
    unset( $settingsConfig );

    include_once PLUGIN_PATH . 'views/settings/form.php';
}

function chat_settingsConfig( string $tab ) {
    return array(
        'page' => PAGE_SLUG,
        'section' => $tab,
    );
}

function chat_sanitizeSettings( $option ) {
    if ( is_array( $option ) ) {
        $out = array();
        foreach ($option as $key => $value) {
            if (str_starts_with($key, 'wp_')) {
                $out[$key] = wp_kses_post( $value );
            }
            else {
                $out[$key] = sanitize_text_field( $value );
            }
        }
        return $out;
    } else {
        return sanitize_text_field( $option );
    }
}

function chat_settings_defaults() {
    $settings = get_option(PAGE_SLUG, array());
    if (empty($settings)) {
        $config = include PLUGIN_PATH . 'config/settings/options.php';
        $defaults = array();
        foreach ($config as $tab) {
            foreach($tab as $section) {
                foreach($section['options'] as $option) {
                    if (isset($option['default'])) {
                        $defaults[$option['id']] = $option['default'];
                    }
                }
            }
        }
        add_option(PAGE_SLUG, $defaults);
    } 
}

function chat_register_settings() {
    $settings = include PLUGIN_PATH . 'config/settings/options.php';
    register_setting( PAGE_SLUG, PAGE_SLUG, 
        array(
            'type' => 'array',
            'description' => '',
            'sanitize_callback' => __NAMESPACE__ . '\\chat_sanitizeSettings',
            'show_in_rest' => false,
            'default' => array(
                'enabled' => null,
            )
        )
    );
    foreach ($settings as $tab) {
        foreach($tab as $section) {
            chat_settings_add_section($section);
        }
    }
}

function chat_settings_add_section($section) {
    add_settings_section($section['id'], $section['name'], function() use ($section) { if (isset($section['description'])) { chat_settings_section_description($section['description']); } }, $section['page'], array('before_section' => '<div class="helsinki-chat-settings-section helsinki-chat-section-'. $section['id'] .'">', 'after_section' => '</div>'));
    foreach ($section['options'] as $option) {
        chat_settings_add_option($option, $section['id'], $section['page']);
    }
}

function chat_settings_section_description($description) {
    printf(
        '<p>%s</p>',
        $description
    );
}

function chat_settings_add_option($option, $section, $page) {
    $option['page'] = $page;
    add_settings_field($option['id'], $option['name'], __NAMESPACE__ . '\\chat_settings_option_callback', $page, $section, $option);
}

function chat_settings_option_callback(array $args) {
    chat_settings_input($args);
}

function chat_settings_input(array $args) {
    $description = '';
    if (isset($args['description'])) {
        $description = sprintf(
            '<p class="description">%s</p>',
            $args['description']
        );
    }

    $option = '';
    $settings = get_option(PAGE_SLUG, array());
    if (isset($settings[$args['id']])) {
        $option = $settings[$args['id']];
    }
    /*if (empty($option) && isset($args['default'])) {
        $option = $args['default'];
    }*/
    $value = '';

    if (isset($option)) {
        if ($args['type'] === 'checkbox') {
            if ($option === 'on') {
                $value = 'checked';
            }
        }
        else if ($args['type'] === 'textarea' || $args['type'] === 'editor' || $args['type'] === 'select') {
            $value = esc_attr($option);
        }
        else {
            $value = sprintf(
                'value="%s"',
                esc_attr($option)
            );
        }
    }

    if ($args['type'] === 'textarea') {
        printf(
            '<textarea class="large-text" name="%s[%s]" rows="4" id="%s">%s</textarea>%s',
            $args['page'],
            $args['id'],
            $args['id'],
            $value,
            $description
        );
    }
    else if ($args['type'] === 'editor') {
        wp_editor(
            html_entity_decode($value),
            $args['id'],
            array(
                'textarea_name' => $args['page'] . '[' . $args['id'] . ']',
                'media_buttons' => false,
                'tinymce' => array(
                    'toolbar1' => 'formatselect,bold,italic,bullist,numlist,link',
                )
            )
        );
    }
    else if ($args['type'] === 'multiple-text') {
        echo '<ul>';
        foreach ($args['fields'] as $key => $field) {
            printf(
                '<li><label for="%s">%s</label><input class="regular-text" name="%s[%s]" type="text" id="%s" %s></li>',
                $args['id'] . '-' . $key,
                $field,
                $args['page'],
                $args['id'] . '-' . $key,
                $args['id'] . '-' . $key,
                isset($settings[$args['id'].'-'.$key]) ? 'value="'.$settings[$args['id'].'-'.$key].'"' : ''
            );    
        }
        printf(
            '%s',
            $description
        );
        echo '</ul>';
    }
    else if ($args['type'] === 'select') {
        $list = '';
        foreach($args['values'] as $listvalue) {
            $list .= sprintf(
                '<option value="%s" %s>%s</option>',
                $listvalue['value'],
                $listvalue['value'] == $value ? 'selected="selected"' : '',
                $listvalue['name']
            );
        }

        printf(
            '<select name="%s[%s]" id="%s">%s</select>',
            $args['page'],
            $args['id'],
            $args['id'],
            $list,
        );
    }
    else {
        printf(
            '<input class="regular-text" name="%s[%s]" type="%s" id="%s" %s>%s',
            $args['page'],
            $args['id'],
            $args['type'],
            $args['id'],
            $value,
            $description
        );
    }
}

function chat_register_polylang_strings() {
    if (function_exists('pll_register_string')) {
        $config = include PLUGIN_PATH . 'config/settings/options.php';
        $settings = get_option(PAGE_SLUG, array());

        foreach($config as $tab) {
            foreach($tab as $section) {
                foreach($section['options'] as $option) {
                    if ($option['type'] === 'text') {
                        if (isset($settings[$option['id']])) {
                            pll_register_string($option['id'], $settings[$option['id']], 'helsinki-chat', false);
                        }
                    }
                    else if ($option['type'] === 'textarea' || $option['type'] === 'editor') {
                        if (isset($settings[$option['id']])) {
                            pll_register_string($option['id'], $settings[$option['id']], 'helsinki-chat', true);
                        }
                    }
                }
            }
        }
    }
}