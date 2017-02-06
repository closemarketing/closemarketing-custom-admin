<?php
/**
 * Plugin Name: Closemarketing Custom Admin
 * Plugin URI: https://www.closemarketing.es
 * Description: Enhacements WordPress admin for Closemarketing.
 * Author: closemarketing, davidperez
 * Author URI: https://www.closemarketing.es/
 * Version: 1.0
 */

/**
 * Localization
 */

load_plugin_textdomain( 'clmk', false,  WPMU_PLUGIN_DIR . '/languages' );

//* Includes General for Closemarketing
include('includes/clean-url-seo.php'); //cleans stop words from slug
include('includes/one-category.php'); //only allows to select one category_archive_meta
include('includes/wp-admin-default.php'); // Personalización Closemarketing Dashboard
include('includes/wp-plugins-recommended.php');
