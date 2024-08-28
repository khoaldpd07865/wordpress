<?php
/**
 * Plugin generic functions file
 *
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to unique number value
 * 
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */
function wpcdt_get_unique() {
	static $unique = 0;
	$unique++;

	// For Elementor, Beaver Builder & VC Page Builder
	if( is_admin() && (defined( 'DOING_AJAX' ) && DOING_AJAX) ) {
		$unique = current_time('timestamp') . '-' . rand();
	}

	return $unique;
}

/**
 * Escape Tags & Slashes. Handles escapping the slashes and tags
 *
 * @package  Countdown Timer Ultimate
 * @since 1.0.0
 */
function wpcdt_esc_attr( $data ){
	return esc_attr( stripslashes( $data ) );
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.0
 */
function wpcdt_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'wpcdt_clean', $var );
	} else {
		$data = is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		return wp_unslash( $data );
	}
}

/**
 * Sanitize number value and return fallback value if it is blank
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.0.0
 */
function wpcdt_clean_number( $var, $fallback = null, $type = 'int' ) {

	if ( $type == 'number' ) {
		$data = intval( $var );
	} else if ( $type == 'abs' ) {
		$data = abs( $var );
	} else {
		$data = absint( $var );
	}

	return ( empty($data) && isset( $fallback ) ) ? $fallback : $data;
}

/**
 * Sanitize color value and return fallback value if it is blank
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.0.0
 */
function wpcdt_clean_color( $color, $fallback = null ) {

	if ( false === strpos( $color, 'rgba' ) ) {
		
		$data = sanitize_hex_color( $color );

	} else {

		$red	= 0;
		$green	= 0;
		$blue	= 0;
		$alpha	= 0.5;

		// By now we know the string is formatted as an rgba color so we need to further sanitize it.
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		$data = 'rgba('.$red.','.$green.','.$blue.','.$alpha.')';
	}

	return ( empty( $data ) && $fallback ) ? $fallback : $data;
}

/**
 * Function to add array after specific key
 * 
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */
function wpcdt_add_array( &$array, $value, $index, $from_last = false ) {

	if( is_array( $array ) && is_array( $value ) ) {

		if( $from_last ) {
			$total_count	= count( $array );
			$index			= ( ! empty( $total_count ) && ( $total_count > $index ) ) ? ( $total_count - $index ): $index;
		}

		$split_arr	= array_splice( $array, max( 0, $index ) );
		$array		= array_merge( $array, $value, $split_arr );
	}

	return $array;
}

/**
 * Function to generate timer style
 * 
 * @since 1.4
 */
function wpcdt_generate_style( $post_id = 0, $design_style = '', $design_data = array() ) {

	// Taking some variable
	$style			= '';
	$timer_width 	= ! empty( $design_data['timer_width'] ) ? $design_data['timer_width'] : '';

	if( $timer_width ) {
		$style = ".wpcdt-timer-{$post_id} .wpcdt-clock{max-width: {$timer_width}px;}";
	}

	return apply_filters('wpcdt_generate_timer_style', $style, $post_id, $design_style, $design_data );
}

/**
* Function to add array after specific key
* 
* @package Countdown Timer Ultimate
* @since 1.5
*/
function wpcdt_designs() {

	$design_arr = array(
			'circle'    => __( 'Circle Style 1', 'countdown-timer-ultimate' ),
			'design-3'  => __( 'Circle Style 2', 'countdown-timer-ultimate' ),
			'design-1'  => __( 'Circle Style 3', 'countdown-timer-ultimate' ),
			'design-6'  => __( 'Simple Clock 1', 'countdown-timer-ultimate' ),
			'design-7'  => __( 'Simple Clock 2', 'countdown-timer-ultimate' ),
			'design-12' => __( 'Simple Clock 3', 'countdown-timer-ultimate' ),
			'design-5'  => __( 'Simple Clock 4', 'countdown-timer-ultimate' ),
			'design-8'  => __( 'Horizontal Flip', 'countdown-timer-ultimate' ),
			'design-2'  => __( 'Vertical Flip', 'countdown-timer-ultimate' ),
			'design-9'  => __( 'Modern Clock', 'countdown-timer-ultimate' ),
			'design-11' => __( 'Shadow Clock', 'countdown-timer-ultimate' ),
			'design-4'  => __( 'Bars Clock', 'countdown-timer-ultimate' ),
		);

	return apply_filters('wpcdt_designs', $design_arr);
}