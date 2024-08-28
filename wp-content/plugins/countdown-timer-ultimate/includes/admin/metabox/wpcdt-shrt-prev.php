<?php
/**
 * Handles shortcode preview metabox HTML
 *
 * @package Countdown Timer Ultimate
 * @since 1.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;
?>

<p><?php _e('To display shortcode, add the following shortcode to your page or post.', 'countdown-timer-ultimate'); ?></p>
<div class="wpos-copy-clipboard wpcdt-shortcode-preview">[wpcdt-countdown id="<?php echo $post->ID; ?>"]</div>

<p><?php _e('If adding the shortcode to your theme files, add the following template code.', 'countdown-timer-ultimate'); ?></p>
<div class="wpos-copy-clipboard wpcdt-shortcode-preview">&lt;?php echo do_shortcode('[wpcdt-countdown id="<?php echo $post->ID; ?>"]'); ?&gt;</div>