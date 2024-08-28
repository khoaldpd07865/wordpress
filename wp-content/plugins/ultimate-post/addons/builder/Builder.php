<?php
namespace ULTP;

defined('ABSPATH') || exit;

class Builder {
    public function __construct(){
        $this->builder_post_type_callback(); // Post Type Register
        add_action('wp', array($this, 'checkfor_header_footer'), 999);
        add_filter('template_include', array($this, 'include_builder_files'), 100);
        add_action('add_meta_boxes', array($this, 'init_metabox_callback'));
        add_action('save_post', array($this, 'metabox_save_data'));
        add_action('save_post', array($this, 'metabox_save_video_data'));
        add_action('delete_post', array($this, 'delete_option_meta_action'));
        add_action('load-post-new.php', array($this, 'disable_new_post_templates'));
    }

    public function checkfor_header_footer() {
        $theme_name = get_template();
        $header_id = ultimate_post()->conditions('header');
        $footer_id = ultimate_post()->conditions('footer');
        
        if ( $header_id ) {
            if ( wp_is_block_theme() ) {
                // add_action('wp_head', function() use ($header_id) {
                //     $this->header_builder_template($header_id);
                // });
                add_action('wp_head', array($this, 'header_builder_template'));
            } else {
                // add_action('get_header', function() use ($header_id) {
                //     $this->header_builder_template($header_id);
                // });
                add_action('get_header', array($this, 'header_builder_template'));
            }
		}
        if ( $footer_id ) {
            if ( wp_is_block_theme() ) {
                add_action('wp_footer', function() use ($footer_id, $theme_name) {
                    $this->footer_builder_template($footer_id, $theme_name);
                });
            } else {
                switch ($theme_name) {
                    case 'astra':
                        remove_all_actions( 'astra_footer' );
                        add_action( 'astra_footer', function() use ($footer_id, $theme_name) {
                            $this->footer_builder_template($footer_id, $theme_name);
                        });
                        break;
                    case 'generatepress':
                        remove_action( 'generate_footer', 'generate_construct_footer_widgets');
                        remove_action( 'generate_footer', 'generate_construct_footer' );
                        add_action( 'generate_footer', function() use ($footer_id, $theme_name) {
                            $this->footer_builder_template($footer_id, $theme_name);
                        });
                        break;
                    default:
                        add_action('get_footer', function() use ($footer_id, $theme_name) {
                            $this->footer_builder_template($footer_id, $theme_name);
                        });
                }
            }
		}
    }

    public function header_builder_template() {
        $header_id = ultimate_post()->conditions('header');
        $theme_name = get_template();
        if ($header_id) {
            if ( !wp_is_block_theme() ) {
                require_once ULTP_PATH.'addons/builder/templates/header.php';
                $templates   = [];
                $templates[] = 'header.php';
                remove_all_actions( 'wp_head' );
                if ($theme_name != 'bricks') {  // Conflict with Bricks Builder Backend
                    ob_start(); 
                }
                locate_template( $templates, true );
                if ($theme_name != 'bricks') { // Conflict with Bricks Builder Backend
                    ob_get_clean();
                } else { 
                    wp_enqueue_style( 'wp-block-library' );  // Gutenberg CSS issue Bricks Builder frontend
                }
            }
            ultimate_post()->register_scripts_common();
            ?> 
                <header id="ultp-header-template" class="<?php esc_html_e('ultp-builderid-'.$header_id); ?>">
                    <?php echo ultimate_post()->content($header_id);  //phpcs:ignore  ?> 
                </header> 
            <?php
        }
	}
    public function footer_builder_template($footer_id, $theme_name = '') {
        if ($footer_id) {
            ultimate_post()->register_scripts_common();
            if ( !wp_is_block_theme() ) {
                require_once ULTP_PATH.'addons/builder/templates/footer.php';
                $templates   = [];
                $templates[] = 'footer.php';
                remove_all_actions( 'wp_footer' );
                if ($theme_name != 'bricks') { // Conflict with Bricks Builder Backend
                    ob_start();
                }
                locate_template( $templates, true );
                if ($theme_name != 'bricks') { // Conflict with Bricks Builder Backend
                    ob_get_clean();
                }
            }
            ?> 
                <footer id="ultp-footer-template" class="<?php esc_html_e('ultp-builderid-'.$footer_id); ?>" role="contentinfo">
                    <?php echo ultimate_post()->content($footer_id) //phpcs:ignore ?>
                </footer> 
            <?php
        }
	}

