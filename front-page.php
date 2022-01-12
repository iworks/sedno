<?php
/**
 * The template for displaying front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sedno
 */

get_header();
get_template_part( 'template-parts/slider' );
?>
	<main id="primary" class="site-main">
<?php

/**
 * Last editorial comment
 */
$args      = array(
	'post_type'      => 'editorial_comment',
	'posts_per_page' => 1,
);
$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) {
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<p><?php esc_html_e( 'Editorial Comment', 'sedno' ); ?></p>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php the_title( '<h3>', '</h3>' ); ?>
		<?php the_content(); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
		<?php
	}
}
?>
		<aside class="most-important">
			<header>
				<p><?php esc_html_e( 'The most important topics', 'sedno' ); ?></p>
			</header>
<?php
$key = 'most';
do_action( 'iworks_cache_keys', $key );
$content = apply_filters( 'iworks_cache_get', '', $key );
if ( empty( $content ) ) {
	ob_start();
	$args = apply_filters(
		'sedno_most_important_wp_query_args',
		array(
			'posts_per_page' => 5,
			'order'          => 'rand',
		)
	);
	// The Query
	$the_query = new WP_Query( $args );
	// The Loop
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			get_template_part( 'template-parts/front-page/most-important' );
		}
		// no posts found
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	$page_for_posts = get_option( 'page_for_posts' );
	$page           = get_page( $page_for_posts );
	if ( is_a( $page, 'WP_Post' ) ) {
		printf(
			'<p><a href="%s" class="button"><span>%s</span></a></p>',
			get_permalink( $page ),
			__( 'Browse all articles', 'sedno' )
		);
	}
	$content = ob_get_contents();
	ob_end_clean();
	do_action( 'iworks_cache_set', $key, $content );
}
echo $content;
?>
		</aside>
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
