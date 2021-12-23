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
	<header class="entry-header">
			<div class="entry-meta"><?php sedno_posted_on(); ?></div><!-- .entry-meta -->
<?php
			the_title( '<h1 class="entry-title">', '</h1>' );
?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->
	<footer class="entry-footer">
<?php get_template_part( 'template-parts/announcement/files' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
