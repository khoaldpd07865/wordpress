<?php // phpcs:ignore WordPress.NamingConventions
/**
 * My Waitlist
 *
 * Shows subscribed waitlist on the account page
 *
 * @author        YITH <plugins@yithemes.com>
 * @package       YITH\WaitingList
 * @version       1.1.1
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit; // Exit if accessed directly.
}

$user_id  = get_current_user_id();
$products = yith_get_user_waitlists( $user_id );

$table_product_column   = esc_html__( 'Product', 'yith-woocommerce-waiting-list' );
$table_variation_column = esc_html__( 'Variation', 'yith-woocommerce-waiting-list' );
$table_stock_column     = esc_html__( 'Stock Status', 'yith-woocommerce-waiting-list' );
$show_leave_button      = get_option( 'yith-wcwtl-enable-button-leave', 'yes' );

/**
 * APPLY_FILTERS: yith_waitlist_my_account_my_waitlist_title
 *
 * Filters the title used for waiting list table (My Account area)
 *
 * @param string $title The title
 *
 * @return string
 */

echo '<h2 class="waitlist-title-section">' . esc_html( apply_filters( 'yith_waitlist_my_account_my_waitlist_title', __( 'Waitlists', 'yith-woocommerce-waiting-list' ) ) ) . '</h2>';

if ( $products ) : ?>

	<table class="shop_table shop_table_responsive my_account_waitlist my_account_orders">

		<thead>
		<tr>
			<th class="waitlist-product" colspan="2"><span class="nobr"><?php echo $table_product_column; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></th>
			<th class="waitlist-product-variation"><span class="nobr"><?php echo $table_variation_column; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></th>
			<th class="waitlist-product-status"><span class="nobr"><?php echo $table_stock_column; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></th>
			<?php if ( yith_plugin_fw_is_true( $show_leave_button ) ) : ?>
				<th class="waitlist-actions">&nbsp;</th>
			<?php endif; ?>
		</tr>
		</thead>

		<tbody>
		<?php
		foreach ( $products as $product_id ) {

			$product = wc_get_product( $product_id );

			if ( empty( $product ) ) {
				continue;
			}

			$product_link  = $product->get_permalink();
			$product_image = $product->get_image( 'shop_thumbnail' );
			$product_title = $product->get_title();

			?>
			<tr class="waitlist">
				<td class="waitlist-product" data-title="<?php echo $table_product_column; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" colspan="2">
					<?php if ( $product->is_visible() ) : ?>
						<a class="product-info" href="<?php echo esc_url( $product_link ); ?>">
							<span class="product-image">
								<?php echo $product_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
							<span class="product-title">
								<?php echo esc_html( $product_title ); ?>
							</span>
						</a>
					<?php else : ?>
						<span class="product-image">
							<?php echo $product_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
						<span class="product-title">
							<?php echo esc_html( $product_title ); ?>
						</span>
					<?php endif; ?>
				</td>
				<td class="waitlist-product-variation" data-title="<?php echo $table_variation_column; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
					<?php
					if ( $product->is_type( 'variation' ) ) {

						$variations = $product->get_variation_attributes();

						$html = '<ul>';

						foreach ( $variations as $key => $value ) {
							$key   = str_replace( 'attribute_pa_', '', urldecode( $key ) );
							$key   = str_replace( 'attribute_', '', $key );
							$html .= '<li>' . ucfirst( $key ) . ': ' . urldecode( $value ) . '</li>';
						}

						$html .= '</ul>';

						echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						echo '-';
					}
					?>
				</td>
				<td class="waitlist-product-status" data-title="<?php echo $table_stock_column; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
					<?php
					$status = $product->get_availability();//phpcs:ignore
					echo '<span class="' . esc_attr( $status['class'] ) . '">' . esc_html( $status['availability'] ) . '</span>';
					?>
				</td>
				<?php if ( yith_plugin_fw_is_true( $show_leave_button ) ) : ?>
					<td class="waitlist-actions" data-title="&nbsp;">
						<?php
						// set url.
						$url = add_query_arg( '_yith_wcwtl_users_list', $product->get_id() );
						$url = wp_nonce_url( $url, 'action_waitlist' );
						$url = add_query_arg( '_yith_wcwtl_users_list-action', 'leave', $url );


							/**
							 * APPLY_FILTERS: yith_waitlist_my_account_leave_label
							 *
							 * Filters the label used for the link which allows customers unsubscribe the list in My Account area
							 *
							 * @param string $label The link label
							 *
							 * @return string
							 */
							echo '<a href="' . esc_url( $url ) . '" class="button leave-waitlist">' . esc_html( apply_filters( 'yith_waitlist_my_account_leave_label', get_option( 'yith-wcwtl-button-leave-label' ) ) ) . '</a>';
						?>
					</td>
				<?php endif; ?>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>

<?php else : ?>
	<p>
		<?php
		/**
		 * APPLY_FILTERS: yith_waitlist_my_account_my_waitlist_empty
		 *
		 * Filters the message shown when list is empty in My Account area
		 *
		 * @param string $message Message
		 *
		 * @return string
		 */
		echo esc_html( apply_filters( 'yith_waitlist_my_account_my_waitlist_empty', __( "You haven't subscribed to a waitlist.", 'yith-woocommerce-waiting-list' ) ) );
		?>
	</p>
<?php endif; ?>
