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
<?php get_template_part( 'template-parts/thumbnail' ); ?>
	<div class="entry-content">
		<header class="entry-header">
			<div class="entry-meta"><?php sedno_posted_on(); ?></div>
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
			<div class="entry-meta"><?php sedno_posted_by(); ?></div>
		</header>
		<?php the_excerpt(); ?>
	</div>
</article>
<!-- #post-<?php the_ID(); ?> -->

