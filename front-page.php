<?php
/**
 * The template for displaying front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sedno
 */

get_header();
?>
    <main id="primary" class="site-main">
		<?php
		while ( have_posts() ) {
			the_post();
			get_template_part( 'template-parts/content', 'page' );
        } // End of the loop.
?>
        <aside class="most-important">
            <header>
                <p><?php esc_html_e( 'The most important topics:', 'sedno' ); ?></p>
            </header>
<?php

$args = array(
    'category_name' => 'wazny-temat',
    'posts_per_page' => 5,
);

// The Query
$the_query = new WP_Query( $args );
 
// The Loop
if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
			get_template_part( 'template-parts/content', 'most-important' );
    }
    // no posts found
}
/* Restore original Post Data */
wp_reset_postdata();


?>
        </aside>
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
