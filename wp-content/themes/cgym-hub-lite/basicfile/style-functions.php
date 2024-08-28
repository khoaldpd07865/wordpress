<?php

function cgym_hub_lite_style() {
    wp_enqueue_style('cgym-hub-lite-basic-style', get_stylesheet_uri());
    wp_enqueue_style('cgym-hub-lite-style', get_template_directory_uri() . '/skinview/css/main.css');
    wp_enqueue_style('cgym-hub-lite-responsive', get_template_directory_uri() . '/skinview/css/responsive.css');
    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/skinview/css/font-awesome.css');
    wp_enqueue_script('cgym-hub-lite-toggle', get_template_directory_uri() . '/skinview/js/toggle.js', array('jquery'));
    wp_enqueue_script('cgym-hub-lite-customjs', get_template_directory_uri() . '/skinview/js/customjs.js', array('jquery'));
}

add_action('wp_enqueue_scripts', 'cgym_hub_lite_style');
?>
<?php

function cgym_hub_lite_header_style() {
    $cgym_hub_lite_header_text_color = get_header_textcolor();
    if (get_theme_support('custom-header', 'default-text-color') === $cgym_hub_lite_header_text_color) {
        return;
    }
    echo '<style id="cgym-hub-lite-custom-header-styles" type="text/css">';
    if ('blank' !== $cgym_hub_lite_header_text_color) {
        echo '.headerlogo .logo a,
            .blog-post h3 a,
            .blog-post .pageheading h1
			 {
				color: #' . esc_attr($cgym_hub_lite_header_text_color) . '
			}';
    }
    echo '</style>';
}
