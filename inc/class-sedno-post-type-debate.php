<?php

require_once 'class-sedno.php';

class Sedno_Post_Type_Debate extends Sedno {

	private $post_type_name = 'debate';

	/**
	 * movie
	 *
	 */
	private $option_name_movie          = '_iworks_movie';
	private $option_name_movie_id       = '_iworks_movie_id';
	private $option_name_movie_provider = '_iworks_movie_provider';

	public function __construct() {
		parent::__construct();
		/**
		 * WordPress Hooks
		 */
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'init', array( $this, 'custom_post_type' ), 0 );
		add_action( 'save_post', array( $this, 'save' ) );
		add_filter( 'the_content', array( $this, 'add_movie' ), 0 );
		/**
		 * Theme hooks
		 */
		add_filter( 'sedno_get_debate_movie_data', array( $this, 'filter_get_debate_movie_data' ) );
	}

	private function get_debate_movie_data( $post_id ) {
		return array(
			'url'      => get_post_meta( $post_id, $this->option_name_movie, true ),
			'id'       => get_post_meta( $post_id, $this->option_name_movie_id, true ),
			'provider' => get_post_meta( $post_id, $this->option_name_movie_provider, true ),
		);
	}

	public function filter_get_debate_movie_data( $data ) {
		return $this->get_debate_movie_data( get_the_ID() );
	}

	/**
	 * Register Custom Post Type
	 *
	 * @since 1.0.0
	 */
	public function custom_post_type() {
		$labels = array(
			'name'                  => _x( 'Debates', 'Post Type General Name', 'sedno' ),
			'singular_name'         => _x( 'Debate', 'Post Type Singular Name', 'sedno' ),
			'menu_name'             => __( 'Debates', 'sedno' ),
			'name_admin_bar'        => __( 'Debates', 'sedno' ),
			'archives'              => __( 'Debates', 'sedno' ),
			'all_items'             => __( 'Debates', 'sedno' ),
			'add_new_item'          => __( 'Add New Debate', 'sedno' ),
			'add_new'               => __( 'Add New', 'sedno' ),
			'new_item'              => __( 'New Debate', 'sedno' ),
			'edit_item'             => __( 'Edit Debate', 'sedno' ),
			'update_item'           => __( 'Update Debate', 'sedno' ),
			'view_item'             => __( 'View Debate', 'sedno' ),
			'view_items'            => __( 'View Debate', 'sedno' ),
			'search_items'          => __( 'Search Debate', 'sedno' ),
			'not_found'             => __( 'Not found', 'sedno' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'sedno' ),
			'items_list'            => __( 'Debate list', 'sedno' ),
			'items_list_navigation' => __( 'Debate list navigation', 'sedno' ),
			'filter_items_list'     => __( 'Filter items list', 'sedno' ),
		);
		$args   = array(
			'can_export'          => true,
			'capability_type'     => 'page',
			'description'         => __( 'Debate', 'sedno' ),
			'exclude_from_search' => true,
			'has_archive'         => _x( 'debates', 'archive slug', 'sedno' ),
			'hierarchical'        => false,
			'label'               => __( 'Debates', 'sedno' ),
			'labels'              => $labels,
			'public'              => true,
			'show_in_admin_bar'   => true,
			'show_in_menu'        => apply_filters( 'sedno_post_type_show_in_menu' . $this->post_type_name, 'edit.php' ),
			'show_in_nav_menus'   => true,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'rewrite'             => array(
				'slug' => _x( 'debate', 'rewrite slug', 'sedno' ),
			),
			'supports'            => array(
				'author',
				'editor',
				'excerpt',
				'page-attributes',
				'revisions',
				'thumbnail',
				'thumbnail',
				'title',
				'trackbacks',
			),
		);
		register_post_type( $this->post_type_name, $args );
	}

	/**
	 * Add meta boxes
	 *
	 * @since 1.0.0
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'',
			__( 'Movie', 'sedno' ),
			array( $this, 'html_movie' ),
			$this->post_type_name,
			'normal',
			'default'
		);
	}

	/**
	 * Save debate data.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $post_id Post ID.
	 */
	public function save( $post_ID ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$nonce = filter_input( INPUT_POST, '_iworks_movie_nonce', FILTER_SANITIZE_STRING );
		if ( ! wp_verify_nonce( $nonce, $this->nonce_value ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_ID ) ) {
			return;
		}
		/**
		 * movie
		 */
		$value = filter_input( INPUT_POST, $this->option_name_movie, FILTER_SANITIZE_URL );
		$this->update_meta( $post_ID, $this->option_name_movie, $value );
		/**
		 * yt thumb
		 */
		$movie_id       = null;
		$movie_provider = null;
		/**
		 * parse short youtube share url
		 */
		if ( preg_match( '#https?://youtu.be/([0-9a-z\-_]+)#i', $value, $matches ) ) {
			$movie_id       = $matches[1];
			$movie_provider = 'youtube';
		} elseif ( preg_match( '#https?://(www\.)?youtube\.com/watch\?v=([0-9a-z\-_]+)#i', $value, $matches ) ) {
			$movie_id       = $matches[2];
			$movie_provider = 'youtube';
		}
		/**
		 * parse Facebook url
		 */
		if ( preg_match( '#https://fb.watch/([^/]+)/#', $value, $matches ) ) {
			$movie_id       = $matches[1];
			$movie_provider = 'facebook';
		} elseif ( preg_match( '#https?://(www\.)?facebook.com/[^/]+/posts/(\d+)#i', $value, $matches ) ) {
			$movie_id       = $matches[2];
			$movie_provider = 'facebook-post';
		}
		$this->update_meta( $post_ID, $this->option_name_movie_id, $movie_id );
		$this->update_meta( $post_ID, $this->option_name_movie_provider, $movie_provider );
	}

	/**
	 * movie files
	 *
	 * @since 1.0.0
	 */
	public function html_movie( $post ) {
		wp_nonce_field( $this->nonce_value, '_iworks_movie_nonce' );
		$value = get_post_meta( $post->ID, $this->option_name_movie, true );
		printf(
			'<input type="url" value="%s" name="%s" class="large-text code" />',
			esc_url( $value ),
			$this->option_name_movie,
		);
	}

	public function add_movie( $content ) {
		if ( ! is_singular( $this->post_type_name ) ) {
			return $content;
		}
		$data = $this->get_debate_movie_data( get_the_ID() );
		if ( ! is_array( $data ) ) {
			return $content;
		}
		if ( ! isset( $data['provider'] ) ) {
			return $content;
		}
		switch ( $data['provider'] ) {
			case 'youtube':
				$content .= PHP_EOL;
				$content .= PHP_EOL;
				$content .= $data['url'];
				$content .= PHP_EOL;
				break;
			case 'facebook':
				$content .= PHP_EOL;
				$content .= PHP_EOL;
				$content .= sprintf( '<a href="%1$s">%1$s</a>', esc_url( $data['url'] ) );
				break;
			case 'facebook-video':
				$content .= sprintf(
					'<!-- wp:video --><figure class="wp-block-video"><video controls src="%s"></video></figure><!-- /wp:video -->',
					$data['url']
				);
				break;
		}
		return $content;
	}
}

