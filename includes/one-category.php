<?php
/*
Plugin name: Una sola categoría
Version: 1.0
Description: Reemplaza la casilla de selección de categorías del editor por botones radio para que solo puedas elegir una categoría por entrada.
*/
function ooc_admin_catcher() {
	if( strstr($_SERVER['REQUEST_URI'], 'wp-admin/post-new.php') 
		|| strstr($_SERVER['REQUEST_URI'], 'wp-admin/post.php') 
		|| strstr($_SERVER['REQUEST_URI'], 'wp-admin/edit.php') ) {
	  ob_start('ooc_one_category_only');
	}
}
add_action( 'init', 'ooc_admin_catcher' );

function ooc_one_category_only($content) {
	return ooc_swap_out_checkboxes($content);
}


function ooc_swap_out_checkboxes($content) {
	$content = str_replace('type="checkbox" name="post_category', 'type="radio" name="post_category', $content);

	foreach (get_all_category_ids() as $i) { 
		$content = str_replace('id="in-popular-category-'.$i.'" type="checkbox"', 'id="in-popular-category-'.$i.'" type="radio"', $content);
	}

	return $content;
}