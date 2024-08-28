<?php
/**
 * REST API Action.
 * 
 * @package ULTP\REST_API
 * @since v.1.0.0
 */
namespace ULTP;

defined('ABSPATH') || exit;

/**
 * REST_API class.
*/
class REST_API {
    
    /**
	 * Setup class.
	 *
	 * @since v.1.0.0
	 */
    public function __construct() {
        add_action( 'rest_api_init', array($this, 'ultp_register_route') );
    }

    /**
	 * REST API Action
     * 
     * @since v.1.0.0
	 * @return NULL
	*/
    public function ultp_register_route() {
        register_rest_route( 'ultp', 'common', array(
                'methods' => \WP_REST_Server::READABLE,
                'args' => array('wpnonce' => []),
                'callback' => array($this,'ultp_route_common_data'),
                'permission_callback' =>  function () {
                    return current_user_can( 'edit_posts' );
                },
            )
        );
        register_rest_route(
			'ultp',
			'/fetch_posts/',
			array(
				array(
					'methods'  => 'POST',
                    'args' => array(),
                    'callback' => array($this, 'ultp_route_post_data'),
                    'permission_callback' =>  function () {
						return current_user_can( 'edit_posts' );
					},
				)
			)
		);
        register_rest_route(
			'ultp',
			'/specific_taxonomy/',
			array(
				array(
					'methods'  => 'POST',
                    'args' => array(),
                    'callback' => array($this, 'ultp_route_taxonomy_info_data'),
                    'permission_callback' =>  function () {
						return current_user_can( 'edit_others_posts' );
					},
				)
			)
		);
        register_rest_route(
			'ultp/v1',
			'/search/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'search_settings_action'),
					'permission_callback' => function () {
						return current_user_can('edit_others_posts');
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'ultp/v2',
			'/template_page_insert/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'template_page_insert'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'ultp/v2',
			'/addon_block_action/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'addon_block_action'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'ultp/v2',
			'/premade_wishlist_save/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'premade_wishlist_save'),
					'permission_callback' => function () {
						return current_user_can('edit_others_posts');
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'ultp/v2',
			'/save_plugin_settings/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'save_plugin_settings'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
					'args' => array()
                )
            )
        );
        register_rest_route(
			'ultp',
			'/ultp_search_data/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'ultp_search_result'),
                    'permission_callback' => '__return_true'
				)
			)
		);
        register_rest_route(
			'ultp/v2',
			'/get_all_settings/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'get_all_settings'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'ultp/v2', 
			'/dashborad/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'get_dashboard_callback'),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'ultp/v2', 
			'/wizard_site_status/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'wizard_site_status_callback'),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'ultp/v2', 
			'/send_initial_plugin_data/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'send_initial_plugin_data_callback'),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'ultp/v2', 
			'/initial_setup_complete/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'initial_setup_complete_callback'),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'ultp/v2', 
			'/custom_tax/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'custom_tax_callback'),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'ultp/v2', 
			'/init_site_dark_logo/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'init_site_dark_logo_callback'),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'ultp/v2', 
			'/init_site_dark_logo/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'init_site_dark_logo_callback'),
					'permission_callback' => function () {
						return current_user_can( 'edit_others_posts' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'ultp/v2', 
			'/get_ultp_image_size/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'get_custom_image_size'),
                    'permission_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'ultp/v2',
			'/template_action/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'template_page_action'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
					'args' => array()
				)
			)
		);
    }

    /**
	 * Delete Template Page and Handle builder, saved templates, custom font actions
     * 
     * @since v.2.6.6 // Shifted from RequestAPI since v4.0.3
     * @param STRING
	 * @return ARRAY | Success Message
	*/
    public function template_page_action($server) {
        $post = $server->get_params();
        $message = '';
        $s_Type = isset($post['type']) ? ultimate_post()->ultp_rest_sanitize_params($post['type']) : '';
        $s_id = isset($post['id']) ? ultimate_post()->ultp_rest_sanitize_params($post['id']) : '';
        $s_status = isset($post['status']) ? ultimate_post()->ultp_rest_sanitize_params($post['status']) : '';

        if ( $s_Type && $s_id ) {
            if ( $s_Type == 'delete' ) {
                if ( isset($post['section']) && $post['section'] == 'builder' ) { // phpcs:ignore WordPress.Security
                    $conditions = get_option('ultp_builder_conditions', []);
                    $builder_type = get_post_meta( $s_id, '__ultp_builder_type', true );
                    if ( isset($conditions[$builder_type][$s_id]) ) {
                        unset($conditions[$builder_type][$s_id]);
                        update_option('ultp_builder_conditions', $conditions);
                    }
                }
                wp_delete_post( $s_id, true);
                $message = __('Template has been deleted.', 'ultimate-post');
            } else if ( $s_Type == 'duplicate' ) {
                $fromBuilder = ( isset($post['section']) && $post['section'] == 'builder' ) ? true : false; // phpcs:ignore WordPress.Security
                $post_id = $s_id;
                $r_post = get_post( $post_id );
                $current_user = wp_get_current_user();
                $new_post_author = $current_user->ID;
                if ( isset( $r_post ) && $r_post != null ) {
                    $args = array(
                        'post_author'    => $new_post_author,
                        'post_content'   => str_replace('u0022', '\u0022', $r_post->post_content),
                        'post_excerpt'   => $r_post->post_excerpt,
                        'post_name'      => $r_post->post_name,
                        'post_status'    => $fromBuilder ? 'draft' : $r_post->post_status,
                        'post_title'     => $r_post->post_title,
                        'post_type'      => $r_post->post_type,
                    );
                }
                $new_post_id = wp_insert_post( $args );

                $css = get_post_meta( $post_id, '_ultp_css', true );
                update_post_meta( $new_post_id, '_ultp_css', $css );
                update_post_meta( $new_post_id, '_ultp_active', 'yes' );

                if ( $fromBuilder ) {
                    $type = get_post_meta( $post_id, '__ultp_builder_type', true );
                    update_post_meta( $new_post_id, '__ultp_builder_type', $type );

                    $width = get_post_meta( $post_id, '__container_width', true );
                    update_post_meta( $new_post_id, '__container_width', $width );
                    
                    $sidebar = get_post_meta( $post_id, '__builder_sidebar', true );
                    update_post_meta( $new_post_id, '__builder_sidebar', $sidebar );
    
                    $widget_area = get_post_meta( $post_id, '__builder_widget_area', true );
                    update_post_meta( $new_post_id, '__builder_widget_area', $widget_area );

                    $conditions = get_option('ultp_builder_conditions', array());
                    if ($conditions && $type) {
                        if (isset($conditions[$type][$post_id])) {
                            $conditions[$type][$new_post_id] = $conditions[$type][$post_id];
                            update_option('ultp_builder_conditions', $conditions);
                        }
                    }
                }
                $message = __('Template has been duplicated.', 'ultimate-post');
            } else if ( $s_Type == 'status') {
                if ( $s_status ) {
                    wp_update_post(array(
                        'ID' => $s_id,
                        'post_status' => $s_status
                    ));
                }
                $message = __('Status has been changed.', 'ultimate-post');
            }
        }
        
        return array(
            'success' => true,
            'message' => $message
        );
    }

    /**
     * Initial Plugin Setup Complete
     *
     * * @since v.2.8.1
     * @return STRING
    */
    public static function initial_setup_complete_callback($server) {
        $post = $server->get_params();
        if ( ! (isset($post['wpnonce']) && wp_verify_nonce( sanitize_key(wp_unslash($post['wpnonce'])), 'ultp-nonce' )) ) {
            die();
		}
        ultimate_post()->set_setting('init_setup', 'yes');
        return rest_ensure_response([
            'success' => true, 
            'redirect' => admin_url('admin.php?page=ultp-settings#home'),
        ]);
    }

    /**
     * Send Plugin Data When Initial Setup
     *
     * * @since v.2.8.1
     * @return STRING
    */
    public function send_initial_plugin_data_callback($server) {
        $post = $server->get_params();
        if ( ! (isset($post['wpnonce']) && wp_verify_nonce( sanitize_key(wp_unslash($post['wpnonce'])), 'ultp-nonce' )) ) {
            die();
		}
        

        $site = isset($post['site']) ? ultimate_post()->ultp_rest_sanitize_params( $post['site'] ) : '';

        require_once ULTP_PATH.'classes/Deactive.php';
        $obj = new \ULTP\Deactive();
        $obj->send_plugin_data('postx_wizard', $site);
    }

    /**
     * wizard_site_status_callback
     *
     * * @since v.3.0.0
     * @return STRING
    */
    public static function wizard_site_status_callback() {
        if ( ! (isset($_POST['wpnonce']) && wp_verify_nonce( sanitize_key(wp_unslash($_POST['wpnonce'])), 'ultp-nonce' )) ) {
			die();
		}
		if ( isset( $_POST['siteType'] ) ) {
            $site_type = ultimate_post()->ultp_rest_sanitize_params( $_POST['siteType'] );
			update_option( '__ultp_site_type', $site_type );
		}
        require_once ULTP_PATH.'classes/Deactive.php';
        $obj = new \ULTP\Deactive();
        $obj->send_plugin_data('postx_wizard', $site_type ? $site_type : 'other');

        return rest_ensure_response( ['success' => true ]);
    }

    /**
	 * Save Settings of Option Panel
     * 
     * @since v.3.0.0
	 * @return NULL
	*/
    public function get_all_settings($server) {
        return rest_ensure_response(['success' => true, 'settings' => ultimate_post()->get_setting()]);
    }
    /**
	 * Save Settings of Option Panel
     * 
     * @since v.3.0.0
	 * @return NULL
	*/
    public function save_plugin_settings($server) {
        $post = $server->get_params();
        $data = ultimate_post()->ultp_rest_sanitize_params($post['settings']);
        if (count($data) > 0) {
            foreach ($data as $key => $val) {
                ultimate_post()->set_setting($key, $val);
            }
            do_action('ultimate_post_after_setting_save');
        }
        return rest_ensure_response([
            'success' => true, 
            'message' => __('You have successfully saved the settings data.', 'ultimate-post') , 
            'wishListArr' => wp_json_encode($data)]);
    }

    /**
	 * Save and get premade_wishlist_save
     * 
     * @since v.3.0.0
     * @param STRING
	 * @return ARRAY | Inserted Post Url 
	*/
    public function premade_wishlist_save($server) {
        $post = $server->get_params();
        $id = isset($post['id'])? ultimate_post()->ultp_rest_sanitize_params($post['id']):'';
        $action = isset($post['action'])? ultimate_post()->ultp_rest_sanitize_params($post['action']):'';
        $wishListArr = get_option('ultp_premade_wishlist', []);
        $request_type = isset($post['type'])?ultimate_post()->ultp_rest_sanitize_params($post['type']):'';

        if ($id && $request_type != 'fetchData') {
            if($action == 'remove') {
                $index = array_search($id, $wishListArr);
                if ($index !== false) {
                    unset($wishListArr[$index]);
                }
            } else {
                if (!in_array($id, $wishListArr)) {
                    array_push($wishListArr,  $id );
                }
            }
            update_option('ultp_premade_wishlist', $wishListArr);
        }
        return rest_ensure_response([
            'success' => true, 
            'message' => $action == 'remove' ? __('Item has been removed from wishlist.', 'ultimate-post') : __('Item added to wishlist.', 'ultimate-post'),
            'wishListArr' => wp_json_encode($wishListArr)]
        );
    }
    /**
	 * Save addon / blocks on/off data 
     * 
     * @since v.3.0.0
     * @param STRING
	 * @return ARRAY | Inserted Post Url 
	*/
    public function addon_block_action($server) {
        $post = $server->get_params();
        $addon_name = isset($post['key'])? ultimate_post()->ultp_rest_sanitize_params($post['key']):'';
        $addon_value = isset($post['value'])? ultimate_post()->ultp_rest_sanitize_params($post['value']):'';
        if ($addon_name) {
            $addon_data = ultimate_post()->get_setting();
            $addon_data[$addon_name] = $addon_value;
            $GLOBALS['ultp_settings'][$addon_name] = $addon_value;
            update_option('ultp_options', $addon_data);
        }
        return array( 
            'success' => true, 
            'message' => ($addon_value == 'true' || $addon_value == 'false') ? ($addon_value == 'true' ? __('The addon has been enabled.', 'ultimate-post') : __('The addon has been disabled.', 'ultimate-post') ) : ($addon_value == 'yes' ? __('The block has been enabled.', 'ultimate-post') : __('The block has been disabled.', 'ultimate-post')) 
        );
    }
    /**
	 * Saved Template & Custom Font Actions 
     * 
     * @since v.3.0.0
     * @param STRING
	 * @return ARRAY | Inserted Post Url 
	*/
    public function get_dashboard_callback($server) {
        $post = $server->get_params();
        $request_type = isset($post['type'])?ultimate_post()->ultp_rest_sanitize_params($post['type']):'';
        $pType = isset($post['pType'])?ultimate_post()->ultp_rest_sanitize_params($post['pType']):'';
        switch ($request_type) {

            case 'saved_templates':
                $post_per_page = 10;
                $data = [];
                $args = array(
                    'post_type' => $pType,
                    'post_status' => array('publish', 'draft'),
                    'posts_per_page' => $post_per_page,
                    'paged' => isset($post['pages'])?ultimate_post()->ultp_rest_sanitize_params($post['pages']):1
                );

                if (isset($post['search'])) {
                    $args['paged'] = 1;
                    $args['s'] = ultimate_post()->ultp_rest_sanitize_params($post['search']);
                }

                $the_query = new \WP_Query( $args );
                if ( $the_query->have_posts() ) {
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post();
                        $final = [
                            'id' => get_the_ID(),
                            'title' => get_the_title(),
                            'date' => get_the_modified_date('Y/m/d h:i a'),
                            'status' => get_post_status(),
                            'edit' => get_edit_post_link()
                        ];
    
                        if ($pType == 'ultp_custom_font') {
                            $final = array_merge($final ,['woff' => false,'woff2' => false,'ttf' => false,'svg' => false,'eot' => false]);
                            $settings = get_post_meta( get_the_ID(), '__font_settings', true );
                            foreach ($settings as $key => $value) {
                                if ($value['ttf']) { $final['ttf'] = true; }
                                if ($value['svg']) { $final['svg'] = true; }
                                if ($value['eot']) { $final['eot'] = true; }
                                if ($value['woff']) { $final['woff'] = true; }
                                if ($value['woff2']) { $final['woff2'] = true; }
                            }
                            $final['font_settings'] = $settings;
                        }
                        $data[] = $final;
                    }
                }
                wp_reset_postdata();
                return array(
                    'success' => true, 
                    'data' => $data,
                    'new' => ($pType == 'ultp_custom_font' ? admin_url('post-new.php?post_type=ultp_custom_font') : admin_url('post-new.php?post_type=ultp_templates')),
                    'found' => $the_query->found_posts,
                    'pages' => $the_query->max_num_pages
                );
            break;
            
            case 'action_draft':
            case 'action_publish':
                if (isset($post['ids']) && is_array($post['ids'])) {
                    $post_ids = ultimate_post()->ultp_rest_sanitize_params($post['ids']);
                    foreach ($post_ids as $id) {
                        wp_update_post(array(
                            'ID' => $id,
                            'post_status' => str_replace('action_', '',$request_type)
                        ));
                    }
                    return array(
                        'success' => true, 
                        'message' => __('Status changed for selected items.', 'ultimate-post')
                    );
                }
            break;

            case 'license_action':
                $message = '';
                
                if ( isset($post['edd_ultp_license_key']) && function_exists('ultimate_post_pro') ) {
                    $is_success = false;
                    $license = trim( ultimate_post()->ultp_rest_sanitize_params( $post['edd_ultp_license_key'] ) );

                    if ($license && $license != '******************') {
                        update_option( 'edd_ultp_license_key', $license);
                        $api_params = array(
                            'edd_action' => 'activate_license',
                            'license'    => $license,
                            'item_id'    => 181,
                            'url'        => home_url()
                        );
                        
                        $response = wp_remote_post( 
                            'https://account.wpxpo.com', 
                            array( 
                                'timeout' => 15, 
                                'sslverify' => false, 
                                'body' => $api_params 
                            )
                        );
        
                        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
                            $message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __('An error occurred, please try again.', 'ultimate-post-pro');
                        } else {
                            $license_data = json_decode( wp_remote_retrieve_body( $response ) );
                            if ( false === $license_data->success ) {
                                update_option( 'edd_ultp_license_key', '');
                                switch( $license_data->error ) {
                                    case 'expired' :
                                        $message = sprintf(
                                            __('Your license key expired on %s.', 'ultimate-post'),
                                            date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                                        );
                                        break;
                                    case 'revoked' :
                                        $message = __('Your license key has been disabled.', 'ultimate-post');
                                        break;
                                    case 'missing' :
                                        $message = __('Invalid license.', 'ultimate-post');
                                        break;
                                    case 'invalid' :
                                    case 'site_inactive':
                                        $message = __( 'Your license is not active for this URL.', 'ultimate-post' );
                                        break;
                                    case 'item_name_mismatch':
                                        $message = __( 'This appears to be an invalid license key.', 'ultimate-post' );
                                        break;
                                    case 'no_activations_left':
                                        $message = __( 'Your license key has reached its activation limit.', 'ultimate-post' );
                                        break;
                                    default :
                                        $message = __( 'An error occurred, please try again.', 'ultimate-post' );
                                        break;
                                }
                            } else {
                                $message = __('Your license key has been updated.', 'ultimate-post');
                                $is_success = true;
                            }
                            update_option( 'edd_ultp_license_status', $license_data->license );
                            update_option( 'edd_ultp_license_expire', $license_data->expires );
                            update_option( 'edd_ultp_activations_left', $license_data->activations_left );

                        }
                    } else {
                        $message = __( 'Invalid license.', 'ultimate-post' );
                    }
                } else {
                    $message = __( 'Invalid license.', 'ultimate-post' );
                    if (!function_exists('ultimate_post_pro')) {
                        $message = __( 'Install & Acivate PostX Pro plugin.', 'ultimate-post' );
                    }
                }
                return array('success' => $is_success, 'message' => $message);
            break;

            case 'action_delete':
                if (isset($post['ids']) && is_array($post['ids'])) {
                    $post_ids = ultimate_post()->ultp_rest_sanitize_params($post['ids']);
                    foreach ($post_ids as $id) {
                        wp_delete_post( $id, true); 
                    }
                }
                return array(
                    'success' => true, 
                    'message' => __('The selected item is deleted.', 'ultimate-post')
                );

            case 'support_data':
                $user_info = get_userdata( get_current_user_id() );
                $name = $user_info->first_name . ($user_info->last_name ? ' ' . $user_info->last_name : '');
                return array(
                    'success' => true, 
                    'data' => array(
                        'name' => $name ? $name : $user_info->user_login,
                        'email' => $user_info->user_email
                    )
                );
                
            case 'support_action':
                $api_params = array(
                    'user_name' => isset($post['name'])? ultimate_post()->ultp_rest_sanitize_params($post['name']):'',
                    'user_email' =>isset($post['email'])? sanitize_email($post['email']):'',
                    'subject' => isset($post['subject'])? ultimate_post()->ultp_rest_sanitize_params($post['subject']):'',
                    'desc' => isset($post['desc'])? sanitize_textarea_field($post['desc']):'',
                );
                $response = wp_remote_get(
                    'https://wpxpo.com/wp-json/v2/support_mail', 
                    array(
                        'method' => 'POST',
                        'timeout' => 120,
                        'body' =>  $api_params
                    )
                );
                $response_data = json_decode($response['body']);
                $success = ( isset($response_data->success) && $response_data->success ) ? true : false;

                return array(
                    'success' => $success,
                    'message' => $success ? __('New Support Ticket has been Created.', 'ultimate-post') : __('New Support Ticket is not Created Due to Some Issues.', 'ultimate-post')
                );
            break;

            case 'helloBarAction':
                set_transient( 'ultp_helloBar'.ULTP_HELLOBAR, 'hide', 1296000);
                return array(
                    'success' => true, 
                    'message' => __('Notice is removed.', 'ultimate-post')
                );
            break;
            case 'generalDiscountAction':
                set_transient( 'ultp_generalDiscount', 'hide', 60 * DAY_IN_SECONDS);
                return array(
                    'success' => true, 
                    'message' => __('Notice is removed.', 'ultimate-post')
                );
            break;

            default:
                # code...
                break;
        }
        
    }

    /**
	 * Insert Post For Imported Template
     * 
     * @since v.2.6.7
     * @param STRING
	 * @return ARRAY | Inserted Post Url 
	*/
    public function template_page_insert($server) {
        $post = $server->get_params();
        $new_page = array(
            'post_title' => isset($post['title'])?ultimate_post()->ultp_rest_sanitize_params($post['title']):'',
            'post_type' => 'page',
            'post_content' => $post['blockCode'],	
            'post_status' => 'draft',
        );
        $post_id = wp_insert_post( $new_page );
        return array( 'success' => true, 'link' => get_edit_post_link($post_id));
    }


    public function search_settings_action($server) {
		global $wpdb;
        $post = $server->get_params();
        $request_type = isset($post['type'])?ultimate_post()->ultp_rest_sanitize_params($post['type']):'';
        $condition_type = isset($post['condition'])?ultimate_post()->ultp_rest_sanitize_params($post['condition']):'';
        $term_type = isset($post['term'])?ultimate_post()->ultp_rest_sanitize_params( $post['term'] ):'';
        switch ($request_type) {
            case 'posts':
            case 'allpost':
            case 'postExclude':
                $post_type = array('post');
                if ($request_type == 'allpost') {
                    $post_type = array_keys(ultimate_post()->get_post_type());
                } else if ($request_type == 'postExclude') {
                    $post_type = array($condition_type);
                }
                $args = array(
                    'post_type'         => $post_type,
                    'post_status'       => 'publish',
                    'posts_per_page'    => 10,
                );
                if (is_numeric($term_type)) {
                    $args['p'] = $term_type;
                } else {
                    $args['s'] = $term_type;
                }

                $post_results = new \WP_Query($args);
                $data = [];
                if (!empty($post_results)) {
                    while ( $post_results->have_posts() ) {
                        $post_results->the_post();
                        $id = get_the_ID();
                        $title = html_entity_decode(get_the_title());
                        $data[] = array('value'=>$id, 'title'=>($title?'[ID: '.$id.'] '.$title:('[ID: '.$id.']')));
                    }
                    wp_reset_postdata();
                }
                return ['success' => true, 'data' => $data];
                break;

            case 'author':
                $term = '%'. $wpdb->esc_like( $term_type ) .'%';
                $post_results = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
                    $wpdb->prepare(
                        "SELECT ID, display_name 
                        FROM $wpdb->users 
                        WHERE user_login LIKE %s OR ID LIKE %s OR user_nicename LIKE %s OR user_email LIKE %s OR display_name LIKE %s LIMIT 10", $term, $term, $term, $term, $term 
                    )
                );
                $data = [];
                if (!empty($post_results)) {
                    foreach ($post_results as $key => $val) {
                        $data[] = array('value'=>$val->ID, 'title'=>'[ID: '.$val->ID.'] '.$val->display_name);
                    }
                }
                return ['success' => true, 'data' => $data];
                break;

            case 'taxvalue':
                $split = explode('###', $condition_type);
                $condition = $split[1] != 'multiTaxonomy' ? array($split[1]) : get_object_taxonomies($split[0]);
                $args = array(
                    'taxonomy'  => $condition,
                    'fields'    => 'all',
                    'orderby'   => 'id', 
                    'order'     => 'ASC',
                    'name__like'=> $term_type
                );
                if (is_numeric($term_type)) {
                    unset($args['name__like']);
                    $args['include'] = array($term_type);
                }

                $post_results = get_terms( $args );
                $data = [];
                if (!empty($post_results)) {
                    foreach ($post_results as $key => $val) {
                        if ($split[1] == 'multiTaxonomy') {
                            $data[] = array('value'=>$val->taxonomy.'###'.$val->slug, 'title'=> '[ID: '.$val->term_id.'] '.$val->taxonomy.': '.$val->name);
                        } else {
                            $data[] = array('value'=>urldecode($val->slug), 'title'=>'[ID: '.$val->term_id.'] '.$val->name, 'live_title'=> $val->name);
                        }
                    }
                }
                return ['success' => true, 'data' => $data];
                break;

            case 'taxExclude':
                $condition = get_object_taxonomies($condition_type);
                $args = array(
                    'taxonomy'  => $condition,
                    'fields'    => 'all',
                    'orderby'   => 'id', 
                    'order'     => 'ASC',
                    'name__like'=> $term_type
                ); 
                if (is_numeric($term_type)) {
                    unset($args['name__like']);
                    $args['include'] = array($term_type);
                }
                $post_results = get_terms( $args );
                $data = [];
                if (!empty($post_results)) {
                    foreach ($post_results as $key => $val) {
                        $data[] = array('value'=>$val->taxonomy.'###'.$val->slug, 'title'=> '[ID: '.$val->term_id.'] '.$val->taxonomy.': '.$val->name);
                    }
                }
                return ['success' => true, 'data' => $data];
                break;
                // allPostType
            case 'allPostType': 
                $all_types = array_values(get_post_types( array( 'public' => true ), 'names' ));
                $postType = array();
                foreach($all_types as $type){
                    $postType[] = array(
                        'title' => $type,
                        'value' => $type,
                    );
                };

                return ['success' => true, 'data' => $postType ];
            default:
                return ['success' => true, 'data' => [['value'=>'', 'title'=>'- Select -']]];
                break;
        }
	}

    /**
	 * Post Data Response of REST API
     * 
     * @since v.1.0.0
     * @param MIXED | Pram (ARRAY), Local (BOOLEAN)
	 * @return ARRAY | Response Image Size as Array
	*/
    public function ultp_route_post_data($prams) {
        $prams = $prams->get_params();
        if ( !(isset($prams['wpnonce']) && wp_verify_nonce( sanitize_key(wp_unslash($prams['wpnonce'])), 'ultp-nonce' )) ) {
			die();
		}
        $data = [];
        $loop = new \WP_Query( ultimate_post()->get_query(ultimate_post()->ultp_rest_sanitize_params($prams)) );
        $max_tax = isset($prams['maxTaxonomy']) && $prams['maxTaxonomy'] ? ( ultimate_post()->ultp_rest_sanitize_params($prams['maxTaxonomy']) == '0' ? 0 : ultimate_post()->ultp_rest_sanitize_params($prams['maxTaxonomy']) ) : 30 ;

        if ($loop->have_posts()) {
            while($loop->have_posts()) {
                $loop->the_post(); 
                $var                = array();
                $post_id            = get_the_ID();
                $user_id            = get_the_author_meta('ID');
                $content_data       = get_the_content();
                $var['ID']          = $post_id;
                $var['title']       = get_the_title();
                $var['permalink']   = get_permalink();
                $var['seo_meta']    = ultimate_post()->get_excerpt($post_id, 1);
                $var['excerpt']     = wp_strip_all_tags($content_data);
                $var['excerpt_full']= wp_strip_all_tags(get_the_excerpt());
                $var['time']        = (int)get_the_date('U')*1000;
                $var['timeModified']= (int)get_the_modified_date('U')*1000;
                $var['post_time']   = human_time_diff(get_the_time('U'),current_time('U'));
                $var['view']        = get_post_meta(get_the_ID(),'__post_views_count', true);
                $var['comments']    = get_comments_number();
                $var['author_link'] = get_author_posts_url($user_id);
                $var['avatar_url']  = get_avatar_url($user_id);
                $var['display_name']= get_the_author_meta('display_name');
                $var['reading_time']= ceil(strlen($content_data)/1200);
                $post_video = get_post_meta($post_id, '__builder_feature_video', true);
                // Video 
                if ($post_video) {
                    $var['has_video'] = ultimate_post()->get_youtube_id($post_video);
                }
                // image
                $image_sizes = ultimate_post()->get_image_size();
                $image_src = array();
                if (has_post_thumbnail()) {
                    $thumb_id = get_post_thumbnail_id($post_id);
                    foreach ($image_sizes as $key => $value) {
                        $image_src[$key] = wp_get_attachment_image_src($thumb_id, $key, false)[0];
                    }
                    $var['image'] = $image_src;
                } elseif(isset($prams['fallbackImg']['id'])) {
                    foreach ($image_sizes as $key => $value) {
                        $image_src[$key] = wp_get_attachment_image_src(esc_attr($prams['fallbackImg']['id']), $key, false)[0];
                    }
                    $var['image'] = $image_src;
                    $var['is_fallback'] = true;
                }

                // tag
                $tag = get_the_terms($post_id, (isset($prams['tag'])?esc_attr($prams['tag']):'post_tag'));
                if (!empty($tag)) {
                    $v = array();
                    foreach ($tag as $k => $val) {
                        if ($k >= $max_tax) { break; }
                        $v[] = array('slug' => $val->slug, 'name' => $val->name, 'url' => get_term_link($val->term_id));
                    }
                    $var['tag'] = $v;
                }

                // Taxonomy
                $cat = get_the_terms($post_id, (isset($prams['taxonomy'])?esc_attr($prams['taxonomy']):'category'));

                if(!empty($cat)){
                    $v = array();
                    foreach ($cat as $k => $val) {
                        if ($k >= $max_tax) { break; }
                        $v[] = array('slug' => $val->slug, 'name' => $val->name, 'url' => get_term_link($val->term_id), 'color' => get_term_meta($val->term_id, 'ultp_category_color', true));
                    }
                    $var['category'] = $v;
                }
                $data[] = $var;
            }
            wp_reset_postdata();
        }
        return rest_ensure_response( $data);
    }


    /**
	 * Taxonomy Data Response of REST API
     * 
     * @since v.1.0.0
     * @param ARRAY | Parameter (ARRAY)
	 * @return ARRAY | Response Taxonomy List as Array
	*/
    public function ultp_route_common_data($prams) {
        if ( ! (isset($_REQUEST['wpnonce']) && wp_verify_nonce( sanitize_key(wp_unslash($_REQUEST['wpnonce'])), 'ultp-nonce' )) ) {
            return rest_ensure_response([]);
		}
        
        $all_post_type = ultimate_post()->get_post_type();
        $data = array();
        foreach ($all_post_type as $post_type_slug => $post_type ) {
            $data_term = array();
            $taxonomies = get_object_taxonomies($post_type_slug);
            foreach ($taxonomies as $key => $taxonomy_slug) {
                $taxonomy_value = get_terms(array(
                    'taxonomy' => $taxonomy_slug,
                    'hide_empty' => false
                ));
                if (!is_wp_error($taxonomy_value)) {
                    $data_tax = array();
                    foreach ($taxonomy_value as $k => $taxonomy) {
                        $data_tax[urldecode_deep($taxonomy->slug)] = $taxonomy->name;
                    }
                    if (count($data_tax) > 0) {
                        $data_term[$taxonomy_slug] = $data_tax;
                    }
                }
            }
            $data[$post_type_slug] = $data_term;
        }
        // Global Customizer
        $global = get_option('postx_global', []);
        // Image Size
        $image_sizes = ultimate_post()->get_image_size();

        return rest_ensure_response(['taxonomy' => $data, 'global' => $global, 'image' => wp_json_encode($image_sizes), 'posttype' => wp_json_encode($all_post_type)]);
    }

    /**
	 * Specific Taxonomy Data Response of REST API
     * 
     * @since v.1.0.0
     * @param ARRAY | Parameter (ARRAY)
	 * @return ARRAY | Response Taxonomy List as Array
	 */
    public function ultp_route_taxonomy_info_data($prams) {
        $prams = $prams->get_params();
        if ( ! (isset($prams['wpnonce']) && wp_verify_nonce( sanitize_key(wp_unslash($prams['wpnonce'])), 'ultp-nonce' )) ) {
            return rest_ensure_response([]);
		}
        $taxValue = isset($prams['taxValue'])?ultimate_post()->ultp_rest_sanitize_params($prams['taxValue']):'';
        $queryNumber = isset($prams['queryNumber'])?ultimate_post()->ultp_rest_sanitize_params($prams['queryNumber']):'';
        $taxType = isset($prams['taxType'])?ultimate_post()->ultp_rest_sanitize_params($prams['taxType']):'';
        $taxSlug = isset($prams['taxSlug'])?ultimate_post()->ultp_rest_sanitize_params($prams['taxSlug']):'';
        $archiveBuilder = isset($prams['archiveBuilder'])?ultimate_post()->ultp_rest_sanitize_params($prams['archiveBuilder']):'';

        return rest_ensure_response( ultimate_post()->get_category_data(json_decode($taxValue), $queryNumber, $taxType, $taxSlug,  $archiveBuilder) );
    }

    /**
	 * Get Taxonomies for Custom Post Type
     * 
     * @since v.3.2.8
     * @param array $params
	 * @return array
	 */
    public function custom_tax_callback($prams) {

        $post_types = isset($prams['postTypes']) ? ultimate_post()->ultp_rest_sanitize_params($prams['postTypes']) : array();

        $data = array(
            array(
                'id' => '_all',
                'name' => __('All', 'ultimate-post')
            )
        );

        foreach ($post_types as $post_type) {
            $taxonomies = get_object_taxonomies($post_type);
            foreach ($taxonomies as $taxonomy) {
                $terms = get_terms(array(
                    'taxonomy' => $taxonomy,
                    'hide_empty' => false
                ));
                foreach ($terms as $term) {
                    $data[] = array(
                        'id' => $term->slug,
                        'name' => $term->name
                    );
                }
            }
        }

        return rest_ensure_response($data);
    }

    /**
	 * Search Block Data Showing
     * 
     * @since v.2.9.9
     * @param STRING
	 * @return ARRAY | Inserted Post Url 
	*/
    public function ultp_search_result($server) {
        $post = $server->get_params();
        $searchText = isset($post['searchText'])?ultimate_post()->ultp_rest_sanitize_params($post['searchText']):'';
        $paged = isset($post['paged'])?ultimate_post()->ultp_rest_sanitize_params($post['paged']):'';
        $postPerPage = isset($post['postPerPage'])?ultimate_post()->ultp_rest_sanitize_params($post['postPerPage']):'';
        $query_args = array(
            's' => $searchText,
            'paged' =>  $paged, 
            'compare' => 'LIKE',
            'orderby' => 'relevance',
            'posts_per_page' => $postPerPage,
        );
        if(isset($post['exclude']) && is_array($post['exclude']) && count($post['exclude']) > 0) {
            $post['exclude'] = ultimate_post()->ultp_rest_sanitize_params($post['exclude']);
            $post_exclude = array();
            foreach( $post['exclude'] as $data ){
                $post_exclude[$data['title']] = $data['title'];
            }
            $all_types = get_post_types( array( 'public' => true ), 'names' );
            $post_type = array_diff_key($all_types, $post_exclude);
            $query_args['post_type'] = $post_type;
        }
        $output = '';
        $query_result = new \WP_Query($query_args);

        if ($query_result->have_posts()) {
            while ($query_result->have_posts()) {
                $query_result->the_post(); 
                $post_id = get_the_ID();
                $title = get_the_title();
                
                $output .= '<div class="ultp-search-result__item">';
                    if ($post['image'] == 1 && has_post_thumbnail()) {
                        $thumb_id = get_post_thumbnail_id($post_id);
                        $output .= '<img class="ultp-searchresult-image" src='.wp_get_attachment_image_src($thumb_id, 'thumbnail', false)[0].' alt="'.$title.'"/>';
                    }
                    $output .= '<div class="ultp-searchresult-content">';
                        $output .= '<div class="ultp-rescontent-meta">';
                            // Category
                            $post_cat = get_the_terms($post_id, 'category');
                            if ($post['category'] == 1 && $post_cat && count($post_cat)) {
                                $output .= '<div class="ultp-searchresult-category">';
                                    foreach($post_cat as $cat){
                                        $output .= '<a href="'.get_term_link($cat->term_id).'">'.$cat->name.'</a>';
                                    }
                                $output .= '</div>';
                            }
                            // Author
                            if ($post['author'] == 1) {
                                $user_id = get_the_author_meta('ID');
                                $output .= '<a href="'.get_author_posts_url($user_id).'" class="ultp-searchresult-author">'.get_the_author_meta('display_name').'</a>';
                            }
                            // Date
                            if ($post['date'] == 1) {
                                $output .= '<div class="ultp-searchresult-publishdate">'.get_the_date('F j, Y').'</div>';
                            }
                        $output .= '</div>';
                        $output .= '<a href="'.get_permalink().'" class="ultp-searchresult-title">'.$title.'</a>';
                        if ($post['excerpt'] == 1) {
                            $output .= '<div class="ultp-searchresult-excerpt">'.wp_trim_words(get_the_excerpt(), isset($post['excerptLimit'])?ultimate_post()->ultp_rest_sanitize_params($post['excerptLimit']):55).'</div>';
                        }
                    $output .= '</div>';
                $output .= '</div>';
            }
        }
        
        return array('post_data' => $output, 'post_count' => $query_result->found_posts);
    }
    
    /**
	 * PostX Site Dark Logo Init
     * 
     * @since v.3.1.9
     * @param ARRAY 
	 * @return BOOOLEAN | Inserted Post Url 
	*/
    public function init_site_dark_logo_callback ($server) {
        $logo_data = $server->get_params();
        $success = true;
        if( isset($logo_data['logo']['url'] ) ){
            update_option( 'ultp_site_dark_logo', $logo_data['logo']['url'] );
        } else {
            $success = false;
        }
        return rest_ensure_response([
            'success' => $success,
        ]);
    }

    
    /**
	 * Getting Image Size
     * 
     * @since v.4.0.1     
     * @param ARRAY | Parameter (number)
	 * @return ARRAY | Image Size List as Array
	*/
    public function get_custom_image_size($server) {
        $img = $server->get_params();
        $image_src = array();
        $image_sizes = ultimate_post()->get_image_size();
        foreach ($image_sizes as $key => $value) {
            $image_src[$key] = wp_get_attachment_image_src($img['id'], $key, false)[0];
        }
        return rest_ensure_response( ['success' => true, 'size' => $image_src, 'id' => $img  ]);
    }
}