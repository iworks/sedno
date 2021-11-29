<?php

require_once 'class-sedno.php';

class Sedno_Theme extends Sedno {


	/**
	 * Select2 is compiled?
	 */
	private $is_select2_compiled_by_grunt = true;

	public function __construct() {
		parent::__construct();
		add_action( 'after_setup_theme', array( $this, 'add_image_sizes' ) );
		add_action( 'after_setup_theme', array( $this, 'simple_catch_theme_hooks' ), PHP_INT_MAX );
		/**
		 * Cookie Class
		 */
		if ( ! is_admin() ) {
			include_once 'class-sedno-cookie-notice.php';
			new Sedno_Cookie_Notice;
		}
		/**
		 * PWA Class
		 */
		if ( ! is_admin() ) {
			include_once 'class-sedno-pwa.php';
			new Sedno_PWA;
		}
		/**
		 * hooks
		 */
		add_action( 'wp_head', array( $this, 'html_head' ), PHP_INT_MAX );
		add_filter( 'get_site_icon_url', array( $this, 'get_site_default_icon_url' ), 10, 3 );
		add_filter( 'site_icon_meta_tags', array( $this, 'site_icon_meta_tags' ) );
		add_action( 'parse_request', array( $this, 'request_favicon' ) );
		add_action( 'init', array( $this, 'register_scripts' ) );
		add_filter( 'login_headertext', array( $this, 'login_headertext' ) );
		add_filter( 'body_class', array( $this, 'body_classses' ) );
		/**
		 * js
		 */
		add_action( 'login_enqueue_scripts', array( $this, 'enqueue' ), PHP_INT_MAX );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), PHP_INT_MAX );
		/**
		 * speed
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_scripts' ), PHP_INT_MAX );
		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_styles' ), PHP_INT_MAX );
		add_action( 'wp_footer', array( $this, 'dequeue_styles' ), PHP_INT_MAX ); // WPML maybe_late_enqueue_template()
		add_filter( 'emoji_svg_url', '__return_false' );
		add_filter( 'wp_resource_hints', array( $this, 'resource_hints' ), 10, 2 );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		/**
		 * clear generaor type
		 */
		add_filter( 'get_the_generator_html', '__return_empty_string' );
		add_filter( 'get_the_generator_xhtml', '__return_empty_string' );
		add_filter( 'get_the_generator_atom', '__return_empty_string' );
		add_filter( 'get_the_generator_rss2', '__return_empty_string' );
		add_filter( 'get_the_generator_rdf', '__return_empty_string' );
		add_filter( 'get_the_generator_comment', '__return_empty_string' );
		add_filter( 'get_the_generator_export', '__return_empty_string' );
		/**
		 * theme mod
		 */
		add_filter( 'theme_mod_background_color', array( $this, 'get_theme_mod_background_color' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
		/**
		 * Plugin: OG
		 */
		add_filter( 'og_image_init', array( $this, 'set_og_image' ) );
	}

	public function login_headertext( $text ) {
		$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$id     = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );

		switch ( $action ) {
			case '':
				$text = esc_html( _x( 'Login', 'login form title', 'sedno' ) );
				break;
			case 'register':
				$text = esc_html( _x( 'Register', 'login form title', 'sedno' ) );
				break;
			case 'lostpassword':
				$text = esc_html( _x( 'Lost Password', 'login form title', 'sedno' ) );
				break;

		}
		if ( 0 < $id && get_option( 'job_manager_submit_job_form_page_id', true ) === $id ) {
			return sprintf(
				'%s<span class="delimiter"> &ndash; </span><span>%s</span>',
				esc_html__( 'Submit Listing', 'sedno' ),
				$text
			);
		}
		return $text;
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		$deps = array();
		if ( ! $this->is_select2_compiled_by_grunt ) {
			$deps[] = 'select2';
		}
		wp_enqueue_style( 'sedno-style', get_stylesheet_uri(), $deps, $this->version );
		wp_style_add_data( 'sedno-style', 'rtl', 'replace' );
		wp_enqueue_script( 'sedno' );
	}

	public function register_scripts() {
		$deps = array();
		/**
		 * theme
		 */
		wp_register_script(
			'sedno',
			$this->url . sprintf( '/assets/scripts/frontend.%sjs', $this->debug ? '' : 'min.' ),
			$deps,
			$this->version,
			true
		);
		$data = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		);
		$data = apply_filters( 'wp_localize_script_sedno_theme', $data );
		wp_localize_script( 'sedno', 'opi_jobs_theme', $data );
	}

	/**
	 * Get default favicon
	 *
	 * @since 1.0.0
	 */
	public function get_site_default_icon_url( $url, $size, $blog_id ) {
		if ( ! empty( $url ) ) {
			return $url;
		}
		return get_stylesheet_directory_uri() . '/assets/images/icons/favicon/apple-icon.png';
	}

	/**
	 * Remove scripts to improve speed
	 *
	 * @since 1.0.0
	 */
	public function dequeue_scripts() {
		if ( ! is_admin() ) {
			wp_deregister_script( 'wp-embed' );
			wp_deregister_script( 'jquery-migrate' );
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', false, '3.5.1' );
		}
	}

	/**
	 * Remove styles to improve speed
	 *
	 * @since 1.4.0
	 */
	public function dequeue_styles() {
		if ( is_admin() ) {
			return;
		}
	}

	/**
	 *
	 * @since 1.0.0
	 */
	public function html_head() {
		/**
		 * turn off iOS phone number scraping
		 */
		echo '<meta name="format-detection" content="telephone=no" />' . PHP_EOL;
		echo '<meta name="msapplication-config" content="/browserconfig.xml" />' . PHP_EOL;
		/**
		 * preload fonts
		 */
		$fonts = array();
		foreach ( $fonts as $one ) {
			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff" crossorigin />%s',
				wp_make_link_relative( $this->get_asset_url( $one, 'fonts' ) ),
				PHP_EOL
			);
		}
	}

	/**
	 * get url
	 *
	 * @since 1.0.0
	 */
	private function get_favicon_url( $icon, $extension = 'png' ) {
		$file = sprintf( '%s.%s', $icon, $extension );
		$file = sprintf( 'icons/favicon/%s?v=%s', sanitize_file_name( $file ), $this->version );
		$url  = $this->get_asset_url( $file );
		return wp_make_link_relative( esc_url( $url ) );
	}

	/**
	 * Favicons + meta settings
	 *
	 * @since 1.0.0
	 */
	public function site_icon_meta_tags( $meta_tags ) {
		$meta_tags = array();
		$icons     = array(
			'icon'    => array(
				16,
				32,
				96,
			),
			'apple'   => array(
				57,
				60,
				72,
				76,
				114,
				120,
				152,
				180,
			),
			'android' => array(
				192,
			),
		);
		foreach ( $icons as $type => $sizes ) {
			foreach ( $sizes as $size ) {
				$s    = sprintf( '%1$dx%1$d', $size );
				$mask = $file = '';
				switch ( $type ) {
					case 'icon':
						$file = sprintf( 'favicon-%s', $s );
						$mask = '<link rel="icon" type="image/png" sizes="%1$s" href="%2$s" />';
						break;
					case 'apple':
						$file = sprintf( 'apple-icon-%s', $s );
						$mask = '<link rel="apple-touch-icon" sizes="%1$s" href="%2$s" />';
						break;
					case 'android':
						$file = sprintf( 'android-icon-%s', $s );
						$mask = '<link rel="apple-touch-icon" sizes="%1$s" href="%2$s" />';
						break;
				}
				if ( ! empty( $mask ) && ! empty( $file ) ) {
					$meta_tags[] = sprintf( $mask, $s, $this->get_favicon_url( $file ) );
				}
			}
		}
		$meta_tags[] = sprintf(
			'<link rel="shortcut icon" href="%s" />',
			$this->get_favicon_url( 'favicon', 'ico' )
		);
		$meta_tags[] = sprintf(
			'<link rel="mask-icon" href="%s" color="#5bbad5" />',
			$this->get_favicon_url( 'safari-pinned-tab', 'svg' )
		);
		$meta_tags[] = sprintf( '<meta name="msapplication-TileColor" content="%s">', esc_attr( $this->color_title ) );
		$meta_tags[] = sprintf( '<meta name="theme-color" content="%s" />', esc_attr( $this->color_theme ) );
		$meta_tags[] = sprintf(
			'<meta name="msapplication-TileImage" content="%s" />',
			$this->get_favicon_url( 'ms-icon-144x144' )
		);
		return $meta_tags;
	}

	/**
	 * Handle "/favicon.json" request.
	 *
	 * @since 1.0.0
	 */
	public function request_favicon() {
		if (
			! isset( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}
		if ( '/favicon.ico' !== $_SERVER['REQUEST_URI'] ) {
			return;
		}
		header( 'Location: ' . $this->get_favicon_url( 'favicon', 'ico' ) );
		exit;
	}

	/**
	 * Handle "/browserconfig.xml" request.
	 *
	 * @since 1.0.0
	 */
	public function browserconfig_xml() {
		if (
			! isset( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}
		if ( '/browserconfig.xml' !== $_SERVER['REQUEST_URI'] ) {
			return;
		}
		header( 'Content-type: text/xml' );
		echo '<' . '?xml version="1.0" encoding="utf-8"?' . '>';
		echo PHP_EOL;
		echo '<browserconfig>';
		echo '<msapplication>';
		echo '<tile>';
		$sizes = array( 70, 150, 310 );
		foreach ( $sizes as $size ) {
			$url = $this->get_asset_url(
				sprintf(
					'icons/favicon/ms-icon-%1$dx%1$d.png',
					$size
				)
			);
			printf( '<square%1$dx%1$dlogo src="%2$s"/>', $size, esc_url( $url ) );
		}
		printf( '<TileColor>%s</TileColor>', $this->color_title );
		echo '</tile>';
		echo '</msapplication>';
		echo '</browserconfig>';
		exit;
	}

	/**
	 * Add dns-prefetch
	 *
	 * @since 1.0.0
	 */
	public function resource_hints( $urls, $relation_types ) {
		if ( 'dns-prefetch' === $relation_types ) {
			// $urls[] = 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js?ver=1.12.4';
		}
		return $urls;
	}

	/**
	 * theme_mod filter
	 *
	 * @since 1.0.4
	 */
	public function get_theme_mod_background_color( $color ) {
		return $this->color_background;
	}

	/**
	 * Set OG:image.
	 *
	 * @since 1.0.5
	 */
	public function set_og_image( $images ) {
		array_unshift(
			$images,
			$this->get_asset_url( 'og-image.png' )
		);
		return $images;
	}

	public function body_classses( $classes ) {
		if ( is_page() ) {
			switch ( get_page_template_slug() ) {
				case 'page-template-list.php':
					$classes[] = 'opi-job-list';
					break;
				case 'page-template-profile-list.php':
					if ( empty( filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING ) ) ) {
						$classes[] = 'opi-job-list';
					}
					break;
			}
		}

		return $classes;
	}

	public function add_image_sizes() {
		add_image_size( 'news', 200, 200, true );
		add_image_size( 'list', 460, 300, true );
	}

	/**
	 * Simple Catch - Theme
	 */
	public function simple_catch_theme_hooks() {
		remove_action( 'after_setup_theme', 'simplecatch_custom_header_setup' );
	}

	public function register_sidebars() {
		register_sidebar(
			array(
				'name'          => __( 'Main Page Footer One', 'sedno' ),
				'id'            => 'sedno-mp-1',
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Main Page Footer Two', 'sedno' ),
				'id'            => 'sedno-mp-2',
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
			)
		);
	}

}

