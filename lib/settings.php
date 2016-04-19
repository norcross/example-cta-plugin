<?php
/**
 * Squares 2016 CTA - settings functions
 *
 * Contains settings page related functions.
 *
 * @package Squares 2016 CTA
 */

/**
 * Set up and load our class.
 */
class SQS2016_Settings
{

	/**
	 * Load our hooks and filters.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_init',                   array( $this, 'load_settings'       )           );
	}

	/**
	 * Register our new settings and load our settings fields.
	 *
	 * @return void
	 */
	public function load_settings() {

		// Add our setting for the amount of days to keep a sticky, and the total number of them.
		register_setting( 'reading', 'squares-cta', array( $this, 'data_sanitize' ) );

		// And create our settings section.
		add_settings_section( 'squares-cta', __( 'Squares 2016 Call To Action', 'squares2016-cta' ), array( $this, 'settings' ), 'reading' );
	}

	/**
	 * Our settings section.
	 *
	 * @param  array $args  The arguments from the add_settings_section call.
	 */
	public function settings( $args ) {

		// Fetch our stored settings.
		$place  = SQS2016_Helper::get_single_option( 'squares-cta', 'below', 'place' );
		$title  = SQS2016_Helper::get_single_option( 'squares-cta', '', 'title' );
		$text   = SQS2016_Helper::get_single_option( 'squares-cta', '', 'text' );

		// Set our settings for the WP_Editor call.
		$editor = SQS2016_Helper::get_wp_editor_args( 'squares-cta[text]' );

		// Add a div to wrap our whole thing for clean.
		echo '<div class="' . esc_attr( $args['id'] ) . '-wrap">';

		// Add our intro content.
		echo '<p>' . esc_html__( 'Enter the title and content for your call to action.', 'squares2016-cta' ) . '</p>';

		// Now set up the table with each value.
		echo '<table id="' . esc_attr( $args['id'] ) . '" class="squares-cta-settings-table form-table">';
		echo '<tbody>';

			// Our placement radio field.
			echo '<tr>';

				// The field label.
				echo '<th scope="row">';
					echo '<label>' . esc_html__( 'CTA Placement', 'squares2016-cta' ) . '</label>';
				echo '</th>';

				// The input field.
				echo '<td>';

					echo '<span class="squares-cta-radio">';
						echo '<label for="squares-cta-place-above">';
						echo '<input type="radio" id="squares-cta-place-above" name="squares-cta[place]" value="above" ' . checked( $place, 'above', false ) . ' />';
						echo ' ' . esc_html__( 'Place above post content.', 'squares2016-cta' ) . '</label>';
					echo '</span>';

					echo '<span class="squares-cta-radio">';
						echo '<label for="squares-cta-place-below">';
						echo '<input type="radio" id="squares-cta-place-below" name="squares-cta[place]" value="below" ' . checked( $place, 'below', false ) . ' />';
						echo ' ' . esc_html__( 'Place below post content.', 'squares2016-cta' ) . '</label>';
					echo '</span>';

					echo '<span class="squares-cta-radio">';
						echo '<label for="squares-cta-place-manual">';
						echo '<input type="radio" id="squares-cta-place-manual" name="squares-cta[place]" value="manual" ' . checked( $place, 'manual', false ) . ' />';
						echo ' ' . esc_html__( 'Manually insert post content via shortcode.', 'squares2016-cta' ) . '</label>';
					echo '</span>';

				echo '</td>';

			// Close our placement radio field.
			echo '</tr>';

			// Our title input field.
			echo '<tr>';

				// The field label.
				echo '<th scope="row">';
					echo '<label for="squares-cta-title">' . esc_html__( 'CTA Title', 'squares2016-cta' ) . '</label>';
				echo '</th>';

				// The input field.
				echo '<td>';
					echo '<input type="text" id="squares-cta-title" class="widefat" name="squares-cta[title]" value="' . esc_attr( $title ) . '" />';
				echo '</td>';

			// Close our title field.
			echo '</tr>';

			// Our text input field.
			echo '<tr>';

				// The field label.
				echo '<th scope="row">';
					echo '<label for="squares-cta-text">' . esc_html__( 'CTA Text', 'squares2016-cta' ) . '</label>';
				echo '</th>';

				// The input field.
				echo '<td>';
					wp_editor( $text, 'squares_cta_text', $editor );
				echo '</td>';

			// Close our title field.
			echo '</tr>';

			// Call our action to include any extra settings.
			do_action( 'squarescta_settings_page', $args );

		// Close the table.
		echo '</tbody>';
		echo '</table>';
	}

	/**
	 * Sanitize the user data inputs.
	 *
	 * @param  array $input  The data entered in a settings field.
	 *
	 * @return array $input  The sanitized data.
	 */
	public function data_sanitize( $input ) {

		// Make sure we have an array.
		$input  = (array) $input;

		// Sanitize the title text input.
		$title  = ! empty( $input['title'] ) ? sanitize_text_field( $input['title'] ) : '';

		// Sanitize the text input.
		$text   = ! empty( $input['text'] ) ? wp_kses_post( $input['text'] ) : '';

		// Sanitize the placement radio input.
		$place  = ! empty( $input['place'] ) ? sanitize_text_field( $input['place'] ) : 'below';

		// Set our new input array.
		$input  = array( 'title' => $title, 'text' => $text, 'place' => $place );

		// And return our input with a filter to allow
		// additional settings to be added later.
		return apply_filters( 'squarescta_data_sanitize', $input );
	}

	// End the class.
}

// Instantiate our class.
$SQS2016_Settings = new SQS2016_Settings();
$SQS2016_Settings->init();
