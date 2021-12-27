<?php

class Sedno_Integration_Service_Google_Analitics {

	private $id = 'G-RQKN1QZTZL';

	public function __construct() {
		add_action( 'wp_head', array( $this, 'add_gtag' ) );
	}

	public function add_gtag() {
		if ( is_admin() ) {
			return;
		}
		if ( is_user_logged_in() ) {
			return;
		}
		if ( ! preg_match( '@^https?://(www.)?sedno.org@', get_site_url() ) ) {
			return;
		}
		?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $this->id; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo $this->id; ?>');
</script>
		<?php
	}
}
