<?php
/**
 * Genesis Closemarketing.
 *
 * This file adds the custom Enhacements by Closemarketing.
 *
 * @package Genesis Closemarketing
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    https://www.closemarketing.es/
 */


add_action( 'admin_menu', 'cmk_remove_genesis_page_scripts_box', 80 );
/**
 * Removes scripts metabox
 *
 * @return void
 */
function cmk_remove_genesis_page_scripts_box() {
	foreach ( get_post_types( '', 'names' ) as $post_type ) {
		remove_meta_box( 'genesis_inpost_scripts_box', $post_type, 'normal' ); 
	}
}

/**
 * Remove Genesis child theme style sheet
 *
 * @uses  genesis_meta  <genesis/lib/css/load-styles.php>
 */
remove_action( 'genesis_meta', 'genesis_load_stylesheet' );
/**
 * Enqueue Genesis child theme style sheet at higher priority
 *
 * @uses wp_enqueue_scripts <http://codex.wordpress.org/Function_Reference/wp_enqueue_style>
 */
add_action( 'wp_enqueue_scripts', 'genesis_enqueue_main_stylesheet', 15 );

// * Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 5 );

if ( function_exists( 'cmk_secondary_menu_arg' ) ) {
	// * Reduce the secondary navigation menu to one level depth
	add_filter( 'wp_nav_menu_args', 'cmk_secondary_menu_args' );
	function cmk_secondary_menu_args( $args ) {

		if ( 'secondary' != $args['theme_location'] ) {
			return $args;
		}

		$args['depth'] = 1;

		return $args;

	}
}

if ( function_exists( 'cmk_remove_scripts_meta_boxes' ) ) {
	add_action( 'init', 'cmk_remove_scripts_meta_boxes' );
	/**
	 * Remove the Genesis 'Scripts' meta box for posts and/or pages.
	 *
	 * @since 2.0.12
	 */
	function cmk_remove_scripts_meta_boxes() {
		remove_post_type_support( 'post', 'genesis-scripts' );
		remove_post_type_support( 'page', 'genesis-scripts' );
	}
}

if ( function_exists( 'cmk_show_excerpts' ) ) {
	// * Show Excerpts regardless of Theme Settings
	add_filter( 'genesis_pre_get_option_content_archive', 'cmk_show_excerpts' );
	function cmk_show_excerpts() {
		return 'excerpts';
	}
}

if ( function_exists( 'sdt_remove_ver_css_js' ) ) {
	// Remove WP Version From Styles.
	add_filter( 'style_loader_src', 'sdt_remove_ver_css_js', 9999 );

	// Remove WP Version From Scripts.
	add_filter( 'script_loader_src', 'sdt_remove_ver_css_js', 9999 );

	// Function to remove version numbers.
	function sdt_remove_ver_css_js( $src ) {
		if ( strpos( $src, 'ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;
	}
}

// * Remove the edit link
add_filter( 'genesis_edit_post_link', '__return_false' );

if ( function_exists( 'cmk_remove_default_images' ) ) {
	// * deregister medium large file
	add_filter( 'intermediate_image_sizes_advanced', 'cmk_remove_default_images' );
	// Remove default image sizes here.
	function cmk_remove_default_images( $sizes ) {
		unset( $sizes['medium_large'] ); // 768px
		return $sizes;
	}
}

if ( function_exists( 'cmk_term_excerpt' ) ) {
	add_action( 'genesis_archive_title_descriptions', 'cmk_term_excerpt' );
	/**
	 * Adds term description to archives pages
	 *
	 * @return void
	 */
	function cmk_term_excerpt() {
		echo '<div class="term-excerpt">';
		echo term_description();
		echo '</div>';
	}
}