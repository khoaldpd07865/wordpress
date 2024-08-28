<?php
defined( 'ABSPATH' ) || exit;

add_filter('ultp_addons_config', 'ultp_oxygen_config');
function ultp_oxygen_config( $config ) {
	$configuration = array(
		'name' => __( 'Oxygen', 'ultimate-post' ),
		'desc' => __( 'It lets you use PostX’s Gutenberg blocks in the Oxygen Builder by using the Saved Template Addon.', 'ultimate-post' ),
		'img' => ULTP_URL.'/assets/img/addons/oxygen.svg',
		'is_pro' => false,
		'docs' => 'https://wpxpo.com/docs/postx/add-on/oxygen-builder-addon/',
		'live' => 'https://www.wpxpo.com/use-gutenberg-blocks-in-oxygen-builder/live_demo_args',
		'video' => 'https://www.youtube.com/watch?v=iGik4w3ZEuE',
		'position' => 20,
		'integration' => true
	);
	$arr['ultp_oxygen'] = $configuration;
	return $arr + $config;
}


function ultp_oxygen_builder() {
	$settings = ultimate_post()->get_setting('ultp_oxygen');
	if ($settings == 'true') {
		if ( class_exists( 'OxygenElement' ) ) {
			require_once ULTP_PATH.'/addons/oxygen/oxygen.php';
		}
	}
}
add_action( 'init', 'ultp_oxygen_builder' );