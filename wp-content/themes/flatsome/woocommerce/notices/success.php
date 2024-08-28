<?php
/**
 * Show messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/success.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see              https://docs.woocommerce.com/document/template-structure/
 * @package          WooCommerce/Templates
 * @version          8.5.0
 * @flatsome-version 3.18.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $notices ) {
	return;
}

if ( fl_woocommerce_version_check( '8.5' ) && apply_filters( 'experimental_flatsome_woocommerce_blockify', false ) ) :
	foreach ( $notices as $notice ) :
		?>
		<div class="wc-block-components-notice-banner is-success"<?php echo wc_get_notice_data_attr( $notice ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> role="alert">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
				<path d="M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z"></path>
			</svg>
			<div class="wc-block-components-notice-banner__content">
				<?php echo wc_kses_notice( $notice['notice'] ); ?>
			</div>
		</div>
		<?php
	endforeach;
else :
	foreach ( $notices as $notice ) :
		?>
		<div class="woocommerce-message message-wrapper"<?php echo wc_get_notice_data_attr( $notice ); ?> role="alert">
			<div class="message-container container success-color medium-text-center">
				<?php echo get_flatsome_icon( 'icon-checkmark' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo wc_kses_notice( $notice['notice'] ); ?>
			</div>
		</div>
		<?php
	endforeach;
endif;
