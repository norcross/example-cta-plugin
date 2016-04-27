<?php
/**
 * Squares 2016 CTA - display functions
 *
 * Contains our front-end display functions.
 *
 * @package Squares 2016 CTA
 */

/**
 * Set up and load our class.
 */
class SQS2016_Display
{

	/**
	 * Load our hooks and filters.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'the_content',                  array( $this, 'display_cta'         )           );
		add_shortcode( 'squarescta',                array( $this, 'shortcode'           )           );
	}

	/**
	 * Our content filter to fetch and display the CTA
	 *
	 * @param  mixed $content  The existing post content.
	 *
	 * @return mixed $content  The (potentially) updated content.
	 */
	public function display_cta( $content ) {

		// Bail if we aren't on a singular item or one we support.
		if ( ! is_singular() || ! in_array( get_post_type(), SQS2016_Helper::get_supported_types() ) ) {
			return str_replace( '[squarescta]', '', $content );
		}

		// Call our global post object.
		global $post;

		// Check for the disable flag and bail right away if it's there.
		if ( false !== $check = SQS2016_Helper::get_single_postmeta( $post->ID, '_squares_post_disable' ) ) {
			return str_replace( '[squarescta]', '', $content );
		}

		// Fetch our placement setup
		$place  = SQS2016_Helper::get_cta_placement( $post->ID );

		// If we set to "manual", then bail.
		if ( 'manual' === $place ) {
			return str_replace( '[squarescta]', '', $content );
		}

		// Go get my box, and bail if we don't have one.
		if ( false === $build = self::build_cta_display( $post->ID, $place ) ) {
			return str_replace( '[squarescta]', '', $content );
		}

		// Return above or below the content, depending on selection.
		return 'above' === $place ? $build . $content : $content . $build;
	}

	/**
	 * The actual shortcode function, which only runs when set to manual.
	 *
	 * @param  array $atts     The shortcode attribute array.
	 * @param  mixed $content  The post content.
	 *
	 * @return mixed $build    The shortcode markup embedded with the content.
	 */
	public function shortcode( $atts, $content = null ) {

		// Bail if we aren't on a singular item or one we support.
		if ( ! is_singular() || ! in_array( get_post_type(), SQS2016_Helper::get_supported_types() ) ) {
			return;
		}

		// Call our global post object.
		global $post;

		// Check for the disable flag and bail right away if it's there.
		if ( false !== $check = SQS2016_Helper::get_single_postmeta( $post->ID, '_squares_post_disable' ) ) {
			return;
		}

		// Fetch our placement setup
		$place  = SQS2016_Helper::get_cta_placement( $post->ID );

		// If we set to "manual", then bail.
		if ( 'manual' !== $place ) {
			return;
		}

		// Return the CTA display build.
		return self::build_cta_display( $post->ID, $place );
	}

	/**
	 * Build out the markup for our call to action.
	 *
	 * @param  integer $post_id  The post ID that may contain custom content.
	 * @param  string  $palce    The selected placement of the CTA. Will be applied to the class.
	 *
	 * @return mixed   $build    The CTA box itself.
	 */
	public static function build_cta_display( $post_id = 0, $place = '' ) {

		// Fetch our postmeta items.
		$title  = SQS2016_Helper::get_single_postmeta( $post_id, '_squares_post_cta', '', 'title' );
		$text   = SQS2016_Helper::get_single_postmeta( $post_id, '_squares_post_cta', '', 'text' );

		// If we had no postmeta title, then pull our global.
		if ( empty( $title ) ) {
			$title  = SQS2016_Helper::get_single_option( 'squares-cta', '', 'title' );
		}

		// If we had no postmeta text, then pull our global.
		if ( empty( $text ) ) {
			$text   = SQS2016_Helper::get_single_option( 'squares-cta', '', 'text' );
		}

		// If we have no text, we should probably bail since there's no CTA to display.
		if ( empty( $text ) ) {
			return;
		}

		// Set a class to use.
		$class  = ! empty( $place ) ? 'squares-cta-box-' . esc_attr( $place ) . ' squares-cta-box' : 'squares-cta-box';

		// Now begin the markup build.
		$build  = '';

		// But a box around it.
		$build .= '<div id="squares-cta-box-' . absint( $post_id ) . '" class="' . esc_html( $class ) . '">';

			// Got a title? Let's show them that special title.
			$build .= ! empty( $title ) ? '<h3 class="squares-cta-box-title">' . esc_attr( $title ) . '</h3>' : '';

			// Since we already checked for text, we don't have to check again.
			$build .= '<div class="squares-cta-box-text">';
			$build .= wpautop( $text );
			$build .= '</div>';

		// Close my box.
		$build .= '</div>';

		// Return the markup.
		return apply_filters( 'squarescta_html_display', $build, $post_id );
	}

	// End the class.
}

// Instantiate our class.
$SQS2016_Display = new SQS2016_Display();
$SQS2016_Display->init();
