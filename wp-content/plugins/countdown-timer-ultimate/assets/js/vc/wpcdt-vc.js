(function ( $ ) {

	/* WP Text Element */
	window.InlineShortcodeView_vc_wp_text = window.InlineShortcodeView.extend({
		render: function () {
			var model_id = this.model.get( 'id' );
			window.InlineShortcodeView_vc_wp_text.__super__.render.call( this );
			vc.frame_window.vc_iframe.addActivity( function (){
				this.wpcdt_all_timer_init();
			});
			return this;
		}
	});

	/* Text Block Element */
	window.InlineShortcodeView_vc_column_text = window.InlineShortcodeView.extend({
		render: function () {
			var model_id = this.model.get( 'id' );
			window.InlineShortcodeView_vc_column_text.__super__.render.call( this );
			vc.frame_window.vc_iframe.addActivity( function (){
				this.wpcdt_all_timer_init();
			});
			return this;
		}
	});

	/* Raw HTML Element */
	window.InlineShortcodeView_vc_raw_html = window.InlineShortcodeView.extend({
		render: function () {
			var model_id = this.model.get( 'id' );
			window.InlineShortcodeView_vc_raw_html.__super__.render.call( this );
			vc.frame_window.vc_iframe.addActivity( function (){
				this.wpcdt_all_timer_init();
			});
			return this;
		}
	});

	/* Message Box Element */
	window.InlineShortcodeView_vc_message = window.InlineShortcodeView.extend({
		render: function () {
			var model_id = this.model.get( 'id' );
			window.InlineShortcodeView_vc_message.__super__.render.call( this );
			vc.frame_window.vc_iframe.addActivity( function (){
				this.wpcdt_all_timer_init();
			});
			return this;
		}
	});

	/* FAQ Element */
	window.InlineShortcodeView_vc_toggle = window.InlineShortcodeView.extend({
		render: function () {
			var model_id = this.model.get( 'id' );
			window.InlineShortcodeView_vc_toggle.__super__.render.call( this );
			vc.frame_window.vc_iframe.addActivity( function (){
				this.wpcdt_all_timer_init();
			});
			return this;
		}
	});

	/* Call to Action Element */
	window.InlineShortcodeView_vc_cta = window.InlineShortcodeView.extend({
		render: function () {
			var model_id = this.model.get( 'id' );
			window.InlineShortcodeView_vc_cta.__super__.render.call( this );
			vc.frame_window.vc_iframe.addActivity( function (){
				this.wpcdt_all_timer_init();
			});
			return this;
		}
	});
})( window.jQuery );