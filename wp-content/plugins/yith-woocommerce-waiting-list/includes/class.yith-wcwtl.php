<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Main class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL' ) ) {
	/**
	 * YITH WooCommerce Waiting List
	 *
	 * @since 1.0.0
	 */
	class YITH_WCWTL {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 * @var YITH_WCWTL
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @since 1.0.0
		 * @var string|int $version Class version
		 */
		public $version = YITH_WCWTL_VERSION;

		/**
		 * Plugin emails array
		 *
		 * @since 1.0.0
		 * @var array $emails
		 */
		public $emails = array();


		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_WCWTL Class instance
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			$this->init_plugin_emails_array();
			$this->load_requested();

			// Load Plugin Framework.
			add_action( 'after_setup_theme', array( $this, 'plugin_fw_loader' ), 1 );

			// Email actions.
			add_filter( 'woocommerce_email_classes', array( $this, 'add_woocommerce_emails' ) );
			add_action( 'woocommerce_init', array( $this, 'load_wc_mailer' ) );
			add_filter( 'woocommerce_email_styles', array( $this, 'custom_style_email' ), 10, 2 );

			// Register plugin account endpoint.
			add_filter( 'init', array( $this, 'add_endpoint' ), 0 );

			// gdpr actions.
			add_filter( 'wp_privacy_personal_data_exporters', array( $this, 'register_exporters' ) );
			add_filter( 'wp_privacy_personal_data_erasers', array( $this, 'register_erasers' ) );

			// Remove customer from list if the order is created.
			add_action(
				'woocommerce_checkout_order_processed',
				array( $this, 'remove_email_on_order_processed' ),
				10,
				3
			);

			/* HPOS Support */
			add_action( 'before_woocommerce_init', array( $this, 'declare_wc_features_support' ) );
		}

		/**
		 * Include and load requested file/class
		 *
		 * @return void
		 * @since 1.9.0
		 */
		protected function load_requested() {

			// Class admin.
			if ( $this->is_admin() ) {
				// required class.
				include_once 'class.yith-wcwtl-admin.php';
				YITH_WCWTL_Admin();
			}

			if ( 'yes' === get_option( 'yith-wcwtl-enable', 'yes' ) ) {

				include_once 'class.yith-wcwtl-mailer.php';
				YITH_WCWTL_Mailer();

				if ( $this->is_admin() ) {
					include_once 'class.yith-wcwtl-meta.php';
					YITH_WCWTL_Meta();

				} elseif ( $this->load_frontend() ) {
					// required class.
					include_once 'class.yith-wcwtl-frontend.php';
					// Class frontend.
					YITH_WCWTL_Frontend();
				}
			}
		}

		/**
		 * Init an array of plugin emails
		 *
		 * @since  1.5.0
		 */
		public function init_plugin_emails_array() {

			if ( isset( $_GET['page'] ) && 'wc-settings' === $_GET['page'] ) { //phpcs:ignore
				/**
				 * APPLY_FILTERS: yith_wcwtl_plugin_emails_array
				 *
				 * Filters list of allowed email types managed by the plugin
				 *
				 * @param array $emails List of email types
				 *
				 * @return array
				 */
				$this->emails = apply_filters(
					'yith_wcwtl_plugin_emails_array',
					array(
						'YITH_WCWTL_Mail_Instock',
					)
				);
			} else {
				$this->emails = apply_filters(
					'yith_wcwtl_plugin_emails_array',
					array(
						'YITH_WCWTL_Mail_Instock',
						'YITH_WCWTL_Mail_Subscribe',
						'YITH_WCWTL_Mail_Admin',
						'YITH_WCWTL_Mail_Subscribe_Optin',
						'YITH_WCWTL_Mail_Promotion',
					)
				);
			}

		}

		/**
		 * Get plugin emails array
		 *
		 * @return array
		 * @since  1.0.0
		 */
		public function get_emails() {
			return $this->emails;
		}

		/**
		 * Load Plugin Framework
		 *
		 * @return void
		 * @since  1.0
		 * @access public
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Check if is admin
		 *
		 * @return boolean
		 * @since  1.1.0
		 * @access public
		 */
		public function is_admin() {
			$context_check    = isset( $_REQUEST['context'] ) && 'frontend' === sanitize_text_field( wp_unslash( $_REQUEST['context'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$actions_to_check = apply_filters(
				'yith_wcwtl_actions_to_check_admin',
				array(
					'jckqv',
				)
			);
			$action_check     = isset( $_REQUEST['action'] ) && in_array( //phpcs:ignore
				sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ),//phpcs:ignore
				$actions_to_check,
				true
			); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$is_admin         = is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX && ( $context_check || $action_check ) );

			/**
			 * APPLY_FILTERS: yith_wcwtl_check_is_admin
			 *
			 * Detect if a function is performed on admin side.
			 *
			 * @param bool $is_admin Is admin
			 *
			 * @return bool
			 */
			return apply_filters( 'yith_wcwtl_check_is_admin', $is_admin );
		}

		/**
		 * Check to load frontend class
		 *
		 * @return boolean
		 * @since  1.2.0
		 */
		public function load_frontend() {
			/**
			 * APPLY_FILTERS: yith_wcwtl_check_load_frontend
			 *
			 * Detects if enable plugin functions on frontend.
			 *
			 * @param bool $enable Enable functions
			 *
			 * @return bool
			 */
			return apply_filters(
				'yith_wcwtl_check_load_frontend',
				get_option( 'yith-wcwtl-enable', 'yes' ) === 'yes'
			);
		}

		/**
		 * Filters woocommerce available mails, to add waitlist related ones
		 *
		 * @param array $emails An array of registered WooCommerce emails.
		 *
		 * @return array
		 * @since 1.0
		 */
		public function add_woocommerce_emails( $emails ) {
			// load common class.
			include 'email/class.yith-wcwtl-mail.php';

			foreach ( $this->emails as $email ) {
				$file_name        = strtolower( str_replace( '_', '-', $email ) );
				$emails[ $email ] = include "email/class.{$file_name}.php";
			}

			return $emails;
		}

		/**
		 * Loads WC Mailer when needed
		 *
		 * @return void
		 * @since  1.0
		 */
		public function load_wc_mailer() {
			foreach ( $this->emails as $email ) {
				$email = str_replace( 'wcwtl', 'waitlist', strtolower( $email ) );
				add_action( 'send_' . $email, array( 'WC_Emails', 'send_transactional_email' ), 10, 2 );
			}
		}

		/**
		 * Add waiting list account endpoint
		 *
		 * @since  1.1.2
		 * @access public
		 */
		public function add_endpoint() {
			WC()->query->query_vars['waiting-list'] = get_option(
				'woocommerce_myaccount_waiting_list_endpoint',
				'waiting-list'
			);
		}

		/**
		 * Register exporter for GDPR compliance
		 *
		 * @param array $exporters List of exporter callbacks.
		 *
		 * @return array
		 * @since  1.5.0
		 */
		public function register_exporters( $exporters = array() ) {
			$exporters['yith-wcwtl-customer-data'] = array(
				'exporter_friendly_name' => __( 'Waitlist Data', 'yith-woocommerce-waiting-list' ),
				'callback'               => array( 'YITH_WCWTL', 'customer_data_exporter' ),
			);

			return $exporters;
		}

		/**
		 * GDPR exporter callback
		 *
		 * @param string $email_address The user email address.
		 * @param int    $page Page.
		 *
		 * @return array
		 * @since  1.5.0
		 */
		public static function customer_data_exporter( $email_address, $page ) {
			$user           = get_user_by( 'email', $email_address );
			$data_to_export = array();
			$products_list  = array();
			// get products list if any.
			if ( $user instanceof WP_User ) {
				$products_list = yith_get_user_waitlists( $user->ID );
			}

			if ( ! empty( $products_list ) ) {

				$products = array();
				foreach ( $products_list as $product_id ) {
					$product = wc_get_product( $product_id );
					if ( isset( $product ) ) {
						$products[] = $product->get_name();
					}
				}

				$data_to_export[] = array(
					'group_id'    => 'yith_wcwtl_data',
					'group_label' => __( 'Waitlist Data', 'yith-woocommerce-waiting-list' ),
					'item_id'     => 'waiting-list',
					'data'        => array(
						array(
							'name'  => __( 'Waitlist Subscriptions', 'yith-woocommerce-waiting-list' ),
							'value' => implode( ', ', $products ),
						),
					),
				);
			}

			return array(
				'data' => $data_to_export,
				'done' => true,
			);
		}

		/**
		 * Register ereaser for GDPR compliance
		 *
		 * @param array $erasers List of erasers callbacks.
		 *
		 * @return array
		 * @since  1.5.0
		 */
		public function register_erasers( $erasers = array() ) {
			$erasers['yith-wcwtl-customer-data'] = array(
				'eraser_friendly_name' => __( 'Waitlist Data', 'yith-woocommerce-waiting-list' ),
				'callback'             => array( 'YITH_WCWTL', 'customer_data_ereaser' ),
			);

			return $erasers;
		}

		/**
		 * GDPR ereaser callback
		 *
		 * @param string $user_email The user email.
		 * @param int    $page Page count.
		 *
		 * @return array
		 * @since  1.5.0
		 */
		public static function customer_data_ereaser( $user_email, $page ) {
			$response = array(
				'items_removed'  => false,
				'items_retained' => false,
				'messages'       => array(),
				'done'           => true,
			);

			$user = get_user_by(
				'email',
				$user_email
			); // Check if user has an ID in the DB to load stored personal data.
			if ( ! $user instanceof WP_User ) {
				return $response;
			}

			$products_list = yith_get_user_waitlists( $user->ID );
			foreach ( $products_list as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( $product && yith_waitlist_unregister_user( $user_email, $product ) ) {
					$response['messages'][] = sprintf(
					// translators: %s stands for product name.
						__(
							'Removed customer from the waitlist for "%s".',
							'woocommerce'
						),
						$product->get_name()
					);
					$response['items_removed'] = true;
				}
			}

			return $response;
		}


		/**
		 * Once an order is created at checkout remove the customer user from waiting list for order products if any.
		 *
		 * @param integer  $order_id The order ID.
		 * @param array    $posted_data An array of posted date from the checkout.
		 * @param WC_Order $order The order object.
		 *
		 * @return void
		 * @since 1.22.0
		 */
		public function remove_email_on_order_processed( $order_id, $posted_data, $order ) {
			$customer_id = $order->get_customer_id();
			$customer    = $customer_id ? get_user_by( 'id', $customer_id ) : false;
			if ( ! $customer ) {
				return;
			}

			foreach ( $order->get_items() as $item ) {
				$product = method_exists( $item, 'get_product' ) ? $item->get_product() : false;
				if ( ! $product ) {
					continue;
				}

				yith_waitlist_unregister_user( $customer->user_email, $product );
			}
		}

		/**
		 * Add custom CSS for Waiting List emails
		 *
		 * @param string $css css code for emails.
		 * @param string $email WooCommerce email.
		 *
		 * @return string
		 */
		public function custom_style_email( $css, $email ) {
			if ( in_array( get_class( $email ), $this->emails ) ) { //phpcs:ignore
				$css .= "
				.im{
					color: inherit!important;
				}
				
				
				#template_header{
					background-color: #075981;
				}
				
				#template_header h1{
					font-size: 24px!important;
                    text-align: center!important;
				}
				
				#header_wrapper{
					padding: 40px 30px!important;
				}
				
				table#template_container {
				    background-color: #fff;
				    border: 1px solid #dedede;
				   /* box-shadow: 0 -2px 8px 0 rgb(0 0 0 / 10%);*/
				    box-shadow: none;
				    border-bottom: none!important;
				}
				
				table#template_footer {
				   /* box-shadow: 0 2px 8px 0 rgb(0 0 0 / 10%)!important; */
				    border: 1px solid #dedede;
				    border-top: none;
				    background-color: #fff;
				    box-sizing: content-box;
				    top: -1px;
				    position: relative;
				}
				
				table#template_footer table{
					padding: 0 40px;
				}
				
				table#template_footer:before {
				    content: '';
				    display: block;
				    position: absolute;
				    width: calc( 100% - 50px );
				    height: 1px;
				    background-color: #ededed;
				    left: 25px;
				    top: 0;
				}
				
			
				.yith-wcwtl-product-info{	
				    display: flex;
                    align-items: center;		
                    background-color: #fafafa;
                    padding: 30px;
                    margin: 35px 0;
                }
                
                .yith-wcwtl-product-info.promotional{
                    background-color: #F0F9FA;
                }
                
                .yith-wcwtl-product-info .action-button{
                    background-color: #00a299;
				    color: #fff;
				    text-decoration: none;
				    padding: 10px 15px;
				    display: inline-block;
				    border-radius: 9px;
				    min-width: 100px;
				    text-align: center;
				    margin: 5px 0;
                }
                
                .yith-wcwtl-product-info .action-button:hover{
                    background-color: #008a82;
                }
                
                .yith-wcwtl-product-info .wrap-image{
				    width: 40%;
				    box-sizing: border-box;
				    margin-right: 30px;
                }
                
                .yith-wcwtl-product-info .wrap-image img{
                    max-width: 100%;
                    height: auto;
                }
                
                .yith-wcwtl-product-info p{
                    margin: 0 0 5px 0!important;
                }
                
                .yith-wcwtl-product-info.image-yes .wrap-title{
				    width: 60%;
                }
                
                
                .yith-wcwtl-product-info .product-title{
                    font-size: 15px;
                    font-weight: 700;
				    color: #545454;
                }                                
                
                .yith-wcwtl-product-info .stock-label{
				    color: #d74040;
				    font-weight: 700;
                }
                
                .yith-wcwtl-unsubscribe-section{
                    border-top: 1px solid #ededed;
				    padding-top: 15px;
				    margin-top: 80px;
				    font-size: 12px;
                }
                
                @media( max-width: 480px ){
				    #body_content .wrap-image {
				        width: 100px!important;
				        margin: 0 auto 15px auto!important;
				    }
				    
				    #body_content .yith-wcwtl-product-info {
				        display: block!important;
				        text-align: center;
				        width: 100%;
				        margin: 0 0 15px 0!important;
				        box-sizing: border-box;
				    }
				    
				    #body_content .wrap-title {
				        width: auto!important;
				    }
				    
				    #body_content a.action-button {
				        padding: 7px!important;
				    }
				}


			";
			}

			return $css;
		}

		/**
		 * Declare support for WooCommerce features.
		 */
		public function declare_wc_features_support() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', YITH_WCWTL_INIT, true );
			}
		}
	}
}

/**
 * Unique access to instance of YITH_WCWTL class
 *
 * @return YITH_WCWTL
 * @since 1.0.0
 */
function YITH_WCWTL() {// phpcs:ignore WordPress.NamingConventions
	return YITH_WCWTL::get_instance();
}
