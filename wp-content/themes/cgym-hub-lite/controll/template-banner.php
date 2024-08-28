<section id="banner">
    <div class="banner ">
        <?php if (is_front_page() || is_home()) { ?>
            <?php if (get_header_image()) : ?>
                <div class="homeslider">
                    <img src="<?php header_image(); ?>" alt="<?php bloginfo('name'); ?>" >
                    <?php
                    $cgym_hub_lite_banner_heading     = get_theme_mod('banner_heading');
                    $cgym_hub_lite_banner_sub_heading = get_theme_mod('banner_sub_heading');
                    $cgym_hub_lite_slider_url         = get_theme_mod('cgym_hub_lite_slider_url');
                    $cgym_hub_lite_slider_readmore    = get_theme_mod('cgym_hub_lite_slider_readmore');

                    if (!empty($cgym_hub_lite_banner_heading) || !empty($cgym_hub_lite_banner_sub_heading)) {
                        ?>
                        <div class="bannercontent">
                            <div class="container">
                                <div class="bannercaption">
                                    <div class="banner_heading"><h3><?php echo esc_html($cgym_hub_lite_banner_heading); ?></h3></div><!--banner_heading-->
                                    <div class="banner_sub_heading"><?php echo esc_html($cgym_hub_lite_banner_sub_heading); ?></div><!--banner_heading-->
                                    <?php if (!empty($cgym_hub_lite_slider_url)) { ?>
                                        <div class="banner_button">
                                            <a class="button bannerbutton" href="<?php echo esc_url($cgym_hub_lite_slider_url); ?>"><?php echo esc_html($cgym_hub_lite_slider_readmore); ?>&nbsp;&nbsp; <i class="fa fa-arrow-circle-right"></i></a>
                                        </div><!--banner_button-->
                                    <?php } ?>
                                </div><!--bannercaption-->
                            </div><!--container-->
                        </div><!--bannercontent-->
                    <?php } ?>
                </div>  <!--homeslider-->


            <?php endif; ?>
        <?php
        } elseif (is_page()) {
            if (has_post_thumbnail()) {
                the_post_thumbnail('full');
            } else {
                ?>
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/skinview/images/innerbanner.jpg" alt="<?php bloginfo('name'); ?>">          
            <?php } ?>
        <?php } else { ?>
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/skinview/images/innerbanner.jpg" alt="<?php bloginfo('name'); ?>">          
<?php } ?>
    </div><!--banner-->
</section><!--banner-->

