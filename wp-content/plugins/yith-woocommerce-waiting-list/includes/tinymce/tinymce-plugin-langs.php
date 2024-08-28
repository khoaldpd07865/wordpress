<?php
/**
 * Tinymce plugin langs. This file is based on wp-includes/js/tinymce/langs/wp-langs.php
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
}

if ( ! class_exists( '_WP_Editors' ) ) {
	require ABSPATH . WPINC . '/class-wp-editor.php';
}

/**
 * Ywcwtl_tinymce_plugin_translation //TODO short description
 *
 * @return string
 */
function ywcwtl_tinymce_plugin_translation() {
	$strings = array(
		'blogname'      => __( 'The Blog Name', 'yith-woocommerce-waiting-list' ),
		'site_title'    => __( 'The Site Title', 'yith-woocommerce-waiting-list' ),
		'product_title' => __( 'The Product Name', 'yith-woocommerce-waiting-list' ),
		'product_price' => __( 'The Product Price', 'yith-woocommerce-waiting-list' ),
		'product_sku'   => __( 'The Product SKU', 'yith-woocommerce-waiting-list' ),
		'unsubscribe'   => __( 'The Unsubscribe link', 'yith-woocommerce-waiting-list' ),
	);
    // phpcs:disable WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['section'] ) && 'yith_wcwtl_mail_instock' === $_GET['section'] ) {
		$strings['product_link'] = __( 'The Product Link', 'yith-woocommerce-waiting-list' );
	} elseif ( isset( $_GET['section'] ) && 'yith_wcwtl_mail_subscribe' === $_GET['section'] ) {
		$strings['remove_link'] = __( 'Unsubscribe link', 'yith-woocommerce-waiting-list' );
	} elseif ( isset( $_GET['section'] ) && 'yith_wcwtl_mail_subscribe_optin' === $_GET['section'] ) {
		$strings['confirm_link'] = __( 'Confirm subscription link', 'yith-woocommerce-waiting-list' );
	} elseif ( isset( $_GET['section'] ) && 'yith_wcwtl_mail_admin' === $_GET['section'] ) {
		$strings['user_email'] = __( 'The user email', 'yith-woocommerce-waiting-list' );
	}
    // phpcs:enable WordPress.Security.NonceVerification.Recommend
	$locale     = _WP_Editors::$mce_locale;
	$translated = 'tinyMCE.addI18n("' . $locale . '.tc_button", ' . wp_json_encode( $strings ) . ");\n";

	return $translated;
}

$strings = ywcwtl_tinymce_plugin_translation();
