<?php
/**
 * Handles Design Setting metabox HTML
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$design_arr 	= wpcdt_designs();
$design_data	= get_post_meta( $post->ID, $prefix.'design', true );

// Circle Style 1
$timer_width					= isset( $design_data['timer_width'] ) 						? $design_data['timer_width'] 					: 750;
$timercircle_animation			= ! empty( $design_data['timercircle_animation'] ) 			? $design_data['timercircle_animation'] 		: '';
$timercircle_width				= ! empty( $design_data['timercircle_width'] ) 				? $design_data['timercircle_width'] 			: 0.1;
$timerbackground_width			= ! empty( $design_data['timerbackground_width'] ) 			? $design_data['timerbackground_width'] 		: 1.2;

// Clock Background Color
$timerbackground_color			= ! empty( $design_data['timerbackground_color'] ) 			? $design_data['timerbackground_color'] 		: '#313332';
$timerdaysbackground_color		= ! empty( $design_data['timerdaysbackground_color'] ) 		? $design_data['timerdaysbackground_color'] 	: '#e3be32';
$timerhoursbackground_color		= ! empty( $design_data['timerhoursbackground_color'] ) 	? $design_data['timerhoursbackground_color'] 	: '#36b0e3';
$timerminutesbackground_color	= ! empty( $design_data['timerminutesbackground_color'] ) 	? $design_data['timerminutesbackground_color'] 	: '#75bf44';
$timersecondsbackground_color	= ! empty( $design_data['timersecondsbackground_color'] ) 	? $design_data['timersecondsbackground_color'] 	: '#66c5af';
?>

<div id="wpcdt_design_sett" class="wpcdt-vtab-cnt wpcdt-design-sett wpcdt-clearfix">

	<div class="wpcdt-tab-info-wrap">
		<div class="wpcdt-tab-title"><?php esc_html_e('Design Settings', 'countdown-timer-ultimate'); ?></div>
		<span class="wpcdt-tab-desc"><?php esc_html_e('Choose Timer design settings.', 'countdown-timer-ultimate'); ?></span>
	</div>

	<table class="form-table wpcdt-tbl">
		<tbody>
			<input type="hidden" name="<?php echo wpcdt_esc_attr( $prefix ); ?>design_style" value="circle">
			
			<tr>
				<th>
					<label for="wpcdt-design"><?php _e('Design', 'countdown-timer-ultimate'); ?></label>
				</th>
				<td>
					<select name="" class="wpcdt-select wpcdt-design" id="wpcdt-design">
						<?php if( ! empty( $design_arr ) ) {
							foreach ($design_arr as $design_key => $design_val) { ?>
								<option value="" <?php selected( 'circle', $design_key ); ?> <?php if( $design_key != 'circle' ) { echo 'disabled="disabled"'; } ?>><?php echo $design_val; ?><?php if( $design_key != 'circle' ) { echo ' [PRO]'; } ?></option>
							<?php }
						} ?>
					</select><br/>
					<span class="description"><?php _e('Select timer design.', 'countdown-timer-ultimate'); ?></span><br/>
					<span class="description wpcdt-pro-feature"><?php esc_html_e('For more designs. ', 'countdown-timer-ultimate'); ?>
					<strong><?php echo sprintf( __( 'Utilize this <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'countdown-timer-ultimate'), WPCDT_PLUGIN_LINK_UNLOCK); ?></strong></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="wpcdt-timer-width"><?php _e('Timer Width', 'countdown-timer-ultimate'); ?></label>
				</th>
				<td>
					<input type="text" name="<?php echo wpcdt_esc_attr( $prefix ); ?>design[timer_width]" value="<?php echo wpcdt_esc_attr( $timer_width ); ?>" class="wpcdt-timer-width" id="wpcdt-timer-width" /> <?php _e('PX', 'countdown-timer-ultimate'); ?><br/>
					<span class="description"><?php _e('Set countdown timer width.', 'countdown-timer-ultimate'); ?></span>
				</td>
			</tr>

			<!-- Start - Circle Style 1 Settings -->
			<tr>
				<th colspan="2">
					<div class="wpcdt-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php _e('Circle Style Settings', 'countdown-timer-ultimate'); ?></div>
				</th>
			</tr>
			<tr>
				<th>
					<label for="wpcdt-animation"><?php _e('Animation', 'countdown-timer-ultimate'); ?></label>
				</th>
				<td>
					<select name="<?php echo wpcdt_esc_attr( $prefix ); ?>design[timercircle_animation]" class="wpcdt-select wpcdt-animation" id="wpcdt-animation">
						<option value="smooth" <?php selected( $timercircle_animation, 'smooth' ); ?>><?php esc_html_e('Smooth', 'countdown-timer-ultimate'); ?></option>
						<option value="ticks" <?php selected( $timercircle_animation, 'ticks' ); ?>><?php esc_html_e('Ticks', 'countdown-timer-ultimate'); ?></option>
					</select><br/>
					<span class="description"><?php _e('Select timer circle animation style.', 'countdown-timer-ultimate'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="wpcdt-circle-width"><?php _e('Circle Width (Foreground)', 'countdown-timer-ultimate'); ?></label>
				</th>
				<td>
					<input type="hidden" name="<?php echo wpcdt_esc_attr( $prefix ); ?>design[timercircle_width]" value="<?php echo wpcdt_esc_attr( $timercircle_width ); ?>" min="0.0033" max="0.133" step="0.0033" class="wpcdt-ui-slider-number wpcdt-circle-width" id="wpcdt-circle-width" />
					<div class="wpcdt-ui-slider"></div>
					<span class="description"><?php _e('Set timer circle width.', 'countdown-timer-ultimate'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="wpcdt-bg-width"><?php _e('Background Width', 'countdown-timer-ultimate'); ?></label>
				</th>
				<td>
					<input type="hidden" name="<?php echo wpcdt_esc_attr( $prefix ); ?>design[timerbackground_width]" value="<?php echo wpcdt_esc_attr( $timerbackground_width ); ?>" min="0.1" max="3" step="0.1" class="wpcdt-ui-slider-number wpcdt-bg-width" id="wpcdt-bg-width" />
					<div class="wpcdt-ui-slider"></div>
					<span class="description"><?php _e('Set timer circle background width.', 'countdown-timer-ultimate'); ?></span>
				</td>
			</tr>

			<tr>
				<th colspan="2">
					<div class="wpcdt-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php _e('Clock Background Colors Settings', 'countdown-timer-ultimate'); ?></div>
				</th>
			</tr>
			<tr>
				<th>
					<label for="wpcdt-circle-bgclr"><?php _e('Background Color', 'countdown-timer-ultimate'); ?></label>
				</th>
				<td>
					<input type="text" name="<?php echo wpcdt_esc_attr( $prefix ); ?>design[timerbackground_color]" value="<?php echo wpcdt_esc_attr( $timerbackground_color ); ?>" class="wpcdt-colorpicker wpcdt-circle-bgclr" id="wpcdt-circle-bgclr" /><br/>
					<span class="description"><?php _e('Choose timer circle background color.', 'countdown-timer-ultimate'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label><?php _e('Foreground Colors', 'countdown-timer-ultimate'); ?></label>
				</th>
				<td class="wpcdt-no-padding">
					<table class="form-table wpcdt-tbl">
						<tr>
							<td>
								<input type="text" name="<?php echo wpcdt_esc_attr( $prefix ); ?>design[timerdaysbackground_color]" value="<?php echo wpcdt_esc_attr( $timerdaysbackground_color ); ?>" class="wpcdt-colorpicker wpcdt-dayclr" id="wpcdt-dayclr" /><br/>
								<span class="description"><?php _e('Choose timer days circle color.', 'countdown-timer-ultimate'); ?></span>
							</td>
							<td>
								<input type="text" name="<?php echo wpcdt_esc_attr( $prefix ); ?>design[timerhoursbackground_color]" value="<?php echo wpcdt_esc_attr( $timerhoursbackground_color ); ?>" class="wpcdt-colorpicker wpcdt-hourclr" id="wpcdt-hourclr" /><br/>
								<span class="description"><?php _e('Choose timer hours circle color.', 'countdown-timer-ultimate'); ?></span>
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="<?php echo wpcdt_esc_attr( $prefix ); ?>design[timerminutesbackground_color]" value="<?php echo wpcdt_esc_attr( $timerminutesbackground_color ); ?>" class="wpcdt-colorpicker wpcdt-minuteclr" id="wpcdt-minuteclr" /><br/>
								<span class="description"><?php _e('Choose timer minutes circle color.', 'countdown-timer-ultimate'); ?></span>
							</td>
							<td>
								<input type="text" name="<?php echo wpcdt_esc_attr( $prefix ); ?>design[timersecondsbackground_color]" value="<?php echo wpcdt_esc_attr( $timersecondsbackground_color ); ?>" class="wpcdt-colorpicker wpcdt-secondclr" id="wpcdt-secondclr" /><br/>
								<span class="description"><?php _e('Choose timer seconds circle color.', 'countdown-timer-ultimate'); ?></span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<!-- End - Circle Style 1 Settings -->
		</tbody>
	</table>
</div>