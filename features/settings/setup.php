<?php

namespace CityOfHelsinki\WordPress\Chat\Features\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

\add_action( 'helsinki_chat_loaded', __NAMESPACE__ . '\\loaded' );
function loaded(): void {
	\add_action( 'admin_init', __NAMESPACE__ . '\\chat_register_settings');
	\add_action( 'admin_menu', __NAMESPACE__ . '\\chat_settings_page' );
	\add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );

	\add_action( 'helsinki_chat_settings_tab_panel', __NAMESPACE__ . '\\chat_renderTabPanel' );
	\add_action( 'helsinki_chat_init', __NAMESPACE__ . '\\chat_settings_defaults');
	//add_action( 'helsinki_chat_init', __NAMESPACE__ . '\\chat_register_polylang_strings');

	\add_filter( 'helsinki_chat_settings', __NAMESPACE__ . '\\chat_settings' );
}

function chat_settings_page_slug(): string {
	return 'helsinki-chat-settings';
}

function chat_settings(): array {
	return \get_option( chat_settings_page_slug(), array() );
}

function update_chat_settings( array $settings ): bool {
	return \add_option( chat_settings_page_slug(), $settings );
}

function enqueue_assets( $hook ): void {
	if ( $hook !== 'toplevel_page_' . chat_settings_page_slug() ) {
		return;
	}

	$path = \apply_filters( 'helsinki_chat_plugin_path', '' );
	$url = \apply_filters( 'helsinki_chat_assets_url', '' );
	$version = \apply_filters( 'helsinki_chat_asset_version', '' );
	$is_debug = \apply_filters( 'helsinki_chat_is_debug', false );

	\wp_enqueue_script(
		'chat-wp-admin-scripts',
		$url . '/admin/js/scripts' . ( $is_debug ? '.min.js' : '.js' ),
		\apply_filters(
			'chat_admin_scripts_dependencies',
			array( 'wp-dom-ready' )
		),
		$version,
		array(
			'strategy' => 'defer',
			'in_footer' => true,
		)
	);

	\wp_set_script_translations(
		'chat-wp-admin-scripts',
		'helsinki-chat',
		$path . 'languages'
	);

	\wp_add_inline_script(
		'chat-wp-admin-scripts',
		sprintf(
			'const helsinkiChatSettings = %s;',
			json_encode( array( 'pages' => chat_available_pages() ) )
		),
		'before'
	);

	\wp_enqueue_style(
		'chat-wp-admin-styles',
		$url . '/admin/css/styles' . ( $is_debug ? '.min.css' : '.css' ),
		\apply_filters(
			'chat_admin_styles_dependencies',
			array( 'wp-components' )
		),
		$version,
		'all'
	);
}

function chat_available_pages(): array {
	$query = new \WP_Query( array(
		'post_type' => 'page',
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order' => 'ASC',
		'post_status' => 'publish',
		'suppress_filters' => false,
		'no_found_rows' => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
		'lang' => '',
	) );

	return array_map(
		fn( \WP_Post $post ) => array(
			'id' => $post->ID,
			'title' => \get_the_title( $post ),
		),
		$query->posts
	);
}

function chat_settings_page() {
    \add_menu_page(
        __('Chat', 'helsinki-chat'),
        __('Chat', 'helsinki-chat'),
        \apply_filters(
            'helsinki_chat_settings_page_capability_requirement',
            'manage_options'
        ),
        chat_settings_page_slug(),
        __NAMESPACE__ . '\\chat_settings_renderpage',
        'dashicons-format-chat',
        null
    );
}

function chat_tabs_config(): array {
	return \apply_filters( 'helsinki_chat_load_config', 'settings/tabs' );
}

function chat_options_config(): array {
	return \apply_filters( 'helsinki_chat_load_config', 'settings/options' );
}

function chat_settings_renderpage() {
    $tabs = chat_tabs_config();

	require_once \apply_filters( 'helsinki_chat_path_to_php_file', array(
		'features', 'settings', 'templates', 'page'
	) );
}

function chat_renderTabPanel( string $tab ) {
    $tabs = chat_tabs_config();
    if ( ! isset( $tabs[$tab] ) ) {
        return;
    }

    $settingsConfig = chat_settingsConfig( $tab );
    $page = $settingsConfig['page'];
    $section = $settingsConfig['section'];
    unset( $settingsConfig );

	require_once \apply_filters( 'helsinki_chat_path_to_php_file', array(
		'features', 'settings', 'templates', 'form'
	) );
}

function chat_settingsConfig( string $tab ) {
    return array(
        'page' => chat_settings_page_slug(),
        'section' => $tab,
    );
}

function chat_sanitizeSettings( $option ) {
    if ( is_array( $option ) ) {
        $out = array();
        foreach ($option as $key => $value) {
            if (str_starts_with($key, 'wp_')) {
                $out[$key] = \wp_kses_post( $value );
            }
            else {
                $out[$key] = \sanitize_text_field( $value );
            }
        }
        return $out;
    } else {
        return \sanitize_text_field( $option );
    }
}

function chat_settings_defaults() {
    $settings = chat_settings();

    if (empty($settings)) {
        $config = chat_options_config();
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

        update_chat_settings( $defaults );
    }
}

function chat_register_settings() {
    $settings = chat_options_config();

    \register_setting(
		chat_settings_page_slug(),
		chat_settings_page_slug(),
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
    \add_settings_section(
		$section['id'],
		$section['name'],
		function() use ($section) {
			if ( isset($section['description']) ) {
				chat_settings_section_description($section['description']);
			}
		},
		$section['page'],
		array(
			'before_section' => '<div class="helsinki-chat-settings-section helsinki-chat-section-'. $section['id'] .'">', 'after_section' => '</div>',
		)
	);

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

    \add_settings_field(
		$option['id'],
		$option['name'],
		__NAMESPACE__ . '\\chat_settings_option_callback',
		$page,
		$section,
		$option
	);
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
    $settings = chat_settings();

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
            $value = \esc_attr($option);
        }
        else {
            $value = sprintf(
                'value="%s"',
                \esc_attr($option)
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
    else if ($args['type'] === 'combobox') {
        printf(
            '<div class="helsinki-chat-combobox-container" id="%s-container">
                <input class="helsinki-chat-combobox-input" type="hidden" name="%s[%s]" id="%s" %s>
                <div class="helsinki-chat-combobox-controller" id="%s-controller"></div>
                %s
            </div>',
            $args['id'],
            $args['page'],
            $args['id'],
            $args['id'],
            $value,
            $args['id'],
            $description
        );
    }
    else if ($args['type'] === 'editor') {
        \wp_editor(
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
        $config = chat_options_config();
        $settings = chat_settings();

        foreach($config as $tab) {
            foreach($tab as $section) {
                foreach($section['options'] as $option) {
                    if ($option['type'] === 'text') {
                        if (isset($settings[$option['id']])) {
                            \pll_register_string($option['id'], $settings[$option['id']], 'helsinki-chat', false);
                        }
                    }
                    else if ($option['type'] === 'textarea' || $option['type'] === 'editor') {
                        if (isset($settings[$option['id']])) {
                            \pll_register_string($option['id'], $settings[$option['id']], 'helsinki-chat', true);
                        }
                    }
                }
            }
        }
    }
}
