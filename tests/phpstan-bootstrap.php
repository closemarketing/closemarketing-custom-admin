<?php
/**
 * PHPStan Bootstrap File
 *
 * This file defines constants and functions that PHPStan needs to understand
 * but are not available during static analysis.
 *
 * @package WordPress
 * @author  Closetechnology
 */

defined( 'ABSPATH' ) || exit;

// Define plugin constants that are used throughout the codebase.
if ( ! defined( 'CLOSEAD_PLUGIN_URL' ) ) {
	define( 'CLOSEAD_PLUGIN_URL', 'http://localhost/wp-content/plugins/closemarketing-custom-admin/' );
}

if ( ! defined( 'CLOSEAD_VERSION' ) ) {
	define( 'CLOSEAD_VERSION', '1.12.1' );
}

if ( ! defined( 'CLOSEAD_PLUGIN' ) ) {
	define( 'CLOSEAD_PLUGIN', __FILE__ );
}

if ( ! defined( 'CLOSEAD_PLUGIN_PATH' ) ) {
	define( 'CLOSEAD_PLUGIN_PATH', '/path/to/wordpress/wp-content/plugins/closemarketing-custom-admin/' );
}

// Define WordPress constants that might be missing.
if ( ! defined( 'DOING_AJAX' ) ) {
	define( 'DOING_AJAX', false );
}

if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', '/path/to/wordpress/' );
}

// Mock WordPress functions that PHPStan can't find.
if ( ! function_exists( 'wp_doing_ajax' ) ) {
	/**
	 * Mock wp_doing_ajax function.
	 *
	 * @return bool
	 */
	function wp_doing_ajax() {
		return defined( 'DOING_AJAX' ) && DOING_AJAX;
	}
}

// Mock WP_CLI class.
if ( ! class_exists( 'WP_CLI' ) ) {
	/**
	 * Mock WP_CLI class.
	 */
	class WP_CLI {
		/**
		 * Print line.
		 *
		 * @param string $message Message to print.
		 */
		public static function line( $message ) {
			echo $message . "\n";
		}

		/**
		 * Add command.
		 *
		 * @param string $command Command name.
		 * @param mixed  $class   Command class.
		 * @return bool
		 */
		public static function add_command( $command, $class ) {
			return true;
		}
	}
}
