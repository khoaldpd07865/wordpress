<?php // phpcs:ignore WordPress.NamingConventions
/**
 * YITH WooCommerce Waiting List Mail Template
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly.

do_action( 'woocommerce_email_header', $email_heading, $email );

echo wp_kses_post( wpautop( wptexturize( $email_content ) ) );

do_action( 'woocommerce_email_footer', $email );

