<?php
/**
 * Squares 2016 CTA - post meta functions
 *
 * Contains our functions related to settings custom CTAs
 * on single posts, overriding the global one.
 *
 * @package Squares 2016 CTA
 */

/**
 * Set up and load our class.
 */
class SQS2016_PostMeta
{

	/**
	 * Load our hooks and filters.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'add_meta_boxes',               array( $this, 'create_metaboxes'    )           );
		add_action( 'save_post',                    array( $this, 'save_postmeta'       )           );
	}

	/**
	 * Set up our metabox call.
	 *
	 * @return void
	 */
	public function create_metaboxes() {

		// Only load for users that have the capability.
		if ( ! current_user_can( 'publish_posts' ) ) {
			return;
		}

		// Get the supported post types.
		if ( false === $types = SQS2016_Helper::get_supported_types() ) {
			return;
		}

		// Now loop the types and generate a metabox for each one.
		foreach ( $types as $type ) {
			add_meta_box( 'squares-post-cta', __( 'Single Post CTA', 'squares2016-cta' ), array( $this, 'postmeta' ), $type, 'normal', 'high' );
		}
	}

	/**
	 * Display our various postmeta inputs for a custom CTA.
	 *
	 * @param  object $post  The global post object.
	 *
	 * @return void
	 */
	public function postmeta( $post ) {

		// Fetch our postmeta items.
		$place  = SQS2016_Helper::get_single_postmeta( $post->ID, '_squares_post_cta', 'below', 'place' );
		$title  = SQS2016_Helper::get_single_postmeta( $post->ID, '_squares_post_cta', '', 'title' );
		$text   = SQS2016_Helper::get_single_postmeta( $post->ID, '_squares_post_cta', '', 'text' );

		// Our disable flag.
		$check  = SQS2016_Helper::get_single_postmeta( $post->ID, '_squares_post_cta_disable' );

		// Set a class on our rows for when we're disabled.
		$class  = ! empty( $check ) ? 'squares-single-row squares-single-row-hidden' : 'squares-single-row';

		// Fetch our arguments for the WP_Editor call.
		$editor = SQS2016_Helper::get_wp_editor_args( 'squares-post[text]' );

		// Begin building out the table boxes.
		echo '<table class="squares-post-cta-table form-table">';
		echo '<tbody>';

			// Our checkbox to disable the whole thing.
			echo '<tr>';

				// The field label.
				echo '<th scope="row">';
					echo '<label>' . esc_html__( 'Disable CTA', 'squares2016-cta' ) . '</label>';
				echo '</th>';

				// The input field.
				echo '<td>';
					echo '<label for="squares-post-disable">';
					echo '<input type="checkbox" name="squares-post-disable" id="squares-post-disable" value="1" ' . checked( $check, 1, false ) . '>';
					echo ' <em>' . esc_html__( 'Check this box to disable the CTA on this post.', 'squares2016-cta' ) . '</em></label>';
				echo '</td>';

			// Close our checkbox field.
			echo '</tr>';

			// Our placement radio field.
			echo '<tr class="' . esc_attr( $class ) . '">';

				// The field label.
				echo '<th scope="row">';
					echo '<label>' . esc_html__( 'Placement', 'squares2016-cta' ) . '</label>';
				echo '</th>';

				// The input field.
				echo '<td>';

					echo '<span class="squares-cta-radio">';
						echo '<label for="squares-post-place-above">';
						echo '<input type="radio" id="squares-post-place-above" name="squares-post[place]" value="above" ' . checked( $place, 'above', false ) . ' />';
						echo ' ' . esc_html__( 'Place above post content.', 'squares2016-cta' ) . '</label>';
					echo '</span>';

					echo '<span class="squares-cta-radio">';
						echo '<label for="squares-post-place-below">';
						echo '<input type="radio" id="squares-post-place-below" name="squares-post[place]" value="below" ' . checked( $place, 'below', false ) . ' />';
						echo ' ' . esc_html__( 'Place below post content.', 'squares2016-cta' ) . '</label>';
					echo '</span>';

					echo '<span class="squares-cta-radio">';
						echo '<label for="squares-post-place-manual">';
						echo '<input type="radio" id="squares-post-place-manual" name="squares-post[place]" value="manual" ' . checked( $place, 'manual', false ) . ' />';
						echo ' ' . esc_html__( 'Manually insert post content via shortcode.', 'squares2016-cta' ) . '</label>';
					echo '</span>';

				echo '</td>';

			// Close our placement radio field.
			echo '</tr>';

			// Our title input field.
			echo '<tr class="' . esc_attr( $class ) . '">';

				// The field label.
				echo '<th scope="row">';
					echo '<label for="squares-post-title">' . esc_html__( 'CTA Title', 'squares2016-cta' ) . '</label>';
				echo '</th>';

				// The input field.
				echo '<td>';
					echo '<input type="text" id="squares-post-title" class="widefat" name="squares-post[title]" value="' . esc_attr( $title ) . '" />';
				echo '</td>';

			// Close our title field.
			echo '</tr>';

			// Our text input field.
			echo '<tr class="' . esc_attr( $class ) . '">';

				// The field label.
				echo '<th scope="row">';
					echo '<label for="squares-post-text">' . esc_html__( 'CTA Text', 'squares2016-cta' ) . '</label>';
				echo '</th>';

				// The input field.
				echo '<td>';
					wp_editor( $text, 'squares_post_text', $editor );
				echo '</td>';

			// Close our title field.
			echo '</tr>';

			// Call our action to include any extra settings.
			do_action( 'squarescta_postmeta_box', $post->ID, $post );

			// Use nonce for verification.
			wp_nonce_field( 'squarescta_post_nonce', 'squarescta_post_nonce', false, true );

		// end table
		echo '</tbody>';
		echo '</table>';
	}

