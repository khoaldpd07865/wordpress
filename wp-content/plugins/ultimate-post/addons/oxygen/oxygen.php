<?php

class PostXOxygenElement extends OxyEl {

    function init() {
        // Do some initial things here.
    }

    function afterInit() {
        $this->removeApplyParamsButton();
    }

    function name() {
        return __('PostX Templates', 'ultimate-post');
    }
    
    function slug() {
        return "postx-templates";
    }

    function icon() {
        return ULTP_URL.'addons/oxygen/icon.svg';
    }

    function button_place() {
        // return "interactive";
    }

    function button_priority() {
        return 9;
    }

    
    function render($options, $defaults, $content) {
		$body_class = get_body_class();
		$templates = $options['templates'];
		
		if ( $templates ) {
			ultimate_post()->register_scripts_common();
			echo ultimate_post()->set_css_style($templates, true); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$args = array( 'p' => $templates, 'post_type' => 'ultp_templates' );
			$the_query = new \WP_Query($args);
			if ( $the_query->have_posts() ) {
				while ($the_query->have_posts()) {
					$the_query->the_post();
					the_content();
				}
				wp_reset_postdata();
			}
		} else {
			if ( isset($_GET['action']) && strpos(sanitize_key($_GET['action']), 'oxy_render_oxy') !== false ) {    // @codingStandardsIgnoreLine
				echo '<p style="text-align:center;">'.sprintf( esc_html__( 'Pick a Template from your saved ones. Or create a template from: %s.' , 'ultimate-post' ) . ' ', '<strong><i>' . esc_html( 'Dashboard > PostX > Saved Templates', 'ultimate-post' ) . '</i></strong>' ).'</p>';
			}
		}
    }

    function controls() {
		$this->addOptionControl(
            array(
                'type' => 'dropdown',
                'name' => esc_html__( 'Select Your Template', 'ultimate-post' ),
                'slug' => 'templates',
                'default' => ''
            )
        )->setValue(ultimate_post()->get_all_lists('ultp_templates'))->rebuildElementOnChange();
    }

    function defaultCSS() {
		ultimate_post()->register_scripts_common();
    }
    
}

new PostXOxygenElement();
