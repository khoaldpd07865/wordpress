<?php
/**
 * @package cgym-hub-lite
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php if (is_singular() && pings_open(get_queried_object())) : ?>
            <link rel="pingback" href="<?php echo esc_url(get_bloginfo('pingback_url')); ?>">
        <?php endif; ?>
        <?php wp_head(); ?>

    </head>
    <body <?php body_class(); ?>>
        <?php wp_body_open(); ?>
        <a class="skip-link screen-reader-text" href="#content">
            <?php esc_attr_e('Skip to content', 'cgym-hub-lite'); ?></a>
        <div id="main_header">
            
                <?php
                get_template_part('controll/template', 'headertop');
                ?>
            
            <div clear="both"></div>
        </div><!--main header -->
        <?php
        get_template_part('controll/template', 'banner');
        if (is_front_page() && !is_home()) {
            get_template_part('template', 'home');
        }
        