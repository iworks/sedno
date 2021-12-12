<?php
// $key     = 'iworks_slider';
// $content = wp_cache_get( $key );
// if ( empty( $content ) ) {
	$content = '';
	ob_start();
	$args  = array(
		'posts_per_page' => 3,
		'meta_query'     => array(
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS',
			),
		),
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		$menu = '<nav><ul>';
		echo '<section id="main-slider">';
		echo '<div class="slider-wrap">';
		$i = 0;
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/content', 'slider', array( 'index' => $i ) );
			$menu .= sprintf(
				'<li><a href="#post-%1$d" data-id="%1$d" data-index="%3$d"><span>%2$s</span></a></li>',
				get_the_ID(),
				get_the_title(),
				$i++
			);
		}
		wp_reset_postdata();
		$menu .= '</ul></nav>';
		printf( '<div class="container">%s</div>', $menu );
		echo '</div>';
		echo '</section>';
	}
	$content = ob_get_contents();
	ob_end_clean();
	// wp_cache_set( $key, $content );
	// }
	echo $content;
