<?php
defined( 'ABSPATH' ) || exit;

add_filter('ultp_addons_config', 'ultp_templates_config');
function ultp_templates_config( $config ) {
	$configuration = array(
		'name' => __( 'Saved Templates', 'ultimate-post' ),
		'desc' => __( 'Create unlimited templates by converting Gutenberg blocks into shortcodes to use them anywhere.', 'ultimate-post' ),
		'img' => ULTP_URL.'/assets/img/addons/saved-template.svg',
		'is_pro' => false,
		'docs' => 'https://wpxpo.com/docs/postx/add-on/shortcodes-support/', 
		'live' => 'https://www.wpxpo.com/postx/addons/save-template/live_demo_args',
		'video' => 'https://www.youtube.com/watch?v=6ydwiIp2Jkg',
		'position' => 10
	);
	$arr['ultp_templates'] = $configuration;
	return $arr + $config;
}

add_action('init', 'ultp_templates_init');
function ultp_templates_init() {
	$settings = ultimate_post()->get_setting('ultp_templates');
	if ($settings == 'true') {
		require_once ULTP_PATH.'/addons/templates/Saved_Templates.php';
		require_once ULTP_PATH.'/addons/templates/Shortcode.php';
		new \ULTP\Saved_Templates();
		new \ULTP\Shortcode();
	}
}