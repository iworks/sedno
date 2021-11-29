<?php
	/**
	 *
	 * @since 1.0.1
	 */

require_once 'class-sedno.php';

class Sedno_PWA extends Sedno {

	/**
	 * Select2 is compiled?
	 */
	private $is_select2_compiled_by_grunt = true;

	public function __construct() {
		parent::__construct();
		add_action( 'parse_request', array( $this, 'manifest_json' ) );
		add_action( 'wp_head', array( $this, 'html_head' ), PHP_INT_MAX );
		add_filter( 'wp_localize_script_opi_jobs_theme', array( $this, 'add_pwa_data' ) );
	}

	public function add_pwa_data( $data ) {
		$data['pwa'] = array(
			'root' => preg_replace( '@' . ABSPATH . '@', '/', get_template_directory() . '/assets/pwa/' ),
		);
		return $data;
	}

	/**
	 *
	 * @since 1.0.0
	 */
	public function html_head() {
		echo '<link rel="manifest" href="/manifest.json" />';
	}

	/**
	 * Handle "/manifest.json" request.
	 *
	 * @since 1.0.0
	 */
	public function manifest_json() {
		if (
			! isset( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}
		if ( '/manifest.json' !== $_SERVER['REQUEST_URI'] ) {
			return;
		}
		$data = array(
			'name'             => get_bloginfo( 'sitename' ),
			'short_name'       => $this->short_name,
			'theme_color'      => $this->color_theme,
			'background_color' => $this->color_background,
			'display'          => 'standalone',
			'orientation'      => 'portrait',
			'Scope'            => preg_replace( '@' . ABSPATH . '@', '/', get_template_directory() . '/assets/pwa/' ),
			'start_url'        => get_template_directory_uri() . '/assets/pwa/index.php',
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
					'purpose' => 'any maskable',
				),
			),
			'splash_pages'     => null,
		);
		header( 'Content-Type: application/json' );
		echo json_encode( $data );
		exit;
	}

}

