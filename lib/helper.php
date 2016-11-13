<?php
/**
 * Example CTA Plugin - helper functions
 *
 * Contains various helper related functions.
 *
 * @package Example CTA Plugin
 */

/**
 * Set up and load our class.
 */
class EXCTA_Helper
{

	/**
	 * Fetch an option from the database with a default fallback.
	 *
	 * @param  string $key      The option key.
	 * @param  string $default  A default value.
	 * @param  string $serial   If we have a serialized data, look for one piece.
	 *
	 * @return mixed  $option   Either the found info, or false.
	 */
	public static function get_single_option( $key = 'example-cta', $default = '', $serial = '' ) {

		// Bail without a key.
		if ( empty( $key ) ) {
			return false;
		}

		// Fetch the option.
		$option = get_option( $key );

		// Bail if no option is found, and no default was set.
		if ( empty( $option ) && empty( $default ) ) {
			return false;
		}

		// Handle the serial.
		if ( ! empty( $serial ) ) {
			return ! empty( $option[ $serial ] ) ? $option[ $serial ] : $default;
		}

		// Return whichever one we have.
		return ! empty( $option ) ? $option : $default;
	}

	/**
	 * Fetch a postmeta data from the database with a default fallback.
	 *
	 * @param  integer $post_id  The post ID to retrieve it from.
	 * @param  string  $key      The postmeta key.
	 * @param  string  $default  A default value.
	 * @param  string  $serial   If we have a serialized data, look for one piece.
	 *
	 * @return mixed   $data     Either the found info, or false.
	 */
	public static function get_single_postmeta( $post_id = 0, $key = '', $default = '', $serial = '' ) {

		// Bail without a post ID or a key.
		if ( empty( $post_id ) || empty( $key ) ) {
			return false;
		}

		// Fetch the option.
		$data   = get_post_meta( $post_id, $key, true );

		// Bail if no data is found, and no default was set.
		if ( empty( $data ) && empty( $default ) ) {
			return false;
		}

		// Handle the serial.
		if ( ! empty( $serial ) ) {
			return ! empty( $data[ $serial ] ) ? $data[ $serial ] : $default;
		}

		// Return whichever one we have.
		return ! empty( $data ) ? $data : $default;
	}

	/**
	 * Get the selected placement for the CTA.
	 *
	 * @param  integer $post_id  The potential post ID being viewed.
	 *
	 * @return string  $place    The placement option.
	 */
	public static function get_cta_placement( $post_id = 0 ) {

		// Fetch our placement setup
		$place  = self::get_single_postmeta( $post_id, '_example_cta_postmeta', '', 'place' );

		// If we had no postmeta placement, then pull our global and return it.
		return ! empty( $place ) ? $place : self::get_single_option( 'example-cta', 'below', 'place' );
	}

	/**
	 * Preset our allowed post types for content modification with filter.
	 *
	 * @return array $types  The post types we are using.
	 */
	public static function get_supported_types() {
		return apply_filters( 'example_cta_post_types', array( 'post' ) );
	}

	/**
	 * Set and return our args for WP_Editor, filtered.
	 *
	 * @param  string $name  The unique textarea name.
	 *
	 * @return array  $args  The WP_Editor args.
	 */
	public static function get_wp_editor_args( $name = '' ) {

		// Set our settings for the WP_Editor call.
		$args   = array(
			'textarea_rows' => 6,
			'textarea_name' => esc_attr( $name ),
			'quicktags'     => array( 'buttons' => 'strong,em,ul,ol,li,link,img' ),
		);

		// Return our editor args, with a filter.
		return apply_filters( 'example_cta_editor_args', $args, $name );
	}

	// End the class.
}

// Instantiate our class.
$EXCTA_Helper = new EXCTA_Helper();
