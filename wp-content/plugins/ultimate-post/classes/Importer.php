<?php
/**
 * Importer System
 * 
 * @package ULTP\Importer
 * @since 4.0.0
 */
namespace ULTP;

defined('ABSPATH') || exit;

/**
 * Importer class.
*/

class Importer {
    /**
	 * Setup class.
	 *
	 * @since 4.0.0
	 */
    public function __construct() {
		add_action('wp_ajax_install_required_plugin', array($this, 'install_required_plugin_callback'));
        add_action('rest_api_init', array($this, 'get_starter_rest_endpoint_callback'));
	}

    /**
	 * REST API Action
     * @since 4.0.0
	*/
    public function get_starter_rest_endpoint_callback() {
        register_rest_route(
            'ultp/v3',
            '/testing_importer/',
            array(
                array(
                    'methods'  => 'POST',
                    'callback' => array($this, 'testing_importer'),
                    'permission_callback' => function () {
                        return current_user_can('manage_options');
                    },
                    'args' => array()
                )
            )
        );
        register_rest_route(
            'ultp/v3',
            '/single_page_import/',
            array(
                array(
                    'methods'  => 'POST',
                    'callback' => array($this, 'single_page_import'),
                    'permission_callback' => function () {
                        return current_user_can('edit_others_posts');
                    },
                    'args' => array()
                )
            )
        );
        register_rest_route(
            'ultp/v3',
            '/deletepost_getnewsletters/',
            array(
                array(
                    'methods'  => 'POST',
                    'callback' => array($this, 'deletepost_getnewsletters'),
                    'permission_callback' => function () {
                        return current_user_can('manage_options');
                    },
                    'args' => array()
                )
            )
        );
        register_rest_route(
            'ultp/v3',
            '/starter_import_content/',
            array(
                array(
                    'methods'  => 'POST',
                    'callback' => array($this, 'starter_import_content_callback'),
                    'permission_callback' => function () {
                        return current_user_can('manage_options');
                    },
                    'args' => array()
                )
            )
        );
        register_rest_route(
            'ultp/v3',
            '/starter_dummy_post/',
            array(
                array(
                    'methods'  => 'POST',
                    'callback' => array($this, 'starter_dummy_post_callback'),
                    'permission_callback' => function () {
                        return current_user_can('manage_options');
                    },
                    'args' => array()
                )
            )
        );
    }

