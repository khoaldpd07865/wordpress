<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package Countdown Timer Ultimate
 * @since 1.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="wrap wpcdt-wrap">
	<style type="text/css">
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box.postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.wpcdt-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wpcdt-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.wpos-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.button-orange{background: #ff2700 !important;border-color: #ff2700 !important; font-weight: 600;}

		.wpos-box{box-shadow: 0 5px 30px 0 rgba(214,215,216,.57);background: #fff; padding-bottom:10px; position:relative;}
		.wpos-box ul{padding: 15px;}
		.wpos-box h5{background:#555; color:#fff; padding:15px; text-align:center;}
		.wpos-box h4{ padding:0 15px; margin:5px 0; font-size:18px;}
		.wpos-box .button{margin:0px 15px 15px 15px; text-align:center; padding:7px 15px; font-size:15px;display:inline-block;}
		.wpos-box .wpos-list{list-style:square; margin:10px 0 0 20px;}
		.wpos-clearfix:before, .wpos-clearfix:after{content: "";display: table;}
		.wpos-clearfix::after{clear: both;}
		.wpos-clearfix{clear: both;}
		.wpos-col{width: 47%; float: left; margin-right:10px; margin-bottom:10px;}
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box.postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.wpcdt-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wpcdt-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.wpos-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.button-orange{background: #ff5d52 !important;border-color: #ff5d52 !important; font-weight: 600;}
		.button-blue{background: #0055fb !important;border-color: #0055fb !important; font-weight: 600;}
	</style>
	<h2><?php esc_html_e( 'How It Works', 'countdown-timer-ultimate' ); ?></h2>
	<div class="post-box-container">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">

				<!--How it workd HTML -->
				<div id="post-body-content">
					<div class="meta-box-sortables">
						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php esc_html_e( 'Need Support & Solutions?', 'countdown-timer-ultimate' ); ?></span>
								</h2>
							</div>
							<div class="inside wpos-clearfix">
								<h2 style="font-size:22px; text-align:center;"> <b>Countdown Timer Ultimate</b> work with the help of shortcodes as well as leading page builders and themes listed below.</h2>
								
								<div class="wpos-clearfix">
									<img src="<?php echo esc_url(WPCDT_URL); ?>/assets/images/page-builder-support.jpg" style="max-width:100% " />
								</div>
							</div><!-- .inside -->
						</div><!-- #general -->
					</div><!-- .meta-box-sortables -->
					
					<!-- Help to improve this plugin! -->
					<div class="meta-box-sortables">
						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php esc_html_e( 'Help to improve this plugin!', 'countdown-timer-ultimate' ); ?></span>
								</h2>
							</div>
							<div class="inside">
								<p><?php echo sprintf( __( 'Enjoyed this plugin? You can help by rate this plugin <a href="%s" target="_blank">5 stars!', 'countdown-timer-ultimate'), 'https://wordpress.org/support/plugin/countdown-timer-ultimate/reviews/' ); ?></a></p>
							</div><!-- .inside -->
						</div><!-- #general -->
					</div><!-- .meta-box-sortables -->
				</div><!-- #post-body-content -->

				<!--Upgrad to Pro HTML -->
				<div id="postbox-container-1" class="postbox-container">
					<!-- <div class="metabox-holder wpos-pro-box"> -->
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox wpos-pro-box">
								<h3 class="hndle">
									<span><?php esc_html_e( 'Countdown Timer Premium Features', 'countdown-timer-ultimate' ); ?></span>
								</h3>
								<div class="inside">
									<ul class="wpos-list">
										<li>12+ stunning cool designs for clock and timer.</li>
										<li>Fully customized clock.</li>
										<li>Schedule Timer</li>
										<li>Recurring Timer</li>
										<li>Simple Timer Shortcode</li>
										<li>Pre Text Timer Shortcode</li>
										<li>Manage Completion Text</li>
										<li>Create unlimited Countdowns Timer</li>
										<li>Create Countdown in pages/posts</li>
										<li>Custom css</li>
										<li>Templating Feature Support</li>
										<li>Easy to integrate with e-commerce coupons like WooCommerce and Easy Digital Downloads.</li>
										<li>Various parameters for clock like background color, text color and etc.</li>
										<li>Option to show/hide Days, hours, minutes and seconds.</li>
										<li>Clock expiration event. Display your desired text on complition of timer.</li>
										<li>Light weight and fast.</li>
										<li>Fully responsive</li>
										<li>100% Multi language</li>
									</ul>
									<div class="upgrade-to-pro">Gain access to <strong>Countdown Timer Ultimate</strong></div>
									<a class="button button-primary wpos-button-full button-orange" href="<?php echo esc_url(WPCDT_PLUGIN_LINK_UPGRADE); ?>" target="_blank"><?php esc_html_e('Grab Countdown Timer Now', 'countdown-timer-ultimate'); ?></a>
								</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					<!-- </div> --><!-- .metabox-holder -->
				</div><!-- #post-container-1 -->
			</div><!-- #post-body -->
		</div><!-- #poststuff -->
	</div><!-- #post-box-container -->
</div>