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
	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
<?php
the_post_thumbnail(
	'list',
	array(
		'alt' => the_title_attribute(
			array(
				'echo' => false,
			)
		),
	)
);
?>
</a>
	<div class="entry-content">
		<header class="entry-header">
			<div class="entry-meta"><?php sedno_posted_on(); ?></div>
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</header>
		<?php the_excerpt(); ?>
	</div>
</article>
<!-- #post-<?php the_ID(); ?> -->

