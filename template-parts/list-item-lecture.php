<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sedno
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php get_template_part( 'template-parts/thumbnail', 'movie' ); ?>
	<div class="entry-content">
		<header class="entry-header">
			<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
		</header>
		<?php the_excerpt(); ?>
	</div>
</article>
<!-- #post-<?php the_ID(); ?> -->

