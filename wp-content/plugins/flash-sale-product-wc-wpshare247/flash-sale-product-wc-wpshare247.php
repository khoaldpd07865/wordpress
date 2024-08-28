<?php
/**
 * Plugin Name: Flash Sale Product for WooCommerce - WPSHARE247
 * Plugin URI: https://wpshare247.com/
 * Description: Add flash sale bar to product bottom
 * Version: 1.0
 * Author: Wpshare247.com
 * Author URI: https://wpshare247.com
 * Text Domain: ws247-fspw
 * Domain Path: /languages/
 * Requires at least: 4.9
 * Requires PHP: 5.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WS247_FSPW', __FILE__ );
define( 'WS247_FSPW_PLUGIN_DIR', untrailingslashit( dirname( WS247_FSPW ) ) );
define( 'WS247_FSPW_PLUGIN_INC_DIR', WS247_FSPW_PLUGIN_DIR . '/inc' );  
require_once WS247_FSPW_PLUGIN_INC_DIR . '/define.php';
require_once WS247_FSPW_PLUGIN_INC_DIR . '/class.setting.page.php';
require_once WS247_FSPW_PLUGIN_INC_DIR . '/metabox.php';
require_once WS247_FSPW_PLUGIN_INC_DIR . '/theme_functions.php';

