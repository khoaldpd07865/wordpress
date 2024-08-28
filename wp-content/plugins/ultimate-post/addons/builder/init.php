<?php
defined( 'ABSPATH' ) || exit;

add_filter('ultp_addons_config', 'ultp_new_builder_config');
function ultp_new_builder_config( $config ) {
	$configuration = array(
		'name' => __( 'Dynamic Site Builder', 'ultimate-post' ),
		'desc' => __( 'The Gutenberg-based Builder allows users to create dynamic templates for Home and all Archive pages.', 'ultimate-post' ),
		'img' => ULTP_URL.'assets/img/addons/builder-icon.svg',
		'docs' => 'https://wpxpo.com/docs/postx/dynamic-site-builder/',
        'live' => 'https://www.wpxpo.com/postx/addons/builder/live_demo_args',
		'video' => 'https://www.youtube.com/watch?v=0qQmnUqWcIg',
		'is_pro' => false,
		'position' => 5
	);
	$config['ultp_builder'] = $configuration;
	return $config;
}

add_action('init', 'ultp_new_builder_init');
function ultp_new_builder_init(){
	$settings = isset($GLOBALS['ultp_settings']) ? $GLOBALS['ultp_settings'] : [];
	if ( isset($settings['ultp_builder']) ) {
		if ($settings['ultp_builder'] == 'true') {
			require_once ULTP_PATH.'/addons/builder/Builder.php';
			require_once ULTP_PATH.'/addons/builder/RequestAPI.php';
			new \ULTP\Builder();
			new \ULTP\RequestAPI();
		}
	}
}