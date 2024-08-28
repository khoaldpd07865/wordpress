<?php
/**
 * @package cgym-hub-lite
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php if (has_post_thumbnail()) { ?>
        <div class="post-thumb">
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
        </div>
    <?php } ?> 

    <header class="entry-header">           
        <h4><a href="<?php the_permalink(); ?>" rel="bookmark"><?php if (is_sticky()) { ?>
                    <span class="favourite"><i class="fa fa-star"></i></span>
                <?php } ?> <?php the_title(); ?></a></h4>

    </header><!-- .entry-header -->

    <?php if (is_search() || !is_single()) : ?>
        <div class="entry-summary">
            <?php if ('post' == get_post_type()) : ?>
                <div class="postmeta">
                    <div class="post-date"><?php cgym_hub_lite_posted_on(); ?></div><!-- post-date -->
                    <div class="post-comment"> <?php cgym_hub_lite_entry_meta();  ?></div>                                  
                </div><!-- postmeta -->
            <?php endif; ?> 
            <?php the_excerpt(); ?>
            <div class="clear"></div>
        </div><!-- .entry-summary -->
    <?php else : ?>
        <div class="entry-content">
            <?php the_content(__('Continue reading ', 'cgym-hub-lite') . '<span class="meta-nav">&rarr;</span>'); ?>
            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'cgym-hub-lite'),
                'after'  => '</div>',
            ));
            ?>

        </div><!-- .entry-content -->

    <?php endif; ?>
    <a class="rdmore" href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo esc_html__('Read More', 'cgym-hub-lite') ?>&nbsp; &nbsp; <i class="fa fa-arrow-circle-right"></i></a>

    <div class="clear"></div>
</article><!-- #post-## -->
