<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sedno
 */
get_header();
?>
	<main id="primary" class="site-main">
		<?php if ( have_posts() ) { ?>
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Archival Editorial Comments', 'sedno' ); ?></h1>
			</header><!-- .page-header -->
			<?php
			/* Start the Loop */
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/list-item', get_post_type() );
			}
			?>
<p style="text-align:center"><a href="https://sedno.org/ogloszenie/komentarze-redakcyjne-dziennika-sedno-z-lat-2018-2020/">Komentarze opublikowane przed 10.11.2021</a> znajdują się w pliku zbiorczym w zakładce <a href="https://sedno.org/ogloszenia/">Ogłoszenia</a>.</p>
			<?php
			do_action( 'sedno_the_posts_navigation' );
		} else {
			get_template_part( 'template-parts/content', 'none' );
		}
		?>
	</main><!-- #main -->
<?php
get_sidebar();
get_footer();
