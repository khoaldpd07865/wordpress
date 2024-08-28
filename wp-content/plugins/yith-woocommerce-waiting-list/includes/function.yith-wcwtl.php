<?php // phpcs:ignore WordPress.NamingConventions
/**
 * General Function
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\WaitingList
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'yith_waitlist_get' ) ) {
	/**
	 * Get waiting list for product id
	 *
	 * @since  1.0.0
	 * @param WC_Product|integer $product Product instance or product ID.
	 * @return array
	 */
	function yith_waitlist_get( $product ) {
		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		$list = $product ? $product->get_meta( '_yith_wcwtl_users_list', true ) : array();
		return is_array( $list ) ? $list : array();
	}
}

if ( ! function_exists( 'yith_waitlist_save' ) ) {
	/**
	 * Save waiting list for product id
	 *
	 * @since  1.0.0
	 * @param WC_Product|integer $product The product instance or the product ID.
	 * @param array              $list Waiting list.
	 * @return void
	 */
	function yith_waitlist_save( $product, $list ) {
		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		if ( $product ) {
			$product->update_meta_data( '_yith_wcwtl_users_list', $list );
			$product->save();
		}
	}
}

if ( ! function_exists( 'yith_waitlist_user_is_register' ) ) {
	/**
	 * Check if user is already register for a waiting list
	 *
	 * @since  1.0.0
	 * @param string $user //TODO: short description.
	 * @param array  $list //TODO: short description.
	 * @return bool
	 */
	function yith_waitlist_user_is_register( $user, $list ) {
		return is_array( $list ) && in_array( $user, $list, true );
	}
}

if ( ! function_exists( 'yith_waitlist_register_user' ) ) {
	/**
	 * Register user to waiting list
	 *
	 * @since  1.0.0
	 * @param string     $user User email //TODO: short description.
	 * @param object|int $product  //TODO: short description.
	 * @return bool
	 */
	function yith_waitlist_register_user( $user, $product ) {

		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		$list = yith_waitlist_get( $product );

		if ( ! is_email( $user ) || yith_waitlist_user_is_register( $user, $list ) ) {
			return false;
		}

		// add product to user meta.
		yith_waitlist_save_user_meta( $product, $user );

		$list[] = $user;
		// save it in product meta.
		yith_waitlist_save( $product, $list );

		return true;
	}
}

if ( ! function_exists( 'yith_waitlist_register_users_bulk' ) ) {
	/**
	 * Register an array of users to waiting list
	 *
	 * @since  1.6.0
	 * @param array      $users An array of users email.
	 * @param object|int $product //TODO: short description.
	 * @return bool
	 */
	function yith_waitlist_register_users_bulk( $users, $product ) {

		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		$list = yith_waitlist_get( $product );

		foreach ( $users as $user ) {
			if ( ! is_email( $user ) || yith_waitlist_user_is_register( $user, $list ) ) {
				continue;
			}
			// add product to user meta.
			yith_waitlist_save_user_meta( $product, $user );
			$list[] = $user;
		}

		// save it in product meta.
		yith_waitlist_save( $product, $list );

		return true;
	}
}

