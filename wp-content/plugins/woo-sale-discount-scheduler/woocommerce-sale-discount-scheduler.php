<?php
/*
Plugin Name: Woocommerce Sale Discount Scheduler
Description: This Plugin provide you options to manage the discount throughout seasonally and occasionally of all your woocommerce products, scheduling discount throughout Any Date and Time.
Author: Geek Code Lab
Version: 1.9.1
WC tested up to: 8.9.0
Requires Plugins: woocommerce
Author URI: https://geekcodelab.com/
Text Domain: woocommerce-sale-discount-scheduler
*/

if(!defined('ABSPATH')) exit;

define("WSDS_BUILD","1.9.1");

if(!defined("WSDS_PLUGIN_DIR_PATH"))
	
	define("WSDS_PLUGIN_DIR_PATH",plugin_dir_path(__FILE__));
	
if(!defined("WSDS_PLUGIN_URL"))
	
	define("WSDS_PLUGIN_URL",plugins_url().'/'.basename(dirname(__FILE__)));

/** Set Plugin Seeting and Support Option Start */
$plugin_name = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin_name", 'wsds_plugin_add_settings_link');
function wsds_plugin_add_settings_link( $links ) {
	$support_link = '<a href="https://geekcodelab.com/contact/"  target="_blank" >' . __( 'Support', 'woo-donations-pro' ) . '</a>'; 
	array_unshift( $links, $support_link );

	$settings_link = '<a href="admin.php?page=wsds-settings">' . __( 'Settings', 'woo-donations-pro' ) . '</a>'; 
	array_unshift( $links, $settings_link );	
	return $links;	
}
	
require_once( WSDS_PLUGIN_DIR_PATH .'admin/functions.php');
require_once( WSDS_PLUGIN_DIR_PATH .'tools/shortcodes.php');
require_once( WSDS_PLUGIN_DIR_PATH .'tools/widgets.php');


/** Enqueue scripts */
add_action( 'admin_enqueue_scripts', 'wsds_enqueue_styles');
add_action( 'wp_enqueue_scripts', 'wsds_front_style_include' );
function wsds_enqueue_styles() {
	wp_enqueue_style( "wsds-admin-style", WSDS_PLUGIN_URL . "/assets/css/admin-style.css", array(), WSDS_BUILD );
	wp_enqueue_script( "wsds-admin-script", WSDS_PLUGIN_URL . "/assets/js/admin-script.js", array(), WSDS_BUILD );
}
function wsds_front_style_include() {
   	wp_enqueue_style( "wsds-front-style", WSDS_PLUGIN_URL . "/assets/css/front-style.css", array(), WSDS_BUILD );
   	wp_enqueue_script( "wsds-public", WSDS_PLUGIN_URL . "/assets/js/public.js", array('jquery'), WSDS_BUILD );
	   wp_localize_script( 'wsds-public', 'wsds_public_object',
	   array()
   );
}

/** Add options for administrator user */
add_action('admin_init', 'wsds_manage_scheduler');
function wsds_manage_scheduler() {
	register_setting('wsds-all-settings','wsds_options','wsds_sanitize_callback');

	$current_roles=wp_get_current_user()->roles;
	if(in_array("administrator",$current_roles)) {
		include( WSDS_PLUGIN_DIR_PATH .'admin/options.php');	
	}
}

function wsds_sanitize_callback($input) {
	$new_input = array();

	if(isset($input) && !empty($input)) {
		foreach($input as $key => $val) {			
			if(isset($input[$key]) && !empty($input[$key])) {
				$new_input[$key] = sanitize_text_field($input[$key]);
			}
		}
	}

	return $new_input;
}

add_action( 'admin_menu', 'wsds_plugin_menu_page' );
function wsds_plugin_menu_page() {
	add_submenu_page(
		'woocommerce',
		'Sale Discount Scheduler Settings',
		'Sale Discount Scheduler Settings',
		'manage_options',
		'wsds-settings',
		'wsds_admin_menu_disable_price_range'
	);
}

function wsds_admin_menu_disable_price_range() {            
	require_once("admin/general-settings.php");
	
}

