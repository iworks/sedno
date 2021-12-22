<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package sedno
 */

get_header();
?>

<main id="primary" class="site-main">

<?php
while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/content', get_post_type() );
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
	if ( function_exists( 'echo_crp' ) ) {
		echo_crp();
	}
}
?>
</main><!-- #main -->
<?php
get_sidebar();
get_footer();

