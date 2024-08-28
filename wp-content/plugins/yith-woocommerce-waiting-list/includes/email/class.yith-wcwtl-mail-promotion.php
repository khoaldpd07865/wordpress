<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Promotion email class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 * @version 2.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL_Mail_Promotion' ) ) {
	/**
	 * Email Class
	 * Extend WC_Email to send mail to waitlist users
	 *
	 * @class    YITH_WCWTL_Mail_Promotion
	 * @extends  WC_Email
	 */
	class YITH_WCWTL_Mail_Promotion extends YITH_WCWTL_Mail {

		/**
		 * Constructor
		 *
		 */
		public function __construct() {

			$this->id = 'yith_waitlist_mail_promotion';
			/* translators: Title used for custom email in WooCommerce -> Settings -> Emails */
			$this->title = __( 'YITH Waitlist - Promotional', 'yith-woocommerce-waiting-list' );

			$this->customer_email = true;
			// Call parent constructor.
			parent::__construct();
		}

	}
}

return new YITH_WCWTL_Mail_Promotion();
