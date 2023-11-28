<?php
/**
 * Optimizer functions
 *
 * Has functions optimize the WordPress
 *
 * @author   closemarketing
 * @category Functions
 * @package  Admin
 */

/**
 * Class for admin fields
 */
class CCA_Optimizer {

	/**
	 * Construct of Class
	 */
	public function __construct() {
		// Remove version WordPress.
		remove_action( 'wp_head', 'wp_generator' );

		// Remove WordPress Feed links.
		remove_action( 'wp_head', 'feed_links', 2 ); // removes feed links.
		remove_action( 'wp_head', 'feed_links_extra', 3 ); // removes comments feed.

		// Remove Emoji WordPress.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );

		if ( function_exists( 'deregister_dashicons' ) ) {
			// Deregister los dashicons si no se muestra la barra de admin.
			add_action( 'wp_print_styles', array( $this, 'deregister_dashicons' ), 100 );
		}
	}

	/**
	 * Deregister Dashicons in public
	 *
	 * @return void
	 */
	public function deregister_dashicons() {
		if ( ! is_admin_bar_showing() ) {
			wp_deregister_style( 'dashicons' );
		}
	}

}
