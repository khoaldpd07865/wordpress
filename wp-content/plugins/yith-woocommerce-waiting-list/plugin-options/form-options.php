<?php
/**
 * YITH Waiting List Form Tab options array
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Waiting List
 * @version 2.0.0
 */

defined( 'YITH_WCWTL_INIT' ) || exit; // Exit if accessed directly.

$tab = array( //phpcs:ignore;
	'form' => array(
		'form-options' => array(
			'type'     => 'multi_tab',
			'sub-tabs' => array(
				'form-settings'      => array(
					'title'       => __( 'Options', 'yith-woocommerce-waiting-list' ),
					'description' => __( 'Set the general options of the Waitlist form shown on the product pages.', 'yith-woocommerce-waiting-list' ),
				),
				'form-customization' => array(
					/* translators: Main section title you find in Waiting List -> Form in Product Page -> Customization */
					'title'       => __( 'Customization', 'yith-woocommerce-waiting-list' ),
					'description' => __( 'Customize the design of the form and the notices shown on the product pages.', 'yith-woocommerce-waiting-list' ),
				),
			),
		),
	),
);

return $tab;
