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
		/**
		 * Most Important
		 */
		include_once 'class-sedno-most-important-posts.php';
		new Sedno_Most_important_Posts;
		/**
		 * Custom Post Types:
		 * - Editorial Comment
		 * - Announcement
		 */
		include_once 'class-sedno-post-type-editorial-comment.php';
		new Sedno_Post_Type_Editorial_Comment;
		include_once 'class-sedno-post-type-announcement.php';
		new Sedno_Post_Type_Announcement;
		include_once 'class-sedno-post-type-lecture.php';
		new Sedno_Post_Type_Lecture;
		include_once 'class-sedno-post-type-debate.php';
		new Sedno_Post_Type_Debate;
		/**
		 * integrations
		 */
		include_once 'integrations/plugins/class-sedno-plugins-contextual-related-posts.php';
		new Sedno_Integration_Plugin_Contextual_Rlated_Posts;
		include_once 'integrations/services/class-sedno-service-google-analytics.php';
		new Sedno_Integration_Service_Google_Analitics;
		/**
		 * hooks
		 */
		add_action( 'init', array( $this, 'register_scripts' ) );
		add_action( 'parse_request', array( $this, 'request_favicon' ) );
		add_action( 'wp_head', array( $this, 'html_head' ), PHP_INT_MAX );
		add_filter( 'body_class', array( $this, 'body_classses' ) );
		add_filter( 'comment_form_default_fields', array( $this, 'remove_website_field' ) );
		add_filter( 'get_site_icon_url', array( $this, 'get_site_default_icon_url' ), 10, 3 );
		add_filter( 'login_headertext', array( $this, 'login_headertext' ) );
		add_filter( 'the_content', array( $this, 'add_thumbnail_image' ) );
		add_filter( 'get_the_archive_title', array( $this, 'archive_title' ), 10, 3 );
		add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );
		add_action( 'plugins_loaded', array( $this, 'set_iworks_cache_keys' ) );
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
		 * social media links
		 */
		add_shortcode( 'sedno_social_media_icons', array( $this, 'shortcode_social_media' ) );
		/**
		 * theme actions
		 */
		add_action( 'sedno_the_posts_navigation', array( $this, 'numeric_posts_nav' ) );
		/**
		 * Plugin: OG
		 */
		add_filter( 'og_og_image_value', array( $this, 'set_og_image' ) );
		/**
		 * Plugin: Simple History
		 */
		add_filter( 'simple_history/db_purge_days_interval', array( $this, 'filter_simple_history_db_purge_days_interval' ) );
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
		wp_localize_script( 'sedno', 'sedno_theme', $data );
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
	 * @since 1.0.0
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
	 * @since 1.0.0
	 */
	public function get_theme_mod_background_color( $color ) {
		return $this->color_background;
	}

	/**
	 * Set OG:image.
	 *
	 * @since 1.0.0
	 */
	public function set_og_image( $image ) {
		if ( empty( $image ) ) {
			return $this->get_asset_url( 'sedno-dziennik.png' );
		}
		return $image;
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
		register_sidebar(
			array(
				'name'           => __( 'After title of Lectures Archive Page', 'sedno' ),
				'id'             => 'sedno-lecture-header',
				'before_widget'  => '<div id="%1$s" class="%2$s">',
				'after_widget'   => '</div>',
				'after_sidebar'  => '</aside>',
				'before_sidebar' => '<aside id="%1$s" class="%2$s">',
			)
		);
		register_sidebar(
			array(
				'name'           => __( 'After title of Debates Archive Page', 'sedno' ),
				'id'             => 'sedno-debate-header',
				'before_widget'  => '<div id="%1$s" class="%2$s">',
				'after_widget'   => '</div>',
				'after_sidebar'  => '</aside>',
				'before_sidebar' => '<aside id="%1$s" class="%2$s">',
			)
		);
	}

	public function add_thumbnail_image( $content ) {
		if ( ! is_singular( 'post' ) ) {
			return $content;
		}
		if ( ! has_post_thumbnail() ) {
			return $content;
		}
		$c       = '<figure class="thumbnail">';
		$c      .= get_the_post_thumbnail( get_the_ID(), 'list' );
		$caption = get_the_post_thumbnail_caption();
		if ( ! empty( $caption ) ) {
			$c .= sprintf(
				'<figcaption>%s</figcaption>',
				$caption
			);
		}
		$c .= '</figure>';
		return $c . $content;
	}

	public function shortcode_social_media( $content, $atts ) {
		$media = array(
			'Twitter'  => 'https://twitter.com/sedno_org',
			'Facebook' => 'https://www.facebook.com/StowarzyszenieEuropejskaDemokracja/',
			'YouTube'  => 'https://www.youtube.com/channel/UChEODSnVNPU-1AV8Pvy2msQ',
		);
		$c     = '<span class="sedno-social-media">';
		foreach ( $media as $class => $url ) {
			$c .= sprintf(
				'<a class="%1$s" href="%2$s" target="%1$s" aria-label="%4$s"><span>%3$s</span></a>',
				esc_attr( strtolower( $class ) ),
				esc_url( $url ),
				esc_html(
					sprintf(
						__( 'Visit out profile on %s', 'sedno' ),
						$class
					)
				),
				esc_attr(
					sprintf(
						__( 'Visit out profile on %s', 'sedno' ),
						$class
					)
				)
			);
		}
		$c .= '</ul>';
		return $c;
	}

	/**
	 * Numeric pagination
	 */
	public function numeric_posts_nav( $args = array() ) {
		/**
		 * check
		 */
		if ( is_singular() ) {
			return;
		}
		/**
		 * configuration
		 */
		$configuration = wp_parse_args(
			$args,
			array(
				'show_first'    => false,
				'show_last'     => false,
				'show_previous' => true,
				'show_next'     => true,
			)
		);
		/**
		 * globals
		 */
		global $wp_query;
		/** Stop execution if there's only 1 page */
		if ( $wp_query->max_num_pages <= 1 ) {
			return;
		}
		$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		$max   = intval( $wp_query->max_num_pages );
		/** Add current page to the array */
		if ( $paged >= 1 ) {
			$links[] = $paged;
		}
		/** Add the pages around the current page to the array */
		if ( $paged >= 3 ) {
			$links[] = $paged - 1;
			$links[] = $paged - 2;
		}
		if ( ( $paged + 2 ) <= $max ) {
			$links[] = $paged + 2;
			$links[] = $paged + 1;
		}
		$content = '';
		/** first & Previous Post Link */
		if ( get_previous_posts_link() ) {
			if ( $configuration['show_first'] ) {
				$content .= sprintf(
					'<li class="first"><a href="%1$s"><span>%2$s</span></a></li>',
					preg_replace( '@/page/\d+/@', '/', remove_query_arg( 'paged' ) ),
					esc_html__( 'First page', 'sedno' )
				);
			}
			if ( $configuration['show_previous'] ) {
				$content .= sprintf( '<li class="previous">%s</li>', get_previous_posts_link( '<span></span>' ) );
			}
		} else {
			if ( $configuration['show_first'] ) {
				$content .= '<li class="first"><span></span></li>';
			}
			if ( $configuration['show_previous'] ) {
				$content .= '<li class="previous"><span></span></li>';
			}
		}
		/** Link to first page, plus ellipses if necessary */
		if ( ! in_array( 1, $links ) ) {
			$class    = 1 == $paged ? ' class="active"' : '';
			$content .= sprintf( '<li%s><a href="%s">%s</a></li>', $class, esc_url( get_pagenum_link( 1 ) ), '1' );
			if ( ! in_array( 2, $links ) ) {
				$content .= '<li class="dots"><span>…</span></li>';
			}
		}

		/** Link to current page, plus 2 pages in either direction if necessary */
		sort( $links );
		foreach ( (array) $links as $link ) {
			$class    = $paged == $link ? ' class="active"' : '';
			$content .= sprintf( '<li%s><a href="%s">%s</a></li>', $class, esc_url( get_pagenum_link( $link ) ), $link );
		}
		/** Link to last page, plus ellipses if necessary */
		if ( ! in_array( $max, $links ) ) {
			if ( ! in_array( $max - 1, $links ) ) {
				$content .= '<li class="dots"><span>…</span></li>';
			}
			$class    = $paged == $max ? ' class="active"' : '';
			$content .= sprintf( '<li%s><a href="%s">%s</a></li>', $class, esc_url( get_pagenum_link( $max ) ), $max );
		}
		/** Next Post Link */
		if ( get_next_posts_link() ) {
			if ( $configuration['show_next'] ) {
				$content .= sprintf( '<li class="next">%s</li>', get_next_posts_link( '&raquo;' ) );
			}
			if ( $configuration['show_last'] ) {
				$content .= sprintf(
					'<li class="last"><a href="%1$s"><span>%2$s</span></a></li>',
					preg_replace( '@/page/\d+/@', '/page/' . $max, remove_query_arg( 'paged' ) ),
					esc_html__( 'Last page', 'sedno' )
				);
			}
		} else {
			if ( $configuration['show_next'] ) {
				$content .= '<li class="next"><span></span></li>';
			}
			if ( $configuration['show_last'] ) {
				$content .= '<li class="last"><span></span></li>';
			}
		}

		if ( empty( $content ) ) {
			return;
		}
		printf(
			'<nav class="navigation %1$s" aria-label="%4$s"><h2 class="screen-reader-text">%2$s</h2><ul class="nav-links">%3$s</ul></nav>',
			'navigation-numeric',
			esc_html__( 'Posts navigation pages', 'sedno' ),
			$content,
			esc_attr__( 'Posts navigation pages', 'sedno' )
		);
	}

	/**
	 * Plugin: Simple History: db purge days interval
	 */
	public function filter_simple_history_db_purge_days_interval( $days ) {
		$days = 365;
		return $days;
	}

	public function remove_website_field( $fields ) {
		unset( $fields['url'] );
		return $fields;
	}

	public function archive_title( $title, $orginal_title, $prefix ) {
		if ( is_tag() ) {
			$title  = __( 'Articles with tag', 'sedno' );
			$title .= sprintf( ' <span>%s</span>', single_tag_title( '', false ) );
			return $title;
		}
		if ( is_home() && ! is_front_page() ) {
			return esc_html__( 'Our publications', 'sedno' );
		}
		if ( is_author() ) {
			$title  = __( 'Author', 'sedno' );
			$title .= '<span class="vcard">';
			if ( function_exists( 'coauthors' ) ) {
				$title .= coauthors( null, null, null, null, false );
			} else {
				$title .= esc_html( get_the_author() );
			}
			$title .= '</span>';
			return $title;
		}
				/*
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		}*/

		return $title;
	}

	public function excerpt_more( $more ) {
		return '&hellip;';
	}

	public function set_iworks_cache_keys() {
		do_action( 'iworks_cache_keys', array( $this, 'add_iworks_cache_keys' ), 'slider' );
		do_action( 'iworks_cache_keys', array( $this, 'add_iworks_cache_keys' ), 'most' );
	}

}

