<?php
defined( 'ABSPATH' ) || exit;

class Gutenberg_Post_Blocks_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'gutenberg-post-blocks';
    }

    public function get_title() {
        return __( 'PostX Template', 'ultimate-post' );
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Settings', 'ultimate-post' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
			'saved_template',
			[
				'label' => __( 'Saved Template', 'ultimate-post' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => ultimate_post()->get_all_lists('ultp_templates'),
			]
		);
        $this->add_control(
			'edit_template',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<a href="'.admin_url('edit.php?post_type=ultp_templates').'" style="color:#fff; background-color:#0c0d0e; padding:10px 20px; border-radius:4px; display:inline-block;" target="_blank"><span style="color:#fff; font-size:12px; width:12px; height:12px;" class="dashicons dashicons-edit"></span> '.__('Edit This Template', 'ultimate-post').'</a>',
			]
		);
        $this->add_control(
			'add_new_template',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<a href="'.admin_url('post-new.php?post_type=ultp_templates').'" style="color:#fff; background-color:#0c0d0e; padding:10px 20px; border-radius:4px; display:inline-block;" target="_blank"><span style="color:#fff; font-size:12px; width:12px; height:12px;" class="dashicons dashicons-plus-alt2"></span> '.__('Add New Template', 'ultimate-post').'</a>',
			]
		);
        $this->end_controls_section();
    }


    protected function render() {
        $settings = $this->get_settings_for_display();
        $body_class = get_body_class();
        $id = $settings['saved_template'];

        if ($id) {
            $this->set_inline_css($id);
            echo '<div class="ultp-shortcode" data-postid="'.esc_attr($id).'">';
                $args = array( 'p' => $id, 'post_type' => 'ultp_templates');
                $the_query = new \WP_Query($args);
                if ($the_query->have_posts()) {
                    while ($the_query->have_posts()) {
                        $the_query->the_post();
                        the_content();
                    }
                    wp_reset_postdata();
                }
            echo '</div>';
        } else {
            if (isset($_GET['action']) && $_GET['action'] == 'elementor') { // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.NonceVerification.Recommended	
                echo '<p style="text-align:center;">'.sprintf( esc_html__( 'Pick a Template from your saved ones. Or create a template from: %s.' , 'ultimate-post' ) . ' ', '<strong><i>' . esc_html( 'Dashboard > PostX > Saved Templates', 'ultimate-post' ) . '</i></strong>' ).'</p>';
            }
        }
    }

    public function set_inline_css($id) {
        if (isset($_GET['action']) || isset($_POST['action'])) { //phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.NonceVerification.Recommended
            echo ultimate_post()->set_css_style($id, true); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            ultimate_post()->set_css_style($id);
        }
    }
}