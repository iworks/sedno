<div class="files">
	<ul>
<?php
$files = get_post_meta( get_the_ID(), '_iworks_media', true );
if ( ! empty( $files ) ) {
	foreach ( $files as $attachement_id ) {
		printf(
			'<li><a href="%s" class="button button-reverse" aria-label="%s" target="sedno-attachement-id-%d">%s</a></li>',
			wp_get_attachment_url( $attachement_id ),
			esc_attr( get_the_title( $attachement_id ) ),
			$attachement_id,
			esc_html(
				preg_match( '/^image/', get_post_mime_type( $attachement_id ) ) ? __( 'View', 'sedno' ) : __( 'Read', 'sedno' )
			)
		);
	}
}
?>
	</ul>
</div>

