<?php
if (!isset($content_width))
    $content_width = 900;

add_action('admin_menu', 'cgym_hub_lite_pros');

function cgym_hub_lite_pros() {
    add_theme_page(esc_html__('CGym Hub Lite Details', 'cgym-hub-lite'), esc_html__('CGym Hub Lite Details', 'cgym-hub-lite'), 'edit_theme_options', 'cgym_hub_lite_pro', 'cgym_hub_lite_pro_details');
}

function cgym_hub_lite_pro_details() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('CGym Hub Lite Details', 'cgym-hub-lite'); ?></h1>

        <div>
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/skinview/images/innerbanner.jpg" alt="<?php bloginfo('name'); ?>" style=" width: 100%;"> 
        </div>

        <p><strong> <?php esc_html_e('Description: ', 'cgym-hub-lite'); ?></strong><?php esc_html_e('CGym Hub Lite WordPress theme is used for fitness trainers, physical trainers, gym trainers, Zumba classes, personal fitness coaches, weight centers, fitness and health consultants, aerobics classes, power yoga, dietician, physiotherapy, wellness, meditation, fitness enthusiasts, health experts, sport club or fitness centres and other fitness-related websites', 'cgym-hub-lite'); ?></p>
        <a class="button button-primary button-large" href="<?php echo esc_url(cgym_hub_lite_THEMEURL); ?>" target="_blank"><?php esc_html_e('Theme Url', 'cgym-hub-lite'); ?></a>	
        <a class="button button-primary button-large" href="<?php echo esc_url(cgym_hub_lite_PROURL); ?>" target="_blank"><?php esc_html_e('Pro Theme Url', 'cgym-hub-lite'); ?></a>
        <a class="button button-primary button-large" href="<?php echo esc_url(cgym_hub_lite_PURCHASEURL); ?>" target="_blank"><?php esc_html_e('Click To Purchase', 'cgym-hub-lite'); ?></a>
        <a class="button button-primary button-large" href="<?php echo esc_url(cgym_hub_lite_PURCHASEURL); ?>" target="_blank"><?php esc_html_e('Price: $39.99 Only', 'cgym-hub-lite'); ?></a>
        <a class="button button-primary button-large" href="<?php echo esc_url(cgym_hub_lite_DOCURL); ?>" target="_blank"><?php esc_html_e('Documentation', 'cgym-hub-lite'); ?></a>
        <a class="button button-primary button-large" href="<?php echo esc_url(cgym_hub_lite_DEMOURL); ?>" target="_blank"><?php esc_html_e('View Demo', 'cgym-hub-lite'); ?></a>
        <a class="button button-primary button-large" href="<?php echo esc_url(cgym_hub_lite_SUPPORTURL); ?>" target="_blank"><?php esc_html_e('Support', 'cgym-hub-lite'); ?></a>
        <a class="button button-primary button-large" href="mailto:<?php echo esc_html(cgym_hub_lite_SUPPORT_EMAIL); ?>" target="_blank"><?php esc_html_e('Support Email', 'cgym-hub-lite'); ?></a>

    </div> 
    </div>
<?php
}

add_action('customize_register', 'cgym_hub_lite_customize_register');
define('cgym_hub_lite_PROURL', 'https://www.themescave.com/themes//wordpress-gym-fitness-hub-pro/');
define('cgym_hub_lite_THEMEURL', 'https://www.themescave.com/themes/wordpress-fitness-cgym-hub-lite/');
define('cgym_hub_lite_DOCURL', 'https://www.themescave.com/documentation/cgym-hub-pro');
define('cgym_hub_lite_DEMOURL', 'https://www.themescave.com/demo/cgym-hub-pro');
define('cgym_hub_lite_SUPPORTURL', 'https://www.themescave.com/forums/forum/cgym-hub-pro/');
define('cgym_hub_lite_PURCHASEURL', 'https://www.themescave.com/themes/?add-to-cart=3669');
define('cgym_hub_lite_SUPPORT_EMAIL', 'support@themescave.com');

