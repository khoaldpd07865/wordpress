<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Admin View: Settings
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="yith-plugin-fw yit-admin-panel-container" id="yith-wcwtl-emails-wrapper">
	<div id="yith-wcwtl-table-emails">
		<div class="heading-table yith-wcwtl-row">
			<span class="yith-wcwtl-column email">
			<?php
			esc_html_e(
				'Email',
				'yith-woocommerce-waiting-list'
			);
			?>
					</span>
			<span class="yith-wcwtl-column recipient">
			<?php
			esc_html_e(
				'Recipient',
				'yith-woocommerce-waiting-list'
			);
			?>
					</span>
			<span class="yith-wcwtl-column action"></span>
			<span class="yith-wcwtl-column status">
			<?php
			_ex(
				'Active',
				'[ADMIN] Column name table emails',
				'yith-woocommerce-waiting-list'
			);
			?>
					</span>
		</div>
		<div class="content-table">
			<?php foreach ( $emails_table as $email_key => $email ) : ?>
				<?php $url = YITH_WCWTL_Admin()->build_single_email_settings_url( $email_key ); ?>
				<div class="yith-wcwtl-row <?php echo 'YITH_WCWTL_Mail_Instock' !== $email_key ? 'yith-premium' : ''; ?>">
					<span class="yith-wcwtl-column email">
						<span class="email-title"> <?php echo esc_html( $email['title'] ); ?> </span>
						<?php echo wc_help_tip( $email['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php if ( 'YITH_WCWTL_Mail_Instock' !== $email_key ) : ?>
							<span class="premium-badge">
							<?php
							esc_html_e(
								'Premium',
								'yith-woocommerce-waiting-list'
							);
							?>
									</span>
						<?php endif; ?>
					</span>
					<span class="yith-wcwtl-column recipient">
						<?php
						echo esc_html( $email['recipient'] );

						?>
					</span>
					<span class="yith-wcwtl-column action">
						<?php
						if ( 'YITH_WCWTL_Mail_Instock' === $email_key ) :
							yith_plugin_fw_get_component(
								array(
									'title'  => __( 'Edit', 'yith-woocommerce-waiting-list' ),
									'type'   => 'action-button',
									'action' => 'edit',
									'icon'   => 'edit',
									'url'    => esc_url( $url ),
									'data'   => array(
										'target' => $email_key,
									),
									'class'  => 'toggle-settings',
								)
							);
						endif;
						?>
					</span>
					<span class="yith-wcwtl-column status">
						 <?php
							$email_status = array(
								'id'      => 'yith-wcwtl-email-status',
								'type'    => 'onoff',
								'default' => 'yes',
								'value'   => 'YITH_WCWTL_Mail_Instock' === $email_key ? $email['enable'] : 'no',
								'data'    => array(
									'email_key' => $email_key,
                                    'nonce'     => sanitize_key( wp_create_nonce( 'yith_wcwtl_email_status' ) ),
								),
							);

							yith_plugin_fw_get_field( $email_status, true );
							?>
					</span>
                    <div class="email-settings" id="<?php esc_attr_e( $email_key ); ?>">
                    <?php
						if ( 'YITH_WCWTL_Mail_Instock' === $email_key ) :
							do_action( 'yith_wcwtl_print_email_settings', $email_key );
						endif;
						?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

</div>
