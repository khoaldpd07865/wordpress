/**
 * frontend.js
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Waiting List
 * @version 1.0.0
 */

jQuery( document ).ready( function( $ ) {
	"use strict";

	var addUrlParameter = function( key, value, t ) {
			key = encodeURI( key );
			value = encodeURI( value );

			var url_elem = t.parents( '#yith-wcwtl-output' ).find( 'a.button, form' ),
				prop = typeof url_elem.attr( 'action' ) != 'undefined' ? 'action' : 'href',
				url = url_elem.attr( prop ),
				kvp = url.split( '&' );

			var i = kvp.length;
			var x;

			// if exists change it!
			while (i--) {
				x = kvp[i].split( '=' );
				if ( x[0] == key ) {
					x[1] = value;
					kvp[i] = x.join( '=' );
					break;
				}
			}

			// if not exists add
			if ( i < 0 ) {
				kvp[kvp.length] = [key, value].join( '=' );
			}
			url_elem.prop( prop, kvp.join( '&' ) );
		},
		getUrlParameter = function( sURL, data ) { // function to get param from url
			var sURLVariables = sURL.split( '?' )[1].split( '&' ),
				sParameterName,
				i;

			for (i = 0; i < sURLVariables.length; i++) {
				sParameterName = sURLVariables[i].split( '=' );
				data.push( {name: sParameterName[0], value: sParameterName[1]} );
			}

			return data;
		},
		submit = function( url, button ) {

			$(document).trigger('yith-wcwtl-submit');

			// build Data
			var ajaxData = [
					{name: 'action', value: 'yith_wcwtl_submit'},
					{name: 'context', value: 'frontend'}
				],
				form = button.closest( '#yith-wcwtl-output' );

			ajaxData = getUrlParameter( url, ajaxData );

			form.block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});



			isSubmitAllowed().then(function( data ) {
				// Run this when your request was successful
				$.ajax( {
					url: woocommerce_params.ajax_url,
					data: $.param( ajaxData ),
					method: 'POST',
					dataType: 'json',
					error: function( e ) {
					},

					success: function( res ) {

						// add message
						$( document ).find( '.yith-wcwtl-ajax-message' ).remove();

						if( 'error' === res.type ){
							// replace form
							form.replaceWith( res.form );

							$( document ).find('.yith-wcwtl-notices').append( res.msg );

							$( document ).find( '#yith-wcwtl-email' ).trigger( 'input' );
						}else{
							$( document ).find( '#yith-wcwtl-output' ).addClass( 'success' );
							$( document ).find( '#yith-wcwtl-output' ).empty().html( res.msg );
						}

						$( document ).find( '#yith-wcwtl-output' ).unblock();

						$( document ).trigger( 'yith-wcwtl-submit-success' );
					}
				} );
			}).catch(function(err) {
				// Run this when promise was rejected via reject()
				console.log(err)
			})


		};

	const ywcwtlIsAjaxEnabled = ywcwtl.ajax === 'yes';

	$( document ).on( 'click', '#yith-wcwtl-output a.yith-wcwtl-submit', function( ev ) {
		ev.preventDefault();
		if( ywcwtlIsAjaxEnabled ){
			submit( $( this ).attr( 'href' ), $( this ) );
		}else{
			isSubmitAllowed().then( function(){
				window.location.href = $( '.yith-wcwtl-submit' ).attr('href');
			} )
		}
	} );

	$( document ).on( 'submit', '#yith-wcwtl-output form', function( ev ) {
		ev.preventDefault();
		submit( $( this ).attr( 'action' ), $( this ) );
	} );

	$( document ).on( 'input', '#yith-wcwtl-email', function() {
		var t = $( this ),
			val = t.val(),
			name = t.attr( 'name' );

		addUrlParameter( name, val, t );
	} );

	$( document ).on( 'change', '#yith-wcwtl-policy-check', function() {
		var t = $( this ),
			name = t.attr( 'name' ),
			val = t.is( ':checked' ) ? 'yes' : 'no';

		addUrlParameter( name, val, t );
	} );

	var isSubmitAllowed = function(){
		if( ywcwtl.recaptcha_site_key === undefined || ! ywcwtl.recaptcha_site_key ){
			return new Promise(function(resolve, reject) { // Performed a promise to prevent fails on then function
				resolve( true );
			});
		}else{
			return ywtwlIsCaptchaValid();
		}
	}
} );