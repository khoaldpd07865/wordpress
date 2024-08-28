<?php
defined('ABSPATH') || exit;

global $post;
$post_id        = get_the_ID();
$title          = esc_html(wp_trim_words(get_the_title( $post_id ), $attr['titleLength'] ? $attr['titleLength'] : 100));
$titlelink      = get_permalink( $post_id );
$post_thumb_id  = get_post_thumbnail_id( $post_id );
$user_id        = $post->post_author;
$author_link    = get_author_posts_url( $user_id );
$display_name   = get_the_author_meta('display_name');
$time           = get_the_date(ultimate_post()->get_format($attr["metaDateFormat"]));
$timeModified   = get_the_modified_date(ultimate_post()->get_format($attr["metaDateFormat"]));
$comment        = get_comments_number();
$view           = get_post_meta(get_the_ID(),'__post_views_count', true);
$post_time      = human_time_diff(get_the_time('U'),current_time('U'));
$word_read      = apply_filters('ultp_word_read_min', 1200);
$reading_time   = ceil(mb_strlen(wp_strip_all_tags(get_the_content()))/$word_read).( isset($attr['metaMinText']) ? ' '.wp_kses($attr['metaMinText'], ultimate_post()->ultp_allowed_html_tags()) : '' );
$is_active      = ultimate_post()->is_lc_active();
$post_video     = isset($attr['vidIconEnable']) && $attr['vidIconEnable'] ? get_post_meta($post_id, '__builder_feature_video', true) : '';