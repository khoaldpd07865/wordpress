<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Email Admin class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL_Mail_Admin' ) ) {
	/**
	 * Email Class
	 * Extend WC_Email to send mail to admin when an user subscribe a waiting list
	 *
	 * @class    YITH_WCWTL_Mail_Admin
	 * @extends  WC_Email
	 */
	class YITH_WCWTL_Mail_Admin extends YITH_WCWTL_Mail {

		/**
		 * Constructor
		 *
		 */
		public function __construct() {

			$this->id = 'yith_waitlist_mail_admin';
			/* translators: Title used for custom email in WooCommerce -> Settings -> Emails */
			$this->title = __( 'YITH Waitlist - User Subscribed', 'yith-woocommerce-waiting-list' );

			$this->customer_email = false;

			// Call parent constructor.
			parent::__construct();
		}
	}
}

return new YITH_WCWTL_Mail_Admin();