    /**
	 * Plugin Installation
     * @since 4.0.0
	*/
    public function install_required_plugin_callback() {
        if ( !wp_verify_nonce( sanitize_key( wp_unslash($_REQUEST['wpnonce']) ), 'ultp-nonce') ) {
            return '';
        }
    
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        if ( ! function_exists( 'plugins_api' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        }
        if ( ! class_exists( 'WP_Upgrader' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        }
        
        $msg = '';
        
        $all_plugins = get_plugins();
        $plugin = ultimate_post()->ultp_rest_sanitize_params(json_decode(stripslashes($_POST['plugin'])));
        
        if ( $plugin && isset($all_plugins) && is_array($all_plugins) && array_key_exists($plugin->path, $all_plugins) ) {
            $activated_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
            if ( is_array($activated_plugins) && in_array($plugin->path, $activated_plugins) ) {
                $msg = '_already_installed_activated';
            } else {
                $activate = activate_plugin( $plugin->path, '', false );
                $msg = is_wp_error( $activate ) ? '_only_activated_error' : '_only_activated_success' ;
            }
        } else {
            $upgrader = new \Plugin_Upgrader( new \WP_Ajax_Upgrader_Skin() );
            if ( isset($plugin->download) ) { 
                // Download
                $link = $api->download;
            } else { 
                // From Org
                $api = plugins_api(
                    'plugin_information',
                    array(
                        'slug' => explode('/', $plugin->path)[0],
                        'fields' => array(
                            'short_description' => false,
                            'sections' => false,
                            'requires' => false,
                            'rating' => false,
                            'ratings' => false,
                            'downloaded' => false,
                            'last_updated' => false,
                            'added' => false,
                            'tags' => false,
                            'compatibility' => false,
                            'homepage' => false,
                            'donate_link' => false,
                        ),
                    )
                );
                $link = $api->download_link;
            }
            $installed = $upgrader->install( $link );
            $activate = activate_plugin( $plugin->path, '', false );
    
            $msg = is_wp_error( $activate ) ? $link : '_download_activation_success' ;
        }
    
        if ( $msg == '_download_activation_error' || $msg == '_only_activated_error' ) {
            wp_send_json_error($msg);
        } else {
            wp_send_json_success($msg);
        }
    }

    /**
	 * starter_dummy_post_callback
     * @since 4.0.0
	*/
    function starter_dummy_post_callback($server) {
        $post = $server->get_params();
        $api_endpoint = isset($post['api_endpoint']) ? sanitize_text_field($post['api_endpoint']) : '';
        $import_dummy = isset($post['importDummy']) ? sanitize_text_field($post['importDummy']) : '';
    
        $response = wp_remote_get(
            $api_endpoint.'/wp-json/importer/site_all_posts', 
            array(
                'method' => 'POST',
                'timeout' => 120,
                'body' => array(
                    'type' => 'site_posts',
                    'license' => get_option('edd_ultp_license_key'),
                    'ultp_ver' => ULTP_VER,
                )
            )
        );
        $response_data = json_decode($response['body']);
        if( !$response_data->success ) {
            return rest_ensure_response([
                'success' => false,
                'returns' => $response_data
            ]); 
        }

        //Dummy Post and Taxonomy creation
        $importable_posts = $response_data->posts;
        $created_posts = $import_dummy != 'no' ? $this->starter_pack_dummy_post_creation($importable_posts) : [];
        return rest_ensure_response([
            'success' => true,
            'created_posts' => $created_posts
        ]);        
    }


    /**
	 * Starter Pack site import
     * @since 4.0.0
	*/
    function starter_import_content_callback($server) {
        $post = $server->get_params();
        $api_endpoint = isset($post['api_endpoint']) ? sanitize_text_field($post['api_endpoint']) : '';
    
        // draft existing builder template
        $builder_parsed_args = array(
            'post_type'              => 'ultp_builder',
            'post_status'            => 'publish',
            'posts_per_page'         => -1,
        );
        $builder_posts = new \WP_Query( $builder_parsed_args );
        if( is_array($builder_posts->posts) && !empty($builder_posts->posts) ) {
            foreach ( $builder_posts->posts as $post ) {
                wp_update_post(array(
                    'ID' =>  $post->ID,
                    'post_status' => 'draft'
                ));
            }
        }
    
        $response = wp_remote_get(
            $api_endpoint.'/wp-json/importer/single', 
            array(
                'method' => 'POST',
                'timeout' => 120,
                'body' => array(
                    'license' => get_option('edd_ultp_license_key'),
                    'ultp_ver' => ULTP_VER,
                )
            )
        );
        $response_data = json_decode($response['body']);
        if( !$response_data->success ) {
            return rest_ensure_response([
                'success' => false,
                'response_data' => $response_data,
            ]); 
        }
        
        // site logo handle
        if ( !get_option('site_logo', '') && isset($response_data->site_logo) && $response_data->site_logo ) {
            update_option('site_logo', $this->upload_post_cat_media( $response_data->site_logo, '', 'Site Logo'));
            
            if ( isset($response_data->dark_logo) && $response_data->dark_logo ) {
                $dark_logo = $this->upload_post_cat_media( $response_data->dark_logo, '', 'Site Dark Logo');
                update_option('ultp_site_dark_logo', wp_get_attachment_url( $dark_logo ));
            }
        }
    
        // Templates Insertion
        $inserted_meta  = [];
        $inserted_pages = [];
        $inserted_header_footer = [];
        $importable_pages = $response_data->content;
        $excludepages = json_decode(ultimate_post()->ultp_rest_sanitize_params(stripslashes($post['excludepages'])));

        if ( !empty($importable_pages) ) {
            foreach ($importable_pages as $key => $val) {
                $title = $val->name;
                if ( is_array($excludepages) && !in_array($title, $excludepages) ) {
                    $post_type = $val->type;
                    $p_content = str_replace(['u0022', 'u002d'], ['\u0022', '\u002d'], $val->content);
                    $p_content = str_replace(['u003c', 'u003e', 'currentPostId'], ['<', '>', 'current_PostId'], $p_content);
                    $post_id = wp_insert_post(array(
                        'post_title'     => $title,
                        'post_type'      => $post_type,
                        'post_status'    => 'publish',
                        'post_content' => $p_content
                    ));
                    if ( $post_id ) {
                        update_post_meta($post_id, '__ultp_starter_pack_post', true);
                        $inserted_pages[$post_id] = $title;
    
                        if ( isset($val->ultp_template) && $val->ultp_template === 'ultp_page_template' ) {
                            update_post_meta($post_id, '_wp_page_template', 'ultp_page_template');
                        }
                        if ( isset($val->home_page) && $val->home_page == 'home_page' ) {
                            if ( get_option('show_on_front', true) != 'page' ) {
                                update_option('show_on_front' , 'page');
                            }
                            update_option('page_on_front' , $post_id);
                            $inserted_header_footer['home_page'] = $post_id;
                        }
                        if ( isset($val->ultp_builder_type) && ( $val->ultp_builder_type == 'header' || $val->ultp_builder_type == 'footer' ) ) {
                            $inserted_header_footer[] = $post_id;
                        }
                        if ( $post_type == 'ultp_builder' && $val->ultp_builder_type ) {
                            $conditions_settings = get_option('ultp_builder_conditions', array());
                            $conditions = $response_data->conditions;
                            $ultp_builder_type = $val->ultp_builder_type;
                            $ultp_builder_id = $val->id;
                            $ultp_builder_conditions = $conditions->$ultp_builder_type;
                            $ultp_builder_conditions = $ultp_builder_conditions->$ultp_builder_id;
                            $conditions_settings[$ultp_builder_type][$post_id] = $ultp_builder_conditions;

                            update_post_meta($post_id, '__ultp_builder_type', $val->ultp_builder_type);
                            update_option('ultp_builder_conditions', $conditions_settings);
                        }
                        if ( isset($val->meta) && is_array($val->meta) ) {
                            foreach ($val->meta as $k => $v) {
                                update_post_meta( $post_id, $k, str_replace(['u0022', 'u002d'], ['\u0022', '\u002d'], $v) );
                            }
                        }
                        if ( isset($val->_ultp_css) ) {
                            $this->save_post_block_css( $post_id, str_replace(['u0022', 'u002d'], ['\u0022', '\u002d'], $val->_ultp_css), '' );
                        }
                        $inserted_meta[$post_id] = $val->_ultp_css;
                    }
                }
            }
        }

        // Menu Creation
        $menuCreated = array();
        $menu_item_parent = array();
        $response_menu = $response_data->menu;
        if ( !empty($response_menu) && is_array($response_menu)) {
            foreach ($response_menu as $key => $menu) {
                $menu_exists = wp_get_nav_menu_object( $menu->title );
                if ( $menu_exists ) {
                    wp_delete_term($menu_exists->term_id, $menu_exists->taxonomy);
                }
                $menu_id = wp_create_nav_menu($menu->title);
                if ( isset($menu->items) && $menu_id && !empty($menu->items) ) {
                    foreach ($menu->items as $key => $v) {
                        $inserted = array_search($v->title, $inserted_pages);
                        if ( $inserted || $v->type == 'custom' || $v->type == 'category' ) {
                            if ( 
                                ( $v->type == 'category' && get_term_by('name', $v->title, 'category') ) 
                                || $v->type != 'category'
                            ) {
                                $item = array(
                                    'menu-item-title' => $v->title,
                                    'menu-item-status' => 'publish'
                                );
                                $item['menu-item-object'] = $v->type;
                                $item['menu-item-type'] = $v->post_type;
                                if ( isset($v->menu_item_parent) && $v->menu_item_parent ) {
                                    $parent_id = $this->find_parent_nav_item(wp_get_nav_menu_items($menu_id), $v->menu_item_parent);
                                    $menu_item_parent[$v->title] = $parent_id;
                                    $item['menu-item-parent-id'] = $parent_id;
                                }
                                if ( $v->type == 'custom' ) {
                                    $item['menu-item-url'] = $v->url ? $v->url : home_url('/');
                                } else if( $v->type == 'category' ) {
                                    $fetched_term = get_term_by('name', $v->title, 'category');
                                    if ( $fetched_term ) {
                                        $item['menu-item-object-id'] = $fetched_term->term_id;
                                    }
                                } else {
                                    $item['menu-item-object-id'] = $inserted;
                                }
                                wp_update_nav_menu_item( $menu_id, 0, $item );
                            }
                        }
                    }
                    update_term_meta($menu_id, '__ultp_starter_pack_term', 'postx_term');
                    $menuCreated[$menu_id] = $item;
                }
            }
        }

        // Navigation Creation for Header Footer Builder
        $navCreated = array();
        $navigation = $response_data->navigation; // returned from importer site
        if ( !empty($inserted_header_footer) && !empty($navigation) ) {
            foreach ($inserted_header_footer as $key => $builderID) {
                $builder_post = get_post($builderID);
                $builder_post_content = $builder_post->post_content;
                foreach ($navigation as $key => $v) {
                    $site_navID = $v->id;
                    if ( strpos($builder_post_content, 'wp:navigation') > -1 && ( strpos($builder_post_content, '{"ref":'.$site_navID.',') > -1 || strpos($builder_post_content, '{"ref":'.$site_navID.'}') > -1 ) ) {

                        $current_parsed_args = array(
                            'post_type'              => 'wp_navigation',
                            'post_status'            => 'publish',
                            'orderby'                => 'date',
                            'order'                  => 'ASC',
                            'posts_per_page'         => -1
                        );
                        $navigation_current_posts = new \WP_Query( $current_parsed_args );
                        $navigation_current_posts = $navigation_current_posts->posts;

                        // check for currently same navigation exist or not
                        $new_navID = '';
                        if ( !$new_navID ) {
                            $menu_exists = wp_get_nav_menu_object( $v->post_title );
                            if ( $menu_exists ) {
                                $menu_blocks =  \WP_Classic_To_Block_Menu_Converter::convert( $menu_exists );
                                $new_navID = wp_insert_post(
                                    array(
                                        'post_content' => $menu_blocks,
                                        'post_title'   => $menu_exists->name,
                                        'post_name'    => $menu_exists->slug,
                                        'post_status'  => 'publish',
                                        'post_type'    => 'wp_navigation',
                                    )
                                );
                                update_post_meta($new_navID, '__ultp_starter_pack_post', true);
                            }
                        }
                        if ( strpos($builder_post_content, '{"ref":'.$site_navID.',') > -1 ) {
                            $builder_post_content = str_replace('{"ref":'.$site_navID.',', '{"ref":'.$new_navID.',', $builder_post_content);
                        } else if ( strpos($builder_post_content, '{"ref":'.$site_navID.'}') ) {
                            $builder_post_content = str_replace('{"ref":'.$site_navID.'}', '{"ref":'.$new_navID.'}', $builder_post_content);
                        }

                        wp_update_post(array(
                            'ID' => $builderID,
                            'post_content' => str_replace(['u0022', 'u002d'], ['\u0022', '\u002d'], $builder_post_content)
                        ));
                        $navCreated[] = $new_navID;
                    }
                }
            }
        }

        // Other Plugin Content creation
        $other_plugin_content = $response_data->other_plugin_content;
        $contact_forms = $other_plugin_content->contact_form7;
        $mc4wp = $other_plugin_content->mc4wp;

        // contact form  7  post create
        if ( is_array($contact_forms) && !empty($contact_forms) ) {
            foreach ( $contact_forms as $post ) {
                $post_id = wp_insert_post(array(
                    'post_title'     => $post->post_title,
                    'post_type'      => 'wpcf7_contact_form',
                    'post_status'    => 'publish',
                    'post_content'   => str_replace(['u002d', '<wordpress@postxkit.wpxpo.com>'], ['\u002d', ''], $post->post_content)
                ));
                update_post_meta($post_id, '_form', $post->_form);
                update_post_meta($post_id, '_hash', $post->_hash);
                update_post_meta($post_id, '__ultp_starter_pack_post', true);
            }
        }

        // mailchimp post create
        if ( !empty($inserted_pages) && !empty($mc4wp) ) {
            foreach ($inserted_pages as $id => $p_t) {
                $current_post = get_post($id);
                $current_post_content = $current_post->post_content;
                foreach ($mc4wp as $key => $v) {
                    $parsed_args = array(
                        'post_type'              => 'mc4wp-form',
                        'post_status'            => 'publish',
                        'posts_per_page'         => -1,
                    );
                    $mc4wp_forms_data = new \WP_Query( $parsed_args );
                    $mc4wp_forms_current_posts = $mc4wp_forms_data->posts;

                    $site_ID = $v->id;
                    if ( strpos($current_post_content, '[mc4wp_form id='.$site_ID.']') > -1 ) {
                        $new_ID = '';
                        // check for same mailchimp post
                        if ( !empty($mc4wp_forms_current_posts) ) {
                            foreach( $mc4wp_forms_current_posts as $key => $val) {
                                if($val->post_title == $v->post_title) {
                                    $new_ID = $val->ID;
                                }
                            }
                        }
                        if ( !$new_ID ) {
                            $new_ID = wp_insert_post(array(
                                'post_title'     => $v->post_title,
                                'post_type'      => 'mc4wp-form',
                                'post_status'    => 'publish',
                                'post_content'   => $v->post_content
                            ));
                            update_post_meta($new_ID, '__ultp_starter_pack_post', true);
                        }

                        $current_post_content = str_replace('[mc4wp_form id='.$site_ID.']', '[mc4wp_form id='.$new_ID.']', $current_post_content);
                        wp_update_post(array(
                            'ID' => $id,
                            'post_content' => str_replace(['u0022', 'u002d'], ['\u0022', '\u002d'], $current_post_content)
                        ));
                    }
                   
                }
            }
        }

        return rest_ensure_response([
            'success' => true,
            'inserted_pages' => $inserted_pages,
            'inserted_meta' => $inserted_meta,
            'navCreated' => $navCreated,
            'menuCreated' => $menuCreated,
            'menu_item_parent' => $menu_item_parent,
            'inserted_header_footer' => $inserted_header_footer,
        ]);
    }

    /**
	 * Find Menu Parent item
     * @since 4.0.0
	*/
    public function find_parent_nav_item($menu_items, $title) {
        if ( $menu_items ) {
            foreach ($menu_items as $menu_item) {
                if ( $menu_item->title == $title ) {
                    return $menu_item->ID;
                }
            }
        }
        return 0;
    }

    /**
	 * Save CSS of pages
     * @since 4.0.0
	*/
    public function save_post_block_css($id, $css='', $type='') {
        try {
			global $wp_filesystem;
			if ( ! $wp_filesystem ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}

			$upload_dir_url = wp_upload_dir();
			$dir = trailingslashit($upload_dir_url['basedir']) . 'ultimate-post/';

			$post_id = (int) $id;
			$filename = "ultp-css-{$post_id}.css";
            $ultp_block_css = $css;

			if ( $ultp_block_css ) {
				// Set Saving ID for Clean Cache
				ultimate_post()->set_setting('save_version', wp_rand(1, 1000));

				update_post_meta($post_id, '_ultp_active', 'yes');

				WP_Filesystem( false, $upload_dir_url['basedir'], true );
				if ( ! $wp_filesystem->is_dir( $dir ) ) {
					$wp_filesystem->mkdir( $dir );
				}
				if ( ! $wp_filesystem->put_contents( $dir . $filename, $ultp_block_css ) ) {
					throw new Exception(__('CSS can not be saved due to permission!!!', 'ultimate-post')); //phpcs:ignore
				}
				update_post_meta($post_id, '_ultp_css', $ultp_block_css);
				return ['success'=>true, 'message'=>__('PostX css file has been updated.', 'ultimate-post')];
			}
            if ( $type == 'delete' && file_exists($dir.$filename) ) {
				wp_delete_file($dir.$filename);
			}
		} catch ( Exception $e ) {
			return [ 'success'=> false, 'message'=> $e->getMessage() ];
        }
    }

    /**
	 * Delete previos site import post/pages
     * @since 4.0.0
	*/
    public function deletepost_getnewsletters($server) {
        $post = $server->get_params();
        $deletePrevious = isset($post['deletePrevious']) ? sanitize_text_field($post['deletePrevious']) : '';
        $get_newsletter = isset($post['get_newsletter']) ? sanitize_text_field($post['get_newsletter']) : '';
        $deleted_terms = [];
        $deleted_posts= [];
        if ( $deletePrevious == 'yes' ) {
            $site_logo = get_option('site_logo', '');
            global $wpdb;
            $post_ids = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='__ultp_starter_pack_post'"));
            $terms = $wpdb->get_col($wpdb->prepare("SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key='__ultp_starter_pack_term'"));

            if ( isset( $post_ids ) && is_array( $post_ids ) ) {
                foreach ( $post_ids as $post_id ) {
                    if ( get_post_type( $post_id ) == 'ultp_builder' ) {
                        $conditions = get_option('ultp_builder_conditions', array());
                        $builder_type = get_post_meta( $post_id, '__ultp_builder_type', true );
                        if ( isset($conditions[$builder_type][$post_id]) ) {
                            unset($conditions[$builder_type][$post_id]);
                            update_option('ultp_builder_conditions', $conditions);
                        }
                    }
                    if ( $site_logo == $post_id ) {
                        update_option('site_logo', '');
                    }
                    $deleted_posts[] = $post_id;
                    wp_delete_post( $post_id, true );
                    $this->save_post_block_css( $post_id, '', 'delete' );
                }
            }
            if ( isset( $terms ) && is_array( $terms ) ) {
                foreach ( $terms as $term_id ) {
                    $deleted_terms[] = $term_id;
                    $term = get_term( $term_id );
                    wp_delete_term( $term_id, $term->taxonomy );
                }
            }
        }
        if ( $get_newsletter == 'yes' ) {
            require_once ULTP_PATH.'classes/Deactive.php';
            $obj = new \ULTP\Deactive();
            $obj->send_plugin_data('postx_starter_pack', '');
        }

        return rest_ensure_response([
            'success' => true,
            'term' => $deleted_terms,
            'posts' => $deleted_posts
        ]);
    }

    /**
	 * Import Single Template
     * @since 4.0.0
	*/
    public function single_page_import($server) {
        $post = $server->get_params();
        $id = isset($post['ID']) ? sanitize_text_field($post['ID']) : '';
        $api_endpoint = isset($post['api_endpoint']) ? sanitize_text_field($post['api_endpoint']) : '';
    
        if ( $id && $api_endpoint ) {
            $import_single = array(
                'id'   => $id, 
                'type' => 'single',
                'license' => get_option('edd_ultp_license_key'),
                'ultp_ver' => ULTP_VER,
            );
            $response = wp_remote_get(
                $api_endpoint.'/wp-json/importer/single', 
                array(
                    'method' => 'POST',
                    'timeout' => 120,
                    'body' =>  $import_single
                )
            );
            $response_data = json_decode($response['body']);
            if ( !$response_data->success ) {
                wp_send_json([
                    'success' => false,
                ]);
            }
            $content = $response_data->content[0];
            return rest_ensure_response([
                'success' => true,
                'content' =>  $content,
            ]);
    
        }
    }

    /**
	 * Create dummy posts
     * @since 4.0.0
	*/
    public function starter_pack_dummy_post_creation($importable_posts) {
        $added_posts = [];
        if ( is_array($importable_posts) && !empty($importable_posts) ) {
            foreach ( $importable_posts as $post ) {
                $post_id = wp_insert_post(array(
                    'post_title'     => $post->post_title,
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'post_content'   => str_replace('u002d', '\u002d', $post->post_content)
                ));
                $image_id = $this->upload_post_cat_media($post->img_src, $post_id, $post->post_title);
                set_post_thumbnail( $post_id, $image_id );
    
                update_post_meta($post_id, '__ultp_starter_pack_post', true);
                if ( isset($post->_ultp_css) && $post->_ultp_css ) {
                    $this->save_post_block_css( $post_id, str_replace(['u0022', 'u002d'], ['\u0022', '\u002d'], $post->_ultp_css), '' );
                }
                $post_category = $post->post_category;
                $k = 0;
                $cat_ids = [];
                if ( is_array($post_category) && !empty($post_category) ) {
                    foreach ( $post_category as $i => $cat ) {
                        $cat_id = $this->starter_pack_dummy_taxonomy_creation($cat->name, $cat->slug, $cat->img_src, 'category');
                        wp_set_post_terms($post_id, $cat_id, 'category', $k == 0 ? false : true );
                        $k++;
                        $cat_ids[] = $cat_id;
                    }
                }
                $post_tags = $post->post_tags;
                $tag_ids = [];
                $j = 0;
                if ( is_array($post_tags) && !empty($post_tags) ) {
                    foreach ( $post_tags as $i => $tag ) {
                        $tag_id = wp_set_post_terms($post_id, $tag->slug, 'post_tag', $j == 0 ? false : true );
                        if ( !is_wp_error($tag_id) && is_array($tag_id) && isset($tag_id[0]) ) {
                            update_term_meta($tag_id[0], '__ultp_starter_pack_term', 'postx_term');
                            $tag_ids[] = $tag_id[0];
                        }
                        $j++;
                    }
                }
                $added_posts[$post_id] = array(
                    'title' => $post->post_title,
                    'cat_id' => $cat_ids,
                    'tag_ids' => $tag_ids
                );
            }
        }
        return $added_posts;
    }

    /**
	 * Create dummy category of posts
     * @since 4.0.0
	*/
    public function starter_pack_dummy_taxonomy_creation($name, $slug, $img_src='', $taxonomy='' ) {
        $new_taxonomy = get_term_by('slug', $slug, $taxonomy);
        if ( !$new_taxonomy ) {
            $new_term = wp_insert_term(
                $name,
                $taxonomy,
                array(
                    'slug' => $slug,
                )
            );
            if ( $new_term ) {
                if ( $img_src ) {
                    $image_id = $this->upload_post_cat_media($img_src , '', $name);
                    update_term_meta( $new_term['term_id'], 'ultp_category_image', $image_id );
                }
                update_term_meta($new_term['term_id'], '__ultp_starter_pack_term', 'postx_term');
                
                return $new_term['term_id'];
            }
        } else {
            return $new_taxonomy->term_id;
        }
    }

    /**
	 * Upload image for post/category
     * @since 4.0.0
	*/
    public function upload_post_cat_media($src, $post_id, $title ) {
        if ( !$src ) {  
            return 0; 
            // $src = ULTP_URL.'assets/img/ultp-fallback-img.png';
        }

        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $image_id = media_sideload_image( $src, $post_id, $title, 'id' );
        update_post_meta($image_id, '__ultp_starter_pack_post', true);
        return $image_id;
    }

    /**
	 * Testing Rest Cases
     * @since 4.0.0
	*/
    public function testing_importer($server) {
        $menu_exists = wp_get_nav_menu_object( 'Test1' );
        return rest_ensure_response([
            'success' => true,
            'menu_exists' => $menu_exists,
        ]);
    }
}