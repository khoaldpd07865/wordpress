<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Admin class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL_Admin' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCWTL_Admin {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 * @var YITH_WCWTL_Admin $instance
		 */
		protected static $instance;

		/**
		 * Plugin options
		 *
		 * @since  1.0.0
		 * @var array $options
		 * @access public
		 */
		public $options = array();

		/**
		 * Plugin version
		 *
		 * @since 1.0.0
		 * @var string $version
		 */
		public $version = YITH_WCWTL_VERSION;

		/**
		 * YIT_Plugin_Panel_WooCommerce object
		 *
		 * @var YIT_Plugin_Panel_WooCommerce
		 */
		protected $panel;

		/**
		 * Premium tab template file name
		 *
		 * @var $premium string
		 */
		protected $premium = 'premium.php';

		/**
		 * Premium version landing link
		 *
		 * @var string
		 */
		protected $premium_landing = 'https://yithemes.com/themes/plugins/yith-woocommerce-waiting-list/';

		/**
		 * Waiting List panel page
		 *
		 * @var string
		 */
		protected $panel_page = 'yith_wcwtl_panel';

		/**
		 * Various links
		 *
		 * @since  1.0.0
		 * @var string
		 * @access public
		 */
		public $doc_url = 'https://yithemes.com/docs-plugins/yith-woocommerce-waiting-list/';

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_WCWTL_Admin
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
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct() {

			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

			// Add action links.
			add_filter(
				'plugin_action_links_' . plugin_basename( YITH_WCWTL_DIR . '/' . basename( YITH_WCWTL_FILE ) ),
				array( $this, 'action_links' )
			);
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

			if ( ! ( defined( 'YITH_WCWTL_PREMIUM' ) && YITH_WCWTL_PREMIUM ) ) {
				add_action( 'yith_waiting_list_premium', array( $this, 'premium_tab' ) );
			}

			add_action( 'after_setup_theme', array( $this, 'load_privacy_dpa' ), 10 );

			add_action( 'yith_waiting_list_premium', array( $this, 'premium_tab' ) );

			/* Emails */
			add_action( 'yith_wcwtl_email_settings', array( $this, 'email_settings' ) );
			add_action( 'yith_wcwtl_print_email_settings', array( $this, 'print_email_settings' ) );

			add_action( 'wp_ajax_yith_wcwtl_save_email_settings', array( $this, 'save_email_settings' ) );
			add_action( 'wp_ajax_nopriv_yith_wcwtl_save_email_settings', array( $this, 'save_email_settings' ) );

			add_action( 'wp_ajax_yith_wcwtl_save_mail_status', array( $this, 'save_mail_status' ) );
			add_action( 'wp_ajax_nopriv_yith_wcwtl_save_mail_status', array( $this, 'save_mail_status' ) );

			// YITH WCWTL Loaded.
			/**
			 * DO_ACTION: yith_wcwtl_loaded
			 *
			 * Plugin core functions are loaded here.
			 */
			do_action( 'yith_wcwtl_loaded' );

		}


		/**
		 * Get the premium landing uri
		 *
		 * @since   1.0.0
		 * @return  string The premium landing link
		 */
		public function get_premium_landing_uri() {
			return apply_filters( 'yith_plugin_fw_premium_landing_uri', $this->premium_landing, YITH_WCWTL_SLUG );
		}

		/**
		 * Action Links
		 *
		 * Add the action links to plugin admin page
		 *
		 * @param mixed $links | links plugin array. //TODO: param type.
		 *
		 * @return   mixed Array
		 * @since    1.0
		 */
		public function action_links( $links ) {
			$links = yith_add_action_links( $links, $this->panel_page, true, YITH_WCWTL_SLUG );

			return $links;
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @return   void
		 * @use      /Yit_Plugin_Panel class
		 * @since    1.0
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->panel ) ) {
				return;
			}

			$admin_tabs = array(
				'form'  => array(
					'title' => __( 'Form in Product Page', 'yith-woocommerce-waiting-list' ),
					'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" /></svg>',
				),
				'email' => array(
					'title' => __( 'Email Settings', 'yith-woocommerce-waiting-list' ),
					'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>',
				),
			);


			$args = array(
				// APPLY_FILTERS: yith-wcwtl-register-panel-create-menu-page | Use this filter if you want prevent that plugin create own menu page. Default value is true | @param bool $create_menu_page create | @return bool.
				'create_menu_page' => apply_filters( 'yith-wcwtl-register-panel-create-menu-page', true ), //phpcs:ignore WordPress.NamingConventions
				// phpcs:ignore WordPress.NamingConventions
				'parent_slug'      => '',
				'page_title'       => 'YITH WooCommerce Waitlist',
				'is_free'          => defined( 'YITH_WCWTL_FREE' ),
				'is_premium'       => defined( 'YITH_WCWTL_PREMIUM' ),
				'menu_title'       => 'Waitlist',
				// APPLY_FILTERS: yith-wcwtl-register-panel-capabilities | Filter default capabilities required to manage plugin options panel. Default value is "manage_options" | @param string $capability User capability | @return string.
				'capability'       => apply_filters( 'yith-wcwtl-register-panel-capabilities', 'manage_options' ), //phpcs:ignore WordPress.NamingConventions
				// phpcs:ignore WordPress.NamingConventions
				'parent'           => '',
				'parent_page'      => apply_filters( 'yith-wcwtl-register-panel-parent-page', 'yith_plugin_panel' ), //phpcs:ignore WordPress.NamingConventions
				// phpcs:ignore WordPress.NamingConventions
				'page'             => $this->panel_page,
				// APPLY_FILTERS: yith-wcwtl-admin-tabs | Filter tabs shown on admin panel | @param array $admin_tabs Admin tabs | @return array.
				'admin-tabs'       => apply_filters( 'yith-wcwtl-admin-tabs', $admin_tabs ), //phpcs:ignore WordPress.NamingConventions
				'options-path'     => YITH_WCWTL_DIR . '/plugin-options',
				'class'            => yith_set_wrapper_class(),
				'ui_version'       => 2,
				'plugin_slug' => 'yith-woocommerce-waiting-list',
				'premium_tab'      => array(
					'features' => array(
						array(
							'title'       => __( 'Advanced design & style options', 'yith-woocommerce-waiting-list' ),
							'description' => __( 'Customize the waiting list subscription box (background color, border radius, padding, etc.) and the notification messages for successful subscription or errors.', 'yith-woocommerce-waiting-list' ),
						),
						array(
							'title'       => __( 'Google reCAPTCHA integration', 'yith-woocommerce-waiting-list' ),
							'description' => __( 'The best way to prevent spam registration.', 'yith-woocommerce-waiting-list' ),
						),
						array(
							'title'       => __( 'Use the social proof principle to push users to subscribe ', 'yith-woocommerce-waiting-list' ),
							'description' => __( 'Show a counter with the number of users subscribed to your products’ waitlists.', 'yith-woocommerce-waiting-list' ),
						),
						array(
							'title'       => __( 'Require email confirmation', 'yith-woocommerce-waiting-list' ),
							'description' => __( 'Choose whether to send a confirmation email to all users or just guest users to verify the email address used for the waiting list.', 'yith-woocommerce-waiting-list' ),
						),
						array(
							'title'       => __( 'Email notifications for shop owner and customer', 'yith-woocommerce-waiting-list' ),
							'description' => __( 'Both the admin and the user get a notification email to confirm the users is added in the product’s waitlist.', 'yith-woocommerce-waiting-list' ),
						),
						array(
							'title'       => __( 'An easy way to track and manage all waitlists', 'yith-woocommerce-waiting-list' ),
							'description' => nl2br( __( "Use the dedicated table to monitor the products with a waiting list and the users who subscribed to it.\n\n You can also manually add a user in a waitlist and export the email addresses of a waitlist in a CSV file.", "yith-woocommerce-waiting-list" ) ),
						),
						array(
							'title'       => __( 'Reduce effort and enable the automatic sending of Back In Stock email', 'yith-woocommerce-waiting-list' ),
							'description' => nl2br( __( "Avoid time and effort and automatically send emails to users in the list when the product is back in stock.\n\n Customize the email through the advanced editor for a better result!", "yith-woocommerce-waiting-list" ) ),
						),
						array(
							'title'       => __( 'Cross-sell in the right way and sell more', 'yith-woocommerce-waiting-list' ),
							'description' => __( 'Send a cross-sell email to recommend similar products in case the products the users subscribed to are not available.', 'yith-woocommerce-waiting-list' ),
						),
						array(
							'title'       => __( 'Get regular updates and support', 'yith-woocommerce-waiting-list' ),
							'description' => __( 'Regular WordPress & WooCommerce updates, new features and technical support.', 'yith-woocommerce-waiting-list' ),
						),
						// ...
					),
				),
			);

			/* === Fixed: not updated theme  === */
			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once YITH_WCWTL_DIR . '/plugin-fw/lib/yit-plugin-panel-wc.php';
			}

			$this->panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}

		/**
		 * Enqueue script premium
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function admin_scripts() {

			$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			if ( isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === $this->panel_page ) {//phpcs:ignore WordPress.Security.NonceVerification
				// style.
				wp_enqueue_style(
					'yith-waitlist-admin-stile',
					YITH_WCWTL_ASSETS_URL . '/css/admin.css',
					array(),
					YITH_WCWTL_VERSION,
					'all'
				);
				// script.
				wp_enqueue_script(
					'yith-waitlist-admin',
					YITH_WCWTL_ASSETS_URL . '/js/admin' . $min . '.js',
					array( 'jquery' ),
					YITH_WCWTL_VERSION,
					true
				);

				wp_localize_script(
					'yith-waitlist-admin',
					'yith_wcwtl_admin',
					array(
						'ajaxurl'   => admin_url( 'admin-ajax.php' ),
						'security'  => wp_create_nonce( 'search-products' ),
						'conf_msg'  => __(
							'Do you really want to send the mail?',
							'yith-woocommerce-waiting-list'
						),
						'email_tab' => admin_url( "admin.php?page={$this->panel_page}&tab=email" ),
					)
				);

				if ( isset( $_GET['tab'] ) && 'email' === $_GET['tab'] ) { //phpcs:ignore
					wp_enqueue_script(
						'yith-waitlist-email-settings',
						YITH_WCWTL_ASSETS_URL . '/js/emails' . $min . '.js',
						array( 'jquery' ),
						YITH_WCWTL_VERSION,
						true
					);
				}
			}
		}

		/**
		 * Premium Tab Template
		 *
		 * Load the premium tab template on admin page
		 *
		 * @return   void
		 * @since    1.0
		 */
		public function premium_tab() {
			$premium_tab_template = YITH_WCWTL_TEMPLATE_PATH . '/admin/' . $this->premium;
			if ( file_exists( $premium_tab_template ) ) {
				include_once $premium_tab_template;
			}

		}

		/**
		 * Plugin_row_meta
		 *
		 * Add the action links to plugin admin page
		 *
		 * @param array    $new_row_meta_args An array of plugin row meta.
		 * @param string[] $plugin_meta An array of the plugin's metadata,
		 *                                    including the version, author,
		 *                                    author URI, and plugin URI.
		 * @param string   $plugin_file Path to the plugin file relative to the plugins directory.
		 * @param array    $plugin_data An array of plugin data.
		 * @param string   $status Status of the plugin. Defaults are 'All', 'Active',
		 *                                      'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
		 *                                      'Drop-ins', 'Search', 'Paused'.
		 *
		 * @return   array
		 * @since    1.0
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status ) {

			if ( defined( 'YITH_WCWTL_INIT' ) && YITH_WCWTL_INIT === $plugin_file ) {
				$new_row_meta_args['slug'] = YITH_WCWTL_SLUG;

				if ( defined( 'YITH_WCWTL_PREMIUM' ) ) {
					$new_row_meta_args['is_premium'] = true;
				}
			}

			return $new_row_meta_args;
		}

		/**
		 * Load privacy DPA class
		 *
		 * @return void
		 * @since  1.5.1
		 */
		public function load_privacy_dpa() {
			if ( class_exists( 'YITH_Privacy_Plugin_Abstract' ) ) {
				include_once 'class.yith-wcwtl-privacy-dpa.php';
			}
		}

		/**
		 * Get waiting list data users list url
		 *
		 * @param integer $product_id The product ID of the waiting list.
		 *
		 * @return string;
		 * @since 1.9.6
		 * @auhtor Francesco Licandro
		 */
		public function get_waitlist_users_url( $product_id ) {
			if ( empty( $product_id ) ) {
				return '#';
			}

			$view_query_args = array(
				'page' => $this->panel_page,
				'tab'  => 'waitlistdata',
				'view' => 'users',
				'id'   => intval( $product_id ),
			);

			return add_query_arg( $view_query_args, admin_url( 'admin.php' ) );
		}

		/**
		 * Add query string to standard location redirect after a post update
		 *
		 * @access public
		 *
		 * @param mixed $location Current location.
		 * @param mixed $post_id The post ID.
		 *
		 * @return string
		 * @since  1.0.0
		 */
		public function add_query_to_redirect_location( $location, $post_id ) {

			$response = apply_filters( 'yith_waitlist_mail_instock_send_response', null );

			if ( true === $response ) {
				$location = add_query_arg( 'yith_wcwtl_message', 1, $location );
			} elseif ( false === $response ) {
				$location = add_query_arg( 'yith_wcwtl_message', 2, $location );
			}

			return esc_url_raw( $location );
		}

		/**
		 * Handle email settings tab
		 * This method based on query string load single email options or the general table
		 *
		 * @since  1.5.0
		 */
		public function email_settings() {

			$emails = YITH_WCWTL()->get_emails();
			// is a single email view?
			$active = '';
			if ( isset( $_GET['section'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification
				foreach ( $emails as $email ) {
					if ( strtolower( $email ) === sanitize_text_field( wp_unslash( $_GET['section'] ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification
						$active = $email;
						break;
					}
				}
			}

			// load mailer.
			$mailer = WC()->mailer();

			$emails_table = array();
			foreach ( $emails as $email ) {
				$email_class            = $mailer->emails[ $email ];
				$emails_table[ $email ] = array(
					'title'       => $email_class->get_title(),
					'description' => $email_class->get_description(),
					'recipient'   => $email_class->is_customer_email() ? __(
						'Customer',
						'woocommerce'
					) : $email_class->get_recipient(),
					'enable'      => $email_class->is_enabled(),
					'content'     => $email_class->get_content_type(),
				);
			}

			include_once YITH_WCWTL_DIR . '/templates/admin/email-settings-tab.php';
		}


		/**
		 * Outout emal settings section
		 *
		 * @param string $email_key Email ID.
		 *
		 * @return void
		 */
		public function print_email_settings( $email_key ) {
			global $current_section;
			$current_section = strtolower( $email_key );
			$mailer          = WC()->mailer();
			$class           = $mailer->emails[ $email_key ];
			WC_Admin_Settings::get_settings_pages();

			if ( ! empty( $_POST ) ) {//phpcs:ignore WordPress.Security.NonceVerification
				$class->process_admin_options();
			}

			include YITH_WCWTL_DIR . '/templates/admin/email-settings-single.php';

			$current_section = null;
		}


		/**
		 * Save email settings in ajax.
		 *
		 * @return void
		 */
		public function save_email_settings() {
			if( ! current_user_can( 'manage_options' ) || ! yith_wcwtl_verify_nonce( 'nonce', 'yith_wcwtl_email_settings' ) ){
				return;
			}

			parse_str( $_POST['params'], $params ); //phpcs:ignore.
			unset( $_POST['params'] ); //phpcs:ignore.

			foreach ( $params as $key => $value ) {
				$_POST[ $key ] = $value;
			}

			global $current_section;
			$current_section = strtolower( $_POST['email_key'] ); //phpcs:ignore.

			$mailer = WC()->mailer();
			$class  = $mailer->emails[ $_POST['email_key'] ]; //phpcs:ignore.
			$class->process_admin_options();

			$current_section = null;

			wp_send_json_success( array( 'msg' => 'Email updated' ) );
			die();
		}

		/**
		 * Save email status in ajax.
		 *
		 * @return void
		 */
		public function save_mail_status() {
			if ( isset( $_POST['email_key'] ) && $_POST['enabled'] && current_user_can( 'manage_options' ) && yith_wcwtl_verify_nonce( 'nonce', 'yith_wcwtl_email_status' ) ) {
				$email_key      = str_replace( 'yith_wcwtl_', '', strtolower( $_POST['email_key'] ) );
				$email_settings = get_option( 'woocommerce_yith_waitlist_' . $email_key . '_settings' );
				if ( is_array( $email_settings ) && ! empty( $email_key ) ) {
					$email_settings['enabled'] = $_POST['enabled'];
					update_option( 'woocommerce_yith_waitlist_' . $email_key . '_settings', $email_settings );
				}
			}
			die();
		}

		/**
		 * Build single email settings page
		 *
		 * @param string $email_key The email key.
		 *
		 * @return string
		 * @since  1.5.0
		 */
		public function build_single_email_settings_url( $email_key ) {
			return admin_url( "admin.php?page={$this->panel_page}&tab=email&section=" . strtolower( $email_key ) );
		}

	}
}
/**
 * Unique access to instance of YITH_WCWTL_Admin class
 *
 * @return YITH_WCWTL_Admin
 * @since 1.0.0
 */
function YITH_WCWTL_Admin() {  // phpcs:ignore WordPress.NamingConventions
	return YITH_WCWTL_Admin::get_instance();
}
