(function($) {

	'use strict';

	/* Initialize Countdown Timer */
	wpcdt_all_timer_init();

	/* Beaver Builder Compatibility for Accordion & Tab */
	$(document).on('click', '.fl-accordion-button, .fl-tabs-label', function() {

		var ele_control	= $(this).attr('aria-controls');
		var timer_wrap	= $('#'+ele_control).find('.wpcdt-timer-circle');

		/* Tweak for countdown timer */
		$( timer_wrap ).each(function( index ) {

			var timer_id	= $(this).find('.wpcdt-timer-js').attr('id');
				timer_id	= timer_id + ' .wpcdt-clock-circle';

			if( typeof(timer_id) !== 'undefined' && timer_id != '' ) {
				setTimeout(function() {
					$('#'+timer_id).TimeCircles().rebuild();
				}, 300);
			}
		});
	});

	/* Visual Composer Compatibility for Toggle */
	$(document).on('click', '.vc_toggle', function() {

		var timer_wrap	= $(this).find('.vc_toggle_content .wpcdt-timer-circle');

		$( timer_wrap ).each(function( index ) {

			var timer_id	= $(this).find('.wpcdt-timer-js').attr('id');
				timer_id	= timer_id + ' .wpcdt-clock-circle';

			if( typeof(timer_id) !== 'undefined' && timer_id != '' ) {
				$('#'+timer_id).TimeCircles().rebuild();
			}
		});
	});

	/* Visual Composer Compatibility for Tabs & Accordion */
	$(document).on('click', '.vc_tta-panel-title', function() {

		var cls_ele		= $(this).closest('.vc_tta-panel');
		var timer_wrap	= cls_ele.find('.wpcdt-timer-circle');

		$( timer_wrap ).each(function( index ) {

			var timer_id	= $(this).find('.wpcdt-timer-js').attr('id');
				timer_id	= timer_id + ' .wpcdt-clock-circle';

			if( typeof(timer_id) !== 'undefined' && timer_id != '' ) {
				$('#'+timer_id).TimeCircles().rebuild();
			}
		});
	});

	/* Divi Builder Compatibility for Tabs, Accordion & Toggle */
	$(document).on('click', '.et_pb_toggle', function() {

		var acc_cont	= $(this).find('.et_pb_toggle_content');
		var timer_wrap	= acc_cont.find('.wpcdt-timer-circle');

		/* Tweak for slick slider */
		$( timer_wrap ).each(function( index ) {

			var timer_id	= $(this).find('.wpcdt-timer-js').attr('id');
				timer_id	= timer_id + ' .wpcdt-clock-circle';

			$('#'+timer_id).css({'visibility': 'hidden', 'opacity': 0});

			if( typeof(timer_id) !== 'undefined' && timer_id != '' ) {
				jQuery('#'+timer_id).TimeCircles().rebuild();
				$('#'+timer_id).css({'visibility': 'visible', 'opacity': 1});
			}
		});
	});
})(jQuery);

/* Function to initialize all the timer */
function wpcdt_all_timer_init() {

	/* Circle Style 1 Timer initialize */
	jQuery( '.wpcdt-timer-circle .wpcdt-clock-circle' ).each( function( index ) {

		var cls_ele			= jQuery(this).closest('.wpcdt-timer-wrap');
		var timer_conf		= JSON.parse( cls_ele.attr('data-conf') );
		var timer_id		= cls_ele.find('.wpcdt-timer-js').attr('id');
			timer_id		= timer_id + ' .wpcdt-clock-circle';
		var current_date	= new Date( timer_conf.current_date );
		var expiry_date		= new Date( timer_conf.expiry_date );

		/* Check Timer Initialize Class */
		if( jQuery('#'+timer_id).hasClass('wpcdt-timer-initialized') ) {
			return;
		}

		var difference		= wpcdt_date_diff( current_date, expiry_date );
		var total_seconds	= difference.total_seconds;

		jQuery('#'+timer_id).TimeCircles({
			'animation'			: timer_conf.timercircle_animation,
			'bg_width'			: ( timer_conf.timer_bg_width != '' )		? timer_conf.timer_bg_width		: 1.2,
			'fg_width'			: ( timer_conf.timercircle_width != '' )	? timer_conf.timercircle_width	: 0.1,
			'circle_bg_color'	: ( timer_conf.timer_bgclr != '' )			? timer_conf.timer_bgclr		: '#313332',
			'time'				: {
									'Days'		: {
													'text'	: timer_conf.day_text,
													'color'	: timer_conf.timer_day_bgclr,
													'show'	: ( timer_conf.is_days == 1 ) ? true : false,
												},
									'Hours'		: {
													'text'	: timer_conf.hour_text,
													'color'	: timer_conf.timer_hour_bgclr,
													'show'	: ( timer_conf.is_hours == 1 ) ? true : false,
												},
									'Minutes'	: {
													'text'	: timer_conf.minute_text,
													'color'	: timer_conf.timer_minute_bgclr,
													'show'	: ( timer_conf.is_minutes == 1 ) ? true : false,
												},
									'Seconds'	: {
													'text'	: timer_conf.second_text,
													'color'	: timer_conf.timer_second_bgclr,
													'show'	: ( timer_conf.is_seconds == 1 ) ? true : false,
												},
								},
		});

		jQuery("#"+timer_id).TimeCircles().addListener( wpcdt_timer_complete );
		jQuery("#"+timer_id).addClass('wpcdt-timer-initialized');

		/* Timer complete function */
		function wpcdt_timer_complete( unit, value, total_seconds ) {

			/* Need to stop timer otherwise it will start again on screen resize */
			if( total_seconds <= 0 ) {
				jQuery('#'+timer_id).TimeCircles().stop();
			}
		}

		jQuery(window).on('resize', function() {
			jQuery('#'+timer_id).TimeCircles().rebuild();
		});
	});
}

/* Function to get difference between two dates */
function wpcdt_date_diff( current_date, expiry_date ) {
	material					= [];
	material['days']			= 0;
	material['hours']			= 0;
	material['minutes']			= 0;
	material['seconds']			= 0;
	material['total_seconds']	= 0;

	if( expiry_date > current_date ) {

		// get total seconds between the times
		var delta = Math.abs( expiry_date - current_date ) / 1000;

		// calculate (and subtract) whole days
		var days			= Math.floor( delta / 86400 );
		delta				-= days * 86400;
		material['days']	= days;

		// calculate (and subtract) whole hours
		var hours			= Math.floor( delta / 3600 ) % 24;
		delta				-= hours * 3600;
		material['hours']	= hours;

		// calculate (and subtract) whole minutes
		var minutes			= Math.floor( delta / 60 ) % 60;
		delta				-= minutes * 60;
		material['minutes']	= minutes;

		// what's left is seconds
		var seconds			= delta % 60;
		material['seconds']	= seconds;

		var total_seconds			= ( expiry_date.getTime() - current_date.getTime() ) / 1000;
		material['total_seconds']	= total_seconds;

		return material;
	}

	return material;
}