    public function disable_new_post_templates() {
        if ( get_current_screen()->post_type == 'ultp_builder' && (!defined('ULTP_PRO_VER')) ){
            $post_count = wp_count_posts('ultp_builder');
            $post_count = $post_count->publish + $post_count->draft;
            if ($post_count > 0) {
                wp_die( 'You are not allowed to do that! Only one template can be created in the free version. Please <a target="_blank" href="'.esc_url(ultimate_post()->get_premium_link()).'">Upgrade Pro.</a>' );
            }
        }
    }

    public function delete_option_meta_action( $post_id ) {
        if (get_post_type( $post_id ) != 'ultp_builder') {
            return;
        }

        $conditions = get_option('ultp_builder_conditions', array());
        if($conditions){
            $has_change = false;
            if(isset($conditions['archive'][$post_id])) {
                $has_change = true;
                unset($conditions['archive'][$post_id]);
            }
            if(isset($conditions['singular'][$post_id])) {
                $has_change = true;
                unset($conditions['singular'][$post_id]);
            }
            if($has_change) {
                update_option('ultp_builder_conditions', $conditions);
            }
        }
        delete_post_meta($post_id, '_ultp_active');
    }


    public function include_builder_files($template) {
        $includes = ultimate_post()->conditions('includes');
        return $includes ? $includes : $template;
    }

    function init_metabox_callback() {
        $title = '<div class="ultp-add-media-image-head"><img src="'.ULTP_URL.'assets/img/logo-sm.svg" /><span>PostX Settings</span></div>';

        add_meta_box(
            'postx-builder-id',
            __('PostX Builder Settings', 'ultimate-post'), 
            array($this, 'container_width_callback'), 
            'ultp_builder', 
            'side'
        );
        add_meta_box(
            'ultp-feature-video',
            $title,
            array($this, 'video_source_callback'),
            'post',
            'side',
            'high'
        );
    }

    function video_source_callback($post) {
        wp_nonce_field('video_meta_box', 'video_meta_box_nonce');
        $video = get_post_meta($post->ID, '__builder_feature_video', true); 
        $caption = get_post_meta($post->ID, '__builder_feature_caption', true); 
        ?>
        <div class="ultp-meta-video">
            <?php if(current_user_can('administrator')) { ?>
                <a class="ultp-dash-builder-btn" target="_blank" href="<?php echo esc_url(admin_url('admin.php?page=ultp-settings#builder')); ?>"><?php echo esc_html__('Enable PostX Single Builder', 'ultimate-post'); ?> </a>
            <?php } ?>
            <label><?php echo esc_html__('Featured Video', 'ultimate-post'); ?></label>
            <div class="ultp-video-input">
                <input id="ultp-add-input" type="text" name="feature-video" value="<?php echo esc_attr($video); ?>" autocomplete="off"/>
                <button class="ultp-add-media"><span class="dashicons dashicons-cloud-upload"></span></button>
            </div>
            <span><strong><?php echo esc_html__('Note: ', 'ultimate-post'); ?></strong><?php echo esc_html__('Enter Youtube/ Vimeo/ Self Hosted URL', 'ultimate-post'); ?></span>
            <label><?php echo esc_html__('Featured Video Caption', 'ultimate-post'); ?></label>
            <input id="ultp-add-caption" type="text" name="video-caption" value="<?php echo esc_attr($caption); ?>" autocomplete="off"/>
        </div>
    <?php }


