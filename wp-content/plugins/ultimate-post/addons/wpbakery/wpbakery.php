<?php

if ( ! class_exists( 'PostX_WPBakery_Widget' ) ) {

    class PostX_WPBakery_Widget {

        /**
         * Main constructor
         */
        public function __construct() {
            add_shortcode( 'postx_wpbakery_widget', __CLASS__ . '::output' );
            if ( function_exists( 'vc_lean_map' ) ) {
                vc_lean_map( 'postx_wpbakery_widget', __CLASS__ . '::map' );
            }
        }

        /**
         * Shortcode output
         */
        public static function output( $atts, $content = null ) {
            $output = '';
            $atts = vc_map_get_attributes( 'postx_wpbakery_widget', $atts );

            $body_class = get_body_class();
            $templates = $atts['saved_template'];
            
            if ( $templates && $templates != 'empty' ) {
                ultimate_post()->register_scripts_common();
                if (isset($_GET['vc_editable'])) {  // @codingStandardsIgnoreLine
                    $output .= ultimate_post()->set_css_style($templates, true);
                } else {
                    ultimate_post()->set_css_style($templates);
                }

                $args = array( 'p' => $templates, 'post_type' => 'ultp_templates' );
                $the_query = new \WP_Query($args);

                if ( $the_query->have_posts() ) {
                    while ($the_query->have_posts()) {
                        $the_query->the_post();
                        ob_start();
                        the_content();
                        $output .= ob_get_clean();
                    }
                    wp_reset_postdata();
                }
            } else {
                if ( isset($_GET['vc_editable']) ) {    // @codingStandardsIgnoreLine
                    $output .= '<p style="text-align:center;">'.sprintf( esc_html__( 'Pick a Template from your saved ones. Or create a template from: %s.' , 'ultimate-post' ) . ' ', '<strong><i>' . esc_html( 'Dashboard > PostX > Saved Templates', 'ultimate-post' ) . '</i></strong>' ).'</p>';
                }
            }

            return $output;
        }

        


        public static function map() {
            return array(
                'name'        => esc_html__( 'PostX Template', 'ultimate-post' ),
                'description' => esc_html__( 'PostX Templates for WPBakery.', 'ultimate-post' ),
                'base'        => 'postx_wpbakery_widget',
                'icon'        => ULTP_URL . '/addons/wpbakery/wpbakery.png',
                'params'      => array(
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Select Your Template', 'ultimate-post' ),
                        'param_name' => 'saved_template',
                        'value'      => array_flip(ultimate_post()->get_all_lists('ultp_templates', 'empty')),
                        'description'=> esc_html__( 'Pick a Template from your saved ones. Or create a template from: "Dashboard > PostX > Saved Templates"', 'ultimate-post' )
                    )
                ),
            );
        }

    }

}

new PostX_WPBakery_Widget;