<?php
/**
 * Handles Countdown Timer Setting metabox HTML
 *
 * @package Countdown Timer Ultimate
 * @since 1.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

// Get timezone from WP settings
$current_offset	= get_option('gmt_offset');
$curr_timezone	= get_option('timezone_string');

// Remove old Etc mappings. Fallback to gmt_offset.
if ( false !== strpos( $curr_timezone,'Etc/GMT' ) ) {
	$curr_timezone = '';
}

// Create a UTC+- zone if no timezone string exists
if ( empty( $curr_timezone ) ) {
	if ( 0 == $current_offset ) {
		$curr_timezone = 'UTC+0';
	} elseif( $current_offset < 0 ) {
		$curr_timezone = 'UTC' . $current_offset;
	} else {
		$curr_timezone = 'UTC+' . $current_offset;
	}
}

// Taking some variable
$prefix				= WPCDT_META_PREFIX;
$timer_date			= get_post_meta( $post->ID, $prefix.'timer_date', true );
$design_style		= get_post_meta( $post->ID, $prefix.'design_style', true );

// Taking content data
$content_data		= get_post_meta( $post->ID, $prefix.'content', true );
$selected_tab		= ! empty( $content_data['tab'] )				? $content_data['tab'] : '';
$timer_day_text		= ! empty( $content_data['timer_day_text'] )	? $content_data['timer_day_text']		: esc_html__('Days', 'countdown-timer-ultimate');
$timer_hour_text	= ! empty( $content_data['timer_hour_text'] )	? $content_data['timer_hour_text']		: esc_html__('Hours', 'countdown-timer-ultimate');
$timer_minute_text	= ! empty( $content_data['timer_minute_text'] ) ? $content_data['timer_minute_text']	: esc_html__('Minutes', 'countdown-timer-ultimate');
$timer_second_text	= ! empty( $content_data['timer_second_text'] ) ? $content_data['timer_second_text']	: esc_html__('Seconds', 'countdown-timer-ultimate');
$is_timerdays		= ! empty( $content_data['is_timerdays'] )		? 1 : 0;
$is_timerhours		= ! empty( $content_data['is_timerhours'] )		? 1 : 0;
$is_timerminutes	= ! empty( $content_data['is_timerminutes'] )	? 1 : 0;
$is_timerseconds	= ! empty( $content_data['is_timerseconds'] )	? 1 : 0;
?>

<div class="wpcdt-sett-wrap wpcdt-clearfix">
	<div class="wpcdt-meta-sett">
		<table class="form-table wpcdt-tbl">
			<tbody>
				<tr>
					<td colspan="2" class="wpcdt-no-padding">
						<div class="wpcdt-info">
							<?php echo sprintf( __('Countdown Timer Ultimate Pro works with WordPress timezone which you had set from <a href="%s" target="_blank">General Setting</a> page.', 'countdown-timer-ultimate'), admin_url('options-general.php') ); ?> <br/>
							<?php echo __('Your Current timezone is', 'countdown-timer-ultimate') .' : '. $curr_timezone; ?>
						</div>
					</td>
				</tr>

				<tr class="wpcdt-pro-feature">
					<th>
						<label for="wpcdt-start-date"><?php _e('Start Date & Time', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<input type="text" name="" value="" class="wpcdt-start-date" id="wpcdt-start-date" disabled="disabled" /><br/>
						<span class="description"><?php _e('Set countdown timer start date.', 'countdown-timer-ultimate'); ?></span>
						<strong><?php echo sprintf( __( 'Utilize this <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'countdown-timer-ultimate'), WPCDT_PLUGIN_LINK_UNLOCK); ?></strong>
					</td>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-end-date"><?php _e('Expiry Date & Time', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<input type="text" name="<?php echo wpcdt_esc_attr( $prefix ); ?>timer_date" value="<?php echo wpcdt_esc_attr( $timer_date ); ?>" class="wpcdt-datetime wpcdt-end-date" id="wpcdt-end-date" /><br/>
						<span class="description"><?php _e('Set countdown timer expiry date.', 'countdown-timer-ultimate'); ?></span><br/>
						<span class="description"><?php _e('Note: Expiry Date & Time is compulsory. Set long future date for long running timer.', 'countdown-timer-ultimate'); ?></span>
					</td>
				</tr>
				<tr class="wpcdt-pro-feature wpcdt-recurring-time">
					<th>
						<label for="wpcdt-recurring-time"><?php _e('Recurring Time', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td>
						<input type="text" name="" value="" class="wpcdt-recurring-time" id="wpcdt-recurring-time" disabled="disabled" />
						<select name="" class="wpcdt-select wpcdt-recurring-type" disabled="disabled">
							<option><?php esc_html_e('Minutes', 'countdown-timer-ultimate'); ?></option>
							<option><?php esc_html_e('Hours', 'countdown-timer-ultimate'); ?></option>
							<option><?php esc_html_e('Days', 'countdown-timer-ultimate'); ?></option>
						</select><br/>
						<span class="description"><?php _e('Set countdown timer recurring time.', 'countdown-timer-ultimate'); ?></span>
						<strong><?php echo sprintf( __( 'Utilize this <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'countdown-timer-ultimate'), WPCDT_PLUGIN_LINK_UNLOCK); ?></strong>
					</td>
				</tr>
				<tr>
					<th>
						<label for="wpcdt-timer-opt"><?php _e('Timer Label', 'countdown-timer-ultimate'); ?></label>
					</th>
					<td class="wpcdt-no-padding">
						<table class="form-table">
							<tbody>
								<tr>
									<td>
										<input type="checkbox" name="<?php echo wpcdt_esc_attr( $prefix ); ?>content[is_timerdays]" value="1" <?php checked( $is_timerdays, 1 ); ?> class="wpcdt-checkbox wpcdt-days" id="wpcdt-days" />
										<input type="text" name="<?php echo wpcdt_esc_attr( $prefix ); ?>content[timer_day_text]" value="<?php echo wpcdt_esc_attr( $timer_day_text ); ?>" class="wpcdt-day-txt" id="wpcdt-day-txt" /><br/>
										<span class="description"><?php _e('Check this box if you want to enable days and add your desired text in timer.', 'countdown-timer-ultimate'); ?></span>
									</td>
									<td>
										<input type="checkbox" name="<?php echo wpcdt_esc_attr( $prefix ); ?>content[is_timerhours]" value="1" <?php checked( $is_timerhours, 1 ); ?> class="wpcdt-checkbox wpcdt-hours" id="wpcdt-hours" />
										<input type="text" name="<?php echo wpcdt_esc_attr( $prefix ); ?>content[timer_hour_text]" value="<?php echo wpcdt_esc_attr( $timer_hour_text ); ?>" class="wpcdt-hour-txt" id="wpcdt-hour-txt" /><br/>
										<span class="description"><?php _e('Check this box if you want to enable hours and add your desired text in timer.', 'countdown-timer-ultimate'); ?></span>
									</td>
								</tr>
								<tr>
									<td>
										<input type="checkbox" name="<?php echo wpcdt_esc_attr( $prefix ); ?>content[is_timerminutes]" value="1" <?php checked( $is_timerminutes, 1 ); ?> class="wpcdt-checkbox wpcdt-minutes" id="wpcdt-minutes" />
										<input type="text" name="<?php echo wpcdt_esc_attr( $prefix ); ?>content[timer_minute_text]" value="<?php echo wpcdt_esc_attr( $timer_minute_text ); ?>" class="wpcdt-minute-txt" id="wpcdt-minute-txt" /><br/>
										<span class="description"><?php _e('Check this box if you want to enable minutes and add your desired text in timer.', 'countdown-timer-ultimate'); ?></span>
									</td>
									<td>
										<input type="checkbox" name="<?php echo wpcdt_esc_attr( $prefix ); ?>content[is_timerseconds]" value="1" <?php checked( $is_timerseconds, 1 ); ?> class="wpcdt-checkbox wpcdt-seconds" id="wpcdt-seconds" />
										<input type="text" name="<?php echo wpcdt_esc_attr( $prefix ); ?>content[timer_second_text]" value="<?php echo wpcdt_esc_attr( $timer_second_text ); ?>" class="wpcdt-second-txt" id="wpcdt-second-txt" /><br/>
										<span class="description"><?php _e('Check this box if you want to enable seconds and add your desired text in timer.', 'countdown-timer-ultimate'); ?></span>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>

				<tr class="wpcdt-pro-feature">
					<th colspan="2">
						<div class="wpcdt-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php _e('Design Common Settings   ', 'countdown-timer-ultimate'); ?><br>
						<?php echo sprintf( __( 'Utilize these <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'countdown-timer-ultimate'), WPCDT_PLUGIN_LINK_UNLOCK); ?></div>
					</th>
				</tr>
				<tr class="wpcdt-pro-feature">
					<td class="wpcdt-no-padding" colspan="2">
						<table class="form-table">
							<tbody>
								<tr>
									<th>
										<label for="wpcdt-bgclr"><?php _e('Timer Background Color', 'countdown-timer-ultimate'); ?></label>
									</th>
									<td class="wpcdt-disabled-editor">
										<input type="text" name="background_pref" value="" class="wpcdt-colorpicker wpcdt-bgclr" id="wpcdt-bgclr" data-alpha="true" disabled="disabled" /><br/>
										<span class="description"><?php _e('Choose timer background color.', 'countdown-timer-ultimate'); ?></span>
									</td>
					
									<th>
										<label for="wpcdt-fontclr"><?php _e('Timer Font Color', 'countdown-timer-ultimate'); ?></label>
									</th>
									<td class="wpcdt-disabled-editor">
										<input type="text" name="font_clr" value="" class="wpcdt-colorpicker wpcdt-fontclr" id="wpcdt-fontclr" disabled="disabled" /><br/>
										<span class="description"><?php _e('Choose timer font color.', 'countdown-timer-ultimate'); ?></span>
									</td>
								</tr>
								<tr>
									<th>
										<label for="wpcdt-lblclr"><?php _e('Timer Label Color', 'countdown-timer-ultimate'); ?></label>
									</th>
									<td class="wpcdt-disabled-editor">
										<input type="text" name="timertext_color" value="" class="wpcdt-colorpicker wpcdt-lblclr" id="wpcdt-lblclr" disabled="disabled" /><br/>
										<span class="description"><?php _e('Choose timer label color.', 'countdown-timer-ultimate'); ?></span>
									</td>
								
									<th>
										<label for="wpcdt-digitclr"><?php _e('Timer Digit Color', 'countdown-timer-ultimate'); ?></label>
									</th>
									<td class="wpcdt-disabled-editor">
										<input type="text" name="timerdigit_color" value="" class="wpcdt-colorpicker wpcdt-digitclr" id="wpcdt-digitclr" disabled="disabled" /><br/>
										<span class="description"><?php _e('Choose timer digit color.', 'countdown-timer-ultimate'); ?></span>
									</td>
								</tr>
							</td>
						</tbody>
					</table>
				</td>

			</tbody>
		</table>
	</div>

	<!-- Tabs - Start -->
	<div class="wpcdt-vtab-wrap">
		<ul class="wpcdt-vtab-nav-wrap">
			<li class="wpcdt-vtab-nav">
				<a href="#wpcdt_content_sett"><i class="dashicons dashicons-text-page" aria-hidden="true"></i> <?php esc_html_e('Content', 'countdown-timer-ultimate'); ?></a>
			</li>

			<li class="wpcdt-vtab-nav">
				<a href="#wpcdt_design_sett"><i class="dashicons dashicons-admin-customizer" aria-hidden="true"></i> <?php esc_html_e('Design', 'countdown-timer-ultimate'); ?></a>
			</li>
		</ul>

		<div class="wpcdt-vtab-cnt-wrp">
			<?php
				// Content Settings
				include_once( WPCDT_DIR . '/includes/admin/metabox/content-metabox.php' );

				// Design Settings
				include_once( WPCDT_DIR . '/includes/admin/metabox/design-metabox.php' );
			?>
		</div>
		<input type="hidden" value="<?php echo wpcdt_esc_attr( $selected_tab ); ?>" class="wpcdt-selected-tab" name="<?php echo wpcdt_esc_attr( $prefix ); ?>content[tab]" />
	</div>
	<!-- Tabs - End -->
</div><!-- end .wpcdt-sett-wrap -->