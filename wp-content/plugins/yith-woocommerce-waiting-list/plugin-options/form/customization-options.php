<?php
/**
 * YITH waitlist Customization Form Settings Tab options array
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH WooCommerce waitlist
 * @version 2.0.0
 */

defined( 'YITH_WCWTL_INIT' ) || exit; // Exit if accessed directly.

$tab = array(
	'form-customization' => array(

		'yith-wcwtl-customization-options-title' => array(
			/* translators: Title for section "Customizations in waitlist -> Form in Product Page -> Customization" */
			'title' => __( 'Customization Options', 'yith-woocommerce-waiting-list' ),
			'type'  => 'title',
			'desc'  => '',
			'id'    => 'yith-wcwtl-customization-options-title',
		),

		'section-background'                     => array(
			'id'        => 'yith-wcwtl-section-background',
			'title'     => __( 'Section background', 'yith-woocommerce-waiting-list' ),
			'desc'      => __(
				'Set the background color for the waitlist section on product pages.',
				'yith-woocommerce-waiting-list'
			),
			'type'      => 'yith-field',
			'yith-type' => 'colorpicker',
			'default'   => '#f9f9f9',
		),

		'waiting-list-message'                   => array(
			'id'        => 'yith-wcwtl-form-message',
			'title'     => __( 'Notice to show in out-of-stock products', 'yith-woocommerce-waiting-list' ),
			'desc'      => __(
				'A message to show before the waitlist form on single product pages.',
				'yith-woocommerce-waiting-list'
			),
			'type'      => 'yith-field',
			'yith-type' => 'textarea-editor',
			'default'   => sprintf(
				__(
					"%1\$s%2\$sThis product is currently sold out.%3\$s%4\$s Don't worry! Enter your email and we'll notify you when it's available again.",
					'yith-woocommerce-waiting-list'
				),
				'<h4>',
				'<span style="color: #993300;">',
				'</span>',
				'</h4>'
			),
		),

		'form-placeholder'                       => array(
			'id'        => 'yith-wcwtl-form-placeholder',
			'title'     => __( 'Form placeholder', 'yith-woocommerce-waiting-list' ),
			'desc'      => __(
				'Enter an optional placeholder to show inside the input field.',
				'yith-woocommerce-waiting-list'
			),
			'type'      => 'yith-field',
			'yith-type' => 'text',
			'default'   => __( 'Enter your email address', 'yith-woocommerce-waiting-list' ),
		),

		'waiting-list-button-add'                => array(
			'id'        => 'yith-wcwtl-button-add-label',
			'title'     => __( 'Button Label', 'yith-woocommerce-waiting-list' ),
			'desc'      => __( 'Enter the button label.', 'yith-woocommerce-waiting-list' ),
			'type'      => 'yith-field',
			'yith-type' => 'text',
			'default'   => __( 'Add to waitlist', 'yith-woocommerce-waiting-list' ),
		),

		'waiting-list-button-colors'             => array(
			'id'           => 'yith-wcwtl-button-colors',
			'title'        => __( 'Button colors', 'yith-woocommerce-waiting-list' ),
			'desc'         => __( 'Set the color for the waitlist button.', 'yith-woocommerce-waiting-list' ),
			'type'         => 'yith-field',
			'yith-type'    => 'multi-colorpicker',
			'colorpickers' => array(
				array(
					'id'      => 'background',
					'name'    => _x(
						'Background',
						'Option: background color for waitlist button',
						'yith-woocommerce-waiting-list'
					),
					'default' => yith_wcwtl_get_proteo_default( 'yith-wcwtl-button-add-background', '#a46497', true ),
				),
				array(
					'id'      => 'text',
					'name'    => _x(
						'Text',
						'Option: color for waitlist button text',
						'yith-woocommerce-waiting-list'
					),
					'default' => yith_wcwtl_get_proteo_default( 'yith-wcwtl-button-add-text-color', '#ffffff', true ),
				),
				array(
					'id'      => 'background-hover',
					'name'    => _x(
						'Background hover',
						'Option: background color for waitlist button on hover status',
						'yith-woocommerce-waiting-list'
					),
					'default' => yith_wcwtl_get_proteo_default(
						'yith-wcwtl-button-add-background-hover',
						'#935386',
						true
					),
				),
				array(
					'id'      => 'text-hover',
					'name'    => _x(
						'Text hover',
						'Option: color for waitlist text button on hover status',
						'yith-woocommerce-waiting-list'
					),
					'default' => yith_wcwtl_get_proteo_default(
						'yith-wcwtl-button-add-text-color-hover',
						'#ffffff',
						true
					),
				),
			),
		),

		'enable-leave-button'                    => array(
			'id'        => 'yith-wcwtl-enable-button-leave',
			'title'     => __( 'Show removal button', 'yith-woocommerce-waiting-list' ),
			'desc'      => __(
				'Enable to allow users to remove their email addresses from the waitlist.',
				'yith-woocommerce-waiting-list'
			),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'yes',
		),

		'waiting-list-button-leave'              => array(
			'id'        => 'yith-wcwtl-button-leave-label',
			'title'     => __( 'Removal button label', 'yith-woocommerce-waiting-list' ),
			'desc'      => __( 'Enter the removal button label.', 'yith-woocommerce-waiting-list' ),
			'type'      => 'yith-field',
			'yith-type' => 'text',
			'default'   => __( 'Leave waitlist', 'yith-woocommerce-waiting-list' ),
			'deps'      => array(
				'id'    => 'yith-wcwtl-enable-button-leave',
				'value' => 'yes',
				'type'  => 'hide',
			),
		),

		'waiting-list-button-leave-colors'       => array(
			'id'           => 'yith-wcwtl-button-leave-colors',
			'title'        => __( 'Removal button colors', 'yith-woocommerce-waiting-list' ),
			'desc'         => __( 'Set the color for the waitlist button.', 'yith-woocommerce-waiting-list' ),
			'type'         => 'yith-field',
			'yith-type'    => 'multi-colorpicker',
			'colorpickers' => array(
				array(
					'id'      => 'background',
					'name'    => _x(
						'Background',
						'Option: background color for remove button',
						'yith-woocommerce-waiting-list'
					),
					'default' => yith_wcwtl_get_proteo_default( 'yith-wcwtl-button-leave-background', '#a46497', true ),
				),
				array(
					'id'      => 'text',
					'name'    => _x(
						'Text',
						'Option: color for the remove button text',
						'yith-woocommerce-waiting-list'
					),
					'default' => yith_wcwtl_get_proteo_default( 'yith-wcwtl-button-leave-text-color', '#ffffff', true ),
				),
				array(
					'id'      => 'background-hover',
					'name'    => _x(
						'Background hover',
						'Option: background color for remove button on hover status',
						'yith-woocommerce-waiting-list'
					),
					'default' => yith_wcwtl_get_proteo_default(
						'yith-wcwtl-button-leave-background-hover',
						'#935386',
						true
					),
				),
				array(
					'id'      => 'text-hover',
					'name'    => _x(
						'Text hover',
						'Option: color for waitlist text button on hover status',
						'yith-woocommerce-waiting-list'
					),
					'default' => yith_wcwtl_get_proteo_default(
						'yith-wcwtl-button-add-text-color-hover',
						'#ffffff',
						true
					),
				),
			),
			'deps'         => array(
				'id'    => 'yith-wcwtl-enable-button-leave',
				'value' => 'yes',
				'type'  => 'hide',
			),
		),

		'subscription-message'                   => array(
			'id'        => 'yith-wcwtl-button-success-msg',
			'title'     => __( 'Subscription message', 'yith-woocommerce-waiting-list' ),
			'type'      => 'yith-field',
			'yith-type' => 'text',
			'class'     => 'yithfullwidth',
			'default'   => __(
				"We added you to this product's waitlist and we'll send you an email when the product is available.",
				'yith-woocommerce-waiting-list'
			),
		),

		'yith-wcwtl-general-options'             => array(
			'type' => 'sectionend',
			'id'   => 'yith-wcwtl-general-options-end',
		),


	),
);

return $tab;
