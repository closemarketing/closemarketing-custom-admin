<?php
/**
 * Block Template ultimasentradas
 *
 * @package    WordPress
 * @author     David Perez <david@closemarketing.es>
 * @copyright  2019 Closemarketing
 * @version    1.0
 */

// Fields data.
if ( empty( $attributes['data'] ) ) {
	return;
}

// Unique HTML ID if available.
$block_id = 'wpblock-ultimasentradas-' . ( $attributes['id'] ?? '' );
if ( ! empty( $attributes['anchor'] ) ) {
	$block_id = $attributes['anchor'];
}

// Custom CSS class name.
$class = 'wpblock-ultimas-entradas ' . ( $attributes['className'] ?? '' );
// Use $post_id and regular functions.
?>
<div id="<?php echo esc_html( $block_id ); ?>" class="ultimas-entradas <?php echo esc_html( $class ); ?>">
<?php
echo '<div class="ultimas-entradas-wrapper">';
$args_query = array(
	'post_type'      => 'post',
	'posts_per_page' => 3,
	'orderby'        => 'date',
);

// The Query.
$the_query = new WP_Query( $args_query );
// The Loop.
if ( $the_query->have_posts() ) {
	$i = 0;
	while ( $the_query->have_posts() ) {
		$the_query->the_post();

		echo '<div class="item-post">';
		echo '<a href="' . esc_url( get_permalink() ) . '">';
		echo '<div class="wrapper-imagen">';
		if ( has_post_thumbnail() ) {
			echo get_the_post_thumbnail( get_the_ID(), 'thumbnail' );
		} else {
			echo '<div class="no-image-blog attachment-ultimas_entradas size-ultimas_entradas"></div>';
		}
		echo '<div class="fecha-post">' . get_the_date( 'd F' ) . '</div>';
		echo '<h3 class="widget-title">' . get_the_title() . '</h3>';
		echo '<p class="widget-excerpt">' . get_the_excerpt() . '</p>';
		echo '<a href="' . get_the_permalink() . '" class="button ver-mas">Ver más</a>';
		echo '</div>';
		echo '</a>';
		echo '</div>';
		wp_reset_postdata();
	}
echo '</div>';
}
?>
</div>