/** Add action for start time and end time update post meta  */
add_action('wsds_start_shedule_sale_discount','wsds_start_schedule_sale_discount_event');
add_action('wsds_end_shedule_sale_discount','wsds_end_schedule_sale_discount_event');
function wsds_start_schedule_sale_discount_event($post_id)
{	
	$status=get_post_meta($post_id,'wsds_schedule_sale_status',true);
	if($status){
		update_post_meta($post_id,'wsds_schedule_sale_mode',1);
	}
}
function wsds_end_schedule_sale_discount_event($post_id)
{	
	$status=get_post_meta($post_id,'wsds_schedule_sale_status',true);
	if($status){
		update_post_meta($post_id,'wsds_schedule_sale_mode',0);	
		update_post_meta($post_id,'wsds_schedule_sale_status',0);	
	}
}

/** Update product price with discount during scheduled time  */
add_filter('woocommerce_product_variation_get_price', 'wsds_return_price', 99, 2);
add_filter('woocommerce_product_get_price', 'wsds_return_price', 99, 2);
function wsds_return_price($price, $product) {
	if(is_admin())  return $price;
    $product_id = $product->get_id();
	$schedule_sale_status=get_post_meta($product_id,'wsds_schedule_sale_status',true); 
	$discount_type=get_post_meta($product_id,'wsds_schedule_sale_discount_type',true);
	$sale_price=get_post_meta($product_id,'wsds_schedule_sale_sale_price',true);
	if (isset($schedule_sale_status) && $schedule_sale_status == 1 && isset($sale_price) && !empty($sale_price)) {

		$start_time=get_post_meta($product_id,'wsds_schedule_sale_st_time',true);
		$end_time=get_post_meta($product_id,'wsds_schedule_sale_end_time',true); 

		if($start_time < time() && $end_time > time()) {
			

			$meta_price = get_post_meta($product_id, '_regular_price');

			if($discount_type=="Percentage") {
				$price = $meta_price[0]-($meta_price[0]*$sale_price)/100;			
			}else{
				$price = ($meta_price[0]-$sale_price);
			}
		}
	}
	return $price;
}

/** Get scheduled discount price */
function wsds_get_discount_price($product_id) {
	$price = "";
	$schedule_sale_status=get_post_meta($product_id,'wsds_schedule_sale_status',true); 
	$sale_price=get_post_meta($product_id,'wsds_schedule_sale_sale_price',true);
	$discount_type=get_post_meta($product_id,'wsds_schedule_sale_discount_type',true);
	if (isset($schedule_sale_status) && $schedule_sale_status == 1 && isset($sale_price) && !empty($sale_price)) {

		$meta_price = get_post_meta($product_id, '_regular_price');
		if($discount_type=="Percentage") {
			$price = $meta_price[0]-($meta_price[0]*$sale_price)/100;
		}else{
			$price = $meta_price[0]-$sale_price;
		}
	}
	return $price;
}

/** Shop loop - Add start countdown to scheduled price products */
function wsds_shop_start_countdown($product_id) {
	$start_countdown_html = $wsds_shop_loop_countdown = '';

	$schedule_sale_status=get_post_meta($product_id,'wsds_schedule_sale_status',true);
	
	$sale_price  = wsds_get_discount_price($product_id);
	$currency_symbol = get_woocommerce_currency_symbol();
	$countdown = get_post_meta($product_id,'wsds_schedule_sale_start_countdown',true);   
	if(metadata_exists('post',$product_id,'wsds_enable_countdown_on_shop_loop')) {
		$wsds_shop_loop_countdown = get_post_meta($product_id,'wsds_enable_countdown_on_shop_loop',true);
	}else{
		if($countdown==1) {
			$wsds_shop_loop_countdown = 'on';
		}
	}

	if(!empty($countdown) && $wsds_shop_loop_countdown == 'on') {
		$wsds_options = get_option('wsds_options');
		$sale_start_label  = (isset($wsds_options['sale_start_label'])) ? $wsds_options['sale_start_label'] : __('This product will be on sale for {wsds_sale_price} after the following timer','woocommerce-sale-discount-scheduler');
		$discount_not_applied_label  = (isset($wsds_options['discount_not_applied_label'])) ? $wsds_options['discount_not_applied_label'] : __('Discount Not Applied: Set Regular Price greater than discount price','woocommerce-sale-discount-scheduler');
		
		$start_time = get_post_meta($product_id,'wsds_schedule_sale_st_time',true);
		$time_diffrent=$start_time-time();
		$s = $time_diffrent;
		$m = floor($s / 60);
		$s = $s % 60;
		$h = floor($m / 60);
		$m = $m % 60;
		$d = floor($h / 24);
		$h = $h % 24;
		$display_msg='';
		if($sale_price<0) {
			$display_msg = $discount_not_applied_label;
		}else{
			$display_msg = str_replace('{wsds_sale_price}',$currency_symbol.$sale_price,$sale_start_label);
		}

		if ($time_diffrent > 0) {
			$day_label = __('Days','woocommerce-sale-discount-scheduler');
			$hour_label = __('Hours','woocommerce-sale-discount-scheduler');
			$minutes_label = __('Minutes','woocommerce-sale-discount-scheduler');
			$seconds_label = __('Seconds','woocommerce-sale-discount-scheduler');
			$start_countdown_html .= '<div id="wsds_countdown_start_'.$product_id.'" data-product="'.$product_id.'" data-start="'.$start_time.'" class="wsds_countdown_start wsds_coundown_shop">
			<span>'.$display_msg.'</span>
			<ul><li><div><span class="wsds_count_digit">'.$d.'</span><span class="wsds_count_lable">'.$day_label.'</span></div></li><li><div><span class="wsds_count_digit">'.$h.'</span><span class="wsds_count_lable">'.$hour_label.'</span></div></li><li><div><span class="wsds_count_digit">'.$m.'</span><span class="wsds_count_lable">'.$minutes_label.'</span></div></li><li><div><span class="wsds_count_digit">'.$s.'</span><span class="wsds_count_lable">'.$seconds_label.'</span></div></li></ul></div>';
		}
	}

	return $start_countdown_html;
}

