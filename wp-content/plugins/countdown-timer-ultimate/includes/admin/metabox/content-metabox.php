<?php
/**
 * Handles Content Setting metabox HTML
 * 
 * @package Countdown Timer Ultimate
 * @since 1.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div id="wpcdt_content_sett" class="wpcdt-vtab-cnt wpcdt-content-sett wpcdt-pro-feature wpcdt-clearfix">

	<div class="wpcdt-tab-info-wrap">
		<div class="wpcdt-tab-title"><?php esc_html_e('Content Settings', 'countdown-timer-ultimate'); ?></div>
		<span class="wpcdt-tab-desc"><?php esc_html_e('Choose Timer content settings.', 'countdown-timer-ultimate'); ?> <strong><?php echo sprintf( __( 'Utilize these <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'countdown-timer-ultimate'), WPCDT_PLUGIN_LINK_UNLOCK); ?></strong></span>
	</div>

	<table class="form-table wpcdt-tbl">
		<tbody>
			<tr>
				<th>
					<label for="wpcdt-show-title"><?php _e('Show Title', 'countdown-timer-ultimate'); ?></label>
				</th>
				<td>
					<select name="" class="wpcdt-select wpcdt-show-title" id="wpcdt-show-title" disabled="disabled">
						<option value=""><?php esc_html_e('Show', 'countdown-timer-ultimate'); ?></option>
						<option value=""><?php esc_html_e('Hide', 'countdown-timer-ultimate'); ?></option>
					</select><br/>
					<span class="description"><?php _e('Show / Hide timer title.', 'countdown-timer-ultimate'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="wpcdt-content-position"><?php _e('Content Position', 'countdown-timer-ultimate'); ?></label>
				</th>
				<td>
					<select name="" class="wpcdt-select wpcdt-content-position" id="wpcdt-content-position" disabled="disabled">
						<option value=""><?php esc_html_e('Above Timer', 'countdown-timer-ultimate'); ?></option>
						<option value=""><?php esc_html_e('Below Timer', 'countdown-timer-ultimate'); ?></option>
					</select><br/>
					<span class="description"><?php _e('Set the timer content position.', 'countdown-timer-ultimate'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="wpcdt-completion-txt"><?php _e('Completion Text', 'countdown-timer-ultimate'); ?></label>
				</th>
				<td class="wpcdt-disabled-editor">
					<?php wp_editor( '', 'wpcdt-completion-txt', array('textarea_name' => '', 'textarea_rows' => 8) ); ?>
					<span class="description"><?php _e('Enter completion text which will be shown after the countdown timer is completed.', 'countdown-timer-ultimate'); ?></span><br>
					<span class="description"><?php _e('Note: To embed any third party video, kindly use like below,', 'countdown-timer-ultimate'); ?></span><br>
					<code> [embed]Your Video URL[/embed] </code>
				</td>
			</tr>
		</tbody>
	</table>
</div>