    function metabox_save_video_data($post_id) {
        if (!isset($_POST['video_meta_box_nonce'])) { return; }
        if (!wp_verify_nonce(sanitize_key(wp_unslash($_POST['video_meta_box_nonce'])), 'video_meta_box')) { return; }
        if (!isset( $_POST['feature-video'])) { return; }
        update_post_meta($post_id, '__builder_feature_video', sanitize_text_field($_POST['feature-video']));
        if (!isset( $_POST['video-caption'])) { return; }
        update_post_meta($post_id, '__builder_feature_caption', sanitize_text_field($_POST['video-caption']));
    }

    
    function container_width_callback($post) {
        wp_nonce_field('container_meta_box', 'container_meta_box_nonce');
        $width = get_post_meta($post->ID, '__container_width', true);
        $sidebar = get_post_meta($post->ID, '__builder_sidebar', true);
        $widget = get_post_meta($post->ID, '__builder_widget_area', true);
        $p_type = get_post_meta($post->ID, '__ultp_builder_type', true);
        $p_type = $p_type ? $p_type : 'archive';

        $widget_area = wp_get_sidebars_widgets();
        if (isset($widget_area['wp_inactive_widgets'])) { unset($widget_area['wp_inactive_widgets']); }
        if (isset($widget_area['array_version'])) { unset($widget_area['array_version']); }
        ?>

        <input type="hidden" name="postx-type" value="<?php echo esc_attr(isset($_GET['postx_type']) ? sanitize_text_field($_GET['postx_type']) : $p_type); // @codingStandardsIgnoreLine ?>"/>   
        <p>
            <label><?php echo esc_html__('Container Width', 'ultimate-post'); ?></label>
            <input type="number" style="width:100%" name="container-width" value="<?php echo esc_attr($width ? $width : 1140); ?>"/>
        </p>
        <p class="postx-meta-sidebar-position">
            <label><?php echo esc_html__('Sidebar', 'ultimate-post'); ?></label>
            <select name="builder-sidebar" style="width:88%">
                <option <?php selected( $sidebar, '' ); ?> value=""><?php echo esc_html__('- None -', 'ultimate-post'); ?></option>
                <option <?php selected( $sidebar, 'left' ); ?> value="left"><?php echo esc_html__('Left Sidebar', 'ultimate-post'); ?></option>
                <option <?php selected( $sidebar, 'right' ); ?> value="right"><?php echo esc_html__('Right Sidebar', 'ultimate-post'); ?></option>
            </select>
        </p>
        <p class="postx-meta-sidebar-widget">
            <label><?php echo esc_html__('Select Sidebar(Widget Area)', 'ultimate-post'); ?></label>
            <select name="builder-widget-area" style="width:88%">
                <option <?php selected( $sidebar, '' ); ?> value=""><?php echo esc_html__('- None -', 'ultimate-post'); ?></option>
                <?php foreach ($widget_area as $key => $val) { ?>
                    <option <?php selected( $widget, $key ); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_html(ucwords(str_replace('-', ' ', $key))); ?></option>
                <?php } ?>
            </select>
        </p>
    <?php }
    
