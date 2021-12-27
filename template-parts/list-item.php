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
<?php
if ( has_post_thumbnail() ) {



	$attachment_id = get_post_thumbnail_id( get_the_ID(), 'list' );
	if ( $attachment_id ) {
		$styles = array();
		$data   = '';

		$value = apply_filters( 'iworks_aggresive_lazy_load_get_dominant_color', null, $attachment_id );
		if ( ! empty( $value ) ) {
			$styles[] = sprintf( 'background-color:#%s', $value );
		}
		$value = apply_filters( 'iworks_aggresive_lazy_load_get_tiny_thumbnail', null, $attachment_id );
		if ( ! empty( $value ) ) {
			$styles[] = sprintf( 'background-image:url(%s)', $value );
		}
		$data .= sprintf(
			' data-src="%s"',
			esc_url( get_the_post_thumbnail_url( get_the_ID() ) )
		);
		printf(
			'<a class="post-thumbnail" href="%s" style="%s"%s aria-hidden="true" tabindex="-1"></a>',
			esc_url( get_permalink() ),
			esc_attr( implode( ';', $styles ) ),
			$data
		);
	}
	?>
</a>
<?php } else { ?>
<?php } ?>
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