	/**
	 * Update the meta value for the single view checkbox.
	 *
	 * @param  integer $post_id  The post ID being passed on save.
	 *
	 * @return void
	 */
	function save_postmeta( $post_id ) {

		// Bail out if running an autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Bail out if running an ajax request.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		// Bail out if running a cron.
		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		// Make sure the current user has the capability to change
		if ( ! current_user_can( 'publish_posts' ) ) {
			return;
		}

		// Do our nonce comparison.
		if ( empty( $_POST['squarescta_post_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['squarescta_post_nonce'] ), 'squarescta_post_nonce' ) ) {
			return;
		}

		// Make sure we have our actual meta coming through.
		if ( empty( $_POST['squares-post'] ) && empty( $_POST['squares-post-disable'] ) ) {
			return;
		}

		// Check against the post types we've allowed.
		if ( ! in_array( get_post_type( $post_id ), SQS2016_Helper::get_supported_types() ) ) {
			return;
		}

		// First check for the enabled / disabled loading.
		if ( ! empty( $_POST['squares-post-disable'] ) ) {
			update_post_meta( $post_id, '_squares_post_cta_disable', sanitize_key( $_POST['squares-post-disable'] ) );
		} else {
			delete_post_meta( $post_id, '_squares_post_cta_disable' );
		}

		// Set an empty data array for sanitizing prior to saving.
		$data   = array();

		// Look for our posted title.
		if ( ! empty( $_POST['squares-post']['title'] ) ) {
			$data['title']  = sanitize_text_field( trim( $_POST['squares-post']['title'] ) );
		}

		// Look for our posted text.
		if ( ! empty( $_POST['squares-post']['text'] ) ) {
			$data['text']   = wp_kses_post( trim( $_POST['squares-post']['text'] ) );
		}

		// Sanitize the placement radio input.
		$data['place']  = ! empty( $_POST['squares-post']['place'] ) ? sanitize_text_field( $_POST['squares-post']['place'] ) : 'below';

		// And filter our data to allow additional settings to be added later.
		$data   = apply_filters( 'squarescta_postmeta_sanitize', $data );

		// Now store it (or delete it).
		if ( ! empty( $data ) ) {
			update_post_meta( $post_id, '_squares_post_cta', $data );
		} else {
			delete_post_meta( $post_id, '_squares_post_cta' );
		}
	}

	// End the class.
}

// Instantiate our class.
$SQS2016_PostMeta = new SQS2016_PostMeta();
$SQS2016_PostMeta->init();
