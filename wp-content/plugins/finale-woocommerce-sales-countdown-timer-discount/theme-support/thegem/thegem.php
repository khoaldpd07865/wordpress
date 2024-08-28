<?php
defined( 'ABSPATH' ) || exit;

add_action( 'wp', 'wcct_theme_helper_thegem', 99 );
if ( ! function_exists( 'wcct_theme_helper_thegem' ) ) {

	function wcct_theme_helper_thegem() {
		$wcct_core = WCCT_Core()->appearance;

		// removing wcct action hooks on theme
		remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_above_title' ), 2 );
		remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_title' ), 9 );
		remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_review' ), 11 );
		remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_price' ), 17 );
		remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_short_desc' ), 21 );
		remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_add_cart' ), 39 );

		add_action( 'thegem_woocommerce_single_product_right', array( $wcct_core, 'wcct_position_above_title' ), 8 );
		add_action( 'thegem_woocommerce_single_product_right', array( $wcct_core, 'wcct_position_below_title' ), 12 );
		add_action( 'thegem_woocommerce_single_product_right', array( $wcct_core, 'wcct_position_below_review' ), 22 );
		add_action( 'thegem_woocommerce_single_product_right', array( $wcct_core, 'wcct_position_below_price' ), 32 );
		add_action( 'thegem_woocommerce_single_product_right', array( $wcct_core, 'wcct_position_below_short_desc' ), 37 );
		add_action( 'thegem_woocommerce_single_product_right', array( $wcct_core, 'wcct_position_below_add_cart' ), 47 );
	}
}
