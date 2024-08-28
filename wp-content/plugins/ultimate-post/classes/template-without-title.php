<?php
defined( 'ABSPATH' ) || exit;

$is_block_theme = wp_is_block_theme();
$header_id = ultimate_post()->get_setting('ultp_builder') != 'false' ? ultimate_post()->conditions( 'header' ) : '';
$footer_id = ultimate_post()->get_setting('ultp_builder') != 'false' ? ultimate_post()->conditions( 'footer' ) : '';

if( $is_block_theme ) {
	?><!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<?php 
		wp_head();
		?>
	</head>
	<body <?php body_class(); ?>>
	<?php wp_body_open();

	if( !$header_id ) {
		ob_start();
        block_template_part('header');
		$header = ob_get_clean();
		echo '<header class="wp-block-template-part">'.$header.'</header>';
    }
} else {
	get_header();
}

do_action( 'ultp_before_content' );

$width = ultimate_post()->get_setting( 'container_width' );
$width = $width ? $width : '1140';
?>
<div class="ultp-template-container" style="margin:0 auto;max-width:<?php echo esc_attr( $width ); ?>px; padding: 0 15px; width: -webkit-fill-available; width: -moz-available;">
	<?php
		while ( have_posts() ) : the_post();
			the_content();
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
	?>
</div>

<?php
do_action( 'ultp_after_content' );
if( $is_block_theme ) {
	?>
	</body>
	</html>
	<?php
	if ( !$footer_id ) {
		ob_start();
        block_template_part('footer');
		$footer = ob_get_clean();
		echo '<footer class="wp-block-template-part">'.$footer.'</footer>';
    }
	if ( $header_id ) {
		$GLOBALS['wp_filter'];
		if ( isset($GLOBALS['wp_filter']['wp_head']) && $GLOBALS['wp_filter']['wp_head']->callbacks && !empty($GLOBALS['wp_filter']['wp_head']->callbacks) ) {
			$callbacks = $GLOBALS['wp_filter']['wp_head']->callbacks;
			foreach($callbacks as $k => $val) {
				if (isset($val) && !empty($val) ) {
					foreach($val as $pp => $qq) {
						if ( $pp && strpos($pp, 'header_builder_template' ) > -1) {
							remove_action('wp_head', $pp);
						}
					}
				}
			}
		}
	}
	wp_head();
	wp_footer();
} else {
	get_footer();
}