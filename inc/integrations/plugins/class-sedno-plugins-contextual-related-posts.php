<?php

class Sedno_Integration_Plugin_Contextual_Rlated_Posts {

	public function __construct() {
		add_filter( 'crp_list_link', array( $this, 'filter_crp_list_link' ), 10, 3 );
	}

	public function filter_crp_list_link( $output, $result, $args ) {
		$title           = crp_title( $args, $result );
		$link            = crp_permalink( $args, $result );
		$link_attributes = crp_link_attributes( $args );
		$classes         = array(
			'thumbnail',
		);
		$styles          = array();
		$data            = '';
		$attachment_id   = get_post_thumbnail_id( $result );
		if ( $attachment_id ) {
			$classes = get_post_class( $classes, $attachment_id );
			$value   = apply_filters( 'iworks_aggresive_lazy_load_get_dominant_color', null, $attachment_id );
			if ( ! empty( $value ) ) {
				$styles[] = sprintf( 'background-color:#%s', $value );
			}
			$value = apply_filters( 'iworks_aggresive_lazy_load_get_tiny_thumbnail', null, $attachment_id );
			if ( ! empty( $value ) ) {
				$styles[] = sprintf( 'background-image:url(%s)', $value );
			}
			$data .= sprintf(
				' data-src="%s"',
				esc_url( get_the_post_thumbnail_url( $result, 'crp_thumbnail' ) )
			);
		} else {
			$classes[] = 'no-thumbnail';
		}
		/**
		 * output
		 */
		$output  = '';
		$output .= sprintf(
			'<a href="%s" %s class="%s">',
			$link,
			$link_attributes,
			implode( ' ', get_post_class( 'crp_link', $result ) )
		);

		$output .= sprintf(
			'<span class="%s" style="%s"%s></span>',
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( implode( ';', $styles ) ),
			$data
		);
		$output .= '<span class="crp_title">' . $title . '</span>'; // Add title when required by settings.
		$output .= '</a>';
		return $output;
	}
}



