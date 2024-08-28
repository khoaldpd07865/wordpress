<?php

function cgym_hub_lite_customize_register($wp_customize) {

    $wp_customize->add_section('cgym_hub_lite_header', array(
        'title'       => esc_html__(' Header Phone, email and address', 'cgym-hub-lite'),
        'description' => '',
        'priority'    => 30,
    ));

    $wp_customize->add_section('cgym_hub_lite_social', array(
        'title'       => esc_html__(' Social Link', 'cgym-hub-lite'),
        'description' => '',
        'priority'    => 35,
    ));

    $wp_customize->add_section('cgym_hub_lite_footer', array(
        'title'       => esc_html__(' Footer', 'cgym-hub-lite'),
        'description' => '',
        'priority'    => 37,
    ));

    //  =============================
    //  = Text Input phone number                =
    //  =============================
    $wp_customize->add_setting('cgym_hub_lite_phone', array(
        'default'           => '',
        'sanitize_callback' => 'cgym_hub_lite_sanitize_phone_number'
    ));

    $wp_customize->add_control('cgym_hub_lite_phone', array(
        'label'   => esc_html__('Phone Number', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_header',
        'setting' => 'cgym_hub_lite_phone',
        'type'    => 'text'
    ));

    //  =============================
    //  = Text Input Email                =
    //  =============================
    $wp_customize->add_setting('cgym_hub_lite_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email'
    ));

    $wp_customize->add_control('cgym_hub_lite_email', array(
        'label'   => esc_html__('Email', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_header',
        'setting' => 'cgym_hub_lite_email',
        'type'    => 'email'
    ));

    
    //  =============================
    //  = Text Input facebook                =
    //  =============================
    $wp_customize->add_setting('cgym_hub_lite_fb', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control('cgym_hub_lite_fb', array(
        'label'   => esc_html__('Facebook', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_social',
        'setting' => 'cgym_hub_lite_fb',
        'type'    => 'url'
    ));
    //  =============================
    //  = Text Input Twitter                =
    //  =============================
    $wp_customize->add_setting('cgym_hub_lite_twitter', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control('cgym_hub_lite_twitter', array(
        'label'   => esc_html__('Twitter', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_social',
        'setting' => 'cgym_hub_lite_twitter',
        'type'    => 'url'
    ));
    //  =============================
    //  = Text Input googleplus                =
    //  =============================
    $wp_customize->add_setting('cgym_hub_lite_youtube', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control('cgym_hub_lite_youtube', array(
        'label'   => esc_html__('YouTube', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_social',
        'setting' => 'cgym_hub_lite_youtube',
        'type'    => 'url'
    ));
    //  =============================
    //  = Text Input linkedin                =
    //  =============================
    $wp_customize->add_setting('cgym_hub_lite_in', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control('cgym_hub_lite_in', array(
        'label'   => esc_html__('Linkedin', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_social',
        'setting' => 'cgym_hub_lite_in',
        'type'    => 'url'
    ));

    //  =============================
    //  = Text Input pininterest                =
    //  =============================
    $wp_customize->add_setting('cgym_hub_lite_pin', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control('cgym_hub_lite_pin', array(
        'label'   => esc_html__('Pinterest', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_social',
        'setting' => 'cgym_hub_lite_pin',
        'type'    => 'url'
    ));
    //  =============================
    //  = slider section              =
    //  =============================
    $wp_customize->add_section('cgym_hub_lite_banner', array(
        'title'       => esc_html__(' Home banner Text', 'cgym-hub-lite'),
        'description' => esc_html__('add home banner text here.', 'cgym-hub-lite'),
        'priority'    => 36,
    ));

// Banner heading Text
    $wp_customize->add_setting('banner_heading', array(
        'default'           => null,
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('banner_heading', array(
        'type'    => 'text',
        'label'   => esc_html__('Add Banner heading here', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_banner',
        'setting' => 'banner_heading'
    )); // Banner heading Text
    // Banner heading Text
    $wp_customize->add_setting('banner_sub_heading', array(
        'default'           => null,
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('banner_sub_heading', array(
        'type'    => 'text',
        'label'   => esc_html__('Add Banner sub heading here', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_banner',
        'setting' => 'banner_sub_heading'
    )); // Banner heading Text
    //  =============================
    //  = url banner readmoret                =
    //  =============================
    $wp_customize->add_setting('cgym_hub_lite_slider_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control('cgym_hub_lite_slider_url', array(
        'label'   => esc_html__('Url', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_banner',
        'setting' => 'cgym_hub_lite_slider_url',
        'type'    => 'url'
    ));

    // Banner heading Text
    $wp_customize->add_setting('cgym_hub_lite_slider_readmore', array(
        'default'           => null,
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('cgym_hub_lite_slider_readmore', array(
        'type'    => 'text',
        'label'   => esc_html__('Add button text here', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_banner',
        'setting' => 'cgym_hub_lite_slider_readmore'
    )); // Banner heading Text
    //  =============================
    //  = box section              =
    //  =============================
    $wp_customize->add_section('cgym_hub_lite_box', array(
        'title'       => esc_html__('Resource Section', 'cgym-hub-lite'),
        'description' => esc_html__('Upload image, it will auto crop with 400*250.', 'cgym-hub-lite'),
        'priority'    => 36,
    ));

    $wp_customize->add_setting('cgym_hub_lite_box1', array(
        'default'           => '0',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'cgym_hub_lite_sanitize_page'
    ));

    $wp_customize->add_control('cgym_hub_lite_box1', array(
        'type'    => 'dropdown-pages',
        'label'   => esc_html__('Select page for Resource first:', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_box'
    ));

    $wp_customize->add_setting('cgym_hub_lite_box2', array(
        'default'           => '0',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'cgym_hub_lite_sanitize_page'
    ));

    $wp_customize->add_control('cgym_hub_lite_box2', array(
        'type'    => 'dropdown-pages',
        'label'   => esc_html__('Select page for Resource second:', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_box'
    ));

    $wp_customize->add_setting('cgym_hub_lite_box3', array(
        'default'           => '0',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'cgym_hub_lite_sanitize_page'
    ));

    $wp_customize->add_control('cgym_hub_lite_box3', array(
        'type'    => 'dropdown-pages',
        'label'   => esc_html__('Select page for Resource third:', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_box'
    ));

    $wp_customize->add_setting('cgym_hub_lite_box4', array(
        'default'           => '0',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'cgym_hub_lite_sanitize_page'
    ));

    $wp_customize->add_control('cgym_hub_lite_box4', array(
        'type'    => 'dropdown-pages',
        'label'   => esc_html__('Select page for Resource Fourth:', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_box'
    ));
    
    // Box CTA Text
    $wp_customize->add_setting('cgym_hub_lite_box_cta', array(
        'default'           => null,
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('cgym_hub_lite_box_cta', array(
        'type'    => 'text',
        'label'   => esc_html__('Add button text', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_box',
        'setting' => 'cgym_hub_lite_box_cta'
    )); 

//  =============================
    //  = box section              =
    //  =============================
    //  =============================
    //  = Footer              =
    //  =============================
    // Footer design and developed
    $wp_customize->add_setting('cgym_hub_lite_design', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field'
    ));

    $wp_customize->add_control('cgym_hub_lite_design', array(
        'label'   => esc_html__('Design and developed', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_footer',
        'setting' => 'cgym_hub_lite_design',
        'type'    => 'textarea'
    ));
    // Footer copyright
    $wp_customize->add_setting('cgym_hub_lite_copyright', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field'
    ));

    $wp_customize->add_control('cgym_hub_lite_copyright', array(
        'label'   => esc_html__('Copyright', 'cgym-hub-lite'),
        'section' => 'cgym_hub_lite_footer',
        'setting' => 'cgym_hub_lite_copyright',
        'type'    => 'textarea'
    ));
}

add_action('customize_register', 'cgym_hub_lite_customize_register');

if (!function_exists('cgym_hub_lite_sanitize_page')) :

    function cgym_hub_lite_sanitize_page($page_id, $setting) {
        // Ensure $input is an absolute integer.
        $page_id = absint($page_id);
        // If $page_id is an ID of a published page, return it; otherwise, return the default.
        return ( 'publish' === get_post_status($page_id) ? $page_id : $setting->default );
    }



endif;