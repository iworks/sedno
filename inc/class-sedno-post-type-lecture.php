<?php

require_once 'class-sedno.php';

class Sedno_Post_Type_Lecture extends Sedno {

	private $post_type_name = 'lecture';

	public function __construct() {
		parent::__construct();
		add_action( 'init', array( $this, 'custom_post_type' ), 0 );
	}

	/**
	 * Register Custom Post Type
	 *
	 * @since 1.0.0
	 */
	public function custom_post_type() {
		$labels = array(
			'name'                  => _x( 'Lectures', 'Post Type General Name', 'sedno' ),
			'singular_name'         => _x( 'Lecture', 'Post Type Singular Name', 'sedno' ),
			'menu_name'             => __( 'Lectures', 'sedno' ),
			'name_admin_bar'        => __( 'Lectures', 'sedno' ),
			'archives'              => __( 'Lectures', 'sedno' ),
			'all_items'             => __( 'Lectures', 'sedno' ),
			'add_new_item'          => __( 'Add New Lecture', 'sedno' ),
			'add_new'               => __( 'Add New', 'sedno' ),
			'new_item'              => __( 'New Lecture', 'sedno' ),
			'edit_item'             => __( 'Edit Lecture', 'sedno' ),
			'update_item'           => __( 'Update Lecture', 'sedno' ),
			'view_item'             => __( 'View Lecture', 'sedno' ),
			'view_items'            => __( 'View Lecture', 'sedno' ),
			'search_items'          => __( 'Search Lecture', 'sedno' ),
			'not_found'             => __( 'Not found', 'sedno' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'sedno' ),
			'items_list'            => __( 'Lecture list', 'sedno' ),
			'items_list_navigation' => __( 'Lecture list navigation', 'sedno' ),
			'filter_items_list'     => __( 'Filter items list', 'sedno' ),
		);
		$args   = array(
			'can_export'          => true,
			'capability_type'     => 'page',
			'description'         => __( 'Lecture', 'sedno' ),
			'exclude_from_search' => true,
			'has_archive'         => _x( 'lectures', 'archive slug', 'sedno' ),
			'hierarchical'        => false,
			'label'               => __( 'Lectures', 'sedno' ),
			'labels'              => $labels,
			'public'              => true,
			'show_in_admin_bar'   => true,
			'show_in_menu'        => apply_filters( 'sedno_post_type_show_in_menu' . $this->post_type_name, 'edit.php' ),
			'show_in_nav_menus'   => true,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'rewrite'             => array(
				'slug' => _x( 'lecture', 'rewrite slug', 'sedno' ),
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

