<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package sedno
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function sedno_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'sedno_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function sedno_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'sedno_pingback_header' );

function sedno_single_post_article_header_style( $post_ID, $z_index ) {
	$thumbnail_id = get_post_thumbnail_id();
	$thumb        = apply_filters( 'iworks_aggresive_lazy_load_get_tiny_thumbnail', '', $thumbnail_id );
	$color        = apply_filters( 'iworks_aggresive_lazy_load_get_dominant_color', '', $thumbnail_id );
	printf(
		' style="z-index:%1$d;background-color:#%2$s;background-image:url(%3$s)" data-src="%4$s" data-int5o5src="%4$s"',
		$z_index,
		esc_attr( $color ),
		esc_attr( $thumb ),
		esc_url( get_the_post_thumbnail_url( $post_ID, 'full' ) )
	);
}
