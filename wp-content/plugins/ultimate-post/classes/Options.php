<?php
/**
 * Options Action.
 * 
 * @package ULTP\Notice
 * @since v.1.0.0
 */
namespace ULTP;

defined('ABSPATH') || exit;

/**
 * Options class.
 */
class Options{

    /**
	 * Setup class.
	 *
	 * @since v.1.0.0
	 */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'handle_external_redirects' ) );
        add_action( 'admin_menu', array( $this, 'menu_page_callback' ) );
        add_action( 'in_admin_header', array( $this, 'remove_all_notices' ) );
        // add_filter( 'plugin_row_meta', array( $this, 'plugin_settings_meta' ), 10, 2 );
        add_filter( 'plugin_action_links_'.ULTP_BASE, array( $this, 'plugin_action_links_callback' ) );
        // add_filter( 'ultp_plugin_notice', array( $this, 'plugin_notice_callback' ) );
    }

    /**
	 * Dashboard Notice Data
     * 
     * @since v.3.1.8
	 * @param NULL
	 * @return NULL
	 */
    public function plugin_notice_callback() {
        return array(
            'start' => '12-2-2023', // Date format "d-m-Y" [08-02-2019]
            'end' => '22-3-2023',
            'content' => 'Upgrade 30% off Sale!!!'
        );
    }
    
    
    /**
	 * Plugin Page Menu Add
     * 
     * @since v.1.0.0
	 * @return ARRAY
	 */
    public function plugin_settings_meta( $links, $file ) {
        if (strpos( $file, 'ultimate-post.php' ) !== false ) {
            $new_links = array(
                'ultp_docs' =>  '<a href="https://wpxpo.com/docs/" target="_blank">'.esc_html__('Docs', 'ultimate-post').'</a>',
                'ultp_tutorial' =>  '<a href="https://www.youtube.com/watch?v=JZxIflYKOuM&list=PLPidnGLSR4qcAwVwIjMo1OVaqXqjUp_s4" target="_blank">'.esc_html__('Tutorials', 'ultimate-post').'</a>',
                'ultp_support' =>  '<a href="'.esc_url(ultimate_post()->get_premium_link( 'https://www.wpxpo.com/contact/', 'plugin_dir_support')).'" target="_blank">'.esc_html__('Support', 'ultimate-post').'</a>'
            );
            $links = array_merge( $links, $new_links );
        }
        return $links;
    }


    /**
	 * Settings Pro Update Link
     * 
     * @since v.1.0.0
	 * @return ARRAY
	 */
    public function plugin_action_links_callback( $links ) {
        $setting_link = array();
        $setting_link['ultp_starter_sites'] = '<a href="'. esc_url(admin_url('admin.php?page=ultp-settings#startersites')) .'">'. esc_html__('Starter Sites', 'ultimate-post') .'</a>';
        $setting_link['ultp_docs'] = '<a href="https://wpxpo.com/docs/postx/?utm_source=db-postx-plugin&utm_medium=docs&utm_campaign=postx-dashboard" target="_blank">'.esc_html__('Docs', 'ultimate-post').'</a>';
        if ( !defined('ULTP_PRO_VER') ) {
            $setting_link['ultp_pro'] = '<a href="'.esc_url(ultimate_post()->get_premium_link('', 'plugin_dir_pro')).'" target="_blank">'.esc_html__('Get PostX Pro', 'ultimate-post').'</a>';

        }
        $support_link['ultp_support'] = '<a href="https://www.wpxpo.com/contact/?utm_source=db-postx-plugin&utm_medium=quick-support&utm_campaign=postx-dashboard">'. esc_html__('Quick Support', 'ultimate-post') .'</a>';
        return array_merge( $setting_link, $links, $support_link);
    }


    /**
	 * Admin Menu Option Page
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public static function menu_page_callback() {
        $ultp_menu_icon = 'data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MCA0OC4zIj4NCiAgPGRlZnM+DQogICAgPHN0eWxlPg0KICAgICAgLmNscy0xIHsNCiAgICAgICAgZmlsbDogI2E3YWFhZDsNCiAgICAgIH0NCiAgICA8L3N0eWxlPg0KICA8L2RlZnM+DQogIDx0aXRsZT5Qb3N0eCBJY29uIGNtcHJzc2QgU1ZHPC90aXRsZT4NCiAgPGc+DQogICAgPHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTguODEsOXY4LjlIOC4xOUE2LjE5LDYuMTksMCwwLDAsMiwyMy43N2EzLjE2LDMuMTYsMCwwLDEtMi0yLjk0VjRBMy4xNiwzLjE2LDAsMCwxLDMuMTUuODVIMjBhMy4xOCwzLjE4LDAsMCwxLDMsMi4zMUE2LjIxLDYuMjEsMCwwLDAsMTguODEsOVoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTAuODUpIi8+DQogICAgPHBhdGggY2xhc3M9ImNscy0xIiBkPSJNNDUsOVYyM0gzMS4xYTYuMjMsNi4yMywwLDAsMC00LjkzLTQuOTNBNS41NCw1LjU0LDAsMCwwLDI1LDE3Ljk0SDIxLjg1VjlBMy4xNSwzLjE1LDAsMCwxLDIzLjEzLDYuNWEzLjEyLDMuMTIsMCwwLDEsMS40My0uNThsLjA5LDBINDEuODNBMy4xNCwzLjE0LDAsMCwxLDQ1LDlaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIC0wLjg1KSIvPg0KICAgIDxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTUwLDI5LjE3VjQ2YTMuMTYsMy4xNiwwLDAsMS0zLjE1LDMuMTVIMzBhMy4xOCwzLjE4LDAsMCwxLTMtMi4zMUE2LjIyLDYuMjIsMCwwLDAsMzEuMjEsNDFWMjZINDYuODVhMy4zLDMuMywwLDAsMSwxLjE0LjIxQTMuMTYsMy4xNiwwLDAsMSw1MCwyOS4xN1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTAuODUpIi8+DQogICAgPHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMjguMTYsMjQuMTNWNDFhMy4xMywzLjEzLDAsMCwxLTEuMjksMi41NCwzLDMsMCwwLDEtMS40Ny41OGwwLDBIOC4xOUEzLjE1LDMuMTUsMCwwLDEsNSw0MVYyNGEzLjE3LDMuMTcsMCwwLDEsMy4xNS0zSDI1YTMuMTIsMy4xMiwwLDAsMSwxLjE0LjIyLDMuMjQsMy4yNCwwLDAsMSwxLjksMi4xQTMuNjMsMy42MywwLDAsMSwyOC4xNiwyNC4xM1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTAuODUpIi8+DQogIDwvZz4NCjwvc3ZnPg0K';
        add_menu_page(
            __( 'PostX', 'ultimate-post' ),
            __( 'PostX', 'ultimate-post' ),
            'manage_options',
            'ultp-settings',
            array(self::class, 'tab_page_content'),
            $ultp_menu_icon,
            58.5
        );

        add_submenu_page(
            'ultp-settings', 
            __( 'PostX Dashboard', 'ultimate-post' ),
            __( 'Getting Started', 'ultimate-post' ),
            'manage_options',
            'ultp-settings'
        );
    
        $menu_lists = array(
            'builder'           => esc_html__( 'Site Builder', 'ultimate-post' ),
            'startersites'       => esc_html__( 'Starter Sites', 'ultimate-post' ),
            'blocks'            => esc_html__( 'Blocks', 'ultimate-post' ),
            'integrations'          => esc_html__( 'Integrations', 'ultimate-post' ),
            'license'          => esc_html__( 'License', 'ultimate-post' ),
            'support'           => esc_html__( 'Quick Support', 'ultimate-post' )
        );
        foreach ( $menu_lists as $key => $val ) {
            add_submenu_page(
                'ultp-settings',
                $val,
                $val,
                'manage_options',
                'ultp-settings#' . $key,
                array(__CLASS__, 'render_main')
            );
        }
        add_submenu_page( 
            'ultp-settings',
            esc_html__( 'Initial Setup', 'ultimate-post' ),  
            esc_html__( 'Initial Setup', 'ultimate-post' ), 
            'manage_options',
            'ultp-initial-setup-wizard',
            array(__CLASS__, 'initial_setup')
        );
        if ( !ultimate_post()->is_lc_active() ) {
            add_submenu_page(
                'ultp-settings',
                '',
                esc_html__( 'Upgrade to Pro', 'ultimate-post' ),
                'manage_options',
                'go_postx_pro',
                array(self::class, 'handle_external_redirects')
            );
        }
        add_theme_page(
            __( 'Starter Sites', 'ultimate-post' ),
            __( 'Starter Sites', 'ultimate-post' ),
            'manage_options',
            'ultp-startersites',
            array(self::class, 'handle_external_redirects')
        );
    }

    public function handle_external_redirects() {
        if ( empty( $_GET['page'] ) ) {     // @codingStandardsIgnoreLine
            return;
        }
        $_page = sanitize_key($_GET['page']);
        if ( 'ultp-startersites' === $_page  ) {   // @codingStandardsIgnoreLine
            exit(wp_safe_redirect(admin_url('admin.php?page=ultp-settings#startersites')));
        } else if ( 'go_postx_pro' === $_page ) {   // @codingStandardsIgnoreLine
            wp_redirect( ultimate_post()->get_premium_link('', 'dashboard_go_pro'));
            die();
        }
    }

    /**
     * Initial Plugin Setting
     *
     * * @since v.2.4.4
     * @return STRING
     */
    public static function initial_setup() { ?>
        <div class="ultp-initial-setting-wrap" id="ultp-initial-setting"></div>
    <?php }

    public static function tab_page_content() {
        echo '<div id="ultp-dashboard"></div>';
    }

    /**
	 * Remove All Notification From Menu Page
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public static function remove_all_notices() {
        if ( isset($_GET['page']) ) {   // @codingStandardsIgnoreLine
            $page = sanitize_key($_GET['page']);    // @codingStandardsIgnoreLine
            if ( $page === 'ultp-settings' ||  
                $page === 'ultp-license' ||  
                $page === 'ultp-initial-setup-wizard'  ) {
                remove_all_actions( 'admin_notices' );
                remove_all_actions( 'all_admin_notices' );
            }
        }
    }
}