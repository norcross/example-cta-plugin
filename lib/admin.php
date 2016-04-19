<?php
/**
 * Squares 2016 CTA - admin functions
 *
 * Contains more generic admin related functions.
 *
 * @package Squares 2016 CTA
 */

/**
 * Set up and load our class.
 */
class SQS2016_Admin
{

	/**
	 * Load our hooks and filters.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts',        array( $this, 'load_stylesheet'     )           );
		add_action( 'admin_enqueue_scripts',        array( $this, 'load_javascript'     )           );
	}

	/**
	 * Call our stylesheet for laying out the settings page.
	 *
	 * @param  string $hook  The admin page hook being called.
	 *
	 * @return void
	 */
	public function load_stylesheet( $hook ) {

		// Only load on our reading page or a single post editor.
		if ( ! in_array( $hook, array( 'options-reading.php', 'post.php' ) ) ) {
			return;
		}

		// Set a suffix for loading the minified or normal.
		$sx = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.css' : '.min.css';
		$vs = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : SQS2016_VER;

		// Load the CSS file itself.
		wp_enqueue_style( 'squares-cta', plugins_url( '/css/squarescta.admin' . $sx, __FILE__ ), array(), $vs, 'all' );
	}

	/**
	 * Call our JS file for the single post CTAs.
	 *
	 * @param  string $hook  The admin page hook being called.
	 *
	 * @return void
	 */
	public function load_javascript( $hook ) {

		// Only load on a single post editor.
		if ( 'post.php' !== $hook ) {
			return;
		}

		// Set a suffix for loading the minified or normal.
		$sx = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.js' : '.min.js';
		$vs = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : SQS2016_VER;

		// Load the JS file itself.
		wp_enqueue_script( 'squares-cta', plugins_url( '/js/squarescta.admin' . $sx, __FILE__ ) , array( 'jquery' ), $vs, true );
	}

	// End the class.
}

// Instantiate our class.
$SQS2016_Admin = new SQS2016_Admin();
$SQS2016_Admin->init();
