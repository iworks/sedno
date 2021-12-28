<?php

require_once 'class-sedno.php';

class Sedno_Post_Type_Announcement extends Sedno {

	private $post_type_name = 'announcement';

	public function __construct() {
		parent::__construct();
		/**
		 * WordPress Hooks
		 */
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_init', array( $this, 'register' ) );
		add_action( 'init', array( $this, 'custom_post_type' ), 0 );
		add_action( 'load-post-new.php', array( $this, 'admin_enqueue' ) );
		add_action( 'load-post.php', array( $this, 'admin_enqueue' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Add meta boxes
	 *
	 * @since 1.0.0
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'',
			__( 'Files', 'sedno' ),
			array( $this, 'html_media' ),
			$this->post_type_name,
			'normal',
			'default'
		);
	}

	/**
	 * Register Custom Post Type
	 *
	 * @since 1.0.0
	 */
	public function custom_post_type() {
		$labels = array(
			'name'                  => _x( 'Announcements', 'Post Type General Name', 'sedno' ),
			'singular_name'         => _x( 'Announcement', 'Post Type Singular Name', 'sedno' ),
			'menu_name'             => __( 'Announcements', 'sedno' ),
			'name_admin_bar'        => __( 'Announcements', 'sedno' ),
			'archives'              => __( 'Announcements', 'sedno' ),
			'all_items'             => __( 'Announcements', 'sedno' ),
			'add_new_item'          => __( 'Add New Announcement', 'sedno' ),
			'add_new'               => __( 'Add New', 'sedno' ),
			'new_item'              => __( 'New Announcement', 'sedno' ),
			'edit_item'             => __( 'Edit Announcement', 'sedno' ),
			'update_item'           => __( 'Update Announcement', 'sedno' ),
			'view_item'             => __( 'View Announcement', 'sedno' ),
			'view_items'            => __( 'View Announcement', 'sedno' ),
			'search_items'          => __( 'Search Announcement', 'sedno' ),
			'not_found'             => __( 'Not found', 'sedno' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'sedno' ),
			'items_list'            => __( 'Announcement list', 'sedno' ),
			'items_list_navigation' => __( 'Announcement list navigation', 'sedno' ),
			'filter_items_list'     => __( 'Filter items list', 'sedno' ),
		);
		$args   = array(
			'can_export'          => true,
			'capability_type'     => 'page',
			'description'         => __( 'Announcement', 'sedno' ),
			'exclude_from_search' => true,
			'has_archive'         => _x( 'announcements', 'archive slug', 'sedno' ),
			'hierarchical'        => false,
			'label'               => __( 'Announcements', 'sedno' ),
			'labels'              => $labels,
			'public'              => true,
			'show_in_admin_bar'   => true,
			'show_in_menu'        => apply_filters( 'sedno_post_type_show_in_menu' . $this->post_type_name, 'edit.php' ),
			'show_in_nav_menus'   => true,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'rewrite'             => array(
				'slug' => _x( 'announcement', 'rewrite slug', 'sedno' ),
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
	 * Register plugin assets.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		wp_register_style(
			strtolower( __CLASS__ ),
			get_stylesheet_directory_uri() . '/assets/css/admin/media.css',
			array(),
			$this->version
		);
		wp_register_script(
			strtolower( __CLASS__ ),
			get_stylesheet_directory_uri() . '/assets/scripts/admin/media.js',
			array(
				'jquery',
				'jquery-ui-sortable',
			),
			$this->version,
			true
		);
	}

	/**
	 * Enqueue plugin assets.
	 *
	 * @since 1.0.0
	 */
	public function admin_enqueue() {
		global $typenow;
		if ( $typenow !== $this->post_type_name ) {
			return;
		}
		wp_enqueue_script( strtolower( __CLASS__ ) );
		wp_enqueue_style( strtolower( __CLASS__ ) );
	}

	/**
	 * Save project data.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $post_id Post ID.
	 */
	public function save( $post_ID ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$nonce = filter_input( INPUT_POST, '_iworks_media_nonce', FILTER_SANITIZE_STRING );
		if ( ! wp_verify_nonce( $nonce, $this->nonce_value ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_ID ) ) {
			return;
		}
		/**
		 * media
		 */
		$value = array();
		if ( isset( $_POST[ $this->option_name_media ] ) ) {
			foreach ( $_POST[ $this->option_name_media ] as $one ) {
				$one = filter_var( $one, FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
				if ( empty( $one ) ) {
					continue;
				}
				$value[] = $one;
			}
		}
		$this->update_meta( $post_ID, $this->option_name_media, $value );
	}
}

