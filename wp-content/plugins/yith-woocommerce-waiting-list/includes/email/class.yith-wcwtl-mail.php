<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Plugin email common class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL_Mail' ) ) {
	/**
	 * Email Class
	 * Extend WC_Email to waitlist email
	 *
	 * @class    YITH_WCWTL_Mail_Instock
	 * @extends  WC_Email
	 */
	class YITH_WCWTL_Mail extends WC_Email {

		/**
		 * Remove subscribe url
		 *
		 * @var string
		 */
		public string $remove_url = '';

		/**
		 * The mail content
		 *
		 * @var string
		 */
		public string $mail_content = '';

		/**
		 * Is language switched?
		 *
		 * @var boolean
		 */
		protected bool $lang_switched = false;


		/**
		 * Generate custom fields by using YITH framework fields.
		 *
		 * @param string $key The key of the field.
		 * @param array  $data The attributes of the field as an associative array.
		 *
		 * @return string
		 */
		public function generate_yith_wcwtl_field_html( $key, $data ) {
			$field_key = $this->get_field_key( $key );
			$value     = $this->get_option( $key );
			$defaults  = array(
				'title'           => '',
				'label'           => '',
				'yith_wcwtl_type' => 'text',
				'description'     => '',
				'desc_tip'        => false,
			);

			wp_enqueue_script( 'yith-plugin-fw-fields' );
			wp_enqueue_style( 'yith-plugin-fw-fields' );

			$data = wp_parse_args( $data, $defaults );

			$field          = $data;
			$field['type']  = $data['yith_wcwtl_type'];
			$field['name']  = $field_key;
			$field['value'] = $value;
			$private_keys   = array( 'label', 'title', 'description', 'yith_wcwtl_type', 'desc_tip' );

			foreach ( $private_keys as $private_key ) {
				unset( $field[ $private_key ] );
			}

			ob_start(); ?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?><?php echo wp_kses_post( $this->get_tooltip_html( $data ) ); // phpcs:ignore Standard.Category.SniffName.ErrorCode. ?></label>
				</th>
				<td class="forminp yith-plugin-ui">
					<fieldset>
						<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span>
						</legend>
						<?php yith_plugin_fw_get_field( $field, true, true ); ?>
						<?php echo wp_kses_post( $this->get_description_html( $data ) ); // phpcs:ignore Standard.Category.SniffName.ErrorCode. ?>
					</fieldset>
				</td>
			</tr>
			<?php

			return ob_get_clean();
		}

		/**
		 * Init email form fields.
		 *
		 * @return string|void
		 */
		public function init_form_fields() {

			parent::init_form_fields();

			unset( $this->form_fields['additional_content'] );

			$this->form_fields['mail_content'] = array(
				'title'       => __( 'Email content', 'yith-woocommerce-waiting-list' ),
				'type'        => 'textarea',
				'description' => sprintf(
				// translators: %s stand for the default email content text.
					__( 'Defaults to <code>%s</code>', 'yith-woocommerce-waiting-list' ),
					$this->mail_content
				),
				'css'         => 'height: 200px;',
				'placeholder' => '',
				'default'     => $this->mail_content,
			);

			$this->form_fields['footer_text'] = array(
				'title'       => __( 'Footer text', 'yith-woocommerce-waiting-list' ),
				'type'        => 'textarea',
				// translators: %s stand for the default email content text.
				'description' => __( 'Text to show in the footer for this email', 'yith-woocommerce-waiting-list' ),
				'placeholder' => '',
			);

			$this->form_fields['show_thumb'] = array(
				'title'           => __( 'Show product image', 'yith-woocommerce-waiting-list' ),
				'type'            => 'yith_wcwtl_field',
				'yith_wcwtl_type' => 'onoff',
				'description'     => __(
					'Enable to show the product image in the email',
					'yith-woocommerce-waiting-list'
				),
				'default'         => 'yes',
			);

		}

		/**
		 * Set the locale to the store locale for customer emails to make sure emails are in the store language.
		 *
		 */
		public function setup_locale() {

			global $sitepress;

			// Get the product language.
			if ( function_exists( 'wpml_get_language_information' ) && ! is_null( $sitepress ) && ! empty( $this->object ) ) {
				$lang = wpml_get_language_information( null, $this->object->get_id() );
				if ( ( isset( $lang['language_code'] ) && $lang['language_code'] !== $sitepress->get_current_language() ) ) {
					$sitepress->switch_lang( $lang['language_code'], false );
					$this->lang_switched = true;
				}
			}

			$this->settings = array();

			parent::setup_locale();
		}

		/**
		 * Restore the locale to the default locale. Use after finished with setup_locale.
		 *
		 */
		public function restore_locale() {

			global $sitepress;

			parent::restore_locale();

			// Reset to default language if needed.
			if ( $this->lang_switched ) {
				$sitepress->switch_lang( $sitepress->get_default_language(), false );
				$this->lang_switched = false;
			}
		}

		/**
		 * Trigger Function
		 *
		 * @access public
		 *
		 * @param array   $emails Waitlist users array.
		 * @param integer $product_id Product id.
		 *
		 * @return void
		 * @since  1.0.0
		 */
		public function trigger( $emails, $product_id ) {
			$this->object = wc_get_product( $product_id );

			if ( ! $this->is_enabled() || ! $this->object ) {
				return;
			}

			$this->setup_locale();

			$response = false;

			if ( ! is_array( $emails ) ) {
				$emails = explode( ',', $emails );
			}

			foreach ( $emails as $email ) {

				add_filter( 'woocommerce_email_footer_text', array( $this, 'customize_email_footer_text' ), 100 );

				$user = get_user_by( 'email', $email );

				ob_start();

				wc_get_template(
					'/email/yith-wcwtl-product-info.php',
					array(
						'email'      => $this,
						'product'    => $this->object,
						'user_email' => $email,
					),
					'',
					YITH_WCWTL_DIR . 'templates/'
				);

				$product_info_html = ob_get_clean();

				$placeholders = apply_filters(
					'yith_wcwtl_email_custom_placeholders',
					array(
						'{product_title}'           => $this->object->get_name(),
						'{blogname}'                => $this->get_blogname(),
						'{product_sku}'             => $this->object->get_sku(),
						'{username}'                => $user instanceof WP_User ? $user->display_name : __(
							'Customer',
							'yith-woocommerce-waiting-list'
						),
						'{product_info}'            => $product_info_html,
						'{product_title_with_link}' => '<a href="' . esc_url( $this->object->get_permalink() ) . '">' . esc_html( $this->object->get_name() ) . '</a>',
						'{user_email}'              => $email,
					),
					$this->object,
					$email
				);

				$this->set_placeholders( $placeholders );

				$response = $this->send(
					$email,
					$this->get_subject(),
					$this->get_content(),
					$this->get_headers(),
					$this->get_attachments()
				);

				do_action( 'restore_language' );
			}

			$this->restore_locale();

			if ( $response ) {
				add_filter( "{$this->id}_send_response", '__return_true' );
			}

			do_action( "{$this->id}_send_completed", $emails, $this->object, $this );
		}

		/**
		 * Set plugin custom placeholders for email
		 *
		 * @param array $placeholders An array of email placeholders.
		 *
		 * @since  1.5.0
		 */
		public function set_placeholders( $placeholders ) {
			foreach ( $placeholders as $placeholder_key => $placeholder_value ) {
				$this->placeholders[ $placeholder_key ] = $placeholder_value;
			}
		}

		/**
		 * Return YITH text editor HTML.
		 *
		 * @param mixed $key The field key.
		 * @param mixed $data the field data.
		 *
		 * @return string
		 */
		public function generate_yith_wcwtl_textarea_html( $key, $data ) {
			$html = yith_waitlist_textarea_editor_html( $key, $data, $this );

			return $html;
		}

		/**
		 * Send mail using standard WP Mail or Mandrill Service
		 *
		 * @access public
		 *
		 * @param string $to Email recipient.
		 * @param string $subject Email subject.
		 * @param string $message Email message.
		 * @param string $headers Email headers.
		 * @param array  $attachments Email attachments.
		 *
		 * @return bool
		 * @since  1.0.0
		 */
		public function send( $to, $subject, $message, $headers, $attachments ) {
			return parent::send( $to, $subject, $message, $headers, $attachments );
		}

		/**
		 * Get custom email content from options
		 *
		 * @access public
		 * @return string
		 * @since  1.0.0
		 */
		public function get_custom_option_content() {
			$content = $this->get_option( 'mail_content' );

			return $this->format_string( $content );
		}

		/**
		 * Get_content_html function.
		 *
		 * @access public
		 * @return string
		 * @since  1.0.0
		 */
		public function get_content_html() {

			$product_url = $this->object instanceof WC_Product ? $this->object->get_permalink() : '';
			$product_sku = $this->object instanceof WC_Product ? $this->object->get_sku() : '';

			if ( $this->get_option( 'show_thumb' ) === 'yes' ) {
				$dimensions = wc_get_image_size( 'shop_catalog' );
				$height     = esc_attr( $dimensions['height'] );
				$width      = esc_attr( $dimensions['width'] );
				$src_image  = $this->object instanceof WC_Product && $this->object->get_image_id() ? wp_get_attachment_image_src(
					$this->object->get_image_id(),
					'shop_catalog'
				) : false;
				$src        = is_array( $src_image ) ? current( $src_image ) : wc_placeholder_img_src();

				$image = '<a href="' . $product_url . '"><img src="' . $src . '" height="' . $height . '" width="' . $width . '" /></a>';
			} else {
				$image = '';
			}

			$args = apply_filters(
				"{$this->id}_args",
				array(
					'product_link'  => $product_url,
					'product_sku'   => $product_sku,
					'product_thumb' => apply_filters( 'yith_wcwl_product_thumb', $image, $this, $product_url ),
					// APPLY_FILTERS: yith_wcwl_product_thumb | Filter product thumb shown in email | @param string $thumb | @param WC_Mail $email Email | @param string $product_url Product url @return string.
					'email_heading' => $this->get_heading(),
					'email_content' => $this->get_custom_option_content(),
					'email'         => $this,
				),
				$this->object
			);

			ob_start();

			wc_get_template( $this->template_html, $args, false, $this->template_base );

			return ob_get_clean();
		}

		/**
		 * Get_content_plain function.
		 *
		 * @access public
		 * @return string
		 * @since  1.0.0
		 */
		public function get_content_plain() {

			$args = apply_filters(
				"{$this->id}_plain_args",
				array(
					'product_title' => $this->object->get_name(),
					'product_link'  => $this->object->get_permalink(),
					'email_heading' => $this->get_heading(),
				),
				$this->object
			);

			ob_start();

			wc_get_template( $this->template_plain, $args, false, $this->template_base );

			return ob_get_clean();
		}

		/**
		 * Set footer text for waitilist emails and replace unsubscribe placeholder, if needed.
		 *
		 * @param string $text Default footer text.
		 *
		 * @return string
		 */
		public function customize_email_footer_text( $text ) {
			$footer_text = $this->get_option( 'footer_text' );
			if ( '' === $footer_text ) {
				$footer_text = get_option( 'woocommerce_email_footer_text' );
			} else {
				$footer_text = str_replace(
					array( '{unsubscribe}', '{/unsubscribe}' ),
					array( '<a href="' . esc_url( $this->remove_url ) . '">', '</a>' ),
					$footer_text
				);
			}

			return $footer_text;
		}

	}
}
