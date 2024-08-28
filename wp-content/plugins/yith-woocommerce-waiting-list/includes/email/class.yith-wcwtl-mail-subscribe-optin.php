<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Email subscribe optin check class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL_Mail_Subscribe_Optin' ) ) {
	/**
	 * Email Class
	 * Extend WC_Email to send mail to waitlist users
	 *
	 * @class    YITH_WCWTL_Mail_Subscribe
	 * @extends  WC_Email
	 */
	class YITH_WCWTL_Mail_Subscribe_Optin extends YITH_WCWTL_Mail {

		/**
		 * Constructor
		 *
		 */
		public function __construct() {

			$this->id = 'yith_waitlist_mail_subscribe_optin';
			/* translators: Title used for custom email in WooCommerce -> Settings -> Emails */
			$this->title       = __( 'YITH Waitlist - Request Confirmation', 'yith-woocommerce-waiting-list' );
			$this->description = __( 'When a user subscribes to a waitlist, this email is sent to request confirmation.', 'yith-woocommerce-waiting-list' );

			$this->template_base  = YITH_WCWTL_TEMPLATE_PATH . '/email/';
			$this->template_html  = 'yith-wcwtl-mail-subscribe-optin.php';
			$this->template_plain = 'plain/yith-wcwtl-mail-subscribe-optin.php';

			$this->customer_email = true;

			// Call parent constructor.
			parent::__construct();
		}
	}
}

return new YITH_WCWTL_Mail_Subscribe_Optin();
