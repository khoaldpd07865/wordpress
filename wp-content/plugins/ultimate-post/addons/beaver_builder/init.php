<?php
defined( 'ABSPATH' ) || exit;

add_filter('ultp_addons_config', 'ultp_beaver_builder_config');
function ultp_beaver_builder_config( $config ) {
	$configuration = array(
		'name' => __( 'Beaver', 'ultimate-post' ),
		'desc' => __( 'It lets you use PostX’s Gutenberg blocks in the Beaver Builder by using the Saved Template Addon.', 'ultimate-post' ),
		'img' => ULTP_URL.'/assets/img/addons/beaver.svg',
		'is_pro' => false,
		'live' => 'https://www.wpxpo.com/use-gutenberg-blocks-in-beaver-builder/live_demo_args',
		'docs' => 'https://wpxpo.com/docs/postx/add-on/beaver-builder-addon/',
		'video' => 'https://www.youtube.com/watch?v=aLfI0RkJO6g',
		'position' => 20,
		'integration' => true
	);
	$arr['ultp_beaver_builder'] = $configuration;
	return $arr + $config;
}


function ultp_postx_beaver_builder() {
	$settings = ultimate_post()->get_setting('ultp_beaver_builder');
	if ($settings == 'true') {
		if ( class_exists( 'FLBuilder' ) ) {
			require_once ULTP_PATH.'/addons/beaver_builder/beaverbuilder.php';
		}
	}
}
add_action( 'init', 'ultp_postx_beaver_builder' );