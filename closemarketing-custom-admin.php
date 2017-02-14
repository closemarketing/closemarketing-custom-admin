<?php
/**
 * Plugin Name: Closemarketing Custom Admin
 * Plugin URI: https://www.closemarketing.es
 * Description: Enhacements WordPress admin for Closemarketing.
 * Author: closemarketing, davidperez
 * Author URI: https://www.closemarketing.es/
 * Version: 0.1
 * Text Domain: closemarketing-custom-admin
 * Domain Path: /languages
 * License: GNU General Public License version 3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

//Loads translation
load_plugin_textdomain('closemarketing-custom-admin', false, dirname( plugin_basename( __FILE__ ) ). '/languages/');

//* Includes General for Closemarketing
include('includes/one-category.php'); //only allows to select one category_archive_meta
include('includes/wp-admin-default.php'); // Custom Closemarketing Dashboard
include('includes/wp-plugins-recommended.php'); //Recommends plugins
