<?php
/**
 * Notice Action.
 * 
 * @package ULTP\Notice
 * @since v.1.0.0
 */
namespace ULTP;

defined('ABSPATH') || exit;

/**
 * Notice class.
 */
class Notice {
    /**
	 * Setup class.
	 *
	 * @since v.1.0.0
	 */
    private $notice_version = 'v13';
    private $valid_notices = array();

    public function __construct() {
        add_filter( 'ultp_dashboard_notice', array( $this, 'dashboard_notice_callback' ) );
        add_action( 'admin_notices', array( $this, 'ultp_installation_notice_callback' ) );
		add_action( 'admin_init', array( $this, 'set_dismiss_notice_callback' ) );
	}

    public function notice_data() {
        $valid_notices = array();
        $default_notice = apply_filters( 'ultp_dashboard_notice', array() );
        if ( is_array( $default_notice ) && count( $default_notice ) > 0 ) {
            foreach ( $default_notice as $key => $notice ) {
                $current_time = gmdate( 'U' );
                if ( $current_time > strtotime( $notice['start'] ) && $current_time < strtotime( $notice['end'] ) && $notice['visibility'] ) {
                    $valid_notices[] = $notice;
                }
            }
        }
        return $valid_notices;
    }

    
    /**
	 * Dashboard Notice Data
     * 
     * @since v.3.1.8
	 * @param NULL
	 * @return NULL
	 */
    public function dashboard_notice_callback() {
        return array(
            array(
                'key' => 'ultp_40k_installation',
                'start' => '7-03-2024',
                'end' => '13-03-2024',
                // 'type' => 'banner',
                // 'content' => ULTP_URL.'assets/img/dashboard_banner/black_friday_free.jpg',
                'type' => 'content',
                'force' => true,
                'url' => ultimate_post()->get_premium_link('', 'dashboard_db_banner'),
                'visibility' => !ultimate_post()->is_lc_active(),
                'priority' => 50,
                'repeat_interval' => '',
            )
        );
    }

    /**
	 * Promotional Dismiss Notice Option Data
     * 
     * @since v.2.0.1
	 * @param NULL
	 * @return NULL
	 */
	public function set_dismiss_notice_callback() {
        $valid_notices = $this->notice_data();
        
        if( !( isset($_GET['ultp_dashboard_nonce']) && wp_verify_nonce(sanitize_key(wp_unslash($_GET['ultp_dashboard_nonce'])), 'ultp-dashboardnonce') )) {
            return;
        }
        if ( count( $valid_notices ) > 0 ) {
            foreach ( $valid_notices as $notice ) {
                $notice_key = isset( $notice['key'] ) ? $notice['key'] : $this->notice_version;                
                if ( isset( $_GET['disable_postx_notice_' .$notice_key ] ) ) {  // @codingStandardsIgnoreLine
                    if ( sanitize_key( $_GET['disable_postx_notice_' . $notice_key] ) == 'yes' ) {  // @codingStandardsIgnoreLine
                        if ( isset( $notice['repeat_interval'] ) && '' != $notice['repeat_interval'] ) {
                            $interval = (int) $notice['repeat_interval'];
                            ultimate_post()->set_transient_without_cache( 'ultp_get_pro_notice_' . $notice_key, 'off',  $interval ); // 30 (2592000) days notice
                        } else {
                            ultimate_post()->set_transient_without_cache( 'ultp_get_pro_notice_' . $notice_key, 'off' ); // 30 (2592000) days notice
                        }
                    }
                }
            }
        }
	}

