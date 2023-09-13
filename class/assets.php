<?php
namespace CityOfHelsinki\WordPress\Chat;
use ArtCloud\Helsinki\Plugin\HDS\Svg;

class Assets {
	public $minified;

	public function init() {
		$this->minified = (defined('WP_DEBUG') && true === WP_DEBUG) ? '' : '';

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'adminScripts' ), 1 );
			add_action( 'admin_enqueue_scripts', array( $this, 'adminStyles' ), 1 );
		}
		else {
			//add_action( 'wp_enqueue_scripts', array( $this, 'publicScripts' ), 1 ); //no public scripts yet
			add_action( 'wp_enqueue_scripts', array( $this, 'publicStyles' ), 2 );
			add_action( 'wp_enqueue_scripts', array( $this, 'chatScripts' ), 1 );
			add_filter('script_loader_tag', array( $this, 'filterScript' ), 10, 3 );
		}

	}

	public function implodeParts( array $parts, string $separator ) {
		return implode(
			$separator,
			array_filter( $parts )
		);
	}

	public function dir( string $base, string $separator, array $parts ) {
		return $base . $separator . $this->implodeParts($parts, '/') . $separator;
	}

	public function assetFile( array $parts ) {
		return $this->implodeParts($parts, '.');
	}

	public function assetPath( string $directory, string $name, string $minified, string $extension ) {
		return $this->dir(
			PLUGIN_PATH . 'assets',
			DIRECTORY_SEPARATOR,
			array($directory, $extension)
			) . $this->assetFile(array(
				$name,
				$minified,
				$extension
			));
	}

	public function assetUrl( string $directory, string $name, string $minified, string $extension ) {
		return $this->dir(
			PLUGIN_URL . 'assets',
			'/',
			array($directory, $extension)
			) . $this->assetFile(array(
				$name,
				$minified,
				$extension
			));
	}

	public function assetVersion( string $path ) {
		return (defined('WP_DEBUG') && true === WP_DEBUG) ? filemtime( $path ) : PLUGIN_VERSION;
	}

	public function adminScripts( string $hook ) {
		wp_enqueue_script(
			'chat-wp-admin-scripts',
			$this->assetUrl('admin', 'scripts', $this->minified, 'js'),
			apply_filters( 'chat_admin_scripts_dependencies', array('wp-dom-ready') ),
			$this->assetVersion( $this->assetPath('admin', 'scripts', $this->minified, 'js') ),
			true
		);

		wp_set_script_translations( 'chat-wp-admin-scripts', 'helsinki-chat', PLUGIN_PATH . 'languages');

		wp_localize_script( 'chat-wp-admin-scripts', 'helsinkiChatSettings', array(
			'pages' => $this->get_pages(),
		));
	}

	public function get_pages() {
		$pages = array();
		$pages_query = new \WP_Query( array(
			'post_type' => 'page',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'post_status' => 'publish',
			'suppress_filters' => false,
			'lang' => '',
		) );

		if ( $pages_query->have_posts() ) {
			while ( $pages_query->have_posts() ) {
				$pages_query->the_post();
				$pages[] = array(
					'id' => get_the_ID(),
					'title' => get_the_title(),
				);
			}
		}

		wp_reset_postdata();

		return $pages;
	}

	public function adminStyles( string $hook ) {
		wp_enqueue_style(
			'chat-wp-admin-styles',
			$this->assetUrl('admin', 'styles', $this->minified, 'css'),
			apply_filters( 'chat_admin_styles_dependencies', array('wp-components') ),
			$this->assetVersion( $this->assetPath('admin', 'styles', $this->minified, 'css') ),
			'all'
		);
	}

	public function publicScripts() {
		wp_enqueue_script(
			'chat-wp-scripts',
			$this->assetUrl('public', 'scripts', $this->minified, 'js'),
			apply_filters( 'chat_scripts_dependencies', array() ),
			$this->assetVersion( $this->assetPath('public', 'scripts', $this->minified, 'js') ),
			true
		);
	}

	public function chatScripts() {
		$settings = get_option('helsinki-chat-settings', array());
		$chat = '';
		if (isset($settings['chat-selection'])) {
			$chat = $settings['chat-selection'];
		}

		$visibility = 'all';
		if (isset($settings['chat-visibility'])) {
			$visibility = $settings['chat-visibility'];
		}

		$selectedPages = array();
		if (isset($settings['chat-pages'])) {
			$selectedPages = explode(',', $settings['chat-pages']);
		}

		$current_page_id = '';
		if (is_front_page()) {
			$current_page_id = get_option('page_on_front');
		} else {
			$current_page_id = get_the_ID();
		}

		if ($visibility === 'selected' && !in_array($current_page_id, $selectedPages)) {
			return;
		}

		if ($chat === 'genesys-v9') {
			$allowed = array(
				'fi',
				'en',
				'sv'
			);
			$localization = '';
			$current_lang = '';
			$chat_name = '';
			$other_langs_enabled = isset($settings['chat-genesys-v9-enable-other-languages']) && $settings['chat-genesys-v9-enable-other-languages'] === 'on' ? true : false;
			$service_string = isset($settings['chat-genesys-v9-service']) ? $settings['chat-genesys-v9-service'] : '';
			$dataURL = isset($settings['chat-genesys-v9-data-url']) ? $settings['chat-genesys-v9-data-url'] : '';

			if (function_exists('pll_current_language')) {
				$current_lang = pll_current_language();
			}
			else {
				$current_lang = substr( get_bloginfo('language'), 0, 2 );
			}

			if (in_array($current_lang, $allowed)) {
				$chat_name = isset($settings['chat-genesys-v9-name-' . $current_lang]) ? $settings['chat-genesys-v9-name-' . $current_lang] : '';
				$localization = isset($settings['chat-genesys-v9-localization-' . $current_lang]) ? $settings['chat-genesys-v9-localization-' . $current_lang] : '';
			}
			else {
				$chat_name = isset($settings['chat-genesys-v9-name-en']) ? $settings['chat-genesys-v9-name-en'] : '';
				$localization = isset($settings['chat-genesys-v9-localization-en']) ? $settings['chat-genesys-v9-localization-en'] : '';
			}

			if (!empty($service_string) && !empty($dataURL) && !empty($localization) && ($other_langs_enabled || in_array($current_lang, $allowed))) {
				wp_enqueue_script(
					'genesys-v9-base',
					'https://apps.mypurecloud.ie/widgets/9.0/cxbus.min.js',
					apply_filters( 'genesys_v9_scripts_dependencies', array('jquery') ),
					PLUGIN_VERSION,
					false
				);	

				wp_enqueue_script(
					'genesys-v9',
					$this->assetUrl('chat/genesys-v9', 'chat-genesys-gui-customization', false, 'js'),
					apply_filters( 'genesys_v9_scripts_dependencies', array('jquery') ),
					$this->assetVersion( $this->assetPath('chat/genesys-v9', 'chat-genesys-gui-customization', false, 'js') ),
					false
				);	
				wp_localize_script('genesys-v9', 'genesys_settings', array(
					'chat_name' => !empty($chat_name) ? $chat_name : __('Start chat', 'helsinki-chat'),
					'chat_aria_label' => __('Start chat', 'helsinki-chat'),
					'service_string' => $service_string,
					'dataURL' => $dataURL,
					'localization' => $localization,
					'language_code' => in_array($current_lang, $allowed) ? $current_lang : 'en',
					'chat_icon' => class_exists(Svg::class) ? Svg::icon('forms-data', 'speechbubble-text') : '',
					'chat_arrow_icon' => class_exists(Svg::class) ? Svg::icon('arrows-operators', 'angle-up') : ''
				) );

				wp_enqueue_style(
					'genesys-v9-style',
					$this->assetUrl('chat/genesys-v9', 'chat-genesys-gui-customization', false, 'css'),
					apply_filters( 'genesys_style_dependencies', array( 'wp-block-library' ) ),
					$this->assetVersion( $this->assetPath('chat/genesys-v9', 'chat-genesys-gui-customization', false, 'css') ),
					'all'
				);
		
			}
		}
		else if ($chat === "genesys-watson") {
			$hostname = isset($settings['chat-genesys-watson-identifier-hostname']) ? $settings['chat-genesys-watson-identifier-hostname'] : '';
			$engagementId = isset($settings['chat-genesys-watson-identifier-engagementId']) ? $settings['chat-genesys-watson-identifier-engagementId'] : '';
			$tenantId = isset($settings['chat-genesys-watson-identifier-tenantId']) ? $settings['chat-genesys-watson-identifier-tenantId'] : '';
			$assistantId = isset($settings['chat-genesys-watson-identifier-assistantId']) ? $settings['chat-genesys-watson-identifier-assistantId'] : '';
		
			if (!empty($hostname) && !empty($engagementId) && !empty($tenantId) && !empty($assistantId)) {
				wp_enqueue_script(
					'genesys-watson',
					sprintf(
						'%s/get-widget-button?tenantId=%s&assistantId=%s&engagementId=%s',
						$hostname,
						$tenantId,
						$assistantId,
						$engagementId
					),
					apply_filters( 'genesys_watson_scripts_dependencies', array('jquery') ),
					PLUGIN_VERSION,
					false
				);
			}
		}
	}

	public function filterScript($tag, $handle, $source) {
		if ( 'genesys-v9-base' === $handle ) {
			$tag = '<script type="text/javascript" src="' . $source . '" id="' . $handle . '" onload="javascript:CXBus.configure({pluginsPath:\'https://apps.mypurecloud.ie/widgets/9.0/plugins/\'}); CXBus.loadPlugin(\'widgets-core\');"></script>';
		}		
		return $tag;
	}

	public function publicStyles() {
		wp_enqueue_style(
			'chat-wp-styles',
			$this->assetUrl('public', 'styles', $this->minified, 'css'),
			apply_filters( 'chat_styles_dependencies', array( 'wp-block-library' ) ),
			$this->assetVersion( $this->assetPath('public', 'styles', $this->minified, 'css') ),
			'all'
		);
	}

}
$assets = new Assets();
$assets->init();
