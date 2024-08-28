<?php
/**
 * Plugin Cache.
 * 
 * @package ULTP\RequestAPI
 * @since v.2.7.0
 */
namespace ULTP;

defined('ABSPATH') || exit;

/**
 * RequestAPI class.
 */
class RequestAPI {
    private $api_endpoint = 'https://ultp.wpxpo.com/wp-json/restapi/v2/';
    
    public function __construct() {
        add_action('rest_api_init', array($this, 'get_template_data'));
    }

    /**
	 * Create Builder Post Type
     * 
     * @since v.2.7.0
	 * @return NULL
	 */
    public function get_template_data() {
        register_rest_route(
			'ultp/v2', 
			'/get_single_premade/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'get_single_premade_callback'),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'ultp/v2',
			'/condition/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'condition_settings_action'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'ultp/v2',
			'/condition_save/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'condition_save_action'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'ultp/v2',
			'/data_builder/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'data_builder_action'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
					'args' => array()
				)
			)
		);
    }

    /**
	 * Builder Post Type Data
     * 
     * @since v.2.7.0
     * @param ARRAY
	 * @return ARRAY | Information of Builder Post
	 */
    public function data_builder_action($server) {
        $post = $server->get_params();
        $s_pid = isset($post['pid']) ? ultimate_post()->ultp_rest_sanitize_params($post['pid']) : '';
        
        $args = array(
            'post_type'         => 'ultp_builder',
            'post_status'       => array('publish', 'draft'),
            'orderby'           => 'title', 
            'order'             => 'ASC',
            'posts_per_page'    => -1,
        );
        $post_results = new \WP_Query($args);
        $post_list = [];
        if (!empty($post_results)) {
            while ( $post_results->have_posts() ) {
                $post_results->the_post();
                $id = get_the_ID();
                $meta_type = get_post_meta( $id, '__ultp_builder_type', true );
                $post_list[] = array(
                    'id' => $id, 
                    'title' => get_the_title(),
                    'author' => get_the_author_meta('display_name'),
                    'date' => get_the_date( get_option('date_format')),
                    'edit' => get_edit_post_link($id),
                    'type' => $meta_type ? $meta_type : 'archive',
                    'status' => get_post_status(),
                );
            }
            wp_reset_postdata();
        }

        $arg = [
            'success' => true,
            'postlist' => $post_list,
            'settings' => get_option('ultp_builder_conditions', array()),
            'defaults' => $this->builder_data()
        ];
        if ( $s_pid ) {
            $post_meta = get_post_meta( $s_pid, '__ultp_builder_type', true );
            $arg['type'] = $post_meta ? $post_meta : 'archive';
        }
        return $arg;
    }


    /**
	 * Get Add Default Condition Data
     * 
     * @since v.2.7.0
	 * @return ARRAY | Default Data
	 */
    public function builder_data() {
        $archive_data = array(
            array(
                'label' => 'All Archive',
                'value' => '',
            ),
            array(
                'label' => 'Author Archive',
                'value' => 'author',
                'search' => 'author###'
            ),
            array(
                'label' => 'Date Archive',
                'value' => 'date',
            ),
            array(
                'label' => 'Search Results',
                'value' => 'search',
            )
        );
        $single_data = array(
            array(
                'label' => 'Front Page', 
                'value' => 'front_page', 
            )
        );
        $header_data = array(
            array(
                'label' => 'Entire Site',
                'value' => 'entire_site', 
            ),
            array(
                'label' => 'Archive',
                'value' => 'archive',
            ),
            array(
                'label' => 'Singular',
                'value' => 'singular',
            )
        );
        
        $post_type = get_post_types( ['public' => true], 'objects' );
        foreach ($post_type as $key => $type) {
            // Post Type
            $single_temp = [ 
                'label' => $type->label, 
                'value' => $type->name, 
                'search' => 'type###'.$type->name 
            ];
            $archive_temp = [];

            // Taxonomy
            $taxonomy = get_object_taxonomies( $type->name, 'objects' );
            if (!empty($taxonomy)) {
                $single_tax = $archive_tax = [];
                $single_tax[] = $single_temp;

                $archive_temp = [ 
                    'label' => $type->label . ' Archive', 
                    'value' => $type->name . '_archive',
                ];
                // $archive_tax[] = $archive_temp;
                foreach ($taxonomy as $key => $val) {
                    if ($val->public) {
                        $single_tax[] = [
                            'label' => 'In ' . $val->label, 
                            'value' => 'in_' . $val->name, 
                            'search' => 'term###'.$val->name
                        ];
                        $archive_tax[] = [
                            'label' => $val->label, 
                            'value' => $val->name, 
                            'search' => 'term###'.$val->name
                        ];

                        if ($val->hierarchical) {
                            // Hierarchical
                            $single_tax[] = [
                                'label' => 'In Child ' . $val->label,
                                'value' => 'in_' . $val->name . '_children',
                                'search' => 'term###'.$val->name
                            ];
                            $archive_tax[] = [
                                'label' => 'Direct Child ' . $val->label . ' Of',
                                'value' => 'child_of_' . $val->name,
                                'search' => 'term###'.$val->name
                            ];
                            $archive_tax[] = [
                                'label' => 'Any Child ' . $val->label . ' Of',
                                'value' => 'any_child_of_' . $val->name,
                                'search' => 'term###'.$val->name
                            ];
                        }
                    }
                }
                // Author
                $single_tax[] = [
                    'label' => 'Posts By Author',
                    'value' => 'post_by_author',
                    'search' => 'author###'
                ];
                $single_temp['attr'] = $single_tax;
                $archive_temp['attr'] = $archive_tax;
            }
            $single_data[] = $single_temp;
            if (!empty($archive_temp)) {
                $archive_data[] = $archive_temp;
            }
        }

        return [
            'singular' => $single_data, 
            'archive' => $archive_data,
            'header'=> $header_data, 
            'footer'=> $header_data
        ];
    }


    /**
	 * Save Conditions Data
     * 
     * @since v.2.7.0
     * @param ARRAY
	 * @return ARRAY | Message of the Condition Success
	 */
    public function condition_save_action($server) {
        $post = $server->get_params();
        $s_settings = isset($post['settings']) ? ultimate_post()->ultp_rest_sanitize_params($post['settings']) : '';
        
        if ( $s_settings ) {
            update_option( 'ultp_builder_conditions', $s_settings );
            return [
                    'success' => true,
                    'data' => 'Settings Saved!!!',
                    's_settings' => $s_settings,
            ];
        }
    }


    /**
	 * Condition Settings Actions
     * 
     * @since v.2.7.0
     * @param ARRAY
	 * @return ARRAY | Data of the Condition
	 */
    public function condition_settings_action($server) {
        global $wpdb;
        $post = $server->get_params();
        $s_type = isset($post['type']) ? ultimate_post()->ultp_rest_sanitize_params($post['type']) : '';
        $s_term = isset($post['term']) ? ultimate_post()->ultp_rest_sanitize_params($post['term']) : '';
        $s_title_return = isset($post['title_return']) ? rest_sanitize_boolean($post['title_return']) : '';
        $search_type = explode('###', $s_type);

        switch ( $search_type[0] ) {
            case 'type':
                $args = array(
                    'post_type'         => $search_type[1],
                    'post_status'       => 'publish',
                    'orderby'           => 'title', 
                    'order'             => 'ASC',
                    's'                 => $s_term,
                    'posts_per_page'    => 10,
                );
                if ( $s_title_return ) {
                    unset($args['s']);
                    $args['p'] = $s_term;
                }
                $post_results = new \WP_Query($args);
                $title = '';
                $data = [];
                if ( !empty($post_results) ) {
                    while ( $post_results->have_posts() ) {
                        $post_results->the_post();
                        $id = get_the_ID();
                        $title = html_entity_decode(get_the_title());
                        $data[] = array('value'=>$id, 'title'=>($title?$title:('##'.$id)));
                    }
                    wp_reset_postdata();
                }
                return ['success' => true, 'data' => ($s_title_return ? $title : $data )];
            break;

            case 'term':
                $args = array(
                    'taxonomy'  => $search_type[1],
                    'fields'    => 'all',
                    'orderby'   => 'id',
                    'order'     => 'ASC',
                    'name__like'=> $s_term
                );
                if ($s_title_return) {
                    $args['term_taxonomy_id'] = array($s_term);
                    unset($args['name__like']);
                }
                $post_results = get_terms( $args );
                $title = '';
                $data = [];
                if (!empty($post_results)) {
                    foreach ($post_results as $key => $val) {
                        $title = $val->name;
                        $data[] = array('value'=>$val->term_id, 'title'=>$title);
                    }
                }
                return ['success' => true, 'data' => ($s_title_return ? $title : $data )];
            break;

            case 'author':
                $term = $s_title_return ? $wpdb->esc_like( $s_term ) : '%'. $wpdb->esc_like( $s_term ) .'%';
                $post_results = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
                    $wpdb->prepare(
                        "SELECT ID, display_name 
                        FROM $wpdb->users 
                        WHERE user_login LIKE %s OR ID LIKE %s OR user_nicename LIKE %s OR user_email LIKE %s OR display_name LIKE %s LIMIT 10", $term, $term, $term, $term, $term 
                    )
                );
                $title = '';
                $data = [];
                if (!empty($post_results)) {
                    foreach ($post_results as $key => $val) {
                        $title = $val->display_name;
                        $data[] = array('value'=>$val->ID, 'title'=>$val->display_name);
                    }
                }
                return ['success' => true, 'data' => ($s_title_return ? $title : $data )];
            break;
            default:
                return ['success' => false];
            break;
        }
        return ['success' => true, 'data' => 'This is Testing !!!'];
    }


    /**
	 * Single Premade Data and Create Builder Posts
     * 
     * @since v.2.7.0
     * @param ARRAY
	 * @return ARRAY | Data of the Premade
	 */
    public function get_single_premade_callback($server) {

        $is_active = ultimate_post()->is_lc_active();
        $obj_count = wp_count_posts('ultp_builder');
        if (!$is_active) {
            $p_count = isset($obj_count->publish) ? $obj_count->publish : 0;
            $d_count = isset($obj_count->draft) ? $obj_count->draft : 0;
            if (($p_count + $d_count) > 0) {
                return array( 'success' => false );
            }
        }

        $post = $server->get_params();
        $id = isset($post['ID']) ? sanitize_text_field($post['ID']) : '';
        $api_endpoint = isset($post['apiEndPoint']) ? sanitize_text_field($post['apiEndPoint']) : '';
        $s_type = isset($post['type']) ? ultimate_post()->ultp_rest_sanitize_params($post['type']) : '';

        if( $id ) {
            $import_single = array(
                'id'   => $id, 
                'type' => 'single',
                'license' => get_option('edd_ultp_license_key'),
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

            if( !$response_data->success ) {
                wp_send_json([
                    'success' => false,
                    'data' => "Something went wrong:" . $response->get_error_message()
                ]);
            }

            $content = $response_data->content[0]->content;
            $post_id = $this->set_post_content($s_type, wp_slash($content));

            return rest_ensure_response([
                'success' => true,
                'link' => get_edit_post_link($post_id)
            ]);
    
        } else {
            $post_id = $this->set_post_content($s_type, '');
            return array( 'success' => true, 'link' => get_edit_post_link($post_id) );
        }
        
    }

     /**
	 * Single Premade Data and Insert Builder Posts
     * 
     * @since v.2.7.0
     * @param ARRAY
	 * @return INT | Post ID
	 */
    public function set_post_content($type, $body = '') {
        $post_id = wp_insert_post(
            array(
                'post_title'   => ucfirst($type) . ' Template',
                'post_content' => $body,
                'post_type'    => 'ultp_builder',
                'post_status'  => 'draft'
            )
        );
        $settings = get_option('ultp_builder_conditions', array());
        switch ($type) {
            case 'singular':
                update_post_meta($post_id, '__ultp_builder_type', 'singular');
                $settings['singular'][$post_id] = ['include/singular/post'];
                break;
            case 'front_page':
                update_post_meta($post_id, '__ultp_builder_type', 'singular');
                $settings['singular'][$post_id] = ['include/singular/front_page'];
                break;
            case 'author':
            case 'post_tag':
            case 'date':
            case 'search':
            case 'archive':
            case 'category':
                update_post_meta($post_id, '__ultp_builder_type', 'archive');
                $extra = $type != 'archive' ? '/'.$type : '';
                $settings['archive'][$post_id] = ['include/archive'.$extra];
                break;
            case 'header':
                update_post_meta($post_id, '__ultp_builder_type', 'header');
                $settings['header'][$post_id] = ['include/header/entire_site'];
                break;
            case 'footer':
                update_post_meta($post_id, '__ultp_builder_type', 'footer');
                $settings['footer'][$post_id] = ['include/footer/entire_site'];
                break;
            case '404':
                update_post_meta($post_id, '__ultp_builder_type', '404');
                $settings['404'][$post_id] = ['include/404'];
                break;
            default:
                break;
        }
        update_option('ultp_builder_conditions', $settings);
        return $post_id;
    }

}