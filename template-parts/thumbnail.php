<?php

$args = wp_parse_args(
	$args,
	array(
		'size'  => 'list',
		'tag'   => 'a',
		'inner' => '',
	)
);
if ( has_post_thumbnail() ) {
	$attachment_id = get_post_thumbnail_id( get_the_ID(), $args['size'] );
	if ( $attachment_id ) {
		$styles = array();
		$data   = '';
		$value  = apply_filters( 'iworks_aggresive_lazy_load_get_dominant_color', null, $attachment_id );
		if ( ! empty( $value ) ) {
			$styles[] = sprintf( 'background-color:#%s', $value );
		}
		$value = apply_filters( 'iworks_aggresive_lazy_load_get_tiny_thumbnail', null, $attachment_id );
		if ( ! empty( $value ) ) {
			$styles[] = sprintf( 'background-image:url(%s)', $value );
		}
		$data .= sprintf(
			' data-src="%s"',
			esc_url( get_the_post_thumbnail_url( get_the_ID(), 'list' ) )
		);
		printf(
			'<%1$s class="post-thumbnail" %2$s style="%3$s" %4$s>%5$s</%1$s>',
			$args['tag'],
			'a' === $args['tag'] ? sprintf( ' aria-hidden="true" tabindex="-1" href="%s"', esc_url( get_permalink() ) ) : '',
			esc_attr( implode( ';', $styles ) ),
			$data,
			$args['inner']
		);
	}
} else {
	printf(
		'<%1$s class="post-thumbnail no-thumbnail"></%1$s>',
		'span'
	);
}

