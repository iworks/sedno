<?php
if ( has_post_thumbnail() ) {
	$movie = apply_filters( 'sedno_get_movie_data', array() );
	if ( is_array( $movie ) && isset( $movie['url'] ) && ! empty( $movie['url'] ) ) {
		printf(
			'<a href="%s" target="_blank" aria-label="%s">',
			esc_url( $movie['url'] ),
			esc_attr( get_the_title() )
		);
	}
	$args = array(
		'tag'   => 'span',
		'inner' => '<span class="filling"></span>',
	);
	if (
		isset( $movie['playlist'] )
		&& $movie['playlist']
		&& isset( $movie['count'] )
		&& 1 < $movie['count']
	) {
		$args['inner'] .= '<span class="playlist"></span>';
		$args['inner'] .= sprintf(
			'<span class="number">%d</span>',
			$movie['count']
		);
	}
	get_template_part( 'template-parts/thumbnail', '', $args );
	if ( is_array( $movie ) && isset( $movie['url'] ) && ! empty( $movie['url'] ) ) {
		echo '</a>';
	}
}

