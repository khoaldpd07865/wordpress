(function($) {

	/* Vertical Tab */
	$( document ).on( "click", ".wpcdt-vtab-nav a", function() {

		$(".wpcdt-vtab-nav").removeClass('wpcdt-active-vtab');
		$(this).parent('.wpcdt-vtab-nav').addClass("wpcdt-active-vtab");

		var selected_tab = $(this).attr("href");
		$('.wpcdt-vtab-cnt').hide();

		/* Show the selected tab content */
		$(selected_tab).show();

		/* Pass selected tab */
		$('.wpcdt-selected-tab').val(selected_tab);
		return false;
	});

	/* Color Picker */
	if( $('.wpcdt-colorpicker').length > 0 ) {
		$('.wpcdt-colorpicker').wpColorPicker({
			width	: 260,
		}).closest('td').addClass('wpcdt-colorpicker-wrap');
	}

	/* Initialize Datetimepicker */
	if( $('.wpcdt-datetime').length > 0 ) {

		$('.wpcdt-datetime').datetimepicker({
			dateFormat	: 'yy-mm-dd',
			timeFormat	: 'HH:mm:ss',
			minDate		: 0,
			changeMonth	: true,
			changeYear	: true,
		});
	}

	/* jQuery UI Slider */
	if( $('.wpcdt-ui-slider').length > 0 ) {
		$('.wpcdt-ui-slider').each(function() {

			var cls_ele 	= $(this).closest('td');
			var slide_val	= cls_ele.find('.wpcdt-ui-slider-number').val();
			var	min_val		= cls_ele.find('.wpcdt-ui-slider-number').attr('min');
			var	max_val		= cls_ele.find('.wpcdt-ui-slider-number').attr('max');
			var	step_val	= cls_ele.find('.wpcdt-ui-slider-number').attr('step');

			$(this).slider({
				min		: min_val	? Math.abs( min_val )	: 0,
				max		: max_val	? Math.abs( max_val )	: 1,
				step	: step_val	? Math.abs( step_val )	: 1,
				slide	: function(event, ui) {
							cls_ele.find('.wpcdt-ui-slider-number').val( ui.value );
							cls_ele.find( ui.handle ).text( ui.value );
						},
				create	: function(event, ui) {
							$(this).slider('value', slide_val );
						},
			});

			cls_ele.find('.ui-slider-handle').text( slide_val );
		});
	}

	/* On change of Select Dropdown */
	$( document ).on( 'change', '.wpcdt-show-hide', function() {

		var prefix		= $(this).attr('data-prefix');
		var inp_type	= $(this).attr('type');
		var showlabel	= $(this).attr('data-label');

		if(typeof(showlabel) == 'undefined' || showlabel == '' ) {
			showlabel = $(this).val();
		}

		if( prefix ) {
			showlabel = prefix +'-'+ showlabel;
			$('.wpcdt-show-hide-row-'+prefix).hide();
			$('.wpcdt-show-for-all-'+prefix).show();
		} else {
			$('.wpcdt-show-hide-row').hide();
			$('.wpcdt-show-for-all').show();
		}

		$('.wpcdt-show-if-'+showlabel).hide();
		$('.wpcdt-hide-if-'+showlabel).hide();

		if( inp_type == 'checkbox' || inp_type == 'radio' ) {
			if( $(this).is(":checked") ) {
				$('.wpcdt-show-if-'+showlabel).show();
			} else {
				$('.wpcdt-hide-if-'+showlabel).show();
			}
		} else {
			$('.wpcdt-show-if-'+showlabel).show();
		}
	});

	/* Vertical Tab */
	$( document ).on( "click", ".wpcdt-vtab-nav a", function() {

		$(".wpcdt-vtab-nav").removeClass('wpcdt-active-vtab');
		$(this).parent('.wpcdt-vtab-nav').addClass("wpcdt-active-vtab");

		var selected_tab = $(this).attr("href");
		$('.wpcdt-vtab-cnt').hide();

		/* Show the selected tab content */
		$(selected_tab).show();

		/* Pass selected tab */
		$('.wpcdt-selected-tab').val(selected_tab);
		return false;
	});

	/* Remain selected tab for user */
	if( $('.wpcdt-selected-tab').length > 0 ) {
		
		var sel_tab = $('.wpcdt-selected-tab').val();

		if( typeof(sel_tab) !== 'undefined' && sel_tab != '' && $(sel_tab).length > 0 ) {
			$('.wpcdt-vtab-nav [href="'+sel_tab+'"]').click();
		} else {
			$('.wpcdt-vtab-nav:first-child a').click();
		}
	}

	/* Click to Copy the Text */
	$(document).on('click', '.wpos-copy-clipboard', function() {
		var copyText = $(this);
		copyText.select();
		document.execCommand("copy");
	});

	/* Publish button event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.layout-rendered', wpcdt_fl_render_preview );
})(jQuery);

/* Function to render shortcode preview for Beaver Builder */
function wpcdt_fl_render_preview() {
	wpcdt_all_timer_init();
}