<?php
/**
 * One Category in posts.
 *
 * Forces checkbox in entries that have only one category.
 *
 * @link URL
 *
 * @package WordPress
 * @subpackage Component
 * @since Version
 */
function cmk_admin_catcher() {
	if( strstr($_SERVER['REQUEST_URI'], 'wp-admin/post-new.php') 
		|| strstr($_SERVER['REQUEST_URI'], 'wp-admin/post.php') 
		|| strstr($_SERVER['REQUEST_URI'], 'wp-admin/edit.php') ) {
	  ob_start('cmk_one_category_only');
	}
}
add_action( 'init', 'cmk_admin_catcher' );

function cmk_one_category_only($content) {
	return cmk_swap_out_checkboxes($content);
}


function cmk_swap_out_checkboxes($content) {
	$content = str_replace('type="checkbox" name="post_category', 'type="radio" name="post_category', $content);

	foreach (get_all_category_ids() as $i) { 
		$content = str_replace('id="in-popular-category-'.$i.'" type="checkbox"', 'id="in-popular-category-'.$i.'" type="radio"', $content);
	}

	return $content;
}