<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpcdt_Admin {

	function __construct() {

		// Action to add admin menu
		add_action( 'admin_menu', array( $this, 'wpcdt_register_menu' ) );

		// Action to add metabox
		add_action( 'add_meta_boxes', array( $this, 'wpcdt_post_sett_metabox' ) );

		// Action to save metabox
		add_action( 'save_post_'.WPCDT_POST_TYPE, array( $this, 'wpcdt_save_metabox_value' ) );

		// Admin Prior Process
		add_action( 'admin_init', array( $this, 'wpcdt_admin_init_process' ) );

		// Action to add custom column to Timer listing
		add_filter( 'manage_'.WPCDT_POST_TYPE.'_posts_columns', array( $this, 'wpcdt_posts_columns' ) );

		// Action to add custom column data to Timer listing
		add_action( 'manage_'.WPCDT_POST_TYPE.'_posts_custom_column', array( $this, 'wpcdt_post_columns_data' ), 10, 2 );
	}

	/**
	 * Function to add menu
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_register_menu() {

		// How It Work Page
		add_submenu_page( 'edit.php?post_type='.WPCDT_POST_TYPE, __('How it works - Countdown Timer Ultimate', 'countdown-timer-ultimate'), __('How It Works', 'countdown-timer-ultimate'), 'manage_options', 'wpcdt-designs', array($this, 'wpcdt_designs_page') );

		// Setting page
		add_submenu_page( 'edit.php?post_type='.WPCDT_POST_TYPE, __('Overview - Countdown Timer Ultimate', 'countdown-timer-ultimate'), '<span style="color:#2ECC71">'.__('Overview', 'countdown-timer-ultimate').'</span>', 'manage_options', 'wpcdt-solutions-features', array($this, 'wpcdt_solutions_features_page') );

		// Premium Feature Page
		add_submenu_page( 'edit.php?post_type='.WPCDT_POST_TYPE, __('Upgrade To PRO - Countdown Timer Ultimate', 'countdown-timer-ultimate'), '<span style="color:#ff2700">'.__('Upgrade To PRO', 'countdown-timer-ultimate').'</span>', 'manage_options', 'wpcdt-premium', array($this, 'wpcdt_premium_page') );
	}

	/**
	 * How it work Page HTML
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_designs_page() {
		include_once( WPCDT_DIR . '/includes/admin/wpcdt-how-it-work.php' );
	}

	/**
	 * Premium Feature Page HTML
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_premium_page() {
		//include_once( WPCDT_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * Solutions & Features Page Html
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 2.0.11
	 */
	function wpcdt_solutions_features_page() {
		include_once( WPCDT_DIR . '/includes/admin/settings/solution-features/solutions-features.php' );
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_post_sett_metabox() {

		// Countdown Timer metabox
		add_meta_box( 'wpcdt-post-sett', __( 'Countdown Timer - Settings', 'countdown-timer-ultimate' ), array($this, 'wpcdt_post_sett_mb_content'), WPCDT_POST_TYPE, 'normal', 'high' );

		// Quick - Side Meta Box
		add_meta_box( 'wpcdt-shrt-prev', __( 'How to Use', 'countdown-timer-ultimate' ), array($this, 'wpcdt_shrt_prev_mb_content'), WPCDT_POST_TYPE, 'side' );
	}

	/**
	 * Post Settings Metabox HTML
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_post_sett_mb_content() {
		include_once( WPCDT_DIR .'/includes/admin/metabox/post-sett-metabox.php');
	}

	/**
	 * Quick Post Settings Metabox HTML
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.5
	 */
	function wpcdt_shrt_prev_mb_content() {
		include_once( WPCDT_DIR .'/includes/admin/metabox/wpcdt-shrt-prev.php');
	}

	/**
	 * Function to save metabox values
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_save_metabox_value( $post_id ) {

		global $post_type;

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                	// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )  	// Check Revision
		|| ( $post_type !=  WPCDT_POST_TYPE ) )              					// Check if current post type is supported.
		{
			return $post_id;
		}

		// Taking some data
		$prefix	= WPCDT_META_PREFIX;
		$data	= array();

		// General Settings Meta
		$data['timer_date']		= ! empty( $_POST[$prefix.'timer_date'] )	? wpcdt_clean( $_POST[$prefix.'timer_date'] )	: date_i18n( 'Y-m-d H:i:s', strtotime("+1 day", current_time('timestamp')) );
		$data['design_style']	= isset( $_POST[$prefix.'design_style'] )	? wpcdt_clean( $_POST[$prefix.'design_style'] )	: 'circle';

		// Content Tab Data
		$data['content']['tab']					= isset( $_POST[$prefix.'content']['tab'] )					? wpcdt_clean( $_POST[$prefix.'content']['tab'] )				: '';
		$data['content']['timer_day_text']		= ! empty( $_POST[$prefix.'content']['timer_day_text'] )	? wpcdt_clean( $_POST[$prefix.'content']['timer_day_text'] )	: __('Days', 'countdown-timer-ultimate');
		$data['content']['timer_hour_text']		= ! empty( $_POST[$prefix.'content']['timer_hour_text'] )	? wpcdt_clean( $_POST[$prefix.'content']['timer_hour_text'] )	: __('Hours', 'countdown-timer-ultimate');
		$data['content']['timer_minute_text']	= ! empty( $_POST[$prefix.'content']['timer_minute_text'] )	? wpcdt_clean( $_POST[$prefix.'content']['timer_minute_text'] )	: __('Minutes', 'countdown-timer-ultimate');
		$data['content']['timer_second_text']	= ! empty( $_POST[$prefix.'content']['timer_second_text'] )	? wpcdt_clean( $_POST[$prefix.'content']['timer_second_text'] )	: __('Seconds', 'countdown-timer-ultimate');
		$data['content']['is_timerdays']		= ! empty( $_POST[$prefix.'content']['is_timerdays'] )		? 1 : 0;
		$data['content']['is_timerhours']		= ! empty( $_POST[$prefix.'content']['is_timerhours'] )		? 1 : 0;
		$data['content']['is_timerminutes']		= ! empty( $_POST[$prefix.'content']['is_timerminutes'] )	? 1 : 0;
		$data['content']['is_timerseconds']		= ! empty( $_POST[$prefix.'content']['is_timerseconds'] )	? 1 : 0;

		// If all labels are unchecked then make them checked
		if( ! $data['content']['is_timerdays'] && ! $data['content']['is_timerhours'] && ! $data['content']['is_timerminutes'] && ! $data['content']['is_timerseconds'] ) {

			$data['content']['is_timerdays']	= 1;
			$data['content']['is_timerhours']	= 1;
			$data['content']['is_timerminutes']	= 1;
			$data['content']['is_timerseconds']	= 1;
		}

		// Circle Style 1 Meta
		$data['design']['timercircle_animation']			= isset( $_POST[$prefix.'design']['timercircle_animation'] )			? wpcdt_clean( $_POST[$prefix.'design']['timercircle_animation'] )						: '';
		$data['design']['timercircle_width']				= ! empty( $_POST[$prefix.'design']['timercircle_width'] )				? wpcdt_clean_number( $_POST[$prefix.'design']['timercircle_width'], null, 'abs' )		: 0.1;
		$data['design']['timer_width']						= isset( $_POST[$prefix.'design']['timer_width'] )						? wpcdt_clean_number( $_POST[$prefix.'design']['timer_width'], '' )						: '';

		// Clock Background Colors
		$data['design']['timerbackground_width']			= ! empty( $_POST[$prefix.'design']['timerbackground_width'] )			? wpcdt_clean_number( $_POST[$prefix.'design']['timerbackground_width'], null, 'abs' )	: 1.2;
		$data['design']['timerbackground_color']			= ! empty( $_POST[$prefix.'design']['timerbackground_color'] )			? wpcdt_clean_color( $_POST[$prefix.'design']['timerbackground_color'] )				: '#313332';
		$data['design']['timerdaysbackground_color']		= ! empty( $_POST[$prefix.'design']['timerdaysbackground_color'] )		? wpcdt_clean_color( $_POST[$prefix.'design']['timerdaysbackground_color'] )			: '#e3be32';
		$data['design']['timerhoursbackground_color']		= ! empty( $_POST[$prefix.'design']['timerhoursbackground_color'] )		? wpcdt_clean_color( $_POST[$prefix.'design']['timerhoursbackground_color'] )			: '#36b0e3';
		$data['design']['timerminutesbackground_color']		= ! empty( $_POST[$prefix.'design']['timerminutesbackground_color'] )	? wpcdt_clean_color( $_POST[$prefix.'design']['timerminutesbackground_color'] )			: '#75bf44';
		$data['design']['timersecondsbackground_color']		= ! empty( $_POST[$prefix.'design']['timersecondsbackground_color'] )	? wpcdt_clean_color( $_POST[$prefix.'design']['timersecondsbackground_color'] )			: '#66c5af';
		
		// Update General Meta
		update_post_meta( $post_id, $prefix.'design_style', $data['design_style'] );
		update_post_meta( $post_id, $prefix.'timer_date', $data['timer_date'] );

		// Update Content Meta
		update_post_meta( $post_id, $prefix.'content', $data['content'] );
		
		// Update Design Meta
		update_post_meta( $post_id, $prefix.'design', $data['design'] );
	}

	/**
	 * Add custom column to Post listing page
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_posts_columns( $columns ) {

		$new_columns['wpcdt_end_date']	= esc_html__('End Date', 'countdown-timer-ultimate');
		$new_columns['wpcdt_shortcode']	= esc_html__('Shortcode', 'countdown-timer-ultimate');

		$columns = wpcdt_add_array( $columns, $new_columns, 1, true );

		return $columns;
	}

	/**
	 * Add custom column data to Post listing page
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_post_columns_data( $column, $post_id ) {

		global $post;

		// Taking some variables
		$prefix = WPCDT_META_PREFIX;

		switch ( $column ) {

			case 'wpcdt_end_date':

				$end_date = get_post_meta( $post_id, $prefix.'timer_date', true );
				
				echo $end_date;
				break;

			case 'wpcdt_shortcode':

				echo '<div class="wpos-copy-clipboard wpcdt-shortcode-preview">[wpcdt-countdown id="'.esc_attr($post_id).'"]</div> <br/>';
				break;
		}
	}

	/**
	 * Admin Prior Process
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.1.4
	 */
	function wpcdt_admin_init_process() {

		global $typenow;

		$current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';

		// If plugin notice is dismissed
		if( isset($_GET['message']) && $_GET['message'] == 'wpcdt-plugin-notice' ) {
			set_transient( 'wpcdt_install_notice', true, 604800 );
		}

		// Redirect to external page for upgrade to menu
		if( $typenow == WPCDT_POST_TYPE ) {

			if( $current_page == 'wpcdt-premium' ) {

				$tab_url		= add_query_arg( array( 'post_type' => WPCDT_POST_TYPE, 'page' => 'wpcdt-solutions-features', 'tab' => 'wpcdt_basic_tabs' ), admin_url('edit.php') );

				wp_redirect( $tab_url );
				exit;
			}
		}
	}
}

$wpcdt_admin = new Wpcdt_Admin();