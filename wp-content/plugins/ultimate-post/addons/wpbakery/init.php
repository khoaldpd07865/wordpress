<?php
defined( 'ABSPATH' ) || exit;

add_filter('ultp_addons_config', 'ultp_wpbakery_config');
function ultp_wpbakery_config( $config ) {
	$configuration = array(
		'name' => __( 'WPBakery', 'ultimate-post' ),
		'desc' => __( 'It lets you use PostX’s Gutenberg blocks in the WPBakery Builder by using the Saved Template Addon.', 'ultimate-post' ),
		'img' => ULTP_URL.'/assets/img/addons/wpbakery.svg',
		'is_pro' => false,
		'docs' => 'https://wpxpo.com/docs/postx/add-on/wpbakery-page-builder-addon/',
		'live' => 'https://www.wpxpo.com/use-gutenberg-blocks-in-wpbakery-page-builder/live_demo_args',
		'video' => 'https://www.youtube.com/watch?v=f99NZ6N9uDQ',
		'position' => 20,
		'integration' => true
	);
	$arr['ultp_wpbakery'] = $configuration;
	return $arr + $config;
}


function ultp_wpbakery_builder() {
	$settings = ultimate_post()->get_setting('ultp_wpbakery');
	if ($settings == 'true') {
		if (defined( 'WPB_VC_VERSION' )) {
			require_once ULTP_PATH.'/addons/wpbakery/wpbakery.php';
		}
	}
}

add_action( 'init', 'ultp_wpbakery_builder' );