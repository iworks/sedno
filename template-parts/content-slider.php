<?php
$class   = '';
$z_index = 4;
if ( isset( $args['index'] ) && 0 === $args['index'] ) {
	$class   = 'active';
	$z_index = 5;
}
?>
<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
	<article id="post-<?php the_ID(); ?>" class="<?php echo $class; ?>" >
		<div class="entry-wrap">
			<div class="entry" style="z-index:<?php echo $z_index + 1; ?>">
				<div class="entry-meta"><?php sedno_posted_on(); ?></div>
				<header class="entry-header"><?php the_title( '<h2 class="entry-title">', '</h2>' ); ?></header>
				<div class="entry-meta entry-meta-author">
				<?php
				if ( function_exists( 'coauthors' ) ) {
					coauthors();
				} else {
					echo esc_html( get_the_author() );
				}
				?>
				 </div><!-- .entry-meta -->
				<div class="entry-content"><?php the_excerpt(); ?></div>
				<span class="button"><?php esc_html_e( 'Read more', 'sedno' ); ?></span>
			</div>
			<div class="thumbnail" <?php sedno_single_post_article_header_style( get_the_ID(), $z_index ); ?>></div>
		</div>
	</article><!-- #post-<?php the_ID(); ?> -->
</a>
