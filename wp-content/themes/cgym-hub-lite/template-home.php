<?php

/**
 * @package CGym Hub Lite
 */
?>
<?php
$boxvariable = array();
for ($k = 1; $k <= 4; $k++) {
?>
    <?php $boxid    = absint(get_theme_mod('cgym_hub_lite_box' . $k)); ?>
    <?php $boxquery = new WP_query('page_id=' . $boxid); ?>
    <?php
    if ($boxquery->have_posts() && $boxid > 0) :
        while ($boxquery->have_posts()) : $boxquery->the_post();
            $thumbnail_id  = get_post_thumbnail_id($post->ID);
            $alt           = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
            $image         = get_the_post_thumbnail_url($post->ID, 'cgym-hub-lite-home-box-size');
            $boxtitle      = get_the_title($post->ID);
            $my_postid     = $post->ID;
            $content       = cgym_hub_lite_get_excerpt($my_postid, '250');
            $url           = get_the_permalink($my_postid);
            $boxvariable[] = array('boxid' => $post->ID, 'boximage' => $image, 'alt' => $alt, 'box_title' => $boxtitle, 'box_content' => $content, 'url' => $url);
        endwhile;
        wp_reset_postdata();
    endif;
    ?>

<?php } ?>
<section style="background-color:#ffffff; " id="pageboxes">
    <div class="container">
        <div class="pageclmn fadeInRight">
            <div class="box-equal-height">

            <?php
            $cgym_hub_lite_sort_id = 1;
            foreach ($boxvariable as $boxvariables) {
            ?>
                <div class="commonboxrs top4box fourbox-<?php echo esc_attr($cgym_hub_lite_sort_id); ?>">
                    <div class="topboxbg">
                        <a href="<?php echo esc_url($boxvariables['url']); ?>">
                            <div class="thumbbx img-responsive img-thumbnail"><img alt="<?php echo esc_attr($boxvariables['alt']); ?>" src="<?php echo esc_url($boxvariables['boximage']); ?>"></div>
                        </a>
                        <div class="fourpagecontent">
                            
                                <a href="<?php echo esc_url($boxvariables['url']); ?>">
                                    <h3><?php echo esc_html($boxvariables['box_title']); ?></h3>
                                </a>
                           
                            <!--top-resourcebox-->
                            <div class="resourcebox">
                            <p><?php
                                $boxid                       = esc_html($boxvariables['boxid']);
                                echo esc_html(cgym_hub_lite_get_excerpt($boxid, 150));
                                ?>
                            </p>
                            </div>
                            <?php
                            $cgym_hub_lite_box_cta = get_theme_mod('cgym_hub_lite_box_cta');
                            if (!empty($cgym_hub_lite_box_cta)) {
                            ?>
                                <a class="rdmore fourreadmore pagemore" href="<?php echo esc_url($boxvariables['url']); ?>"><?php echo esc_html($cgym_hub_lite_box_cta) ?> &nbsp; &nbsp;<i class="fa fa-angle-right"></i></a>
                            <?php } ?>
                        </div>
                        <!--resourcebox-->
                    </div>
                </div>
            <?php
                $cgym_hub_lite_sort_id++;
            }
            ?>
        </div>
        </div><!-- middle-align -->
        <div class="clear"></div>
    </div><!-- container -->
</section>

<!-- <div class="fourbox fourbox-3">
    <div class="topboxbg">
        <a href="http://localhost/themescave/demo/cgym-hub-lite/index.php/primary-care/">
            <div class="thumbbx img-responsive img-thumbnail">
                <img alt="" src="http://localhost/themescave/demo/cgym-hub-lite/wp-content/uploads/2022/04/box3.jpg">
            </div>
        </a>
        <div class="pagecontent">

            <a href="http://localhost/themescave/demo/cgym-hub-lite/index.php/primary-care/">
                <h3>Cardio Programme</h3>
            </a>
            <div class="resourcebox">

                <p>Phasellus nec metus scelerisque, Proin id vehicula enim feugiat est quis, vestibulum ante Proin id vehicula enim Pellentesque </p>
            </div>

            <a class="rdmore" href="http://localhost/themescave/demo/cgym-hub-lite/index.php/primary-care/">Explore more &nbsp; &nbsp;<i class="fa fa-arrow-circle-right"></i></a>
        </div>

    </div>

</div> -->