    /**
	 * Dismiss Notice HTML Data
     * 
     * @since v.1.0.0
	 * @param NULL
	 * @return STRING
	 */
	public function ultp_installation_notice_callback() {
        $valid_notices = $this->notice_data();
        $ultp_dashboard_nonce = wp_create_nonce('ultp-dashboardnonce');
        if ( count( $valid_notices ) > 0 ) {
            $this->ultp_notice_css();
            foreach ( $valid_notices as $notice ) {
                $notice_key = isset( $notice['key'] ) ? $notice['key'] : $this->notice_version;
                if ( ultimate_post()->get_transient_without_cache('ultp_get_pro_notice_' . $notice_key) != 'off' ) {
                    if ( ( $notice['force'] || get_transient('wpxpo_installation_date') != 'yes' ) ) {
                        if ( ! isset( $_GET['disable_postx_notice_' . $notice_key] ) ) {    // @codingStandardsIgnoreLine
                            switch ( $notice['type'] ) {
                                case 'banner': ?>
                                    <div class="wc-install ultp-free-notice">
                                        <div class="wc-install-body ultp-image-banner">
                                            <a class="wc-dismiss-notice" href="<?php echo esc_url( add_query_arg( array( 'disable_postx_notice_' . $notice_key => 'yes', 'ultp_dashboard_nonce' => $ultp_dashboard_nonce ) ) ); ?>">Dismiss</a>
                                            <a class="ultp-btn-image" target="_blank" href="<?php echo esc_url($notice['url']); ?>">
                                                <img loading="lazy" src="<?php echo esc_url($notice['content']); ?>" alt="Discount Banner"/>
                                            </a>
                                        </div>
                                    </div>
                                <?php
                                break;
                                case 'content':
                                    $icon = ULTP_URL . 'assets/img/logo-sm.svg';
                                    $url = 'https://www.wpxpo.com/postx/pricing/?utm_source=postx-ad&utm_medium=topbar-banner&utm_campaign=postx-dashboard';
                                    ?>
                                        <div class="ultp-notice-wrapper notice data_collection_notice"> 
                                            <?php
                                            if ( isset( $icon ) ) {
                                                ?>
                                                    <div class="ultp-notice-icon"> <img src="<?php echo esc_url( $icon ); ?>"/>  </div>
                                                <?php
                                            }
                                            ?>
                                            
                                            <div class="ultp-notice-content-wrapper">
                                                <div class="">Cheers to <strong>40k+ Active Installations!</strong> Millions to come. Let's celebrate with a <strong>flat 40% off </strong>on PostX Pro</div>
                                                <div class="ultp-notice-buttons"> 
                                                    <a class="ultp-notice-btn button button-primary" href="<?php echo esc_url( $url ); ?>" target="_blank"> Upgrade to Pro </a>
                                                    <a href=<?php echo esc_url( add_query_arg( array( 'disable_postx_notice_' . $notice_key => 'yes', 'ultp_dashboard_nonce' => $ultp_dashboard_nonce ) ) ); ?> class="ultp-notice-dont-save-money" > I don’t want to save money </a>
                                                </div>
                                            </div>
                                            <a href=<?php echo esc_url( add_query_arg( array( 'disable_postx_notice_' . $notice_key => 'yes', 'ultp_dashboard_nonce' => $ultp_dashboard_nonce ) ) ); ?> class="ultp-notice-close"><span class="ultp-notice-close-icon dashicons dashicons-dismiss"> </span></a>
                                        </div>
                                    <?php
                                break;
                            }
                        }
                    }
                }
            }
        }
		
	}

