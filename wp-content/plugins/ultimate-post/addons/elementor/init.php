<?php
defined( 'ABSPATH' ) || exit;

add_filter('ultp_addons_config', 'ultp_elementor_config');
function ultp_elementor_config( $config ) {
	$configuration = array(
		'name' => __( 'Elementor', 'ultimate-post' ),
		'desc' => __( 'It lets you use PostX’s Gutenberg blocks in the Elementor Builder by using the Saved Template Addon.', 'ultimate-post' ),
		'img' => ULTP_URL.'/assets/img/addons/elementor-icon.svg',
		'is_pro' => false,
		'live' => 'https://www.wpxpo.com/postx/addons/elementor/live_demo_args',
		'docs' => 'https://wpxpo.com/docs/postx/add-on/elementor-addon/',
		'video' => 'https://www.youtube.com/watch?v=GJEa2_Tow58',
		'position' => 20,
		'integration' => true
	);
	$arr['ultp_elementor'] = $configuration;
	return $arr + $config;
}

add_action('plugins_loaded', 'ultp_elementor_init');
function ultp_elementor_init() {
	$settings = ultimate_post()->get_setting('ultp_elementor');
	if ($settings == 'true') {
		if (did_action( 'elementor/loaded' )) {
			require_once ULTP_PATH.'/addons/elementor/Elementor.php';
			Elementor_ULTP_Extension::instance();
		}
	}
}