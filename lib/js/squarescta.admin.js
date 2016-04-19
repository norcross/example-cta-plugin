//********************************************************************************************************************************
// now start the engine
//********************************************************************************************************************************
jQuery(document).ready( function($) {

	/**
	 * set some various vars for later
	 */
	var ctaSelect;

	/**
	 * show or hide our fields given the user selection
	 */
	$( '.squares-post-cta-table' ).on( 'change', 'input#squares-post-disable', function() {

		// Figure out what we just checked.
		ctaSelect = $( this ).is( ':checked' ) ? 'hide' : 'show';

		// Find each of our rows and handle them.
		$( '.squares-post-cta-table' ).find( 'tr.squares-single-row' ).each( function() {

			// Hide ALL THE THINGS.
			if ( 'hide' === ctaSelect ) {
				$( this ).addClass( 'squares-single-row-hidden' );
			}

			// Show ALL THE THINGS.
			if ( 'show' === ctaSelect ) {
				$( this ).removeClass( 'squares-single-row-hidden' );
			}
		});

	});

//********************************************************************************************************************************
// that's all folks. we're done here
//********************************************************************************************************************************
});