    /**
	 * Admin Notice CSS File
     * 
     * @since v.1.0.0
	 * @param NULL
	 * @return STRING
	 */
	public function ultp_notice_css() {
		?>
		<style type="text/css">
            .ultp-notice-wrapper {
                border: 1px solid #c3c4c7;
                border-left: 3px solid #037fff;
                margin-bottom: 15px;
                display: flex;
                align-items: center;
                background: #F7F9FF;
                width: 100%;
                padding: 10px 0px;
                position: relative;
            }
            .ultp-notice-icon {
                margin-left: 15px;
            }
            .ultp-notice-icon img {
                max-width: 42px;
                width: 100%;
            }
            .ultp-notice-content-wrapper {
                display: flex;
                flex-direction: column;
                gap: 8px;
                font-size: 14px;
                line-height: 20px;
                margin-left: 15px;
            }
            .ultp-notice-buttons {
                display: flex;
                align-items: center;
                gap: 15px;
            }
            .ultp-notice-dont-save-money {
                font-size: 12px;
            }
            .ultp-notice-close {
                position: absolute;
                right: 2px;
                top: 5px;
                text-decoration: unset;
                color: #b6b6b6;
                font-family: dashicons;
                font-size: 16px;
                font-style: normal;
                font-weight: 400;
                line-height: 20px;
            }
            .ultp-notice-close-icon {
                font-size: 14px;
            }
            .ultp-free-notice.wc-install {
                display: flex;
                align-items: center;
                background: #fff;
                margin-top: 40px;
                width: calc(100% - 50px);
                border: 1px solid #ccd0d4;
                padding: 4px;
                border-radius: 4px;
                border-left: 3px solid #007fe1;
                line-height: 0;
            }   
            .ultp-free-notice.wc-install img {
                margin-right: 0; 
                max-width: 100%;
            }
            .ultp-free-notice .wc-install-body {
                -ms-flex: 1;
                flex: 1;
                position: relative;
                padding: 10px;
            }
            .ultp-free-notice .wc-install-body.ultp-image-banner{
                padding: 0px;
            }
            .ultp-free-notice .wc-install-body h3 {
                margin-top: 0;
                font-size: 24px;
                margin-bottom: 15px;
            }
            .ultp-install-btn {
                margin-top: 15px;
                display: inline-block;
            }
			.ultp-free-notice .wc-install .dashicons{
				display: none;
				animation: dashicons-spin 1s infinite;
				animation-timing-function: linear;
			}
			.ultp-free-notice.wc-install.loading .dashicons {
				display: inline-block;
				margin-top: 12px;
				margin-right: 5px;
			}
            .ultp-free-notice .wc-install-body h3 {
                font-size: 20px;
                margin-bottom: 5px;
            }
            .ultp-free-notice .wc-install-body > div {
                max-width: 100%;
                margin-bottom: 10px;
            }
            .ultp-free-notice .button-hero {
                padding: 8px 14px !important;
                min-height: inherit !important;
                line-height: 1 !important;
                box-shadow: none;
                border: none;
                transition: 400ms;
            }
            .ultp-free-notice .ultp-btn-notice-pro {
                background: #2271b1;
                color: #fff;
            }
            .ultp-free-notice .ultp-btn-notice-pro:hover,
            .ultp-free-notice .ultp-btn-notice-pro:focus {
                background: #185a8f;
            }
            .ultp-free-notice .button-hero:hover,
            .ultp-free-notice .button-hero:focus {
                border: none;
                box-shadow: none;
            }
			@keyframes dashicons-spin {
				0% {
					transform: rotate( 0deg );
				}
				100% {
					transform: rotate( 360deg );
				}
			}
			.ultp-free-notice .wc-dismiss-notice {
                color: #fff;
                background-color: #000000;
                padding-top: 0px;
                position: absolute;
                right: 0;
                top: 0px;
                padding: 10px 10px 14px;
                border-radius: 0 0 0 4px;
                display: inline-block;
                transition: 400ms;
            }
            .ultp-free-notice .wc-dismiss-notice:hover {
                color:red;
            }
			.ultp-free-notice .wc-dismiss-notice .dashicons{
                display: inline-block;
                text-decoration: none;
                animation: none;
                font-size: 16px;
			}
            /* ===== Eid Banner Css ===== */
            .ultp-free-notice .wc-install-body {
                background: linear-gradient(90deg,rgb(0,110,188) 0%,rgb(2,17,196) 100%);
            }
            .ultp-free-notice p{
                color: #fff;
                margin: 5px 0px;
                font-size: 16px;
                font-weight: 300;
                letter-spacing: 1px;
            }
            .ultp-free-notice p.ultp-enjoy-offer {
                display: inline;
                font-weight: bold;
                
            }
            .ultp-free-notice .ultp-get-now {
                font-size: 14px;
                color: #fff;
                background: #14a8ff;
                padding: 8px 12px;
                border-radius: 4px;
                text-decoration: none;
                margin-left: 10px;
                position: relative;
                top: -4px;
                transition: 400ms;
            }
            .ultp-free-notice .ultp-get-now:hover{
                background: #068fe0;
            }
            .ultp-free-notice .ultp-dismiss {
                color: #fff;
                background-color: #000964;
                padding-top: 0px;
                position: absolute;
                right: 0;
                top: 0px;
                padding: 10px 8px 12px;
                border-radius: 0 0 0 4px;
                display: inline-block;
                transition: 400ms;
            }
            .ultp-free-notice .ultp-dismiss:hover {
                color: #d2d2d2;
            }
            /*----- ULTP Into Notice ------*/
            .notice.notice-success.ultp-notice {
                border-left-color: #4D4DFF;
                padding: 0;
            }
            .ultp-notice-container {
                display: flex;
            }
            .ultp-notice-container a{
                text-decoration: none;
            }
            .ultp-notice-container a:visited{
                color: white;
            }
            .ultp-notice-container img {
                height: 100px; 
                width: 100px;
            }
            .ultp-notice-image {
                padding-top: 15px;
                padding-left: 12px;
                padding-right: 12px;
                background-color: #f4f4ff;
            }
            .ultp-notice-image img{
                max-width: 100%;
            }
            .ultp-notice-content {
                width: 100%;
                padding: 16px;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }
            .ultp-notice-ultp-button {
                max-width: fit-content;
                padding: 8px 15px;
                font-size: 16px;
                color: white;
                background-color: #4D4DFF;
                border: none;
                border-radius: 2px;
                cursor: pointer;
                margin-top: 6px;
                text-decoration: none;
            }
            .ultp-notice-heading {
                font-size: 18px;
                font-weight: 500;
                color: #1b2023;
            }
            .ultp-notice-content-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .ultp-notice-close .dashicons-no-alt {
                font-size: 25px;
                height: 26px;
                width: 25px;
                cursor: pointer;
                color: #585858;
            }
            .ultp-notice-close .dashicons-no-alt:hover {
                color: red;
            }
            .ultp-notice-content-body {
                font-size: 14px;
                color: #343b40;
            }
            .ultp-notice-wholesalex-button:hover {
                background-color: #6C6CFF;
                color: white;
            }
            span.ultp-bold {
                font-weight: bold;
            }
            a.ultp-pro-dismiss:focus {
                outline: none;
                box-shadow: unset;
            }
            .ultp-free-notice .loading, .ultp-notice .loading {
                width: 16px;
                height: 16px;
                border: 3px solid #FFF;
                border-bottom-color: transparent;
                border-radius: 50%;
                display: inline-block;
                box-sizing: border-box;
                animation: rotation 1s linear infinite;
                margin-left: 10px;
            }
            a.ultp-notice-ultp-button:hover {
                color: #fff !important;
            }
            @keyframes rotation {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }
		</style>
		<?php
    }

    public function set_notice($key='',$value='',$expiration='') {
        if($key) {
            $option_name = 'ultp_notice';
            $notice_data = ultimate_post()->get_option_without_cache($option_name,array());
            if(!isset($notice_data) || !is_array($notice_data)) {
                $notice_data = array();
            } 
    
            $notice_data[$key] = $value;
    
            if($expiration) {
                $expire_notice_key = 'timeout_'.$key;
                $notice_data[$expire_notice_key] = time() + $expiration;
            }
            update_option( $option_name, $notice_data );
        }
    }

    public function get_notice($key='') {
        if($key) {
            $option_name = 'ultp_notice';
            $notice_data = ultimate_post()->get_option_without_cache($option_name,array());
            if(!isset($notice_data) || !is_array($notice_data)) {
                return false;
            }

            if(isset($notice_data[$key])) {
                $expire_notice_key = 'timeout_'.$key;
                if(isset($notice_data[$expire_notice_key]) && $notice_data[$expire_notice_key]< time()  ) {
                    unset($notice_data[$key]);
                    unset($notice_data[$expire_notice_key]);
                    update_option( $option_name, $notice_data);
                    return false;
                }
                return $notice_data[$key];
            }
        }
        return false;
    }
    
}