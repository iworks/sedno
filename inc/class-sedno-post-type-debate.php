<?php

require_once 'class-sedno-post-type.php';

class Sedno_Post_Type_Debate extends Sedno_Post_Type {

	protected $post_type_name = 'debate';

	public function __construct() {
		parent::__construct();
		add_filter( 'the_content', array( $this, 'add_movie' ), 0 );
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
}

