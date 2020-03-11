<?php
/**
 * Plugin Name: Closemarketing Custom Admin
 * Plugin URI: https://www.closemarketing.es
 * Description: Enhacements WordPress admin for Closemarketing.
 * Author: closemarketing
 * Author URI: https://www.closemarketing.es/
 * Version: 1.6
 * Text Domain: closemarketing-custom-admin
 * Domain Path: /languages
 * License: GNU General Public License version 3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WordPress
 */

// * Loads translation
load_plugin_textdomain( 'closemarketing-custom-admin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


// * Includes Libraries for Closemarketing
require_once dirname( __FILE__ ) . '/includes/class-cca-wpadmin.php';

// * Plugins recommended
require_once dirname( __FILE__ ) . '/includes/class-tgm-plugin-activation.php';

require_once dirname( __FILE__ ) . '/includes/wp-plugins-recommended.php';

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require_once dirname( __FILE__ ) . '/includes/woocommerce.php';
}

// Find Genesis Theme Data.
$theme = wp_get_theme( 'genesis' );

// Restrict activation to only when the Genesis Framework is activated.
if ( basename( get_template_directory() ) === 'genesis' ) {
	require_once dirname( __FILE__ ) . '/includes/genesis.php';
}

// * Add Gravityforms CSS.
if ( class_exists( 'GFCommon' ) ) {
	add_action( 'wp_print_styles', 'cca_remove_gravityforms_style' );
	/**
	 * Dequeues CSS original from Gravity Forms
	 *
	 * @return void
	 */
	function cca_remove_gravityforms_style() {
		wp_dequeue_style( 'gforms_css' );
		wp_enqueue_style(
			'gforms_css',
			plugin_dir_url( __FILE__ ) . 'includes/css/gravityforms.min.css',
			null,
			GFCommon::$version
		);
	}
}

