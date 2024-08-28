<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Class YITH_WCWTL_Mailer.
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'YITH_WCWTL_Mailer' ) ) {
	/**
	 * Waiting List Mailer - handles email process.
	 *
	 * @package     YITH WooCommerce Waiting List
	 * @version     1.6.0
	 */
	class YITH_WCWTL_Mailer {

		/**
		 * An array of processed products on execution
		 *
		 * @since 1.8.0
		 * @var array
		 */
		protected $processed_products = array();

		/**
		 * Single instance of the class
		 *
		 * @since 1.9.0
		 * @var YITH_WCWTL_Mailer
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.9.0
		 * @return YITH_WCWTL_Mailer
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * YITH_WCWTL_Importer constructor.
		 *
		 * @since  1.6.0
		 */
		private function __construct() {

			add_action( 'yith_wcwtl_schedule_email_send', array( $this, 'schedule_email_send' ), 10, 2 );
			add_action( 'yith_waitlist_mail_instock_send_completed', array( $this, 'post_send_action' ), 10, 3 );
			
		}


		/**
		 * Send mail to users in waitlist for product when pass from 'out of stock' status to 'in stock'
		 *
		 * @access public
		 * @since  1.0.0
		 * @param integer $product_id   The product ID.
		 * @param string  $stock_status The new product stock status.
		 * @param object  $product      The product object.
		 */
		public function mailout_on_status_change( $product_id, $stock_status, $product ) {

			global $sitepress;

			if ( ! in_array( $stock_status, apply_filters( 'yith_wcwl_allowed_stock_statuses', array( 'instock' ) ) ) || in_array( $product_id, $this->processed_products, true ) ) {
				return;
			}

			$this->processed_products[] = $product_id;

			// get languages active if any.
			$languages = apply_filters( 'wpml_active_languages', null, 'orderby=id&order=asc' );
			if ( is_null( $languages ) ) {
				$languages = array( 'en' => array( 'language_code' => 'en' ) );
			}

			$current_product_id = $product_id;
			$current_product    = $product;
			$product_type       = $product->is_type( 'variation' ) ? 'product_variation' : 'product';

			foreach ( $languages as $language ) {

				if ( ! is_null( $sitepress ) ) {
					$current_product_id = apply_filters( 'wpml_object_id', $product_id, $product_type, false, $language['language_code'] );
					if ( is_null( $current_product_id ) ) {
						continue;
					}
					$current_product = wc_get_product( $current_product_id );
					$sitepress->switch_lang( $language['language_code'], false );
				}
				/**
				 * DO_ACTION: yith_wcwtl_schedule_email_send
				 *
				 * This hook is used by the plugin to notify admin that a customer subscribed a list
				 *
				 * @param string $user_email the user email
				 * @param int $product_id product ID
				 */
				do_action( 'yith_wcwtl_schedule_email_send', $current_product );
			}

			// reset to default language.
			! is_null( $sitepress ) && $sitepress->switch_lang( $sitepress->get_default_language(), false );

			if ( class_exists( 'YITH_WCWTL_Admin' ) ) {
				add_filter( 'redirect_post_location', array( YITH_WCWTL_Admin(), 'add_query_to_redirect_location' ), 20, 2 );
			}
		}

		/**
		 * Schedule email send action
		 *
		 * @param WC_Product|integer $product Current product object or a product ID.
		 * @param array              $users An optional array of customers email.
		 *
		 * @return void;
		 */
		public function schedule_email_send( $product, $users = array() ) {

			if ( ! ( $product instanceof WC_Product ) ) {
				$product = wc_get_product( intval( $product ) );
			}

			if ( empty( $product ) ) {
				return;
			}

			if ( empty( $users ) ) {
				// get waitlist users for product.
				$users = yith_waitlist_get_registered_users( $product );
				if ( yith_waitlist_is_limited_stock_email() ) {
					/**
					 * APPLY_FILTERS: yith_wcwtl_limited_stock_email_quantity
					 *
					 * Filters the number of email scheduled to be sent
					 *
					 * @param int $stock_quantity Product stock quantity
					 * @param array $users List of users
					 *
					 * @return int
					 */
					$qty   = apply_filters( 'yith_wcwtl_limited_stock_email_quantity', $product->get_stock_quantity(), $users );
					$users = array_slice( $users, 0, $qty, true );
				}
			}

			// return if list is empty.
			if ( empty( $users ) ) {
				return;
			}

			$group              = 'ywcwtl_' . $product->get_id();
			$has_hook_scheduled = as_next_scheduled_action( 'send_yith_waitlist_mail_instock', null, $group );
			if ( $has_hook_scheduled ) {
				as_unschedule_all_actions( 'send_yith_waitlist_mail_instock', null, $group );
			}

			// Create users group.
			/**
			 * APPLY_FILTERS: yith_wcwtl_scheduled_email_chunk
			 *
			 * Filters how many users notify at time
			 *
			 * @param int $value Number of users
			 *
			 * @return int
			 */
			$users         = array_chunk( $users, apply_filters( 'yith_wcwtl_scheduled_email_chunk', 50 ) );
			$schedule_time = time();

			do {
				$temp_users = array_shift( $users );
				as_schedule_single_action(
					$schedule_time,
					'send_yith_waitlist_mail_instock',
					array(
						'users'   => $temp_users,
						'product' => $product->get_id(),
					),
					$group
				);
				$schedule_time += 2 * apply_filters( 'yith_wcwtl_schedule_time', MINUTE_IN_SECONDS );

			} while ( ! empty( $users ) );
		}

		/**
		 * Post send email actions
		 *
		 * @since 1.9.0
		 * @param array      $users An array of customer users.
		 * @param WC_Product $product The product object.
		 * @param WC_Email   $email The email object.
		 * @return void
		 */
		public function post_send_action( $users, $product, $email ) {
			$response = apply_filters( $email->id . '_send_response', null );

			if ( 'yes' !== get_option( 'yith-wcwtl-keep-after-email', 'no' ) && $response ) {
				yith_waitlist_unregister_users( $users, $product );
			}
		}
	}
}

/**
 * Unique access to instance of YITH_WCWTL_Mailer class
 *
 * @since 1.0.0
 * @return YITH_WCWTL_Mailer
 */
function YITH_WCWTL_Mailer() { // phpcs:ignore WordPress.NamingConventions
	return YITH_WCWTL_Mailer::get_instance();
}
