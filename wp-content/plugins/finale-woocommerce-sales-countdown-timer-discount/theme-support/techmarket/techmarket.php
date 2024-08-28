<?php
defined( 'ABSPATH' ) || exit;

add_action( 'wp', 'wcct_theme_helper_techmarket', 99 );
if ( ! function_exists( 'wcct_theme_helper_techmarket' ) ) {

	function wcct_theme_helper_techmarket() {
		$wcct_core = WCCT_Core()->appearance;

		// removing wcct action hooks on theme
		remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_title' ), 9 );
		remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_review' ), 11 );
		remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_price' ), 17);
		remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_add_cart' ), 39 );

		add_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_title' ), 7 );
		add_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_review' ), 12 );
		add_action( 'techmarket_single_product_action', array( $wcct_core, 'wcct_position_below_price' ), 32 );
		add_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_add_cart' ), 51 );
	}
}
