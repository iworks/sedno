<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package sedno
 */

if ( ! function_exists( 'sedno_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function sedno_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}
		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date( get_option( 'date_format' ) ) )
		);
		$posted_on   = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'sedno' ),
			$time_string
			// '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'sedno_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function sedno_posted_by() {
		$byline = '';
		if ( function_exists( 'coauthors_posts_links' ) ) {
			$byline = sprintf(
				/* translators: %s: post author. */
				esc_html_x( 'by %s', 'post author', 'sedno' ),
				coauthors_posts_links( null, null, null, null, false )
			);
		} else {
			$byline = sprintf(
				/* translators: %s: post author. */
				esc_html_x( 'by %s', 'post author', 'sedno' ),
				'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
			);
		}

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'sedno_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function sedno_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'sedno' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'sedno' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', _x( '</li><li>', 'list item separator', 'sedno' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<div class="tags-links"><ul><li>%1$s</li></ul></div>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/**
			 * social
			 */
			echo '<div class="social-media-share">';
			echo '<ul>';
			$links     = array(
				'twitter'  => __( 'Share %s on twitter', 'sedno' ),
				'facebook' => __( 'Share %s on facebook', 'sedno' ),
				'copy'     => __( 'Copy link: %s', 'sedno' ),
			);
			$permalink = get_permalink();
			$title     = html_entity_decode( get_the_title() );
			foreach ( $links as $class => $label ) {
				if ( 'copy' === $class ) {
					continue;
				}
				$link = $permalink;
				switch ( $class ) {
					case 'twitter':
						$link = add_query_arg(
							array(
								'url'  => urlencode( $link ),
								'text' => urlencode( $title ),
							),
							'https://twitter.com/intent/tweet'
						);
						break;
					case 'facebook':
						$link = add_query_arg(
							array(
								'u' => urlencode( $link ),
							),
							'https://www.facebook.com/sharer/sharer.php'
						);
						break;
					case 'copy':
						$link = '#';
				}
				printf(
					'<li><a href="%1$s" class="%4$s" data-title="%2$s" target="%4$s"><span>%3$s</span></a></li>',
					$link,
					get_the_title(),
					esc_html( sprintf( $label, $permalink ) ),
					esc_attr( $class )
				);
			}
			/**
			 * copy
			 */
			$class = 'copy';
			printf(
				'<li><a href="#" data-url="%s" class="%s"><span>%s</span></a></li>',
				esc_url( $permalink ),
				$class,
				sprintf( $links[ $class ], $permalink )
			);
			echo '</ul></div>';
		}
		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'sedno' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'sedno_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function sedno_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
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

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;
