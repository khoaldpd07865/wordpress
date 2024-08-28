<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Waitlist form on single product page
 *
 * @author        YITH <plugins@yithemes.com>
 * @package       YITH\WaitingList
 * @version       1.1.1
 * @var WC_Product $product The form product.
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit; // Exit if accessed directly.
}

$user     = wp_get_current_user();
$waitlist = yith_waitlist_get( $product_id );

$url                 = wp_doing_ajax() ? add_query_arg(
	'_yith_wcwtl_users_list',
	$product_id,
	$product->get_permalink()
) : add_query_arg( '_yith_wcwtl_users_list', $product_id );
$url = add_query_arg( array(
	'_yith_wcwtl_users_list-action' => 'register',
	'nonce'                         => sanitize_key( wp_create_nonce( 'yith_wcwtl_submit_waitlist' ) )
), $url );
$email               = isset( $_POST['yith-wcwtl-email'] ) ? sanitize_email( wp_unslash( $_POST['yith-wcwtl-email'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
$email_placeholder   = get_option(
	'yith-wcwtl-form-placeholder',
	__( 'Enter your email address', 'yith-woocommerce-waiting-list' )
);
$show_leave_button   = get_option( 'yith-wcwtl-enable-button-leave', 'yes' );
$is_user_in_waitlist = is_array( $waitlist ) && $user->exists() && isset( $user->user_email ) && yith_waitlist_user_is_register(
	$user->user_email,
	$waitlist
);

?>

<div id="yith-wcwtl-output" class="<?php echo $is_user_in_waitlist ? 'subscribed' : ''; ?>">

	<?php if ( $is_user_in_waitlist ) : ?>
		<p style="margin-bottom: 20px"><?php echo esc_html( get_option( 'yith-wcwtl-button-success-msg' ) ); ?></p>
	<?php elseif ( $message ) : ?>
		<div class="yith-wcwtl-msg"><?php echo wp_kses_post( wpautop( $message, true ) ); ?></div>
	<?php endif; ?>

	<?php if ( ! $product->is_type( 'variation' ) && ! $user->exists() ) : ?>

		<form method="post" action="<?php echo esc_url( $url ); ?>">
			<label for="yith-wcwtl-email">
				<input type="email" name="yith-wcwtl-email" id="yith-wcwtl-email"  value = "<?php echo esc_attr( $email ); ?>" placeholder="<?php echo esc_attr( $email_placeholder ); ?>"/>
			</label>
			<input type="submit" value="<?php echo esc_html( $label_button_add ); ?>" class="button alt yith-wcwtl-submit"/>
			<?php
			/**
			 * DO_ACTION: yith_wcwtl_after_submit
			 *
			 * Use this hook to process code or output additional HTML after submit button in waiting list form
			 */
			do_action( 'yith_wcwtl_after_submit' );
			?>
			<?php echo yith_waitlist_policy_checkbox(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</form>

	<?php elseif ( ! $user->exists() ) : ?>
		<label for="yith-wcwtl-email">
			<input type="email" name="yith-wcwtl-email" id="yith-wcwtl-email" class="wcwtl-variation" value="<?php echo esc_attr( $email ); ?>" placeholder="<?php echo esc_attr( $email_placeholder ); ?>"/>
		</label>
		<?php do_action( 'yith_wcwtl_before_submit' ); ?>
		<a href="<?php echo esc_url( $url ); ?>" class="button alt yith-wcwtl-submit"><?php echo esc_html( $label_button_add ); ?></a>
		<?php do_action( 'yith_wcwtl_after_submit' ); ?>
		<?php echo yith_waitlist_policy_checkbox(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<?php elseif ( $is_user_in_waitlist ) : ?>

		<?php if ( 'yes' === $show_leave_button ) : ?>
			<?php
			// change action arg.
			$url = add_query_arg( '_yith_wcwtl_users_list-action', 'leave', $url );
			?>
			<a href="<?php echo esc_url( $url ); ?>" class="button button-leave yith-wcwtl-submit alt"><?php echo esc_html( $label_button_leave ); ?></a>
		<?php endif; ?>


	<?php else : ?>

		<?php echo yith_waitlist_policy_checkbox(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php do_action( 'yith_wcwtl_before_submit' ); ?>
		<a class="yith-wcwtl-submit button" href="<?php echo esc_url( $url ); ?>" class="button alt"><?php echo esc_html( $label_button_add ); ?></a>
		<?php do_action( 'yith_wcwtl_after_submit' ); ?>

	<?php endif; ?>

	<div class="yith-wcwtl-notices"></div>
</div>
