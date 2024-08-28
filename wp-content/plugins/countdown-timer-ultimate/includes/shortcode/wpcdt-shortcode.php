<?php
/**
 * 'wpcdt-countdown' Shortcode
 *
 * @package Countdown Timer Ultimate
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wpcdt_countdown_timer( $atts, $content = null ) {

	// Divi Frontend Builder - Do not Display Preview
	if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_POST['is_fb_preview'] ) && isset( $_POST['shortcode'] ) ) {
		return '<div class="wpcdt-builder-shrt-prev">
					<div class="wpcdt-builder-shrt-title"><span>'.esc_html__('Countdown Timer - Shortcode', 'countdown-timer-ultimate').'</span></div>
					wpcdt-countdown
				</div>';
	}

	// Fusion Builder Live Editor - Do not Display Preview
	if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) || ( isset( $_POST['action'] ) && $_POST['action'] == 'get_shortcode_render' )) ) {
		return '<div class="wpcdt-builder-shrt-prev">
					<div class="wpcdt-builder-shrt-title"><span>'.esc_html__('Countdown Timer - Shortcode', 'countdown-timer-ultimate').'</span></div>
					wpcdt-countdown
				</div>';
	}

	// Taking some globals
	global $post;

	$atts = shortcode_atts( array(
		'id' => '',
	), $atts, 'wpcdt-countdown');

	$atts['unique']		= wpcdt_get_unique();
	$atts['timer_id']	= ! empty( $atts['id'] ) ? $atts['id'] : 0;

	extract( $atts );

	// If timer is not there, recursive loop or no query post found
	if ( empty( $timer_id ) ) {
		return $content;
	}

	// Taking variables
	$prefix			= WPCDT_META_PREFIX;
	$timer_date		= get_post_meta( $timer_id, $prefix.'timer_date', true );
	$current_time	= current_time( 'timestamp' );

	// If timer `Expiry Date & Time` is not there
	if( empty( $timer_date ) ) {
		return $content;
	}

	// Getting General Settings
	$timer_date	= strtotime( $timer_date );

	// Taking data in atts variable
	$content_data	= get_post_meta( $timer_id, $prefix.'content', true );
	$day_text		= ! empty( $content_data['timer_day_text'] )	? $content_data['timer_day_text']		: '';
	$hour_text		= ! empty( $content_data['timer_hour_text'] )	? $content_data['timer_hour_text']		: '';
	$minute_text	= ! empty( $content_data['timer_minute_text'] ) ? $content_data['timer_minute_text']	: '';
	$second_text	= ! empty( $content_data['timer_second_text'] ) ? $content_data['timer_second_text']	: '';
	$is_days		= ! empty( $content_data['is_timerdays'] )		? $content_data['is_timerdays']			: '';
	$is_hours		= ! empty( $content_data['is_timerhours'] )		? $content_data['is_timerhours']		: '';
	$is_minutes		= ! empty( $content_data['is_timerminutes'] )	? $content_data['is_timerminutes']		: '';
	$is_seconds		= ! empty( $content_data['is_timerseconds'] )	? $content_data['is_timerseconds']		: '';
	$expiry_date	= date_i18n( 'Y-m-d H:i:s', $timer_date );
	$current_date	= date_i18n( 'Y-m-d H:i:s', $current_time );
	$date_c			= date('c');
	$totalseconds	= ( $timer_date - $current_time );

	// Taking some variable
	$design_data = get_post_meta( $timer_id, $prefix.'design', true );

	// Taking some data
	$design	= get_post_meta( $timer_id, $prefix.'design_style', true );

	// Getting circle style 1 settings
	$timercircle_animation	= ! empty( $design_data['timercircle_animation'] )	? $design_data['timercircle_animation'] : '';
	$timercircle_width		= ! empty( $design_data['timercircle_width'] )		? $design_data['timercircle_width']		: 0.1;
	$timer_bg_width			= ! empty( $design_data['timerbackground_width'] )	? $design_data['timerbackground_width'] : 1.2;

	// Getting clock background colors settings
	$timer_bgclr			= ! empty( $design_data['timerbackground_color'] )			? $design_data['timerbackground_color']			: '#313332';
	$timer_day_bgclr		= ! empty( $design_data['timerdaysbackground_color'] )		? $design_data['timerdaysbackground_color'] 	: '#e3be32';
	$timer_hour_bgclr		= ! empty( $design_data['timerhoursbackground_color'] ) 	? $design_data['timerhoursbackground_color']	: '#36b0e3';
	$timer_minute_bgclr		= ! empty( $design_data['timerminutesbackground_color'] )	? $design_data['timerminutesbackground_color']	: '#75bf44';
	$timer_second_bgclr		= ! empty( $design_data['timersecondsbackground_color'] )	? $design_data['timersecondsbackground_color']	: '#66c5af';

	$classes = " wpcdt-timer-{$design} wpcdt-timer-{$timer_id}";

	// Timer Config
	$timer_conf = compact('current_date', 'expiry_date', 'timercircle_animation', 'timercircle_width', 'timer_bg_width', 'timer_bgclr', 'timer_day_bgclr', 'timer_hour_bgclr', 'timer_minute_bgclr', 'timer_second_bgclr', 'day_text', 'hour_text', 'minute_text', 'second_text', 'is_days', 'is_hours', 'is_minutes', 'is_seconds');

	wp_enqueue_script('wpcdt-timecircle-js'); // Enqueue Timer Circle JS

	// Dequeue Timer JS
	wp_dequeue_script( 'wpcdt-public-js' );

	// Enqueue Timer JS
	wp_enqueue_script('wpcdt-public-js');

	ob_start();

	// If timer greater then or qual to current time
	if( $timer_date >= $current_time ) {

		// Print Inline Style
		echo "<style type='text/css'>". wpcdt_generate_style( $timer_id, $design, $design_data ) ."</style>";

		// Include Design File
		include( WPCDT_DIR. '/templates/circle.php' );
	}

	$content .= ob_get_clean();
	return $content;
}

// Countdown Timer Shortcode
add_shortcode( 'wpcdt-countdown', 'wpcdt_countdown_timer' );