add_action( 'woocommerce_after_shop_loop_item', 'wsds_shop_sale_start_countdown', 5 );
function wsds_shop_sale_start_countdown() {
	global $product;
	$product_id  = $product->get_id();	
	$countdown_html =  wsds_shop_start_countdown($product_id);
	_e($countdown_html);
}

/** Shop loop - Add ongoing countdown to scheduled price products */
function wsds_shop_ongoing_countdown_html($product_id) {
	$ongoing_countdown_html = '';

	$start_time=get_post_meta($product_id,'wsds_schedule_sale_st_time',true);
	$end_time=get_post_meta($product_id,'wsds_schedule_sale_end_time',true); 

	if($start_time < time() && $end_time > time()) {
		$sale_price = wsds_get_discount_price($product_id);
		$currency_symbol=get_woocommerce_currency_symbol();	

		$wsds_shop_loop_countdown = '';
		$countdown = get_post_meta($product_id,'wsds_schedule_sale_end_countdown',true);   
		if(metadata_exists('post',$product_id,'wsds_enable_countdown_on_shop_loop')) {
			$wsds_shop_loop_countdown = get_post_meta($product_id,'wsds_enable_countdown_on_shop_loop',true);
		}else{
			if($countdown==1) {
				$wsds_shop_loop_countdown = 'on';
			}
		}

		if(!empty($countdown) && $wsds_shop_loop_countdown == 'on') {
			
			$time_diffrent=$end_time-time();
			$s = $time_diffrent;
			$m = floor($s / 60);
			$s = $s % 60;
			$h = floor($m / 60);
			$m = $m % 60;
			$d = floor($h / 24);
			$h = $h % 24;

			$wsds_options = get_option('wsds_options');
			$sale_end_label  = (isset($wsds_options['sale_end_label'])) ? $wsds_options['sale_end_label'] : __("Don't miss out! Sale ends after following timer","woocommerce-sale-discount-scheduler");
			
			if ($time_diffrent > 0)
			{
				$day_label = __('Days','woocommerce-sale-discount-scheduler');
				$hour_label = __('Hours','woocommerce-sale-discount-scheduler');
				$minutes_label = __('Minutes','woocommerce-sale-discount-scheduler');
				$seconds_label = __('Seconds','woocommerce-sale-discount-scheduler');
				$ongoing_countdown_html .= '<div id="wsds_countdown_end_'.$product_id.'" data-product="'.$product_id.'" data-end="'.$end_time.'" class="wsds_countdown_end wsds_coundown_shop">
				<span>'.$sale_end_label.'</span>
				<ul><li><div><span class="wsds_count_digit">'.$d.'</span><span class="wsds_count_lable">'.$day_label.'</span></div></li><li><div><span class="wsds_count_digit">'.$h.'</span><span class="wsds_count_lable">'.$hour_label.'</span></div></li><li><div><span class="wsds_count_digit">'.$m.'</span><span class="wsds_count_lable">'.$minutes_label.'</span></div></li><li><div><span class="wsds_count_digit">'.$s.'</span><span class="wsds_count_lable">'.$seconds_label.'</span></div></li></ul></div>';
			}
		}
	}

	return $ongoing_countdown_html;
}

