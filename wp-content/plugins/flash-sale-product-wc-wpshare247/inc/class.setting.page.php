<?php
if( !class_exists('Ws247_fspw') ):
	class Ws247_fspw{
		 const FIELDS_GROUP = 'Ws247_fspw-fields-group'; 
		 
		/**
		 * Constructor
		 */
		function __construct() {
			$this->slug = WS247_FSPW_SETTING_PAGE_SLUG;
			$this->option_group = self::FIELDS_GROUP;
			add_action('admin_menu',  array( $this, 'add_setting_page' ) );
			add_action('admin_init', array( $this, 'register_plugin_settings_option_fields' ) );
			add_filter('plugin_action_links', array( $this, 'add_action_link' ), 999, 2 );
			add_action( 'wp_enqueue_scripts', array($this, 'register_scripts') );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		}
		
		public function add_action_link( $links, $file  ){
			$plugin_file = basename ( dirname ( WS247_FSPW ) ) . '/'. basename(WS247_FSPW, '');
			if($file == $plugin_file){
				$setting_link = '<a href="' . admin_url('admin.php?page='.WS247_FSPW_SETTING_PAGE_SLUG) . '">'.__( 'Settings' ).'</a>';
				array_unshift( $links, $setting_link );
			}
			return $links;
		}
		
		public function add_setting_page() {
			add_submenu_page( 
				'woocommerce',
				__("Setting", WS247_FSPW_TEXTDOMAIN),
				__("Flash sale", WS247_FSPW_TEXTDOMAIN),
				'manage_options',
				$this->slug,
				array($this, 'the_content_setting_page')
			);
		}
		
		public function load_textdomain(){
			load_plugin_textdomain( WS247_FSPW_TEXTDOMAIN, false, dirname( plugin_basename( WS247_FSPW ) ) . '/languages/' ); 
		}
		
		
		static function create_option_prefix($field_name){
			return WS247_FSPW_PREFIX.$field_name;
		}
		
		public function get_option($field_name){
			return get_option(WS247_FSPW_PREFIX.$field_name);
		}
		
		static function class_get_option($field_name){
			return get_option(WS247_FSPW_PREFIX.$field_name);
		}
		
		public function register_field($field_name){
			register_setting( $this->option_group, WS247_FSPW_PREFIX.$field_name);
		}
		
		public function register_plugin_settings_option_fields() {
			/***
			****register list fields
			****/
			$arr_register_fields = array(
											//-------------------------------general tab
											'bar_text', 'sold_out_text', 'bar_bg_color', 'sold_out_bg_color',
											'hide_sold_out', 'hide_product_page'
										);
			
			if($arr_register_fields){
				foreach($arr_register_fields as $key){
					$this->register_field($key);
				}
			}
		}
		
		public function the_content_setting_page(){
			require_once WS247_FSPW_PLUGIN_INC_DIR . '/option-form-template.php';
		}
		
		
		function register_scripts() {
			//Css
			wp_enqueue_style( 'wpshare247.com_fspw.css', WS247_FSPW_PLUGIN_INC_ASSETS_URL . '/fspw.css', false, '1.0' );
		}

	//End class--------------	
	}
	
	new Ws247_fspw();
endif;