    function metabox_save_data($post_id) {
        // For Featured Video
        if (isset($_POST['video_meta_box_nonce'])) {
            if (wp_verify_nonce(sanitize_key(wp_unslash($_POST['video_meta_box_nonce'])), 'video_meta_box')) {
                if (isset($_POST['feature-video'])) {
                    update_post_meta($post_id, '__builder_feature_video', sanitize_text_field($_POST['feature-video']));
                }
                if(!isset( $_POST['video-caption'])){
                    update_post_meta($post_id, '__builder_feature_caption', sanitize_text_field($_POST['video-caption']));
                }
            }
        }
        
        // For Container and Sidebar Information
        if (!isset($_POST['container_meta_box_nonce'])){ return; }
        if (!wp_verify_nonce( sanitize_key( wp_unslash($_POST['container_meta_box_nonce'])), 'container_meta_box')) { return; }
        if (isset($_POST['container-width'])) {
            update_post_meta($post_id, '__container_width', sanitize_text_field($_POST['container-width']));
        }
        if (isset($_POST['builder-sidebar'])) {
            update_post_meta($post_id, '__builder_sidebar', sanitize_text_field($_POST['builder-sidebar']));
        }
        if (isset($_POST['builder-widget-area'])) {
            update_post_meta($post_id, '__builder_widget_area', sanitize_text_field($_POST['builder-widget-area']));
        }

        // Save Conditions Data
        if (get_post_type($post_id) == 'ultp_builder') {
            if (get_post_type($post_id) == 'ultp_builder') {
                $settings = get_option('ultp_builder_conditions', array());
                $p_type = isset($_POST['postx-type']) ? sanitize_text_field($_POST['postx-type']) : 'singular';
                switch ($p_type) {
                    case 'singular':
                        update_post_meta($post_id, '__ultp_builder_type', 'singular');
                        if (isset($settings['singular'])) {
                            if (!isset($settings['singular'][$post_id])) {
                                $settings['singular'][$post_id] = ['include/singular/post'];
                            }
                        } else {
                            $settings['singular'][$post_id] = ['include/singular/post'];
                        }
                        break;
                    case 'post_tag':
                    case 'date':
                    case 'search':
                    case 'author':
                    case 'archive':
                    case 'category':
                        update_post_meta($post_id, '__ultp_builder_type', 'archive');
                        $extra = $p_type != 'archive' ? '/'.$p_type : '';
                        if (isset($settings['archive'])) {
                            if (!isset($settings['archive'][$post_id])) {  
                                $settings['archive'][$post_id] = ['include/archive'.$extra];
                            }
                        } else {
                            $settings['archive'][$post_id] = ['include/archive'+$extra];
                        }
                        break;
                    case 'header':
                        if ( isset($settings['header']) ) {
                            if ( !isset($settings['header'][$post_id]) ) {
                                $settings['header'][$post_id] = ['include/header/entire_site'];
                            }
                        } else {
                            $settings['header'][$post_id] = ['include/header/entire_site'];
                        }
                        break;
                    case 'footer':
                        if ( isset($settings['footer']) ) {
                            if ( !isset($settings['footer'][$post_id]) ) {
                                $settings['footer'][$post_id] = ['include/footer/entire_site'];
                            }
                        } else {
                            $settings['footer'][$post_id] = ['include/footer/entire_site'];
                        }
                        break;
                    case '404':
                        if ( isset($settings['404']) ) {
                            if ( !isset($settings['404'][$post_id]) ) {
                                $settings['404'][$post_id] = ['include/404'];
                            }
                        } else {
                            $settings['404'][$post_id] = ['include/404'];
                        }
                    default:
                        break;
                }
                update_option('ultp_builder_conditions', $settings);
            }
        }
    }

    // Builder Post Type Register
    public function builder_post_type_callback() {
        $labels = array(
            'name'                => _x( 'Builder', 'Builder', 'ultimate-post' ),
            'singular_name'       => _x( 'Builder', 'Builder', 'ultimate-post' ),
            'menu_name'           => __( 'Builder', 'ultimate-post' ),
            'parent_item_colon'   => __( 'Parent Builder', 'ultimate-post' ),
            'all_items'           => __( 'Builder', 'ultimate-post' ),
            'view_item'           => __( 'View Builder', 'ultimate-post' ),
            'add_new_item'        => __( 'Add New', 'ultimate-post' ),
            'add_new'             => __( 'Add New', 'ultimate-post' ),
            'edit_item'           => __( 'Edit Builder', 'ultimate-post' ),
            'update_item'         => __( 'Update Builder', 'ultimate-post' ),
            'search_items'        => __( 'Search Builder', 'ultimate-post' ),
            'not_found'           => __( 'No Builder Found', 'ultimate-post' ),
            'not_found_in_trash'  => __( 'Not Builder found in Trash', 'ultimate-post' ),
        );
        $args = array(
            'labels'              => $labels,
            'show_in_rest'        => true,
            'supports'            => array( 'title', 'editor', 'comments' ),
            'hierarchical'        => false,
            'public'              => false,
            'rewrite'             => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'capability_type'     => 'page',
        );
        register_post_type( 'ultp_builder', $args );
    }
}