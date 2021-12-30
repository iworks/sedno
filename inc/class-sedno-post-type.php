<?php

require_once 'class-sedno.php';

abstract class Sedno_Post_Type extends Sedno {

	protected $post_type_name = '';

	/**
	 * movie
	 *
	 */
	protected $option_name_movie          = '_iworks_movie';
	protected $option_name_movie_id       = '_iworks_movie_id';
	protected $option_name_movie_provider = '_iworks_movie_provider';
	protected $option_name_movie_playlist = '_iworks_movie_playlist';
	protected $option_name_movie_count    = '_iworks_movie_count';

	public function __construct() {
		parent::__construct();
		/**
		 * WordPress Hooks
		 */
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box_movie' ) );
		add_action( 'init', array( $this, 'custom_post_type' ), 0 );
		add_action( 'save_post', array( $this, 'save_movie_data' ) );
		add_filter( 'post_class', array( $this, 'add_custom_post_classes' ), 10, 3 );
		/**
		 * Theme hooks
		 */
		add_filter( 'sedno_get_movie_data', array( $this, 'filter_get_movie_data' ) );
	}

	private function get_movie_data( $post_id ) {
		return array(
			'url'      => get_post_meta( $post_id, $this->option_name_movie, true ),
			'id'       => get_post_meta( $post_id, $this->option_name_movie_id, true ),
			'provider' => get_post_meta( $post_id, $this->option_name_movie_provider, true ),
			'count'    => max( 1, intval( get_post_meta( $post_id, $this->option_name_movie_count, true ) ) ),
			'playlist' => get_post_meta( $post_id, $this->option_name_movie_playlist, true ),
		);
	}

	public function filter_get_movie_data( $data ) {
		return $this->get_movie_data( get_the_ID() );
	}

	/**
	 * Register Custom Post Type
	 *
	 * @since 1.0.0
	 */
	abstract public function custom_post_type();

	/**
	 * Add meta boxes
	 *
	 * @since 1.0.0
	 */
	public function add_meta_box_movie() {
		add_meta_box(
			'',
			__( 'Movie', 'sedno' ),
			array( $this, 'html_movie' ),
			$this->post_type_name,
			'normal',
			'default'
		);
	}

	/**
	 * movie files
	 *
	 * @since 1.0.0
	 */
	public function html_movie( $post ) {
		wp_nonce_field( $this->nonce_value, '_iworks_movie_nonce' );
		$value = get_post_meta( $post->ID, $this->option_name_movie, true );
		echo '<p>';
		echo '<label>';
		esc_html_e( 'Movie URL', 'sedno' );
		echo '<br />';
		printf(
			'<input type="url" value="%s" name="%s" class="large-text code" />',
			esc_url( $value ),
			$this->option_name_movie,
		);
		echo '</label>';
		echo '</p>';
		/**
		 * number of movies
		 */
		$value = max( 1, intval( get_post_meta( $post->ID, $this->option_name_movie_count, true ) ) );
		echo '<p>';
		echo '<label>';
		esc_html_e( 'Number of movies', 'sedno' );
		echo '<br />';
		printf(
			'<input type="number" value="%d" min="1" name="%s" class="small-text code"/>',
			$value,
			$this->option_name_movie_count,
		);
		echo '</label>';
		echo '</p>';
	}

	public function add_movie( $content ) {
		if ( ! is_singular( $this->post_type_name ) ) {
			return $content;
		}
		$data = $this->get_movie_data( get_the_ID() );
		if ( ! is_array( $data ) ) {
			return $content;
		}
		if ( ! isset( $data['provider'] ) ) {
			return $content;
		}
		switch ( $data['provider'] ) {
			case 'youtube':
			case 'youtube-playlist':
				$content .= PHP_EOL;
				$content .= PHP_EOL;
				$content .= $data['url'];
				$content .= PHP_EOL;
				break;
			case 'facebook':
				$content .= PHP_EOL;
				$content .= PHP_EOL;
				$content .= sprintf( '<a href="%1$s">%1$s</a>', esc_url( $data['url'] ) );
				break;
			case 'facebook-video':
				$content .= sprintf(
					'<!-- wp:video --><figure class="wp-block-video"><video controls src="%s"></video></figure><!-- /wp:video -->',
					$data['url']
				);
				break;
		}
		return $content;
	}

	public function save_movie_data( $post_ID ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$nonce = filter_input( INPUT_POST, '_iworks_movie_nonce', FILTER_SANITIZE_STRING );
		if ( ! wp_verify_nonce( $nonce, $this->nonce_value ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_ID ) ) {
			return;
		}
		/*
		 * movie count
		 */
		$value = filter_input( INPUT_POST, $this->option_name_movie_count, FILTER_SANITIZE_NUMBER_INT );
		$this->update_meta( $post_ID, $this->option_name_movie_count, $value );
		/**
		 * movie url
		 */
		$value = filter_input( INPUT_POST, $this->option_name_movie, FILTER_SANITIZE_URL );
		$this->update_meta( $post_ID, $this->option_name_movie, $value );
		/**
		 * yt thumb
		 */
		$movie_id       = null;
		$movie_provider = null;
		$playlist       = 'no';
		/**
		 * parse short youtube share url
		 */
		if ( preg_match( '#https?://youtu.be/([0-9a-z\-_]+)#i', $value, $matches ) ) {
			$movie_id       = $matches[1];
			$movie_provider = 'youtube';
		} elseif ( preg_match( '#https?://(www\.)?youtube\.com/watch\?v=([0-9a-z\-_]+)#i', $value, $matches ) ) {
			$movie_id       = $matches[2];
			$movie_provider = 'youtube';
		} elseif ( preg_match( '#https://(www\.)?youtube.com/channel/([^/]+)/playlists#', $value, $matches ) ) {
			$movie_id       = $matches[2];
			$movie_provider = 'youtube-playlist';
			$playlist       = 'yes';
		} elseif ( preg_match( '#https://youtube.com/playlist#', $value ) ) {
			$params = wp_parse_args( preg_replace( '/^[^\?]+\?/', '', $value ) );
			if ( isset( $params['list'] ) && ! empty( $params['list'] ) ) {
				$movie_id       = $params['list'];
				$movie_provider = 'youtube-playlist';
				$playlist       = 'yes';
			}
		}
		/**
		 * parse Facebook url
		 */
		if ( preg_match( '#https://fb.watch/([^/]+)/#', $value, $matches ) ) {
			$movie_id       = $matches[1];
			$movie_provider = 'facebook';
		} elseif ( preg_match( '#https?://(www\.)?facebook.com/[^/]+/posts/(\d+)#i', $value, $matches ) ) {
			$movie_id       = $matches[2];
			$movie_provider = 'facebook-post';
		}
		$this->update_meta( $post_ID, $this->option_name_movie_id, $movie_id );
		$this->update_meta( $post_ID, $this->option_name_movie_provider, $movie_provider );
		$this->update_meta( $post_ID, $this->option_name_movie_playlist, $playlist );

	}

	public function add_custom_post_classes( $classes, $class, $post_id ) {
		$data = $this->get_movie_data( $post_id );
		if ( is_array( $data ) ) {
			if ( isset( $data['url'] ) && ! empty( $data['url'] ) ) {
				$classes[] = 'has-movie-url';
			}
			if ( isset( $data['provider'] ) && ! empty( $data['provider'] ) ) {
				$classes[] = sprintf(
					'movie-provider-%s',
					esc_attr( $data['provider'] )
				);
			}
			if ( isset( $data['playlist'] ) && $data['playlist'] ) {
				$classes[] = 'is-playlist';
			}
		}
		return $classes;
	}
}

