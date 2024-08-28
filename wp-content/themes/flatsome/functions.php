<?php
/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */
update_option( 'flatsome_wup_supported_until', '01.11.2024' );
update_option( 'flatsome_wup_purchase_code', 'd9312df0-0cfc-4f64-9008-cac584881ac1' );
update_option( 'flatsome_wup_buyer', 'chowordpress.com' );
require get_template_directory() . '/inc/init.php';

flatsome()->init();

/**
 * It's not recommended to add any custom code here. Please use a child theme
 * so that your customizations aren't lost during updates.
 *
 * Learn more here: https://developer.wordpress.org/themes/advanced-topics/child-themes/
 */
