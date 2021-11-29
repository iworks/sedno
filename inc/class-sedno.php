<?php

abstract class Sedno {

	/**
	 * Theme url.
	 *
	 * @since 1.0.0
	 * @var string $option_name_icon Option name ICON.
	 */
	protected $url = '';

	/**
	 * Option name, used to save data on postmeta table.
	 *
	 * @since 1.0.0
	 * @var string $option_name_icon Option name ICON.
	 */
	protected $version = 'THEME_VERSION';

	/**
	 * Debug
	 *
	 * @since 1.0.0
	 * @var boolean $debug
	 */
	protected $debug = 'false';

	/**
	 * Configuration for:
	 * /manifest.json
	 * /browserconfig.xml
	 *
	 * @since 1.0.0
	 */
	protected $color_title      = '#2d2683';
	protected $color_theme      = '#2d2683';
	protected $color_background = '#2d2683';
	protected $short_name       = 'Dziennik SEDNO';

	protected function __construct() {
		$child_version = wp_get_theme();
		$this->version = $child_version->Version;
		$this->url     = get_stylesheet_directory_uri();
		$this->debug   = defined( 'WP_DEBUG' ) && WP_DEBUG;
		/**
		 * manifest.json
		 */
		if ( class_exists( 'iWorks_PWA' ) ) {
			add_filter( 'iworks_pwa_configuration', array( $this, 'iworks_pwa_configuration' ) );
			add_filter( 'iworks_pwa_offline_svg', array( $this, 'iworks_pwa_offline_svg' ) );
			add_filter( 'iworks_pwa_offline_file', array( $this, 'iworks_pwa_offline_file' ) );
			add_filter( 'iworks_pwa_offline_urls_set', array( $this, 'iworks_pwa_offline_urls_set' ) );
		}
	}

	/**
	 * Check is login page
	 */
	protected function is_wp_login() {
		$ABSPATH_MY = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, ABSPATH );
		return (
			(
				in_array( $ABSPATH_MY . 'wp-login.php', get_included_files() )
				|| in_array( $ABSPATH_MY . 'wp-register.php', get_included_files() )
			) || (
				isset( $_GLOBALS['pagenow'] )
				&& $GLOBALS['pagenow'] === 'wp-login.php'
			)
				|| $_SERVER['PHP_SELF'] == '/wp-login.php'
		);
	}

	/**
	 * Get assets URL
	 *
	 * @since 1.0.0
	 *
	 * @param string $file File name.
	 * @param string $group Group, default "images".
	 *
	 * @return string URL into asset.
	 */
	protected function get_asset_url( $file, $group = 'images' ) {
		$url = sprintf(
			'%s/assets/%s/%s',
			$this->url,
			$group,
			$file
		);
		return esc_url( $url );
	}

	public function iworks_pwa_configuration( $data ) {
		return wp_parse_args( $this->manifest_json_data(), $data );
	}

	private function manifest_json_data() {
		$data = array(
			'name'             => get_bloginfo( 'sitename' ),
			'short_name'       => $this->short_name,
			'theme_color'      => $this->color_theme,
			'background_color' => $this->color_background,
			'display'          => 'standalone',
			'Scope'            => '/',
			'start_url'        => '/',
			'icons'            => array(
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-36x36.png' ) ),
					'sizes'   => '36x36',
					'type'    => 'image/png',
					'density' => '0.75',
				),
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-48x48.png' ) ),
					'sizes'   => '48x48',
					'type'    => 'image/png',
					'density' => '1.0',
				),
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-72x72.png' ) ),
					'sizes'   => '72x72',
					'type'    => 'image/png',
					'density' => '1.5',
				),
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-96x96.png' ) ),
					'sizes'   => '96x96',
					'type'    => 'image/png',
					'density' => '2.0',
				),
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-144x144.png' ) ),
					'sizes'   => '144x144',
					'type'    => 'image/png',
					'density' => '3.0',
				),
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-192x192.png' ) ),
					'sizes'   => '192x192',
					'type'    => 'image/png',
					'density' => '4.0',
				),
				array(
					'src'   => esc_url( $this->get_asset_url( 'icons/favicon/android-icon-512x512.png' ) ),
					'sizes' => '512x512',
					'type'  => 'image/png',
				),
				array(
					'src'     => esc_url( $this->get_asset_url( 'icons/favicon/maskable.png' ) ),
					'sizes'   => '1024x1024',
					'type'    => 'image/png',
					'density' => 'any maskable',
				),
			),
			'splash_pages'     => null,
		);
		return $data;
	}

	public function iworks_pwa_offline_svg( $svg ) {
		$svg = file_get_contents( get_stylesheet_directory() . '/assets/images/logo.svg' );
		return $svg;
	}

	public function iworks_pwa_offline_file( $data ) {
		return file_get_contents( $this->url . '/assets/pwa/offline.html' );
	}

	public function iworks_pwa_offline_urls_set( $set ) {
		$url = get_privacy_policy_url();
		if ( ! empty( $url ) ) {
			$set[] = $url;
		}
		$set[] = $this->url . '/assets/images/icons/favicon/favicon.ico';
		return $set;
	}
}

