<?php
defined( 'ABSPATH' ) || exit;

add_action( 'wp', 'wcct_theme_helper_claue', 99 );
if ( ! function_exists( 'wcct_theme_helper_claue' ) ) {
	function wcct_theme_helper_claue() {
		$wcct_core = WCCT_Core()->appearance;
		$options   = get_post_meta( get_the_ID(), '_custom_wc_options', true );

		// Get product single style
		$style = ( is_array( $options ) && $options['wc-single-style'] ) ? $options['wc-single-style'] : ( cs_get_option( 'wc-single-style' ) ? cs_get_option( 'wc-single-style' ) : '1' );
		if ( $style != '3' ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			remove_action( 'woocommerce_single_product_summary', 'jas_claue_wc_before_price', 5 );
			remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_title' ), 9 );
			remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_review' ), 11 );
			remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_price' ), 17 );

			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 18 );
			add_action( 'woocommerce_single_product_summary', 'jas_claue_wc_before_price', 7 );
			add_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_title' ), 5 );
			add_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_review' ), 15 );
			add_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_price' ), 18);
			add_action( 'woocommerce_after_single_product_summary', function () {
				echo '<div class="wcct_clear wcct_clear_20"></div>';
			}, 20.8 );
		} else {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
			remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_review' ), 11 );
			remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_above_title' ), 2);
			remove_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_add_cart' ), 39 );

			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 7 );
			add_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_below_review' ), 5 );
			add_action( 'woocommerce_single_product_summary', array( $wcct_core, 'wcct_position_above_title' ), 6 );
			add_action( 'woocommerce_after_add_to_cart_button', array( $wcct_core, 'wcct_position_below_add_cart' ), 99 );
			add_action( 'woocommerce_after_add_to_cart_button', function () {
				echo '<div class="wcct_clear wcct_clear_20"></div>';
			}, 98 );
			add_action( 'woocommerce_after_single_product_summary', function () {
				echo '<div class="wcct_clear wcct_clear_20"></div>';
			}, 20.8 );
		}
	}
}
