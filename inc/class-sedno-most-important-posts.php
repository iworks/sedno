<?php

require_once 'class-sedno.php';

/**
 * Class extends post_type "post" by some features.
 *
 * @since 1.0.0
 */
class Sedno_Most_important_Posts extends Sedno {

	private $option_name     = '_sedno_most_important';
	private $number_of_posts = 5;

	/**
	 * Class consttruct function.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
		add_action( 'admin_init', array( $this, 'register' ) );
		add_action( 'wp_ajax_iworks_posts_search', array( $this, 'ajax_search' ) );
		add_filter( 'sedno_most_important_wp_query_args', array( $this, 'set_query_args' ) );
	}

	public function set_query_args( $args ) {
		$value = get_option( $this->option_name );
		if ( empty( $value ) ) {
			return $args;
		}
		if ( ! is_array( $value ) ) {
			$value = array( $value );
		}
		$args = array(
			'orderby'        => 'post__in',
			'post__in'       => $value,
			'posts_per_page' => 5,
		);
		return $args;
	}

	/**
	 * Register assets.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		wp_register_style(
			strtolower( __CLASS__ ),
			get_stylesheet_directory_uri() . '/assets/css/admin/most-important.css',
			array(),
			$this->version
		);
		wp_register_script(
			strtolower( __CLASS__ ),
			get_stylesheet_directory_uri() . '/assets/scripts/admin/most-important.js',
			array(
				'jquery',
				'jquery-ui-sortable',
				'jquery-ui-autocomplete',
			),
			$this->version,
			true
		);
	}

	/**
	 * Add admin submenu.
	 *
	 * @since 1.0.0
	 */
	public function add_submenu_page() {
		$hook = add_posts_page(
			__( 'Most Important Posts', 'sedno' ),
			__( 'Most Important', 'sedno' ),
			'edit_posts',
			strtolower( __CLASS__ ),
			array( $this, 'admin_page' )
		);
		add_action( 'load-' . $hook, array( $this, 'admin_enqueue' ) );
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @since 1.0.0
	 */
	public function admin_enqueue() {
		wp_enqueue_style( strtolower( __CLASS__ ) );
		wp_enqueue_script( strtolower( __CLASS__ ) );
	}

	/**
	 * Save data from form.
	 *
	 * @since 1.0.0
	 */
	private function update() {
		$nonce = filter_input( INPUT_POST, $this->option_name . '_nonce', FILTER_SANITIZE_STRING );
		if ( ! wp_verify_nonce( $nonce, $this->option_name ) ) {
			return;
		}
		if ( ! isset( $_POST[ $this->option_name ] ) || ! is_array( $_POST[ $this->option_name ] ) ) {
			delete_option( $this->option_name );
			return;
		}
		$value = array_filter( $_POST[ $this->option_name ], 'intval' );
		update_option( $this->option_name, $value );
	}

	/**
	 * Print admin page.
	 *
	 * @since 1.0.0
	 */
	public function admin_page() {
		do_action( 'iworks_cache_set', 'most', null );
		$this->update();
		echo '<div class="wrap">';
		echo '<form method="post">';
		printf( '<input type="hidden" name="page" value="%s" />', esc_attr( $this->option_name ) );
		printf( '<h2>%s</h2>', esc_html__( 'Most Important Posts', 'sedno' ) );
		printf(
			'<p class="description">%s</p>',
			esc_html__( 'Select one or more articles to main page "Most Important Topics" section..', 'sedno' )
		);
		printf(
			'<p class="description">%s</p>',
			esc_html__( 'Drag and drop element to change order.', 'sedno' )
		);
		$value = get_option( $this->option_name );
		echo '<ul class="iworks-most-important">';
		wp_nonce_field( $this->option_name, $this->option_name . '_nonce' );
		$count = 0;
		if ( is_array( $value ) ) {
			foreach ( $value as $one ) {
				$this->one( $one );
				$count++;
			}
		}
		for ( $i = $count; $i < $this->number_of_posts; $i++ ) {
			$this->one();
		}
		echo '</ul>';
		echo '<p class="submit">';
		printf(
			'<input type="submit" name="submit" id="submit" class="button button-primary" value="%s">',
			esc_attr__( 'Save changes', 'sedno' )
		);
		echo '</p>';
		echo '</form>';
		echo '</div>';
	}

	/**
	 * AJAX helper for search posts.
	 *
	 * @since 1.0.0
	 */
	public function ajax_search() {
		$args    = array(
			'posts_per_page' => 20,
			'post_status'    => 'publish',
			'post_type'      => array( 'post' ),
			's'              => stripslashes( filter_input( INPUT_POST, 'search', FILTER_SANITIZE_STRING ) ),
		);
		$results = new WP_Query( $args );
		$items   = array();
		if ( ! empty( $results->posts ) ) {
			foreach ( $results->posts as $result ) {
				$items[] = array(
					'label'     => $result->post_title,
					'excerpt'   => get_the_excerpt( $result->ID ),
					'id'        => $result->ID,
					'thumbnail' => get_the_post_thumbnail_url( $result ),
				);
			}
		}
		wp_send_json_success( $items );
	}

	/**
	 * Helper for one row at admin page.
	 *
	 * @param integer $post_id Post ID.
	 *
	 * @since 1.0.0
	 */
	private function one( $post_id = null ) {
		$title     = sprintf(
			'<input type="text" class="large-text" placeholder="%s" />',
			esc_attr__( 'Start typing to search article...', 'sedno' )
		);
		$excerpt   = '';
		$thumbnail = '<span class="dashicons dashicons-format-image"></span>';
		$excerpt   = '<div class="excerpt"></div>';
		if ( null !== $post_id ) {
			$post      = get_post( $post_id );
			$title    .= sprintf( '<h3>%s</h3>', $post->post_title );
			$excerpt   = sprintf( '<div class="excerpt">%s</div>', get_the_excerpt( $post_id ) );
			$thumbnail = get_the_post_thumbnail( $post_id );
		} else {
			$title .= '<h3></h3>';
		}
		echo '<li>';
		echo '<div class="iworks-most-important-one">';
		printf( '<div class="image">%s</div>', $thumbnail );
		echo '<div class="iworks-most-important-one-text">';
		echo $title, $excerpt;
		echo '</div>';
		printf( '<input class="id" type="hidden" name="%s[]" value="%d" />', esc_attr( $this->option_name ), $post_id );
		echo '</div>';
		echo '</li>';
	}

}
