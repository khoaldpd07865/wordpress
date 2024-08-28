<?php
defined( 'ABSPATH' ) || exit;

add_filter('ultp_addons_config', 'ultp_toc_config');
function ultp_toc_config( $config ) {
	$configuration = array(
		'name' => __( 'Table of Contents', 'ultimate-post' ),
		'desc' => __( 'It enables a highly customizable block to the Gutenberg blocks library to display the Table of Contents.', 'ultimate-post' ),
		'img' => ULTP_URL.'/assets/img/addons/table-of-content.svg',
		'is_pro' => false,
		'docs' => 'https://wpxpo.com/docs/postx/add-on/table-of-content/', 
		'live' => 'https://www.wpxpo.com/postx/addons/table-of-content/live_demo_args',
		'video' => 'https://www.youtube.com/watch?v=xKu_E720MkE',
		'position' => 25
	);
	$arr['ultp_table_of_content'] = $configuration;
	return $arr + $config;
}

add_filter( 'rank_math/researches/toc_plugins', function( $toc_plugins ) {
	if (has_block( 'ultimate-post/table-of-content' ) ) {
		$toc_plugins['ultimate-post/ultimate-post.php'] = 'PostX';
	}
 	return $toc_plugins;
});