if ( ! function_exists( 'yith_waitlist_unregister_user' ) ) {
	/**
	 * Unregister user from waiting list
	 *
	 * @since  1.0.0
	 * @param string         $user    User email.
	 * @param object|integer $product Product id.
	 * @return bool
	 */
	function yith_waitlist_unregister_user( $user, $product ) {

		$list = yith_waitlist_get( $product );

		if ( yith_waitlist_user_is_register( $user, $list ) ) {
			// remove product from user meta.
			yith_waitlist_remove_user_meta( $product, $user );

			$list = array_diff( $list, array( $user ) );

			// save it in product meta.
			yith_waitlist_save( $product, $list );
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'yith_waitlist_unregister_users' ) ) {
	/**
	 * Unregister users from waiting list
	 *
	 * @since  1.0.0
	 * @param array          $users    Users email array.
	 * @param object|integer $product Product id.
	 * @return bool
	 */
	function yith_waitlist_unregister_users( $users, $product ) {

		$list = yith_waitlist_get( $product );
		if ( ! empty( $list ) ) {

			foreach ( $users as $user ) {
				$key = array_search( $user, $list, true );
				if ( false !== $key ) {
					unset( $list[ $key ] );
					yith_waitlist_remove_user_meta( $product, $user );
				}
			}

			yith_waitlist_save( $product, $list );
		}
		return true;
	}
}

if ( ! function_exists( 'yith_waitlist_get_registered_users' ) ) {
	/**
	 * Get registered users for product waitlist
	 *
	 * @since  1.0.0
	 * @param object|integer $product //TODO: short description.
	 * @return mixed
	 */
	function yith_waitlist_get_registered_users( $product ) {

		$list  = yith_waitlist_get( $product );
		$users = array();

		if ( is_array( $list ) ) {
			foreach ( $list as $key => $email ) {
				$users[] = $email;
			}
		}

		return $users;
	}
}

if ( ! function_exists( 'yith_waitlist_empty' ) ) {
	/**
	 * Empty waitlist by product id
	 *
	 * @since  1.0.0
	 * @param object|integer $product //TODO: short description.
	 * @return void
	 */
	function yith_waitlist_empty( $product ) {
		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		if ( $product ) {
			// first of all get all users and update their meta.
			$users = yith_waitlist_get_registered_users( $product );
			foreach ( $users as $user ) {
				yith_waitlist_remove_user_meta( $product, $user );
			}
			// now empty waiting list.
			delete_post_meta( $product->get_id(), '_yith_wcwtl_users_list' );

		}
	}
}



if ( ! function_exists( 'yith_count_users_on_waitlist' ) ) {
	/**
	 * Count users on waitlist
	 *
	 * @since  1.0.0
	 * @param object|integer $product //TODO: short description.
	 * @return bool
	 */
	function yith_count_users_on_waitlist( $product ) {
		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		if ( ! $product ) {
			return false;
		}

		$user = $product->get_meta( '_yith_wcwtl_users_list', true );
		return $user ? count( $user ) : 0;
	}
}

if ( ! function_exists( 'yith_waitlist_send_stock_email' ) ) {
	/**
	 * Send in stock email to users registered to product wait list or to given users array
	 *
	 * @since  1.8.0
	 * @param WC_Product $product //TODO: short description.
	 * @param array      $users //TODO: short description.
	 * @return bool
	 */
	function yith_waitlist_send_stock_email( $product, $users = array() ) {

		// check for excluded product.
		if ( ! $product ) {
			return false;
		}

		$qty_limited = yith_waitlist_is_limited_stock_email();
		if ( empty( $users ) ) {
			// get waitlist users for product.
			$users = yith_waitlist_get_registered_users( $product );
			if ( $qty_limited ) {
				$qty   = $product->get_stock_quantity();
				$users = array_slice( $users, 0, $qty, true );
			}
		}

		// if list is empty.
		if ( empty( $users ) ) {
			return false;
		}

		// send mail.
		/**
		 * DO_ACTION: send_yith_waitlist_mail_instock
		 *
		 * This hook is used by the plugin to notify customer that product is back in stock
		 *
		 * @param array $users list of users
		 * @param WC_Product $product product
		 */
		do_action( 'send_yith_waitlist_mail_instock', $users, $product );
		return true;
	}
}


if ( ! function_exists( 'yith_waitlist_is_limited_stock_email' ) ) {
	function yith_waitlist_is_limited_stock_email() {
		return 'limited-number' === get_option( 'woocommerce_yith_waitlist_mail_instock_settings' )['send_to'];
	}
}

/***************
 * USER FUNCTION
 */

if ( ! function_exists( 'yith_get_user_waitlists' ) ) {
	/**
	 * Get meta for user subscribed waiting lists
	 *
	 * @since  1.0.0
	 * @param int $id User id.
	 * @return mixed
	 */
	function yith_get_user_waitlists( $id ) {
		return get_user_meta( $id, '_yith_wcwtl_products_list', true );
	}
}

if ( ! function_exists( 'yith_get_user_wailists' ) ) {
	/**
	 * // TODO: description.
	 *
	 * @param mixed $id //TODO: short description.
	 * @return mixed
	 * @deprecated use yith_get_user_waitlists instead
	 */
	function yith_get_user_wailists( $id ) {
		return yith_get_user_waitlists( $id );
	}
}

if ( ! function_exists( 'yith_waitlist_user_meta' ) ) {
	/**
	 * Save new waiting list in user meta
	 *
	 * @since  1.0.0
	 * @param object|int $product //TODO: short description.
	 * @param string     $email User email.
	 */
	function yith_waitlist_save_user_meta( $product, $email ) {
		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		$user = get_user_by( 'email', $email );

		if ( ! $user || ! $product ) {
			return;
		}

		$products = yith_get_user_waitlists( $user->ID );
		if ( ! is_array( $products ) ) {
			$products = array();
		}
		$products[] = $product->get_id();

		update_user_meta( $user->ID, '_yith_wcwtl_products_list', array_unique( $products ) );
	}
}

if ( ! function_exists( 'yith_waitlist_remove_user_meta' ) ) {
	/**
	 * Remove waiting list from user meta
	 *
	 * @since  1.0.0
	 * @param object|int $product Product Id.
	 * @param string     $email   User email.
	 */
	function yith_waitlist_remove_user_meta( $product, $email ) {

		$product = ( $product instanceof WC_Product ) ? $product->get_id() : absint( $product );

		$user = get_user_by( 'email', $email );

		if ( ! $user ) {
			return;
		}

		$products = yith_get_user_waitlists( $user->ID );
		$products = is_array( $products ) ? array_diff( $products, array( $product ) ) : array();

		update_user_meta( $user->ID, '_yith_wcwtl_products_list', $products );
	}
}


if ( ! function_exists( 'yith_waitlist_get_custom_style' ) ) {
	/**
	 * Get custom style from panel options
	 *
	 * @return string
	 * @since  1.1.3
	 */
	function yith_waitlist_get_custom_style() {

		// get size font.
		$size = yith_wcwtl_get_proteo_default( 'yith-wcwtl-general-font-size', '15' );
		$size = ( $size < 1 || $size > 99 ) ? 15 : intval( $size );

		$section_background    = get_option( 'yith-wcwtl-section-background', '#f9f9f9' );
		$section_padding       = yith_plugin_fw_get_dimensions_by_option( 'yith-wcwtl-section-padding' ) ? yith_plugin_fw_get_dimensions_by_option( 'yith-wcwtl-section-padding' ) : array(
			'top'    => '40px',
			'right'  => '40px',
			'bottom' => '40px',
			'left'   => '40px',
		);
		$section_border_radius = get_option( 'yith-wcwtl-section-border-radius', 15 );

		$add_background = yith_wcwtl_get_proteo_default(
			'yith-wcwtl-button-add-background',
			'#a46497',
			false,
			array(
				'key'    => 'yith-wcwtl-button-colors',
				'subkey' => 'background',
			)
		);

		$add_color = yith_wcwtl_get_proteo_default(
			'yith-wcwtl-button-add-text-color',
			'#ffffff',
			false,
			array(
				'key'    => 'yith-wcwtl-button-colors',
				'subkey' => 'text',
			)
		);

		$add_background_h = yith_wcwtl_get_proteo_default(
			'yith-wcwtl-button-add-background-hover',
			'#935386',
			false,
			array(
				'key'    => 'yith-wcwtl-button-colors',
				'subkey' => 'background-hover',
			)
		);

		$add_color_h = yith_wcwtl_get_proteo_default(
			'yith-wcwtl-button-add-text-color-hover',
			'#ffffff',
			false,
			array(
				'key'    => 'yith-wcwtl-button-colors',
				'subkey' => 'text-hover',
			)
		);

		$leave_background = yith_wcwtl_get_proteo_default(
			'yith-wcwtl-button-leave-background',
			'#a46497',
			false,
			array(
				'key'    => 'yith-wcwtl-button-leave-colors',
				'subkey' => 'background',
			),
		);

		$leave_color = yith_wcwtl_get_proteo_default(
			'yith-wcwtl-button-leave-text-color',
			'#ffffff',
			false,
			array(
				'key'    => 'yith-wcwtl-button-leave-colors',
				'subkey' => 'text',
			)
		);

		$leave_background_h =
            yith_wcwtl_get_proteo_default(
                    'yith-wcwtl-button-leave-background-hover',
                    '#935386',
			false,
                    array(
                            'key' => 'yith-wcwtl-button-leave-colors',
                            'subkey' => 'background-hover' ),
            );
		$leave_color_h      =
            yith_wcwtl_get_proteo_default(
                    'yith-wcwtl-button-leave-text-color-hover',
                    '#ffffff',
			false,
                    array(
                            'key' => 'yith-wcwtl-button-leave-colors',
                            'subkey' => 'text-hover',
                        ),
            );

		$notice_error_text_color   = is_array( get_option( 'yith-wcwtl-notice-colors',
			'#AF2323' ) ) ? get_option( 'yith-wcwtl-notice-colors', '#AF2323' )['error-text'] : '#AF2323';
		$notice_error_bg_color     = is_array( get_option( 'yith-wcwtl-notice-colors',
			'#f9f9f9' ) ) ? get_option( 'yith-wcwtl-notice-colors', '#f9f9f9' )['error-bg'] : '#f9f9f9';
		$notice_success_text_color = is_array( get_option( 'yith-wcwtl-notice-colors',
			'#149900' ) ) ? get_option( 'yith-wcwtl-notice-colors', '#149900' )['success-text'] : '#149900';
		$notice_success_bg_color   = is_array( get_option( 'yith-wcwtl-notice-colors',
			'#f7fae2' ) ) ? get_option( 'yith-wcwtl-notice-colors', '#AF2323' )['success-bg'] : '#f7fae2';


		$css = "
		    #yith-wcwtl-output { background-color: {$section_background}; padding: {$section_padding['top']} {$section_padding['right']} {$section_padding['bottom']} {$section_padding['left']}; border-radius: {$section_border_radius}px;}
		    #yith-wcwtl-output.success, #yith-wcwtl-output.subscribed{ background-color: {$notice_success_bg_color}; color: {$notice_success_text_color} }
		    #yith-wcwtl-output .button{background:{$add_background};color:{$add_color};}
			#yith-wcwtl-output .button:hover{background:{$add_background_h};color:{$add_color_h};}
			#yith-wcwtl-output .button.button-leave{background:{$leave_background};color:{$leave_color};}
			#yith-wcwtl-output .button.button-leave:hover{background:{$leave_background_h};color:{$leave_color_h};}
			#yith-wcwtl-output .yith-wcwtl-error{ background-color: {$notice_error_bg_color}; color: {$notice_error_text_color} }
			";

		/**
		 * APPLY_FILTERS: yith_waitlist_custom_style
		 *
		 * Filters the custom style.
		 *
		 * @param string $style Custom style
		 *
		 * @return string
		 */
		return apply_filters( 'yith_waitlist_custom_style', $css );
	}
}

if ( ! function_exists( 'yith_waitlist_textarea_editor_html' ) ) {
	/**
	 * Print textarea editor html for email options
	 *
	 * @access public
	 * @since  1.0.0
	 * @param string $key //TODO: short description.
	 * @param array  $data //TODO: short description.
	 * @param object $email //TODO: short description.
	 * @return string
	 */
	function yith_waitlist_textarea_editor_html( $key, $data, $email ) {

		$field = $email->get_field_key( $key );

		$defaults = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
		);

		$data = wp_parse_args( $data, $defaults );

		$editor_args = array(
			'wpautop'       => true, // use wpautop?.
			'media_buttons' => true, // show insert/upload button(s).
			'textarea_name' => esc_attr( $field ), // set the textarea name to something different, square brackets [] can be used here.
			'textarea_rows' => 20, // rows = "...".
			'tabindex'      => '',
			'editor_css'    => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
			'editor_class'  => '', // add extra class(es) to the editor textarea.
			'teeny'         => false, // output the minimal editor config used in Press This.
			'dfw'           => false, // replace the default fullscreen with DFW (needs specific DOM elements and css).
			'tinymce'       => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array().
			'quicktags'     => true, // load Quicktags, can be used to pass settings directly to Quicktags using an array().
		);

		ob_start();
		?>

		<tr valign="top">
			<th scope="row" class="select_categories">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo wp_kses_post( $email->get_tooltip_html( $data ) ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<div id="<?php echo esc_attr( $field ); ?>-container">
						<div
							class="editor"><?php wp_editor( $email->get_option( $key ), esc_attr( $field ), $editor_args ); ?></div>
						<?php echo wp_kses_post( $email->get_description_html( $data ) ); ?>
					</div>
				</fieldset>
			</td>
		</tr>

		<?php

		return ob_get_clean();
	}
}


if ( ! function_exists( 'yith_waitlist_policy_checkbox' ) ) {
	/**
	 * Add policy checkbox html for subscription form
	 *
	 * @since  1.5.0
	 * @return string
	 */
	function yith_waitlist_policy_checkbox() {
		// first check option.
        if ( get_option( 'yith-wcwtl-enable-privacy-checkbox', 'yes' ) !== 'yes' ) {
			return '';
		}
		$text = get_option( 'yith-wcwtl-privacy-checkbox-text', '' );
		$text = function_exists( 'wc_replace_policy_page_link_placeholders' ) ? wc_replace_policy_page_link_placeholders( $text ) : $text;

		$html  = '<label for="yith-wcwtl-policy-check">';
		$html .= '<input type="checkbox" name="yith-wcwtl-policy-check" id="yith-wcwtl-policy-check" value="yes">';
		$html .= '<span>' . wp_kses_post( $text ) . '</span></label>';

		return $html;
	}
}

if ( ! function_exists( 'yith_wcwtl_get_proteo_default' ) ) {
	/**
	 * Filter option default value if Proteo theme is active
	 *
	 * @since  1.5.1
	 * @param string  $key //TODO: short description.
	 * @param mixed   $default //TODO: short description.
	 * @param boolean $force_default //TODO: short description.
	 * @return string
	 */
	function yith_wcwtl_get_proteo_default( $key, $default = '', $force_default = false, $multiple = false ) {
		// get value from DB if requested and return if not empty.
		if ( ! $force_default ) {
            if( $multiple ){
                $value = get_option( $multiple['key'] );
                if( is_array(  $value ) ){
	                $value = $value[$multiple['subkey']];
                }
            }else{
	            $value = get_option( $key );
            }
		}

		if ( ! empty( $value ) ) {
			return $value;
		}

		if ( ! defined( 'YITH_PROTEO_VERSION' ) ) {
			return $default;
		}

		switch ( $key ) {
			case 'yith-wcwtl-general-font-size':
				$default = get_theme_mod( 'yith_proteo_base_font_size', 16 );
				break;
			case 'yith-wcwtl-general-font-color':
				$default = get_theme_mod( 'yith_proteo_base_font_color', '#404040' );
				break;
			case 'yith-wcwtl-button-add-background':
			case 'yith-wcwtl-button-leave-background':
				$default = get_theme_mod( 'yith_proteo_button_style_1_bg_color', '#448a85' );
				break;
			case 'yith-wcwtl-button-add-background-hover':
			case 'yith-wcwtl-button-leave-background-hover':
				$default = get_theme_mod( 'yith_proteo_button_style_1_bg_color_hover', yith_proteo_adjust_brightness( get_theme_mod( 'yith_proteo_main_color_shade', '#448a85' ), 0.2 ) );
				break;
			case 'yith-wcwtl-button-add-text-color':
			case 'yith-wcwtl-button-leave-text-color':
				$default = get_theme_mod( 'yith_proteo_button_style_1_text_color', '#ffffff' );
				break;
			case 'yith-wcwtl-button-add-text-color-hover':
			case 'yith-wcwtl-button-leave-text-color-hover':
				$default = get_theme_mod( 'yith_proteo_button_style_1_text_color_hover', '#ffffff' );
				break;

		}

		return $default;
	}
}


if ( ! function_exists( 'yith_wcwtl_verify_nonce' ) ) {
	/**
	 * Verify nonce.
	 *
	 * @param string $name Input name used to identify the nonce.
	 * @param string $action Action name used to identify the nonce.
	 *
	 * @return bool
	 */
	function yith_wcwtl_verify_nonce( $name, $action ) {
		return isset( $_REQUEST[ $name ] ) && wp_verify_nonce( wp_unslash( $_REQUEST[ $name ] ), $action );
	}
}