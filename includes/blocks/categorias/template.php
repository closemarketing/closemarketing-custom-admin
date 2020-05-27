<?php
/**
 * Block Template Categorias
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
$block_id = 'wpblock-categorias-' . ( $attributes['id'] ?? '' );
if ( ! empty( $attributes['anchor'] ) ) {
	$block_id = $attributes['anchor'];
}

// Custom CSS class name.
$class = 'wpblock-categorias ' . ( $attributes['className'] ?? '' );
// Use $post_id and regular functions.
?>
<div id="<?php echo esc_html( $block_id ); ?>" class="categorias <?php echo esc_html( $class ); ?>">
<?php

$terms = get_terms(
	array(
		'taxonomy' => 'product_cat',
		'orderby'  => 'menu_order',
		'number'   => 8,
		'parent'   => 0,
	)
);
echo '<div class="grid-categorias">';
if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
	foreach ( $terms as $trm ) {
		echo '<div class="item">';
		$thumbnail_id = get_term_meta( $trm->term_id, 'thumbnail_id', true );
		$image        = wp_get_attachment_image_src( $thumbnail_id, 'categorias-inicio' );
		if ( $image ) {
			if( wp_is_mobile() ) {
				echo '<a class="boxes-mob" href="' . get_term_link( $trm ) . '">';
			} else {
				echo '<a class="boxes" href="' . get_term_link( $trm ) . '">';
			}
			echo '<img src="' . $image[0] . '" class="da-image"/>';
			echo '<div class="overlay">';
			echo '<h3>' . $trm->name . '</h3>';
			echo '</div>';
			echo '</a>';
		} else {
			echo '<h3>' . $trm->name . '</h3>';
		}			
			echo '</div>';
	}
}

echo '</div>';
?>

<script>

//Detect Closest Edge
function closestEdge(x,y,w,h) {
	var topEdgeDist = distMetric(x,y,w/2,0);
	var bottomEdgeDist = distMetric(x,y,w/2,h);
	var leftEdgeDist = distMetric(x,y,0,h/2);
	var rightEdgeDist = distMetric(x,y,w,h/2);
	var min = Math.min(topEdgeDist,bottomEdgeDist,leftEdgeDist,rightEdgeDist);
	switch (min) {
		case leftEdgeDist:
			return "left";
		case rightEdgeDist:
			return "right";
		case topEdgeDist:
			return "top";
		case bottomEdgeDist:
			return "bottom";
	}
}

//Distance Formula
function distMetric(x,y,x2,y2) {
	var xDiff = x - x2;
	var yDiff = y - y2;
	return (xDiff * xDiff) + (yDiff * yDiff);
}


var boxes = document.querySelectorAll(".boxes");

for(var i = 0; i < boxes.length; i++){

	boxes[i].onmouseenter = function(e){
		var x = e.pageX - this.offsetLeft;
		var y = e.pageY - this.offsetTop;
		var edge = closestEdge(x,y,this.clientWidth, this.clientHeight);
		var overlay = this.childNodes[1];
		var image = this.childNodes[0];

		switch(edge){
			case "left":
				//tween overlay from the left
				overlay.style.top = "0%";
				overlay.style.left = "-100%";
				TweenMax.to(overlay, .5, {left: '0%'});
				TweenMax.to(image, .5, {scale: 1.2});
				break;
			case "right":
				overlay.style.top = "0%";
				overlay.style.left = "100%";
				//tween overlay from the right
				TweenMax.to(overlay, .5, {left: '0%'});
				TweenMax.to(image, .5, {scale: 1.2});
				break;
			case "top":
				overlay.style.top = "-100%";
				overlay.style.left = "0%";
				//tween overlay from the right
				TweenMax.to(overlay, .5, {top: '0%'});
				TweenMax.to(image, .5, {scale: 1.2});
				break;
			case "bottom":
				overlay.style.top = "100%";
				overlay.style.left = "0%";
				//tween overlay from the right
				TweenMax.to(overlay, .5, {top: '0%'});
				TweenMax.to(image, .5, {scale: 1.2});
				break;
		}
	};

   
	boxes[i].onmouseleave = function(e){
		var x = e.pageX - this.offsetLeft;
		var y = e.pageY - this.offsetTop;
		var edge = closestEdge(x,y,this.clientWidth, this.clientHeight);
		var overlay = this.childNodes[1];
		var image = this.childNodes[0];

		switch(edge){
			case "left":
				TweenMax.to(overlay, .5, {left: '-100%'});
				TweenMax.to(image, .5, {scale: 1.0});
				break;
			case "right":
				TweenMax.to(overlay, .5, {left: '100%'});
				TweenMax.to(image, .5, {scale: 1.0});
				break;
			case "top":
				TweenMax.to(overlay, .5, {top: '-100%'});
				TweenMax.to(image, .5, {scale: 1.0});
				break;
			case "bottom":
				TweenMax.to(overlay, .5, {top: '100%'});
				TweenMax.to(image, .5, {scale: 1.0});
				break;
		}
	};
}
</script>
</div>
