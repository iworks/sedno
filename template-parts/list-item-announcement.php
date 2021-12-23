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
	<div class="entry-meta"><?php sedno_posted_on(); ?></div>
	<div class="entry-content">
		<header class="entry-header">
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</header>
		<?php the_content(); ?>
	</div>
<?php get_template_part( 'template-parts/announcement/files' ); ?>
</article>
<!-- #post-<?php the_ID(); ?> -->

