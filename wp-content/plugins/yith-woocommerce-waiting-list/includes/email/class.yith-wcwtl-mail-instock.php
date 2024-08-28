<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Email instock class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL_Mail_Instock' ) ) {
	/**
	 * Email Class
	 * Extend WC_Email to send mail to waitlist users
	 *
	 * @class    YITH_WCWTL_Mail_Instock
	 * @extends  WC_Email
	 */
	class YITH_WCWTL_Mail_Instock extends YITH_WCWTL_Mail {

		/**
		 * Constructor
		 *
		 */
		public function __construct() {

			$this->id          = 'yith_waitlist_mail_instock';
			/* translators: Title used for custom email in WooCommerce -> Settings -> Emails */
			$this->title       = __( 'YITH Waitlist - Product Back in Stock', 'yith-woocommerce-waiting-list' );
			$this->description = __( 'When a product is back in stock, this email is sent to all the users registered on its waitlist.', 'yith-woocommerce-waiting-list' );

			$this->template_base  = YITH_WCWTL_TEMPLATE_PATH . '/email/';
			$this->template_html  = 'yith-wcwtl-mail-instock.php';
			$this->template_plain = 'plain/yith-wcwtl-mail-instock.php';

			$this->customer_email = true;

			$this->init_email_attributes();


			// Triggers for this email.
			add_action( 'send_yith_waitlist_mail_instock_notification', array( $this, 'trigger' ), 10, 2 );

			add_filter( 'yith_wcwtl_email_custom_placeholders', array( $this, 'email_add_placeholders' ), 10, 3 );

			// Call parent constructor.
			parent::__construct();


		}

		/**
		 * Load email attributes
		 *
		 * @since  1.5.5
		 */
		public function init_email_attributes() {
			$this->heading      = __( 'The wait is over: a product you like is back in stock', 'yith-woocommerce-waiting-list' );
			$this->subject      = __( 'A product you are waiting for is back in stock', 'yith-woocommerce-waiting-list' );
			$this->mail_content = nl2br( __( "Hi {username},\n We're happy to let you know that the {product_title_with_link} on your waitlist just came back in stock!\n Because you asked, we wanted to make sure you're the first one to know, but we can't guarantee the item will remain available for long.\n Hit the link below to get it before it's gone!{product_info}\n\n Best regards,\n{blogname}", 'yith-woocommerce-waiting-list' ) );
		}

		public function init_form_fields() {

			parent::init_form_fields();

			$this->form_fields['analytics_tracking'] = array(
				'title'       => sprintf( __( '%sAnalytics tracking parameters Premium%s', 'yith-woocommerce-waiting-list' ), '<span class="field-title">', '</span>' ),
				'type'        => 'textarea',
				// translators: %s stand for the default email content text.
				'default'     => '',
				'description' => __( 'Enter the parameters to add to the product link. Enter each parameter on a new line in the format “utm_source=waitlist;” (without quotes). You can also use {product_id} and {product_sku} as placeholders for the ID and SKU of the product', 'yith-woocommerce-waiting-list' )
			);



		}

		/**
		 * Email Trigger
		 *
		 * @since 1.0.0
		 * @param array $users An array of users.
		 * @param array $product_id The product ID.
		 */
		public function trigger( $users, $product_id  ) {
			$this->init_email_attributes();
			$this->init_form_fields();
			$this->init_settings();

			parent::trigger( $users, $product_id );
		}

		/**
		 * Add custom email placeholder to default array
		 *
		 * @since  1.5.0
		 * @param array        $placeholders The email placeholders.
		 * @param object       $product The product object instance.
		 * @param array|string $users An array of users.
		 * @return array
		 */
		public function email_add_placeholders( $placeholders, $product, $users ) {
			/**
			 * APPLY_FILTERS: yith_waitlist_link_label_instock_email
			 *
			 * Filters label used for link inside in-stock email.
			 *
			 * @param string $label Label
			 * @param WC_Product $product Product
			 *
			 * @return string
			 */
			$link_label = apply_filters( 'yith_waitlist_link_label_instock_email', __( 'link', 'yith-woocommerce-waiting-list' ), $product );
			$link       = ( $this->get_email_type() === 'html' ) ? '<a href="' . $product->get_permalink() . '">' . $link_label . '</a>' : $product->get_permalink();
			// let third part filter link.
			/**
			 * APPLY_FILTERS: yith_waitlist_link_html_instock_email
			 *
			 * Filters HTML for the link used inside in-stock email.
			 *
			 * @param string $link Link
			 * @param WC_Product $product Product
			 * @param string $type Email type
			 *
			 * @return string
			 */
			$link = apply_filters( 'yith_waitlist_link_html_instock_email', $link, $product, $this->get_email_type() );

			$placeholders['{product_link}'] = $link;

			return $placeholders;
		}
	}
}

return new YITH_WCWTL_Mail_Instock();
