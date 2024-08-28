<?php
defined( 'ABSPATH' ) || exit;

add_filter('ultp_addons_config', 'ultp_bricks_builder_config');
function ultp_bricks_builder_config( $config ) {
	$configuration = array(
		'name' => __( 'Bricks Builder', 'ultimate-post' ),
		'desc' => __( 'It lets you use PostX’s Gutenberg blocks in the Bricks Builder by using the Saved Template Addon.', 'ultimate-post' ),
		'img' => ULTP_URL.'/assets/img/addons/bricks.svg',
		'is_pro' => false,
		'live' => 'https://www.wpxpo.com/introducing-postx-bricks-builder-integration/live_demo_args',
		'docs' => 'https://wpxpo.com/docs/postx/add-on/bricks-builder-addon/',
		'video' => 'https://www.youtube.com/watch?v=t0ae3TL48u0',
		'position' => 20,
		'integration' => true
	);
	$arr['ultp_bricks_builder'] = $configuration;
	return $arr + $config;
}


function ultp_postx_bricks_builder() {
	$settings = ultimate_post()->get_setting('ultp_bricks_builder');
	if ($settings == 'true') {
		if ( defined( 'BRICKS_VERSION' ) ) {
			\Bricks\Elements::register_element( ULTP_PATH . '/addons/bricks_builder/bricksbuilder.php' );
		}
	}
}
add_action( 'init', 'ultp_postx_bricks_builder', 11 );