<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sedno
 */
?>
<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php
$movie          = apply_filters( 'sedno_get_movie_data', array() );
$show_thumbnail = true;
if ( is_array( $movie ) && isset( $movie['provider'] ) && ! empty( $movie['provider'] ) ) {
	switch ( $movie['provider'] ) {
		case 'youtube':
			if ( isset( $movie['id'] ) ) {
				$show_thumbnail = false;
				$data           = sprintf(
					'data-src="%s"',
					sprintf( 'https://img.youtube.com/vi/%s/%s.jpg', $movie['id'], 'maxresdefault' )
				);
				printf(
					'<%1$s class="post-thumbnail post-thumbnail-youtube" style="background-image:url(%3$s)" %2$s></%1$s>',
					'span',
					$data,
					sprintf( 'https://img.youtube.com/vi/%s/%s.jpg', $movie['id'], '0' )
				);
			}
			break;
		default:
	}
}
if ( $show_thumbnail ) {
	get_template_part( 'template-parts/thumbnail', '', array( 'tag' => 'span' ) );
}
?>
	<div class="entry-content">
		<header class="entry-header">
			<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
		</header>
		<?php the_excerpt(); ?>
	</div>
</article>
</a>
<!-- #post-<?php the_ID(); ?> -->

