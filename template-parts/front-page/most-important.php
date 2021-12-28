<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sedno
 */
?>
<a href="<?php the_permalink(); ?>">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php
$args = array(
	'size' => 'news',
	'tag'  => 'span',
);
get_template_part( 'template-parts/thumbnail', null, $args );
?>
		<header class="entry-header">
<?php
the_title( '<h2>', '</h2>' );
?>
			<div class="entry-meta"><?php echo esc_html( get_the_author() ); ?> </div><!-- .entry-meta -->
		</header>
	</article>
</a>
<!-- #post-<?php the_ID(); ?> -->

