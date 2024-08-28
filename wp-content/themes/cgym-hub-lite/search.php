<?php
/**
 * @package cgym-hub-lite
 */

get_header(); ?>

<div id="content" class="container">
     <div class="page_content">
        <section class="site-main">
            <div class="blog-post">
				<?php if ( have_posts() ) : ?>                    
                    <?php while ( have_posts() ) : the_post(); ?>
                     <?php get_template_part( 'content' ); ?>
                    <?php endwhile; ?>
                    <?php the_posts_pagination();?>
                <?php else : ?>
                <h1 class="entry-title"><?php printf( esc_html__( '404 Page Found', 'cgym-hub-lite' )); ?></h1>

                <?php endif; ?>
            </div><!-- blog-post -->
        </section>  
            <?php get_sidebar();?> 
        <div class="clear"></div>
    </div><!-- site-aligner -->
</div><!-- container -->
<?php get_footer(); ?>