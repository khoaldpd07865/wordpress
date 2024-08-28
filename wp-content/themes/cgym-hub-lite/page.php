<?php
/**
 * @package cgym-hub-lite
 */
get_header();
if (is_front_page() && !is_home()) {
    $ccovid_medical_lite_site_main = "sitefull";
} else {
    $ccovid_medical_lite_site_main = "site-main";
}
?>
<div id="content" class="container">
    <div class="page_content">
        <div class="<?php echo esc_attr($ccovid_medical_lite_site_main); ?>">
            <div class="blog-post">
                <?php
                if (have_posts()) :

                    while (have_posts()) : the_post();
                        ?>   
                        <div class="pageheading"><h1><?php the_title(); ?></h1></div>
                        <div class="pagecontent"><?php the_content(); ?></div>
                        <div>
                            <?php
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . __('Pages:', 'cgym-hub-lite'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>
                        <?php if (is_singular()) wp_enqueue_script("comment-reply"); ?>
                        <?php
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;
                        ?>
                        <?php
                    endwhile;
                endif;
                ?>
            </div><!--blog-post -->
        </div><!--col-md-8-->
        <?php if (!is_front_page()) { ?>
            <?php get_sidebar(); ?>
        <?php } ?>
        <div class="clear"></div>
    </div><!-- row -->
</div><!-- container -->
<?php get_footer(); ?>