add_action( 'woocommerce_after_shop_loop_item', 'wsds_shop_sale_ongoing_countdown', 5 );
function wsds_shop_sale_ongoing_countdown() {
	global $product;
	$product_id  = $product->get_id();
	$product_ids = wsds_get_schedule_product_list(1);
	$countdown_html = wsds_shop_ongoing_countdown_html($product_id);
	_e($countdown_html);
}

/** Single product - Add sale start countdown to scheduled price products */
add_action( 'woocommerce_single_product_summary', 'wsds_sale_start_countdown', 30 ); 
function wsds_sale_start_countdown() {
	global $product;
	$product_id = $product->get_id();	
	$sale_price=wsds_get_discount_price($product_id);
	$currency_symbol=get_woocommerce_currency_symbol();
	$schedule_sale_status=get_post_meta($product_id,'wsds_schedule_sale_status',true); 
	if (isset($schedule_sale_status) && $schedule_sale_status == 1)
	{
		$wsds_single_product_countdown = '';
		$countdown=get_post_meta($product_id,'wsds_schedule_sale_start_countdown',true); 
		if(metadata_exists('post',$product_id,'wsds_enable_countdown_on_single_product')) {
			$wsds_single_product_countdown = get_post_meta($product_id,'wsds_enable_countdown_on_single_product',true);
		}else{
			if($countdown==1) {
				$wsds_single_product_countdown = 'on';
			}
		}

		if(!empty($countdown) && $wsds_single_product_countdown == 'on') {
			$wsds_options = get_option('wsds_options');
			$sale_start_label  = (isset($wsds_options['sale_start_label'])) ? $wsds_options['sale_start_label'] : __('This product will be on sale for {wsds_sale_price} after the following timer','woocommerce-sale-discount-scheduler');
			$discount_not_applied_label  = (isset($wsds_options['discount_not_applied_label'])) ? $wsds_options['discount_not_applied_label'] : __('Discount Not Applied: Set Regular Price greater than discount price','woocommerce-sale-discount-scheduler');

			$start_time=get_post_meta($product_id,'wsds_schedule_sale_st_time',true);
			$time_diffrent=$start_time-time();
			$s = $time_diffrent;
			$m = floor($s / 60);
			$s = $s % 60;
			$h = floor($m / 60);
			$m = $m % 60;
			$d = floor($h / 24);
			$h = $h % 24;
			if($sale_price<0) {
				$display_msg = $discount_not_applied_label;
			}else{
				$display_msg = str_replace('{wsds_sale_price}',$currency_symbol.$sale_price,$sale_start_label);
			}
			if ($time_diffrent > 0)
			{
				echo '
				<div id="wsds_countdown_start_'.$product_id.'" data-product="'.$product_id.'" data-start="'.$start_time.'" class="wsds_countdown_start wsds_coundown_single">
					
					<span>'.$display_msg.'</span>
					<ul>
						<li>
							<div>
								<span class="wsds_count_digit">'.$d.'</span>
								<span class="wsds_count_lable">Days</span>
								<div class="border-over"></div>
								<div class="slice">
									<div class="bar"></div>
								</div>
							</div>
						</li>
						<li>
							<div>
								<span class="wsds_count_digit">'.$h.'</span>
								<span class="wsds_count_lable">Hours</span>
								<div class="border-over"></div>
							</div>
						</li>
						<li>
							<div>
								<span class="wsds_count_digit">'.$m.'</span>
								<span class="wsds_count_lable">Min</span>
								<div class="border-over"></div>
							</div>
						</li>
						<li>
							<div>
								<span class="wsds_count_digit">'.$s.'</span>
								<span class="wsds_count_lable">Sec</span>
								<div class="border-over"></div>
							</div>
						</li>
					</ul>
				</div>';
			}
		}
	}
}

