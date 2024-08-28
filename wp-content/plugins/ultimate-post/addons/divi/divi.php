<?php
add_action( 'et_builder_ready', 'ultp_postx_template_divi_modules' );

function ultp_postx_template_divi_modules() {
	
	if ( ! class_exists( 'ET_Builder_Module' ) ) { return; }

	class PostX_Template_Module extends ET_Builder_Module {

		public $slug       = 'ultp_postx_template';
		public $vb_support = 'partial';
		
		function init() {
			$this->name			= esc_html__( 'PostX Template', 'ultimate-post' );
			$this->icon_path	= plugin_dir_path( __FILE__ ) . 'icon.svg';
		}
	
		function get_fields() {
			return array(
				'templates' => array(
					'label'			=> esc_html__( 'Select Your Template', 'ultimate-post' ),
					'type'			=> 'select',
					'options'		=> ultimate_post()->get_all_lists('ultp_templates', 'none'),
					'default'		=> 'none',
					'description'	=> esc_html__( 'Pick a Template from your saved ones. Or create a template from: <strong><i>Dashboard > PostX > Saved Templates</i></strong>', 'ultimate-post' ),
				)
			);
		}
	
		function render( $attrs, $render_slug, $content = null ) {
			$templates = $this->props['templates'];
			
			$output = '';
			$content = '';
			$body_class = get_body_class();
			if ( $templates && $templates != 'none' ) {
				$args = array( 'p' => $templates, 'post_type' => 'ultp_templates');
				$the_query = new \WP_Query($args);
				if ($the_query->have_posts()) {
				    while ($the_query->have_posts()) {
				        $the_query->the_post();
						ob_start();
				        the_content();
						if (in_array('et-fb', $body_class)) {
							echo ultimate_post()->set_css_style($templates, true); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							ultimate_post()->register_scripts_common();
							ultimate_post()->set_css_style($templates);
						}
						$content = ob_get_clean();
				    }
				    wp_reset_postdata();
				}
			} else {
				if (in_array('et-fb', $body_class)) {
					$content = '<p style="text-align:center;">'.sprintf( esc_html__( 'Pick a Template from your saved ones. Or create a template from: %s.' , 'ultimate-post' ) . ' ', '<strong><i>' . esc_html( 'Dashboard > PostX > Saved Templates', 'ultimate-post' ) . '</i></strong>' ).'</p>';
				}
			}

			// Render module content
			$output = sprintf(
				'<div class="ultp-shortcode" data-postid="%1$s">%2$s</div>',
				esc_html($templates),
				et_sanitized_previously($content)
			);
			
			return $this->_render_module_wrapper( $output, $render_slug );
		}
	}

	new PostX_Template_Module;
}
