<?php
defined( 'ABSPATH' ) || exit;

add_filter('ultp_addons_config', 'ultp_custom_font_config');
function ultp_custom_font_config( $config ) {
	$configuration = array(
		'name' => __( 'Custom Font', 'ultimate-post' ),
		'desc' => __( 'It allows you to upload custom fonts and use them on any PostX blocks with all typographical options.', 'ultimate-post' ),
		'img' => ULTP_URL.'assets/img/addons/custom_font.svg',
		'is_pro' => false,
		'live' => 'https://www.wpxpo.com/wordpress-custom-fonts/live_demo_args',
		'docs' => 'https://wpxpo.com/docs/postx/add-on/custom-fonts/',
		'video' => 'https://www.youtube.com/watch?v=tLqUpj_gL-U',
		'position' => 7
	);
	$arr['ultp_custom_font'] = $configuration;
	return $arr + $config;
}

add_action('init', 'ultp_custom_font_init');
function ultp_custom_font_init() {
	$settings = ultimate_post()->get_setting('ultp_custom_font');
	if ($settings == 'true') {
		if ( current_user_can('manage_options') ) {
            require_once ULTP_PATH.'/addons/custom_font/Custom_Font.php';
			new \ULTP\Custom_Font();
        }
	}
}