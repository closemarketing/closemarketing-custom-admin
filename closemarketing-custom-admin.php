<?php
/**
 * Plugin Name: Closemarketing Custom Admin
 * Plugin URI: https://www.closemarketing.es
 * Description: Enhacements WordPress admin for Closemarketing.
 * Author: davidperez
 * Author URI: https://www.closemarketing.es/
 * Version: 1.1
 * Text Domain: closemarketing-custom-admin
 * Domain Path: /languages
 * License: GNU General Public License version 3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WordPress
 */

// * Loads translation
load_plugin_textdomain( 'closemarketing-custom-admin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

define( 'CMK_RESIZE_WIDTH', 1920 ); // 1000 px wide
define( 'CMK_RESIZE_HEIGHT', 1920 ); // 900 px high

define( 'CMK_RESIZE_QUALITY', 70 );
define( 'CMK_MAX_UPLOAD_SIZE', '10971520b' );

// * Includes Libraries for Closemarketing
require_once dirname( __FILE__ ) . '/includes/one-category.php';

require_once dirname( __FILE__ ) . '/includes/clean-url-seo.php';

require_once dirname( __FILE__ ) . '/includes/resize-before-upload.php';

require_once dirname( __FILE__ ) . '/includes/wp-admin-default.php';

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
