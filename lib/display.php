<?php
/**
 * Example CTA Plugin - display functions
 *
 * Contains our front-end display functions.
 *
 * @package Example CTA Plugin
 */

/**
 * Set up and load our class.
 */
class EXCTA_Display
{

	/**
	 * Load our hooks and filters.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'the_content',                  array( $this, 'display_cta'         )           );
		add_shortcode( 'example_cta',               array( $this, 'shortcode'           )           );
	}

	/**
	 * Our content filter to fetch and display the CTA.
	 *
	 * @param  mixed $content  The existing post content.
	 *
	 * @return mixed $content  The (potentially) updated content.
	 */
	public function display_cta( $content ) {

		// Bail if we aren't on a singular item or one we support.
		if ( ! is_singular() || ! in_array( get_post_type(), EXCTA_Helper::get_supported_types() ) ) {
			return str_replace( '[example_cta]', '', $content );
		}

		// Call our global post object.
		global $post;

		// Check for the disable flag and bail right away if it's there.
		if ( false !== $check = EXCTA_Helper::get_single_postmeta( $post->ID, '_example_cta_post_disable' ) ) {
			return str_replace( '[example_cta]', '', $content );
		}

		// Fetch our placement setup.
		$place  = EXCTA_Helper::get_cta_placement( $post->ID );

		// If we set to "manual", then bail.
		if ( 'manual' === $place ) {
			return $content;
		}

		// Go get my box, and bail if we don't have one.
		if ( false === $build = self::build_cta_display( $post->ID, $place ) ) {
			return str_replace( '[example_cta]', '', $content );
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
		if ( ! is_singular() || ! in_array( get_post_type(), EXCTA_Helper::get_supported_types() ) ) {
			return;
		}

		// Call our global post object.
		global $post;

		// Check for the disable flag and bail right away if it's there.
		if ( false !== $check = EXCTA_Helper::get_single_postmeta( $post->ID, '_example_cta_post_disable' ) ) {
			return;
		}

		// Fetch our placement setup.
		$place  = EXCTA_Helper::get_cta_placement( $post->ID );

		// If we did NOT set to "manual", then bail.
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
		$title  = EXCTA_Helper::get_single_postmeta( $post_id, '_example_cta_postmeta', '', 'title' );
		$text   = EXCTA_Helper::get_single_postmeta( $post_id, '_example_cta_postmeta', '', 'text' );

		// If we had no postmeta title, then pull our global.
		if ( empty( $title ) ) {
			$title  = EXCTA_Helper::get_single_option( 'example-cta', '', 'title' );
		}

		// If we had no postmeta text, then pull our global.
		if ( empty( $text ) ) {
			$text   = EXCTA_Helper::get_single_option( 'example-cta', '', 'text' );
		}

		// If we have no text, we should probably bail since there's no CTA to display.
		if ( empty( $text ) ) {
			return false;
		}

		// Set a class to use.
		$class  = ! empty( $place ) ? 'example-cta-box-' . esc_attr( $place ) . ' example-cta-box' : 'example-cta-box';

		// Now begin the markup build.
		$build  = '';

		// But a box around it.
		$build .= '<div id="example-cta-box-' . absint( $post_id ) . '" class="' . esc_html( $class ) . '">';

			// Got a title? Let's show them that special title.
			$build .= ! empty( $title ) ? '<h3 class="example-cta-box-title">' . esc_attr( $title ) . '</h3>' : '';

			// Since we already checked for text, we don't have to check again.
			$build .= '<div class="example-cta-box-text">';
			$build .= wpautop( $text );
			$build .= '</div>';

		// Close my box.
		$build .= '</div>';

		// Return the markup.
		return apply_filters( 'example_cta_html_display', $build, $post_id );
	}

	// End the class.
}

// Instantiate our class.
$EXCTA_Display = new EXCTA_Display();
$EXCTA_Display->init();
