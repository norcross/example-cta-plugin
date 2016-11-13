<?php
/**
 * Example CTA Plugin - settings functions
 *
 * Contains settings page related functions.
 *
 * @package Example CTA Plugin
 */

/**
 * Set up and load our class.
 */
class EXCTA_Settings
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

		// Add our setting for the serialized array of items in the default CTA.
		register_setting( 'reading', 'example-cta', array( $this, 'data_sanitize' ) );

		// Create our settings section, hooked into the "reading" section.
		add_settings_section( 'example-cta', __( 'Example Call To Action', 'example-cta-plugin' ), array( $this, 'settings' ), 'reading' );
	}

	/**
	 * Our settings section.
	 *
	 * @param  array $args  The arguments from the `add_settings_section` call.
	 */
	public function settings( $args ) {

		// Fetch our stored settings.
		$place  = EXCTA_Helper::get_single_option( 'example-cta', 'below', 'place' );
		$title  = EXCTA_Helper::get_single_option( 'example-cta', '', 'title' );
		$text   = EXCTA_Helper::get_single_option( 'example-cta', '', 'text' );

		// Set our settings for the WP_Editor call.
		$editor = EXCTA_Helper::get_wp_editor_args( 'example-cta[text]' );

		// Add a div to wrap our whole thing for clean.
		echo '<div class="' . esc_attr( $args['id'] ) . '-wrap">';

		// Add our intro content.
		echo '<p>' . esc_html__( 'Enter the title and content for your call to action.', 'example-cta-plugin' ) . '</p>';

		// Now set up the table with each value.
		echo '<table id="' . esc_attr( $args['id'] ) . '" class="example-cta-settings-table form-table">';
		echo '<tbody>';

			// Our placement radio field.
			echo '<tr>';

				// The field label.
				echo '<th scope="row">';
					echo '<label>' . esc_html__( 'CTA Placement', 'example-cta-plugin' ) . '</label>';
				echo '</th>';

				// The input field.
				echo '<td>';

					echo '<span class="example-cta-radio">';
						echo '<label for="example-cta-place-above">';
						echo '<input type="radio" id="example-cta-place-above" name="example-cta[place]" value="above" ' . checked( $place, 'above', false ) . ' />';
						echo ' ' . esc_html__( 'Place above post content.', 'example-cta-plugin' ) . '</label>';
					echo '</span>';

					echo '<span class="example-cta-radio">';
						echo '<label for="example-cta-place-below">';
						echo '<input type="radio" id="example-cta-place-below" name="example-cta[place]" value="below" ' . checked( $place, 'below', false ) . ' />';
						echo ' ' . esc_html__( 'Place below post content.', 'example-cta-plugin' ) . '</label>';
					echo '</span>';

					echo '<span class="example-cta-radio">';
						echo '<label for="example-cta-place-manual">';
						echo '<input type="radio" id="example-cta-place-manual" name="example-cta[place]" value="manual" ' . checked( $place, 'manual', false ) . ' />';
						echo ' ' . sprintf( __( 'Manually insert post content via %s shortcode.', 'example-cta-plugin' ), '<code>[example_cta]</code>' ) . '</label>';
						echo ' ';
					echo '</span>';

				echo '</td>';

			// Close our placement radio field.
			echo '</tr>';

			// Our title input field.
			echo '<tr>';

				// The field label.
				echo '<th scope="row">';
					echo '<label for="example-cta-title">' . esc_html__( 'CTA Title', 'example-cta-plugin' ) . '</label>';
				echo '</th>';

				// The input field.
				echo '<td>';
					echo '<input type="text" id="example-cta-title" class="widefat" name="example-cta[title]" value="' . esc_attr( $title ) . '" />';
				echo '</td>';

			// Close our title field.
			echo '</tr>';

			// Our text input field.
			echo '<tr>';

				// The field label.
				echo '<th scope="row">';
					echo '<label for="example-cta-text">' . esc_html__( 'CTA Text', 'example-cta-plugin' ) . '</label>';
				echo '</th>';

				// The input field.
				echo '<td>';
					wp_editor( $text, 'example_cta_text', $editor );
				echo '</td>';

			// Close our title field.
			echo '</tr>';

			// Call our action to include any extra settings.
			do_action( 'example_cta_settings_page', $args );

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
		return apply_filters( 'example_cta_data_sanitize', $input );
	}

	// End the class.
}

// Instantiate our class.
$EXCTA_Settings = new EXCTA_Settings();
$EXCTA_Settings->init();
