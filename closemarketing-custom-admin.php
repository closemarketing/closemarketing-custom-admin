<?php
/**
 * Plugin Name: Closemarketing Custom Admin
 * Plugin URI: https://www.closemarketing.es
 * Description: Enhacements WordPress admin for Closemarketing.
 * Author: davidperez
 * Author URI: https://www.closemarketing.es/
 * Version: 0.5.1
 * Text Domain: closemarketing-custom-admin
 * Domain Path: /languages
 * License: GNU General Public License version 3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

//Loads translation
load_plugin_textdomain('closemarketing-custom-admin', false, dirname(plugin_basename(__FILE__)) . '/languages/');

define('CMK_RESIZE_WIDTH', 1920); //1000 px wide
define('CMK_RESIZE_HEIGHT', 1920); //900 px high

define('CMK_RESIZE_QUALITY', 70); //0-100, 100 being high quality
define('CMK_MAX_UPLOAD_SIZE', '10971520b'); //size in bytes

//* Includes Libraries for Closemarketing
include_once dirname(__FILE__) . '/includes/one-category.php';

include_once dirname(__FILE__) . '/includes/clean-url-seo.php';

include_once dirname(__FILE__) . '/includes/resize-before-upload.php';

include_once dirname(__FILE__) . '/includes/wp-admin-default.php';

//* Plugins recommended
include_once dirname(__FILE__) . '/includes/class-tgm-plugin-activation.php';

include_once dirname(__FILE__) . '/includes/wp-plugins-recommended.php';
