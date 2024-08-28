<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Frontend class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 * @version 1.1.1
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL_Frontend' ) ) {
	/**
	 * Frontend class.
	 * The class manage all the Frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCWTL_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 * @var YITH_WCWTL_Frontend
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $version = YITH_WCWTL_VERSION;

		/**
		 * Frontend script was enqueued
		 *
		 * @since 1.0.0
		 * @var boolean
		 */
		public $scripts_enqueued = false;

		/**
		 * Current object product
		 *
		 * @since 1.0.0
		 * @var object
		 */
		protected $current_product = false;

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 * @return YITH_WCWTL_Frontend
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
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct() {

			// add form.
            add_action( 'woocommerce_simple_add_to_cart', array( $this, 'add_form' ) ); // WooCommerce blocks support
            add_action( 'woocommerce_variable_add_to_cart', array( $this, 'add_form' ) ); // WooCommerce blocks support
			// add form on quick view.
			add_action( 'jck_qv_summary', array( $this, 'add_form' ) );
			add_action( 'yith_wcqv_before_product_summary', array( $this, 'add_form' ) );
			// add form on get variation AJAX call.
			add_action( 'init', array( $this, 'add_form_ajax' ) );

			// submit AJAX.
			add_action( 'wp_ajax_yith_wcwtl_submit', array( $this, 'waiting_submit_ajax' ) );
			add_action( 'wp_ajax_nopriv_yith_wcwtl_submit', array( $this, 'waiting_submit_ajax' ) );
			// submit.
			add_action( 'template_redirect', array( $this, 'waiting_submit' ), 100 );

			// my account.
			add_filter( 'woocommerce_account_menu_items', array( $this, 'add_menu_item' ), 10, 1 );
			add_action( 'woocommerce_account_waiting-list_endpoint', array( $this, 'account_template' ) );

			// enqueue frontend js.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );

			// update user meta.
			add_action( 'woocommerce_created_customer', array( $this, 'add_meta_to_new_user' ), 10, 3 );

			// shortcode waitlist table.
			add_shortcode( 'ywcwtl_waitlist_table', array( $this, 'shortcode_waitlist_my_account' ) );
			// shortcode waitlist form.
			add_shortcode( 'ywcwtl_form', array( $this, 'shortcode_the_form' ) );

		}

		/**
		 * Register scripts frontend
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function register_scripts() {
			$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			wp_register_script( 'yith-wcwtl-frontend', YITH_WCWTL_ASSETS_URL . '/js/frontend' . $min . '.js', array( 'jquery' ), YITH_WCWTL_VERSION, true );

			wp_register_style( 'yith-wcwtl-style', YITH_WCWTL_ASSETS_URL . '/css/ywcwtl.css', array(), YITH_WCWTL_VERSION, 'all' );

			$this->enqueue_scripts();
		}

		/**
		 * Enqueue scripts and style
		 *
		 * @since  1.0.8
		 * @access public
		 */
		public function enqueue_scripts() {

			if ( ! $this->scripts_enqueued ) {
				wp_enqueue_script( 'yith-wcwtl-frontend' );
				wp_enqueue_style( 'yith-wcwtl-style' );

				$custom_style = yith_waitlist_get_custom_style();
				if ( $custom_style && is_string( $custom_style ) ) {
					wp_add_inline_style( 'yith-wcwtl-style', $custom_style );
				}

				wp_localize_script(
					'yith-wcwtl-frontend',
					'ywcwtl',
					apply_filters(
						'yith_wcwtl_localized_frontend_object_args',
						array(
							'ajax' => 'yes',
						)
					),
				);

				// scripts enqueued!
				$this->scripts_enqueued = true;
			}
		}

		/**
		 * Check if the product can have the waitlist form
		 *
		 * @since  1.1.3
		 * @param object $product WC Product The product to check.
		 * @return boolean
		 */
		public function can_have_waitlist( $product ) {

			/**
			 * APPLY_FILTERS: yith_wcwtl_allowed_product_type
			 *
			 * Filters product types which expect the waiting list form.
			 *
			 * @param array  $types  list of product types
			 *
			 * @return array
			 */
			$allowed_type = apply_filters(
				'yith_wcwtl_allowed_product_type',
				array(
					'simple',
					'variable',
					'variation',
					'yith-composite',
					'yith_bundle',
					'ticket-event',
				)
			);

			$return = ! ( ! $product->is_type( $allowed_type ) || $product->is_in_stock() );

			// can third part filter this result.
			/**
			 * APPLY_FILTERS: yith_wcwtl_can_product_have_waitlist
			 *
			 * Filters default capability for a product to have a waitlist or not.
			 *
			 * @param bool $return Have a waiting list
			 * @param WC_Product $product Product
			 *
			 * @return array
			 */
			return apply_filters( 'yith_wcwtl_can_product_have_waitlist', $return, $product );
		}

		/**
		 * Add form to stock html
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function add_form() {
			global $post;

			/**
			 * APPLY_FILTERS: yith_wcwtl_show_form
			 *
			 * Filters if show or not the waiting list form according to the current page
			 *
			 * @param bool $show Show the form
			 *
			 * @return bool
			 */
			if ( apply_filters( 'yith_wcwtl_show_form', true, $post ) && ( is_product() ) ) {

				$this->current_product = wc_get_product( $post->ID );

				if ( ! $this->current_product ) {
					return;
				}

				// check for WooCommerce 3.0.0.
				if ( function_exists( 'wc_get_stock_html' ) ) {
					add_filter( 'woocommerce_get_stock_html', array( $this, 'output_form_3_0' ), 20, 2 );
				} else {
					if ( $this->current_product->is_type( 'variable' ) ) {
						add_action( 'woocommerce_stock_html', array( $this, 'output_form' ), 20, 3 );
					} else {
						add_action( 'woocommerce_stock_html', array( $this, 'output_form' ), 20, 2 );
					}
				}
			}
		}

		/**
		 * Add form on WC AJAX get variations method
		 *
		 * @since  1.3.1
		 */
		public function add_form_ajax() {
			if ( ! isset( $_REQUEST['wc-ajax'] ) || 'get_variation' !== sanitize_text_field( wp_unslash( $_REQUEST['wc-ajax'] ) )//phpcs:ignore WordPress.Security.NonceVerification
				|| empty( $_POST['product_id'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification
				return;
			}

			$variable_product = wc_get_product( absint( $_POST['product_id'] ) );//phpcs:ignore WordPress.Security.NonceVerification

			if ( ! $variable_product ) {
				return;
			}

			$data_store   = WC_Data_Store::load( 'product' );
			$variation_id = $data_store->find_matching_product_variation( $variable_product, wp_unslash( $_POST ) ); //phpcs:ignore WordPress.Security.NonceVerification

			$this->current_product = wc_get_product( $variation_id );

			// check for WooCommerce 3.0.0.
			if ( function_exists( 'wc_get_stock_html' ) ) {
				add_filter( 'woocommerce_get_stock_html', array( $this, 'output_form_3_0' ), 20, 2 );
			} else {
				add_action( 'woocommerce_stock_html', array( $this, 'output_form' ), 20, 2 );
			}
		}

		/**
		 * Add form to stock html
		 *
		 * @access public
		 * @since  1.0.0
		 * @param string           $html Current output html.
		 * @param int              $availability The product availability.
		 * @param object | boolean $product (Optional) The product instance, false otherwise.
		 * @return string
		 */
		public function output_form( $html, $availability, $product = false ) {

			if ( ! $product ) {
				$product = $this->current_product;
			}

			// check first id product is excluded.
			if ( is_callable( array( $product, 'get_id' ) ) ) {
				$id = $product->get_id();
			} else {
				$id = isset( $product->variation_id ) ? $product->variation_id : $product->id;
			}

			ob_start();
			echo do_shortcode( '[ywcwtl_form product_id="' . $id . '"]' );
			// then add form to current html.
			$html .= ob_get_clean();

			return $html;
		}

		/**
		 * Add form to stock html for WooCommerce 3.0.0
		 *
		 * @access public
		 * @since  1.2.0
		 * @param string     $html Current output html.
		 * @param WC_Product $product The product instance.
		 * @return string
		 */
		public function output_form_3_0( $html, $product ) {
			return $this->output_form( $html, '', $product );
		}

		/**
		 * Shortcode that add the form for the waiting list
		 *
		 * @since  1.1.3
		 * @param  array $atts Shortcode attributes.
		 * @return string
		 */
		public function shortcode_the_form( $atts ) {

			extract( //phpcs:ignore WordPress.PHP.DontExtract
				shortcode_atts(
					array(
						'product_id' => '',
					),
					$atts
				)
			);

			if ( $product_id ) {
				$product = wc_get_product( $product_id );
			} else {
				// get global.
				global $product;
			}

			// exit if product is null or if can't have waitlist.
			if ( is_null( $product ) || ! $product || ! $this->can_have_waitlist( $product ) ) {
				return '';
			}

			/**
			 * APPLY_FILTERS: yith_wcwtl_form_template_args
			 *
			 * Filters list of args used in the template yith-wcwtl-form.php
			 *
			 * @param array $args List of args
			 *
			 * @return array
			 */
			$args = apply_filters(
				'yith_wcwtl_form_template_args',
				array(
					'product'            => $product,
					'product_id'         => $product->get_id(),
					'message'            => get_option( 'yith-wcwtl-form-message' ),
					'label_button_add'   => get_option( 'yith-wcwtl-button-add-label' ),
					'label_button_leave' => get_option( 'yith-wcwtl-button-leave-label' ),
				)
			);

			ob_start();

			wc_get_template( 'yith-wcwtl-form.php', $args, '', YITH_WCWTL_DIR . 'templates/' );

			return ob_get_clean();
		}

		/**
		 * Frontend Waiting List submit AJAX
		 *
		 * @access public
		 * @since  1.4.0
		 */
		public function waiting_submit_ajax() {

			if ( ! ( isset( $_REQUEST['_yith_wcwtl_users_list'] ) && is_numeric( $_REQUEST['_yith_wcwtl_users_list'] ) && isset( $_REQUEST['_yith_wcwtl_users_list-action'] ) ) && ! yith_wcwtl_verify_nonce( 'nonce', 'yith_wcwtl_submit_waitlist' ) ) { //phpcs:ignore WordPress.Security.NonceVerification
				die();
			}

			$product_id            = absint( $_REQUEST['_yith_wcwtl_users_list'] );//phpcs:ignore WordPress.Security.NonceVerification
			$this->current_product = wc_get_product( $product_id );

			if ( ! $this->current_product ) {
				die(); // return if product doesn't exist.
			}

			$res = $this->submit( $product_id, $this->current_product );

			$json = array();

			$args = version_compare( '3.9', WC()->version ) === 1 ? array( 'messages' => array( $res['msg'] ) ) : array( 'notices' => array( 0 => array( 'notice' => $res['msg'] ) ) );

			ob_start();
			// get message.
			echo '<div class="yith-wcwtl-ajax-message">';
			wc_get_template( '/notices/' . $res['type'] . '.php', $args, '', YITH_WCWTL_DIR . 'templates/' );
			echo '</div>';
			$json['type'] = $res['type'];
			$json['msg']  = ob_get_clean();

			// get form.
			ob_start();
			echo do_shortcode( '[ywcwtl_form product_id="' . $product_id . '"]' );
			$json['form'] = ob_get_clean();

			wp_send_json( $json );
		}

		/**
		 * Frontend Waiting List submit
		 *
		 * @access public
		 * @since  1.4.0
		 */
		public function waiting_submit() {

			if ( ! ( isset( $_REQUEST['_yith_wcwtl_users_list'] ) && is_numeric( $_REQUEST['_yith_wcwtl_users_list'] ) && isset( $_REQUEST['_yith_wcwtl_users_list-action'] ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification
				return false;
			}

			$product_id = absint( $_REQUEST['_yith_wcwtl_users_list'] );//phpcs:ignore WordPress.Security.NonceVerification
			$product    = wc_get_product( $product_id );

			if ( ! $product ) {
				return false; // return if product doesn't exist.
			}

			$res = $this->submit( $product_id, $product );

			// lets filter redirection url.
			/**
			 * APPLY_FILTERS: yith_wcwtl_destination_url_after_submit
			 *
			 * Filters the redirection url used after submit of the waiting list form.
			 *
			 * @param string $permalink Redirection link
			 * @param WC_Product $product Product
			 *
			 * @return string
			 */
			$dest = apply_filters( 'yith_wcwtl_destination_url_after_submit', $product->get_permalink(), $product );

			// redirect.
			wc_add_notice( $res['msg'], $res['type'] );
			wp_safe_redirect( esc_url( $dest ) );
			exit;
		}

		/**
		 * Handle Waiting List submit actions
		 *
		 * @access protected
		 * @since  1.4.0
		 * @param int        $product_id The product ID.
		 * @param WC_Product $product Product instance.
		 * @return mixed
		 */
		public function submit( $product_id, $product ) {

			do_action( 'yith_wcwtl_before_submit_process', $product_id );

			$user       = wp_get_current_user();
			$msg        = '';
			$action     = ( isset( $_REQUEST['_yith_wcwtl_users_list-action'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['_yith_wcwtl_users_list-action'] ) ) : false;//phpcs:ignore WordPress.Security.NonceVerification
			$user_email = ( isset( $_REQUEST['yith-wcwtl-email'] ) ) ? sanitize_email( wp_unslash( $_REQUEST['yith-wcwtl-email'] ) ) : $user->user_email;//phpcs:ignore WordPress.Security.NonceVerification

			// first check for email.
			if ( ! $user_email || ! is_email( $user_email ) ) {
				$msg  = __( 'You must provide a valid email address to join the waitlist for this product.', 'yith-woocommerce-waiting-list' );
				$type = 'error';
			} elseif ( $this->invalid_policy() ) {
				$msg  = __( 'You must accept our Privacy Policy to join the waitlist', 'yith-woocommerce-waiting-list' );
				$type = 'error';
			} else {
				// set standard msg and type to success.
				$type = 'success';

				// start user session and set cookies.
				if ( ! isset( $_COOKIE['woocommerce_items_in_cart'] ) ) {
					do_action( 'woocommerce_set_cart_cookies', true );
				}

				if ( 'register' === $action ) {
					if ( yith_waitlist_register_user( $user_email, $product ) ) {
						// send email.
						$msg = get_option( 'yith-wcwtl-button-success-msg' );

					} else {
						$msg  = get_option( 'yith-wcwtl-button-error-msg-for-user-already-subscribed' );
						$type = 'error';
					}
				} elseif ( 'leave' === $action && yith_waitlist_unregister_user( $user_email, $product ) ) {
					$msg  = get_option( 'yith-wcwtl-button-leave-msg' );
					$type = 'success';
				} else {
					$msg  = get_option( 'yith-wcwtl-button-error-msg' );
					$type = 'error';
				}
			}

			do_action( 'yith_wcwtl_after_submit_process', $product_id );

			return array(
				'msg'  => $msg,
				'type' => $type,
			);
		}

		/**
		 * Check policy status
		 * Check if policy status is needed on submit form
		 *
		 * @since  1.5.0
		 * @return boolean
		 */
		public function invalid_policy() {
			if ( ( isset( $_REQUEST['_yith_wcwtl_users_list-action'] ) && 'register' !== sanitize_text_field( wp_unslash( $_REQUEST['_yith_wcwtl_users_list-action'] ) ) ) || isset( $_REQUEST['is-double-optin'] ) //phpcs:ignore WordPress.Security.NonceVerification
				|| get_option( 'yith-wcwtl-enable-privacy-checkbox', 'yes' ) !== 'yes' ) {
				return false;
			}
			return ! isset( $_REQUEST['yith-wcwtl-policy-check'] ) || 'yes' !== sanitize_text_field( wp_unslash( $_REQUEST['yith-wcwtl-policy-check'] ) ); //phpcs:ignore WordPress.Security.NonceVerification
		}

		/**
		 * Add waitlist section to my-account page
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function add_waitlist_my_account() {
			echo do_shortcode( '[ywcwtl_waitlist_table]' );
		}

		/**
		 * Waiting list table my account shortcode
		 *
		 * @access public
		 * @since  1.1.1
		 */
		public function shortcode_waitlist_my_account() {
			ob_start();
			wc_get_template( 'yith-wcwtl-my-waitlist.php', array(), '', YITH_WCWTL_DIR . 'templates/' );
			return ob_get_clean();
		}

		/**
		 * Update user meta after registration
		 *
		 * @access public
		 * @since  1.0.0
		 * @param int    $customer_id The customer ID.
		 * @param mixed  $new_customer_data The customer data array..
		 * @param string $password_generated Password generated.
		 */
		public function add_meta_to_new_user( $customer_id, $new_customer_data, $password_generated ) {

			global $wpdb;
			// get ids.
			$email = '%' . $new_customer_data['user_email'] . '%';
			$query = $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value LIKE %s", '_yith_wcwtl_users_list', $email );
			$ids   = $wpdb->get_col( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery

			// update post meta for new user.
			! empty( $ids ) && update_user_meta( $customer_id, '_yith_wcwtl_products_list', $ids );
		}

		/**
		 * Add menu item for standard wc account navigation ( for version >= 2.6 )
		 *
		 * @since  1.1.2
		 * @param array $items My Account menu items.
		 * @return array
		 */
		public function add_menu_item( $items ) {

			$new_items = array();

			if ( ! is_array( $items ) ) {
				return $items;
			}

			$items_keys = array_keys( $items );
			$last_key   = end( $items_keys );

			foreach ( $items as $key => $value ) {
				if ( $key === $last_key ) {
					$new_items['waiting-list'] = __( 'Waitlist', 'yith-woocommerce-waiting-list' );
				}
				$new_items[ $key ] = $value;
			}

			return $new_items;
		}

		/**
		 * Load my account section
		 *
		 * @since  1.6.0
		 */
		public function account_template() {
			echo do_shortcode( '[ywcwtl_waitlist_table]' );
		}


	}
}
/**
 * Unique access to instance of YITH_WCWT_Frontend class
 *
 * @since 1.0.0
 * @return YITH_WCWTL_Frontend
 */
function YITH_WCWTL_Frontend() {  // phpcs:ignore WordPress.NamingConventions
	return YITH_WCWTL_Frontend::get_instance();
}
