<?php

require_once 'class-sedno.php';

class Sedno_Post_Type_Editorial_Comment extends Sedno {

	private $post_type_name = 'editorial_comment';

	public function __construct() {
		parent::__construct();
		add_action( 'init', array( $this, 'custom_post_type' ), 0 );
	}

	/**
	 * Register Custom Post Type
	 *
	 * @since 1.3.9
	 */
	public function custom_post_type() {
		$labels = array(
			'name'                  => _x( 'Editorial Commentes', 'Post Type General Name', 'sedno' ),
			'singular_name'         => _x( 'Editorial Comment', 'Post Type Singular Name', 'sedno' ),
			'menu_name'             => __( 'Editorial Commentes', 'sedno' ),
			'name_admin_bar'        => __( 'Editorial Commentes', 'sedno' ),
			'archives'              => __( 'Editorial Commentes', 'sedno' ),
			'all_items'             => __( 'Editorial Commentes', 'sedno' ),
			'add_new_item'          => __( 'Add New Editorial Comment', 'sedno' ),
			'add_new'               => __( 'Add New', 'sedno' ),
			'new_item'              => __( 'New Editorial Comment', 'sedno' ),
			'edit_item'             => __( 'Edit Editorial Comment', 'sedno' ),
			'update_item'           => __( 'Update Editorial Comment', 'sedno' ),
			'view_item'             => __( 'View Editorial Comment', 'sedno' ),
			'view_items'            => __( 'View Editorial Comment', 'sedno' ),
			'search_items'          => __( 'Search Editorial Comment', 'sedno' ),
			'not_found'             => __( 'Not found', 'sedno' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'sedno' ),
			'items_list'            => __( 'Editorial Comment list', 'sedno' ),
			'items_list_navigation' => __( 'Editorial Comment list navigation', 'sedno' ),
			'filter_items_list'     => __( 'Filter items list', 'sedno' ),
		);
		$args   = array(
			'can_export'          => true,
			'capability_type'     => 'page',
			'description'         => __( 'Editorial Comment', 'sedno' ),
			'exclude_from_search' => true,
			'has_archive'         => _x( 'editorial-comments', 'archive slug', 'sedno' ),
			'hierarchical'        => false,
			'label'               => __( 'Editorial Commentes', 'sedno' ),
			'labels'              => $labels,
			'public'              => true,
			'show_in_admin_bar'   => true,
			'show_in_menu'        => apply_filters( 'sedno_post_type_show_in_menu' . $this->post_type_name, 'edit.php' ),
			'show_in_nav_menus'   => true,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'rewrite'             => array(
				'slug' => _x( 'editorial-comment', 'rewrite slug', 'sedno' ),
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

}