/** Single product - Add sale ongoing countdown to scheduled price products */
add_action( 'woocommerce_single_product_summary', 'wsds_schedule_sale_ongoing_countdown', 30 );
function wsds_schedule_sale_ongoing_countdown() {
	global $product;
	$product_id=$product->get_id();	
	$sale_price=wsds_get_discount_price($product_id);
	$currency_symbol=get_woocommerce_currency_symbol();
	$schedule_sale_status=get_post_meta($product_id,'wsds_schedule_sale_status',true); 
	if (isset($schedule_sale_status) && $schedule_sale_status == 1)
	{ 
		$wsds_single_product_countdown = '';
		$countdown=get_post_meta($product_id,'wsds_schedule_sale_end_countdown',true);   
		if(metadata_exists('post',$product_id,'wsds_enable_countdown_on_single_product')) {
			$wsds_single_product_countdown = get_post_meta($product_id,'wsds_enable_countdown_on_single_product',true);
		}else{
			if($countdown==1) {
				$wsds_single_product_countdown = 'on';
			}
		}

		if(!empty($countdown) && $wsds_single_product_countdown == 'on') {
			$end_time=get_post_meta($product_id,'wsds_schedule_sale_end_time',true);  
			
			$time_diffrent=$end_time-time();
			$s = $time_diffrent;
			$m = floor($s / 60);
			$s = $s % 60;
			$h = floor($m / 60);
			$m = $m % 60;
			$d = floor($h / 24);
			$h = $h % 24;
			if ($time_diffrent > 0)
			{
				$wsds_options = get_option('wsds_options');
				$sale_end_label  = (isset($wsds_options['sale_end_label'])) ? $wsds_options['sale_end_label'] : __("Don't miss out! Sale ends after following timer","woocommerce-sale-discount-scheduler");

				echo '
				<div id="wsds_countdown_end_'.$product_id.'" data-product="'.$product_id.'" data-end="'.$end_time.'" class="wsds_countdown_end wsds_coundown_single">
					
					<span>'.$sale_end_label.'</span>
					<ul>
						<li>
							<div>
								<span class="wsds_count_digit">'.$d.'</span>
								<span class="wsds_count_lable">Days</span>
								<div class="border-over"></div>
								<div class="slice">
									<div class="bar"></div>
								</div>
							</div>
						</li>
						<li>
							<div>
								<span class="wsds_count_digit">'.$h.'</span>
								<span class="wsds_count_lable">Hours</span>
								<div class="border-over"></div>
							</div>
						</li>
						<li>
							<div>
								<span class="wsds_count_digit">'.$m.'</span>
								<span class="wsds_count_lable">Min</span>
								<div class="border-over"></div>
							</div>
						</li>
						<li>
							<div>
								<span class="wsds_count_digit">'.$s.'</span>
								<span class="wsds_count_lable">Sec</span>
								<div class="border-over"></div>
							</div>
						</li>
					</ul>
				</div>';
			}
		}
	}
}

/** Admin script for date picker - Start time and End time field script */
add_action('admin_footer', 'wsds_schedule_sale_discount_admin_footer_function');
function wsds_schedule_sale_discount_admin_footer_function() {
	$screen = get_current_screen();
	if(isset($screen) && !empty($screen)){
		if(isset($screen->post_type)){
			if($screen->post_type == 'product') {
				?>
				<script>
					jQuery(document).ready(function () {
						jQuery('body').on('focus',".wsds_st_date,.wsds_end_date", function(){
							jQuery(this).datepicker({ dateFormat: 'yy-m-d' });
						});
					});
				</script>
				<?php 
			}
		}
	}
}

/**
 * Pushing sale countdown of product inside `woocommerce_available_variation`
 */
function wsds_rewrite_wc_available_variation( $default, $class, $variation ) {
	$product_id = $variation->get_id();

	// Getting variation sale discount status by variation id
	$schedule_discount_status = get_post_meta($variation->get_id(), 'wsds_schedule_sale_status', true);

	// Pushing the initial price [if WC_Product class initialized]
	if(isset($schedule_discount_status) && $schedule_discount_status == 1) {
		$start_countdown_html = wsds_shop_start_countdown($product_id);
		$ongoing_countdown_html = wsds_shop_ongoing_countdown_html($product_id);
		// $product_id . ' -- ' .
		$default['wsds_countdown_status'] = $schedule_discount_status;
		$default['wsds_countdown_html'] = $start_countdown_html . $ongoing_countdown_html;
	}else{
		$default['wsds_countdown_status'] = 0;
		$default['wsds_countdown_html'] = '';
	}

	return apply_filters( 'wsds_woocommerce_available_variation', $default, $class, $variation );
}
add_filter( 'woocommerce_available_variation', 'wsds_rewrite_wc_available_variation', 99, 3 );

/**
 * Added HPOS support for woocommerce
 */
add_action( 'before_woocommerce_init', 'wsds_before_woocommerce_init' );
function wsds_before_woocommerce_init() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}