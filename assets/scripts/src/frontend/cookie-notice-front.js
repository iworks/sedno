( function ( $ ) {
	/**
	 * bind
	 */
	$( document ).ready( function () {
		var value;
		$( window.opi_jobs_theme.cookie.id + ' .opi-jobs-cn-set-cookie' ).on( 'click', function ( e ) {
			e.preventDefault();
			$( this ).opiJobsSetCookieNotice();
			return false;
		} );
		/**
		 * it ws already shown
		 */
		value = $.fn.opiJobsGetCookieValue( window.opi_jobs_theme.cookie.cookie.name + '_close' );
		if ( 'hide' === value ) {
			$( window.opi_jobs_theme.cookie.id ).hide();
		}
	} );

	/**
	 * get cookie value
	 */
	$.fn.opiJobsGetCookieValue = function( cname ) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	};

	/**
	 * set Cookie Notice
	 */
	$.fn.opiJobsSetCookieNotice = function () {
		var notice = $( window.opi_jobs_theme.cookie.id );
		var expires = new Date();
		var value = parseInt( expires.getTime() );
		var cookie = '';
		/**
		 * set time
		 */
		value = parseInt( expires.getTime() );
		/**
		 * add time
		 */
		value += parseInt( window.opi_jobs_theme.cookie.cookie.value ) * 1000;
		/**
		 * add time zone
		 */
		value += parseInt( window.opi_jobs_theme.cookie.cookie.timezone ) * 1000;
		/**
		 * set time
		 */
		expires.setTime( value + 2 * 24 * 60 * 60 * 1000 );
		/**
		 * add cookie timestamp
		 */
		cookie = window.opi_jobs_theme.cookie.cookie.name + '=' + value/1000 + ';';
		cookie += ' expires=' + expires.toUTCString() + ';';
		if ( window.opi_jobs_theme.cookie.cookie.domain ) {
			cookie += ' domain=' + window.opi_jobs_theme.cookie.cookie.domain + ';';
		}
		/**
		 * Add cookie now (fix cache issue)
		 */
		cookie += ' path=' + window.opi_jobs_theme.cookie.cookie.path + ';';
		if ( 'on' === window.opi_jobs_theme.cookie.cookie.secure ) {
			cookie += ' secure;';
		}
		document.cookie = cookie;
		cookie = window.opi_jobs_theme.cookie.cookie.name + '_close=hide;';
		cookie += ' expires=;';
		if ( window.opi_jobs_theme.cookie.cookie.domain ) {
			cookie += ' domain=' + window.opi_jobs_theme.cookie.cookie.domain + ';';
		}
		cookie += ' path=' + window.opi_jobs_theme.cookie.cookie.path + ';';
		if ( 'on' === window.opi_jobs_theme.cookie.cookie.secure ) {
			cookie += ' secure;';
		}
		document.cookie = cookie;
		/**
		 * set user meta
		 */
		if ( undefined !== window.opi_jobs_theme.cookie.logged && 'yes' === window.opi_jobs_theme.cookie.logged ) {
			var data = {
				'action': 'opi_jobs_cookie_notice',
				'user_id': window.opi_jobs_theme.cookie.user_id,
				'nonce': window.opi_jobs_theme.cookie.nonce
			};
			$.post( window.opi_jobs_theme.cookie.ajaxurl, data );
		} else {
			// Dimiss the notice for visitor.
			var data = {
				'action': 'opi_jobs_dismiss_visitor_notice',
				'nonce': window.opi_jobs_theme.cookie.nonce
			};
			$.post( window.opi_jobs_theme.cookie.ajaxurl, data );
		}
		/**
		 * hide
		 */
		notice.fadeOut( 400 );
	};

} )( jQuery );
