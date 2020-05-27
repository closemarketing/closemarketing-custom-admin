<?php
/**
 * Bloque Categorias
 *
 * Muestra las categorias
 *
 * @package    WordPress
 * @author     David Perez <david@closemarketing.es>
 * @copyright  2019 Closemarketing
 * @version    1.0
 */

add_filter( 'rwmb_meta_boxes', 'cmk_register_block_mostrar_categorias' );
/**
 * Registers the block Mostrar productos nuevos
 *
 * @param  array $meta_boxes Array of registered metaboxes.
 * @return array $meta_boxes
 */
function cmk_register_block_mostrar_categorias( $meta_boxes ) {
	$meta_boxes[] = [
		'title'           => 'Mostrar categorias',
		'id'              => 'mostrar_categorias',
		'description'     => 'Muestra las categorias',
		'type'            => 'block',
		'icon'            => 'awards',
		'category'        => 'layout', // common, formatting, widgets, embed
		'context'         => 'side',
		'keywords'        => [
			'closemarketing',
			'categorias',
		],
		'render_template' => plugin_dir_path( __FILE__ ) . 'template.php',
		'enqueue_style'   => plugin_dir_url( __FILE__ ) . 'style.css',
		'supports'        => array(
			'align'           => [
				'wide',
				'full',
			],
			'customClassName' => true,
			'anchor'          => false,
		),
	];
	return $meta_boxes;
}


add_filter( 'rwmb_meta_boxes', 'cmk_register_block_mostrarcategorias' );
/**
 * Registers the block Mostrar categorias
 *
 * @param  array $meta_boxes Array of registered metaboxes.
 * @return array $meta_boxes
 */
function cmk_register_block_mostrarcategorias( $meta_boxes ) {
	$meta_boxes[] = [
		'title'           => 'Mostrar categorias',
		'id'              => 'mostrarcategorias',
		'description'     => 'Muestra el bloque',
		'type'            => 'block',
		'icon'            => 'dashicons-grid-view', // More: https://developer.wordpress.org/resource/ .
		'category'        => 'common', // Options: common, formatting, widgets, embed .
		'context'         => 'side',
		'keywords'        => [
			'closemarketing',
			'categorias',
		],
		'render_template' => plugin_dir_path( __FILE__ ) . 'template.php',
		'enqueue_style'   => plugin_dir_url( __FILE__ ) . 'style.css',
		'supports'        => array(
			'align'           => [
				'wide',
				'full',
			],
			'customClassName' => true,
			'anchor'          => false,
		),

		// Block fields.
		'fields'          => [
			// TEXT.
			array(
				'name'              => 'Título',
				'label_description' => 'Título del bloque',
				'id'                => "title",
				'desc'              => '',
				'type'              => 'text',
				'std'               => '',
				'clone'             => false,
			),
		],
	];
	return $meta_boxes;
}