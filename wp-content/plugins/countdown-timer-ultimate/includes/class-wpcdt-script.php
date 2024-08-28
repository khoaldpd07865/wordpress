<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpcdt_Script {

	function __construct() {

		// Action to add style & script in backend
		add_action( 'admin_enqueue_scripts', array($this, 'wpcdt_admin_style_script') );

		// Action to add style & script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wpcdt_front_style_script') );
	}

	/**
	 * Function to register admin scripts and styles
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.5
	 */
	function wpcdt_register_admin_assets() {

		global $wp_version;

		/* Styles */
		// Registring admin css
		wp_register_style( 'wpcdt-admin-css', WPCDT_URL.'assets/css/wpcdt-admin.css', array(), WPCDT_VERSION );

		/* Scripts */
		// Registring admin script
		wp_register_script( 'wpcdt-admin-js', WPCDT_URL.'assets/js/wpcdt-admin.js', array('jquery'), WPCDT_VERSION, true );
	}

	/**
	 * Function to add styleS & scriptS at backend side
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_admin_style_script( $hook ) {

		global $post_type;

		$this->wpcdt_register_admin_assets();

		/***** Registering Styles *****/
		// Time Picker style
		wp_register_style( 'wpcdt-jquery-ui-css', WPCDT_URL.'assets/css/wpcdt-time-picker.css', array(), WPCDT_VERSION );

		/***** Registering Scripts *****/
		// TimePicker script
		wp_register_script( 'wpcdt-ui-timepicker-addon-js', WPCDT_URL.'assets/js/jquery-ui-timepicker-addon.min.js', array('jquery'), WPCDT_VERSION, true );

		// If page is Post page then enqueue script
		if( $post_type == WPCDT_POST_TYPE ) {

			/***** Enqueue Styles *****/
			wp_enqueue_style( 'wp-color-picker' );		// ColorPicker Style
			wp_enqueue_style( 'wpcdt-jquery-ui-css' );	// TimePicker Style
			wp_enqueue_style( 'wpcdt-admin-css' );		// Admin Style

			/* Enqueue Scripts */
			wp_enqueue_script( 'wp-color-picker' );					// ColorPicker Script
			wp_enqueue_script( 'jquery-ui-datepicker' );			// Date Picker Script
			wp_enqueue_script( 'jquery-ui-slider' );				// jQuery UI Slider Script
			wp_enqueue_script( 'wpcdt-ui-timepicker-addon-js' );	// TimerPicker Addon Script
			wp_enqueue_script( 'wpcdt-admin-js' );					// Admin Script
		}

		// How it Work Page
		if( $hook == WPCDT_POST_TYPE.'_page_wpcdt-designs' || $hook == WPCDT_POST_TYPE.'_page_wpcdt-solutions-features') {
			wp_enqueue_script( 'wpcdt-admin-js' ); // Admin Script
		}

		if( $hook == WPCDT_POST_TYPE.'_page_wpcdt-solutions-features' || $hook == WPCDT_POST_TYPE.'_page_wpcdt-premium' ) {
			wp_enqueue_style( 'wpcdt-admin-css' );		// Admin Style
		}

		// VC Page Builder Frontend
		if( function_exists('vc_is_inline') && vc_is_inline() ) {
			wp_register_script( 'wpcdt-vc', WPCDT_URL . 'assets/js/vc/wpcdt-vc.js', array(), WPCDT_VERSION, true );
			wp_enqueue_script( 'wpcdt-vc' );
		}
	}

	/**
	 * Function to add styles & scripts at front side
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_front_style_script() {

		// Global Variable
		global $post;

		// Registring public css
		wp_register_style( 'wpcdt-public-css', WPCDT_URL.'assets/css/wpcdt-public.css', array(), WPCDT_VERSION );

		// Registring timer script
		wp_register_script( 'wpcdt-timecircle-js', WPCDT_URL.'assets/js/wpcdt-timecircles.js', array('jquery'), WPCDT_VERSION, true );

		// Register Elementor script
		wp_register_script( 'wpcdt-elementor-js', WPCDT_URL.'assets/js/elementor/wpcdt-elementor.js', array('jquery'), WPCDT_VERSION, true );

		// Registring public script
		wp_register_script( 'wpcdt-public-js', WPCDT_URL.'assets/js/wpcdt-public.js', array('jquery'), WPCDT_VERSION, true );

		// Enqueue Public style
		wp_enqueue_style( 'wpcdt-public-css' );

		// Enqueue Script for Elementor Preview
		if ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) {

			wp_enqueue_script( 'wpcdt-timecircle-js' );
			wp_enqueue_script( 'wpcdt-public-js' );
			wp_enqueue_script( 'wpcdt-elementor-js' );
		}

		// Enqueue Style & Script for Beaver Builder
		if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

			$this->wpcdt_register_admin_assets();

			// Admin Style & Script
			wp_enqueue_style( 'wpcdt-admin-css');
			wp_enqueue_script( 'wpcdt-admin-js' );

			// Public Scripts
			wp_enqueue_script( 'wpcdt-timecircle-js' );
			wp_enqueue_script( 'wpcdt-public-js' );
		}

		// Enqueue Style & Script for VC Page Builder Frontend
		if( function_exists('vc_is_inline') && vc_is_inline() ) {
			wp_enqueue_script( 'wpcdt-timecircle-js' );
			wp_enqueue_script( 'wpcdt-public-js' );
		}

		// Enqueue Admin Style & Script for Divi Page Builder
		if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
			$this->wpcdt_register_admin_assets();

			wp_enqueue_style( 'wpcdt-admin-css');
		}

		// Enqueue Admin Style for Fusion Page Builder
		if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) ) ) {
			$this->wpcdt_register_admin_assets();

			wp_enqueue_style( 'wpcdt-admin-css');
		}
	}
}

$wpcdt_script = new Wpcdt_Script();