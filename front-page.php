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
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'sedno' ),
				'after'  => '</div>',
			)
		);
		?>
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

$args = array(
	'category_name'  => 'wazny-temat',
	'posts_per_page' => 5,
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
?>
		</aside>
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
