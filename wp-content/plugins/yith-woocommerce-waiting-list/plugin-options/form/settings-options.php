<?php
/**
 * YITH Waiting List Form Settings Tab options array
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Waiting List
 * @version 2.0.0
 */

defined( 'YITH_WCWTL_INIT' ) || exit; // Exit if accessed directly.

$tab = array( // phpcsignore.
	'form-settings' => array(

		'yith-wcwtl-general-options-title' => array(
			/* translators: Title for options section in Waiting List -> Form in Product Page -> Options */
			'title' => __( 'Form Options', 'yith-woocommerce-waiting-list' ),
			'type'  => 'title',
			'id'    => 'yith-wcwtl-form-options-title',
		),

		'waiting-privacy-checkbox'         => array(
			'id'        => 'yith-wcwtl-enable-privacy-checkbox',
			'title'     => __( 'Enable Privacy Policy checkbox', 'yith-woocommerce-waiting-list' ),
			'desc'      => __(
				'Show a mandatory Privacy Policy checkbox under the Waitlist form.',
				'yith-woocommerce-waiting-list'
			),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'yes',
		),

		'waiting-privacy-checkbox-text'    => array(
			'id'        => 'yith-wcwtl-privacy-checkbox-text',
			'title'     => __( 'Privacy checkbox text', 'yith-woocommerce-waiting-list' ),
			'desc'      => nl2br(
				__(
					"Enter the text to show in the Privacy Policy checkbox field in the Waiting List form.\nYou can use the shortcode [terms] and [privacy_policy] (from WooCommerce 3.4.0).\n\nDon't forget to set the \"Privacy Policy\" and \"Terms\" pages in Appearance > Customize > WooCommerce > Checkout. ",
					'yith-woocommerce-waiting-list'
				)
			),
			'type'      => 'yith-field',
			'yith-type' => 'textarea',
			'default'   => sprintf(
				// translators: %s stands for HTML link tag.
				__(
					'I have read and accept the %1$s Privacy Policy %2$s',
					'yith-woocommerce-waiting-list'
				),
				'<a href="#">',
				'</a>',
			),
			'rows'      => 2,
			'deps'      => array(
				'id'    => 'yith-wcwtl-enable-privacy-checkbox',
				'value' => 'yes',
				'type'  => 'hide',
			),
		),

		'enable-google-recaptcha'          => array(
			'id'                 => 'yith-wcwtl-enable-google-recaptcha',
			'title'              => __( 'Enable Google reCAPTCHA', 'yith-woocommerce-waiting-list' ),
			'desc'               => __(
				'Enable Google reCAPTCHA to avoid spam registrations.',
				'yith-woocommerce-waiting-list'
			),
			'type'               => 'yith-field',
			'yith-type'          => 'onoff',
			'default'            => 'no',
			'is_option_disabled' => true,
			'option_tags'        => array( 'premium' ),
		),


		array(
			'type' => 'sectionend',
		),

	),
);

return $tab;
