<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sedno
 */

get_header();
?>

	<main id="primary" class="site-main">

<?php

if ( have_posts() ) {

	if ( is_home() && ! is_front_page() ) {
		?>
			<header class="page-header">
			<?php
			the_archive_title( '<h1 class="page-title">', '</h1>' );
			the_archive_description( '<div class="archive-description">', '</div>' );
			?>
			</header><!-- .page-header -->
		<?php
	} elseif ( is_single() && is_home() && ! is_front_page() ) {
		?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
		<?php
	}

	/* Start the Loop */
	while ( have_posts() ) {
		the_post();

		if ( is_singular() ) {
			/*
			 * Include the Post-Type-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
			 */
			get_template_part( 'template-parts/content', get_post_type() );
		} else {
			get_template_part( 'template-parts/list-item', get_post_type() );
		}
	}
	do_action( 'sedno_the_posts_navigation' );
} else {
	get_template_part( 'template-parts/content', 'none' );
}
?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
