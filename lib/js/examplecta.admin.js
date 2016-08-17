//******************************************************************************
// Let's start the engine.
//******************************************************************************
jQuery(document).ready( function($) {

	/**
	 * Set some various vars for later.
	 */
	var ctaSelect;

	/**
	 * Show or hide our fields given the user selection.
	 */
	$( '.example-cta-postmeta-table' ).on( 'change', 'input#example-cta-post-disable', function() {

		// Figure out what we just checked.
		ctaSelect = $( this ).is( ':checked' ) ? 'hide' : 'show';

		// Find each of our rows and handle them.
		$( '.example-cta-postmeta-table' ).find( 'tr.example-cta-single-row' ).each( function() {

			// Hide ALL THE THINGS.
			if ( 'hide' === ctaSelect ) {
				$( this ).addClass( 'example-cta-single-row-hidden' );
			}

			// Show ALL THE THINGS.
			if ( 'show' === ctaSelect ) {
				$( this ).removeClass( 'example-cta-single-row-hidden' );
			}
		});

	});

//******************************************************************************
// And...that's all folks. We're done here.
//******************************************************************************
});