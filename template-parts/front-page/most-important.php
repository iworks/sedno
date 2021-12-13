<?php
/**
 * Template part for displaying page content in page.php
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
	'news',
	array(
		'alt' => the_title_attribute(
			array(
				'echo' => false,
			)
		),
	)
);
the_title( '<h2>', '</h2>' );
?>
	</a>
</article><!-- #post-<?php the_ID(); ?> -->

