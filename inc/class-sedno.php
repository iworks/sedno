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

	/**
	 * media
	 *
	 */
	protected $option_name_media = '_iworks_media';

	/**
	 * Nounce value
	 */
	protected $nonce_value = '4PufQi59LMAEnB1yp3r4m6y9x49RbIUy';

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

	/**
	 * Media files
	 *
	 * @since 1.0.0
	 */
	public function html_media( $post ) {
		wp_enqueue_media();
		wp_nonce_field( $this->nonce_value, '_iworks_media_nonce' );
		$rows_name = 'iworks-media-file-rows';
		$classes   = array(
			'iworks-media-container',
			'image-wrapper',
			empty( $src ) ? '' : ( 0 < $value ? ' has-file' : ' has-old-file' ),
		);
		printf(
			'<div data-rows="%s" class="%s">',
			esc_attr( $rows_name ),
			esc_attr( implode( ' ', $classes ) )
		);
		echo '<p>';
		printf(
			'<button type="button" class="button button-add-file">%s</button>',
			esc_html__( 'Add file', 'sedno' )
		);
		echo '</p>';
		printf(
			'<div class="%s" aria-hidden="true">',
			esc_attr( $rows_name )
		);
		$value = get_post_meta( $post->ID, $this->option_name_media, true );
		if ( is_array( $value ) ) {
			foreach ( $value as $attachment_ID ) {
				$this->media_row( $this->get_attachment_data( $attachment_ID ) );
			}
		}
		echo '</div>';
		echo '</div>';
		echo '<script type="text/html" id="tmpl-iworks-media-file-row">';
		$this->media_row();
		echo '</script>';
	}

	/**
	 * Media row helper
	 *
	 * @since 1.0.0
	 */
	protected function media_row( $data = array() ) {
		$data = wp_parse_args(
			$data,
			array(
				'id'      => '{{{data.id}}}',
				'type'    => '{{{data.type}}}',
				'subtype' => '{{{data.subtype}}}',
				'url'     => '{{{data.url}}}',
				'icon'    => '{{{data.icon}}}',
				'caption' => '{{{data.caption}}}',
			)
		);
		?>
	<div class="iworks-media-file-row">
		<span class="dashicons dashicons-move"></span>
		<span class="icon iworks-media-<?php echo esc_attr( $data['type'] ); ?>-<?php echo esc_attr( $data['subtype'] ); ?>" style="background-image:url(<?php echo esc_attr( $data['icon'] ); ?>"></span>
		<span><a href="<?php echo esc_attr( $data['url'] ); ?>" target="_blank"><?php echo esc_html( $data['url'] ); ?></a><br /><small><?php echo esc_html( $data['caption'] ); ?></small></span>
		<button type="button" aria-label="<?php esc_attr_e( 'Remove file', 'sedno' ); ?>"><span class="dashicons dashicons-trash"></span></button>
		<input type="hidden" name="<?php echo esc_attr( $this->option_name_media ); ?>[]" value="<?php echo esc_attr( $data['id'] ); ?>" />
	</div>
		<?php
	}

	/**
	 * Add post meta
	 */
	protected function update_meta( $post_id, $option_name, $option_value ) {
		if ( empty( $option_value ) ) {
			delete_post_meta( $post_id, $option_name );
			return;
		}
		$result = add_post_meta( $post_id, $option_name, $option_value, true );
		if ( ! $result ) {
			update_post_meta( $post_id, $option_name, $option_value );
		}
	}

	private function get_attachment_data( $attachment_ID ) {
		$content_type = explode( '/', get_post_mime_type( $attachment_ID ) );
		if ( ! is_array( $content_type ) ) {
			$content_type = array(
				'unknown',
				'unknown',
			);
		}
		return         array(
			'id'      => $attachment_ID,
			'caption' => wp_get_attachment_caption( $attachment_ID ),
			'url'     => wp_get_attachment_url( $attachment_ID ),
			'type'    => $content_type[0],
			'subtype' => isset( $content_type[1] ) ? $content_type[1] : '',
			'icon'    => wp_get_attachment_image_src( $attachment_ID, 'thumbnail', true )[0],
		);
	}
}

