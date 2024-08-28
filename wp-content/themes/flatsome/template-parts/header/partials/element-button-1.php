<?php
/**
 * Button 1 element.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.18.0
 */

?>
<li class="html header-button-1">
	<div class="header-button">
		<?php
		echo flatsome_apply_shortcode( 'button', array(
			'text'        => get_theme_mod( 'header_button_1', 'Button 1' ),
			'link'        => get_theme_mod( 'header_button_1_link' ),
			'target'      => get_theme_mod( 'header_button_1_link_target', '_self' ),
			'rel'         => get_theme_mod( 'header_button_1_link_rel' ),
			'radius'      => get_theme_mod( 'header_button_1_radius', '99px' ),
			'size'        => get_theme_mod( 'header_button_1_size' ),
			'color'       => get_theme_mod( 'header_button_1_color', 'primary' ),
			'depth'       => get_theme_mod( 'header_button_1_depth', '0' ),
			'depth_hover' => get_theme_mod( 'header_button_1_depth_hover', '0' ),
			'style'       => get_theme_mod( 'header_button_1_style' ),
		) );
		?>
	</div>
</li>
