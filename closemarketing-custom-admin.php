<?php
/**
 * Plugin Name: CLOSE Custom Admin
 * Plugin URI: https://close.marketing
 * Description: Enhacements WordPress admin for CLOSE webs.
 * Author: closemarketing
 * Author URI: https://close.marketing/
 * Version: 1.12.0
 * Text Domain: closemarketing-custom-admin
 * Domain Path: /languages
 * License: GNU General Public License version 3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WordPress
 */

define( 'CLOSEAD_VERSION', '1.12.0' );
define( 'CLOSEAD_PLUGIN', __FILE__ );
define( 'CLOSEAD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CLOSEAD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Loads translation.
load_plugin_textdomain( 'closemarketing-custom-admin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


// Includes Libraries for Closemarketing.
require_once CLOSEAD_PLUGIN_PATH . 'includes/class-cca-wpadmin.php';
require_once CLOSEAD_PLUGIN_PATH . 'includes/class-cca-optimizer.php';

// Plugins recommended.
require_once CLOSEAD_PLUGIN_PATH . 'includes/class-tgm-plugin-activation.php';
require_once CLOSEAD_PLUGIN_PATH . 'includes/wp-plugins-recommended.php';

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require_once CLOSEAD_PLUGIN_PATH . 'includes/woocommerce.php';
}

// Find Genesis Theme Data.
$theme = wp_get_theme( 'genesis' );

// Restrict activation to only when the Genesis Framework is activated.
if ( basename( get_template_directory() ) === 'genesis' ) {
	require_once CLOSEAD_PLUGIN_PATH . 'includes/genesis.php';
}


new CCA_WPAdmin();
new CCA_Optimizer();
