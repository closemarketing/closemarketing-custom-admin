<?php
/* # WooCommerce
---------------------------------------------------------------------------------------------------- */

add_action( 'after_setup_theme', 'cmk_theme_setup' );
/**
 * Adds support to lightbox gallery from Woocommerce
 *
 * @return void
 */
function cmk_theme_setup() {
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

add_filter( 'woocommerce_package_rates', 'cmk_shipping_when_free_is_available', 100 );
/**
 * Hide shipping rates when free shipping is available.
 * Updated to support WooCommerce 2.6 Shipping Zones.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
function cmk_shipping_when_free_is_available( $rates ) {
	$free = array();
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}
	return ! empty( $free ) ? $free : $rates;
}

add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );
/**
 * Optimize WooCommerce Scripts
 * Remove WooCommerce Generator tag, styles, and scripts from non WooCommerce pages.
 *
 * @return void
 */
function child_manage_woocommerce_styles() {
	// remove generator meta tag.
	remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

	// first check that woo exists to prevent fatal errors.
	if ( function_exists( 'is_woocommerce' ) ) {
		// dequeue scripts and styles.
		if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
			wp_dequeue_style( 'woocommerce_frontend_styles' );
			wp_dequeue_style( 'woocommerce_fancybox_styles' );
			wp_dequeue_style( 'woocommerce_chosen_styles' );
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_dequeue_script( 'wc_price_slider' );
			wp_dequeue_script( 'wc-single-product' );
			wp_dequeue_script( 'wc-add-to-cart' );
			wp_dequeue_script( 'wc-cart-fragments' );
			wp_dequeue_script( 'wc-checkout' );
			wp_dequeue_script( 'wc-add-to-cart-variation' );
			wp_dequeue_script( 'wc-single-product' );
			wp_dequeue_script( 'wc-cart' );
			wp_dequeue_script( 'wc-chosen' );
			wp_dequeue_script( 'woocommerce' );
			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );
			wp_dequeue_script( 'jquery-blockui' );
			wp_dequeue_script( 'jquery-placeholder' );
			wp_dequeue_script( 'fancybox' );
			wp_dequeue_script( 'jqueryui' );
		}
	}

}
