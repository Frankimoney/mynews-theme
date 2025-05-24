/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );
	// Primary color.
	wp.customize( 'mynews_primary_color', function( value ) {
		value.bind( function( to ) {
			$( 'a, .main-navigation a:hover, .entry-title a:hover' ).css( 'color', to );
			$( '.button, button, input[type="button"], input[type="reset"], input[type="submit"]' ).css( 'background-color', to );
		} );
	} );
	
	// Footer background color.
	wp.customize( 'mynews_footer_bg_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-footer' ).css( 'background-color', to );
		} );
	} );
	
	// Footer text color.
	wp.customize( 'mynews_footer_text_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-footer' ).css( 'color', to );
			$( '.site-footer a' ).css( 'color', to );
		} );
	} );
	
	// Footer accent color.
	wp.customize( 'mynews_footer_accent_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-footer .widget-title:after, .site-footer .footer-widget-title:after' ).css( 'background-color', to );
			$( '.site-footer .footer-contact li i' ).css( 'color', to );
			$( '.site-footer a:hover' ).css( 'color', '#ffffff' );
		} );
	} );
} )( jQuery );
