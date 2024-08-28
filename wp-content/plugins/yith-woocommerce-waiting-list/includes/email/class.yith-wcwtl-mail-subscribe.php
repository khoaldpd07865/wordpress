<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Email subscribe class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL_Mail_Subscribe' ) ) {
	/**
	 * Email Class
	 * Extend WC_Email to send mail to waitlist users
	 *
	 * @class    YITH_WCWTL_Mail_Subscribe
	 * @extends  WC_Email
	 */
	class YITH_WCWTL_Mail_Subscribe extends YITH_WCWTL_Mail {

		/**
		 * Constructor
		 *
		 */
		public function __construct() {
			$this->id = 'yith_waitlist_mail_subscribe';
			/* translators: Title used for custom email in WooCommerce -> Settings -> Emails */
			$this->title       = __( 'YITH Waitlist - Subscription Email', 'yith-woocommerce-waiting-list' );
			$this->description = __( 'When a user subscribes to a waitlist, this email is sent to confirm the subscription.', 'yith-woocommerce-waiting-list' );

			// Call parent constructor.
			parent::__construct();
		}

	}
}

return new YITH_WCWTL_Mail_Subscribe();
