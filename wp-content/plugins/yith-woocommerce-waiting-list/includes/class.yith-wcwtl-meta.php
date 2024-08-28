<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Meta class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL_Meta' ) ) {
	/**
	 * Product metabox class.
	 * The class manage the products metabox for waitlist.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCWTL_Meta {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 * @var YITH_WCWTL_Meta
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
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 * @return YITH_WCWTL_Meta
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
		 * @since  1.0.0
		 */
		public function __construct() {

			// enqueue script.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

			// ajax send mail.
			add_action( 'wp_ajax_yith_waitlist_send_mail', array( $this, 'yith_waitlist_send_mail_ajax' ) );
			add_action( 'wp_ajax_nopriv_yith_waitlist_send_mail', array( $this, 'yith_waitlist_send_mail_ajax' ) );
		}

		/**
		 * Enqueue scripts
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function enqueue_scripts() {
			$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

            wp_enqueue_style( 'yith-plugin-ui' );
            wp_enqueue_script( 'yith-plugin-fw-fields' );

			wp_enqueue_script( 'yith-waitlist-metabox', YITH_WCWTL_ASSETS_URL . '/js/metabox' . $min . '.js', array( 'jquery' ), YITH_WCWTL_VERSION, true );

			wp_localize_script(
				'yith-waitlist-metabox',
				'yith_wcwtl_meta',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
		}

		/**
		 * Check product and call add_meta function
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function add_meta_box() {

			global $post;

			if ( get_post_type( $post ) !== 'product' ) {
				return;
			}

			$title = __( 'Waitlist', 'yith-woocommerce-waiting-list' );
			// get product.
			$product_id = $post->ID;
			$product    = wc_get_product( $product_id );

			if ( $product->is_type( array( 'simple', 'yith_bundle', 'ticket-event' ) ) && ! $product->is_in_stock() ) {
				// add metabox.
				$this->add_meta( $product_id, $title );
			} elseif ( $product->is_type( 'variable' ) ) {
				// get variation.
				$variations = $product->get_available_variations();

				foreach ( $variations as $variation ) {

					if ( isset( $variation['is_in_stock'] ) && $variation['is_in_stock'] ) {
						continue;
					}
					// translators: %s stand for the product variation ID.
					$title = sprintf( __( 'Waitlist for variation: #%s', 'yith-woocommerce-waiting-list' ), $variation['variation_id'] );
					$this->add_meta( $variation['variation_id'], $title );
				}
			}
		}

		/**
		 * Add waitlist metabox on edit product page
		 *
		 * @access public
		 * @since  1.0.0
		 * @param string $id    Product or Variation id.
		 * @param string $title The meta title.
		 */
		public function add_meta( $id, $title ) {

			/**
			 * APPLY_FILTERS: yith_wcwtl_metabox_waitlist_title
			 *
			 * Filters waiting list metabox title
			 *
			 * @param string $title Metabox title
			 *
			 * @return string
			 */
			$title = apply_filters( 'yith_wcwtl_metabox_waitlist_title', $title );

			add_meta_box(
				'_yith_wcwtl_users_list_' . $id,
				$title,
				array( $this, 'build_meta_box' ),
				'product',
				'side',
				'default',
				$id
			);
		}

		/**
		 * Callback function to output metabox in product edit page
		 *
		 * @access public
		 * @since  1.0.0
		 * @param mixed $product The product/post instance.
		 * @param array $args The metabox array arguments.
		 */
		public function build_meta_box( $product, $args ) {
			// get product id.
			$product_id = $args['args'];
			// get users.
			$users = yith_waitlist_get_registered_users( $product_id );

			if ( ! empty( $users ) ) {
				$view_url = YITH_WCWTL_Admin()->get_waitlist_users_url( $product_id );
				?>
				<div class="yith-waitlist-metabox" style="margin-bottom:10px;">

					<?php // translators: %s stand for the number of subscribed users on waiting list.
                    echo esc_html( sprintf( _n( 'There is %s user on the waitlist for this product.', 'There are %s users on the waitlist for this product', count( $users ), 'yith-woocommerce-waiting-list' ), count( $users ) ) );
                    echo '<div class="action-buttons" style="margin-top: 30px;">';

                        if ( ! empty( $users ) ) {
                            yith_plugin_fw_get_component(
                                array(
                                    'title'  => _x( 'Send', '[Button label] Send email to users in list', 'yith-woocommerce-waiting-list' ),
                                    'type'   => 'action-button',
                                    'action' => 'send',
                                    'icon'   => 'mail-out',
                                    'url'    => '#',
                                    'class'  => 'yith-waitlist-send-mail',
                                    'data'   => array(
                                            'product_id' => $product_id,
	                                        'nonce' => sanitize_key( wp_create_nonce( 'yith_wcwtl_send_email' ) )
                                    )
                                )
                            );
                        }
                    echo '</div>';
                    ?>
				</div>
				<?php
			} else {
				esc_html_e( 'There are no users on this waitlist', 'yith-woocommerce-waiting-list' );
			}

			echo '<p class="response-message"></p>';
		}

		/**
		 * Ajax action for send mail to waitlist users
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function yith_waitlist_send_mail_ajax() {

			if ( ! isset( $_REQUEST['product'] ) || ! current_user_can( 'manage_options' ) || ! yith_wcwtl_verify_nonce( 'nonce', 'yith_wcwtl_send_email' ) ) { //phpcs:ignore WordPress.Security.NonceVerification
				die();
			}

			$product_id = absint( $_REQUEST['product'] ); //phpcs:ignore WordPress.Security.NonceVerification
			$product    = wc_get_product( $product_id );

			/**
			 * DO_ACTION: yith_wcwtl_schedule_email_send
			 *
			 * Use this hook to process your code before "Send" button is rendered
			 *
			 * @param array $users the list of users
			 * @param int $product_id the product ID
			 */
			do_action( 'yith_wcwtl_schedule_email_send', $product );

			// pass param to js.
			/**
			 * APPLY_FILTERS: yith_wcwtl_mail_instock_send_success
			 *
			 * Filters the message shown when the in stock email is sent correctly
			 *
			 * @param string $message Message
			 *
			 * @return string
			 */
			echo wp_json_encode(
				array(
					'msg'  => apply_filters( 'yith_wcwtl_mail_instock_send_success', __( 'Email sent correctly.', 'yith-woocommerce-waiting-list' ) ),
					'send' => true,
				)
			);

			die();
		}
	}
}

/**
 * Unique access to instance of YITH_WCWTL_Meta class
 *
 * @since 1.0.0
 * @return YITH_WCWTL_Meta
 */
function YITH_WCWTL_Meta() { // phpcs:ignore WordPress.NamingConventions
	return YITH_WCWTL_Meta::get_instance();
}
