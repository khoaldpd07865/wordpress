<?php
/**
 * Template for Countdown Timer Circle
 *
 * @package Countdown Timer Ultimate
 * @version 1.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wpcdt-wrap wpcdt-timer-wrap wpcdt-clearfix <?php echo esc_attr($classes); ?>" data-conf="<?php echo htmlspecialchars( json_encode( $timer_conf ) ); ?>">
	<div class="wpcdt-timer-inr wpcdt-timer wpcdt-timer-js" id="wpcdt-timer-<?php echo esc_attr($unique); ?>" data-id="<?php echo esc_attr($timer_id); ?>">
		<div class="wpcdt-clock wpcdt-clock-circle" data-timer="<?php echo esc_attr($totalseconds); ?>"></div>
	</div>
</div>