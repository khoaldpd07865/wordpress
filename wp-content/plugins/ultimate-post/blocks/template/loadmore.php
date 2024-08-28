<?php
defined( 'ABSPATH' ) || exit;

// Adv Filter Integration
$adv_filter_dataset = ultimate_post()->get_adv_data_attrs($attr);

$attr["blockId"] = sanitize_html_class($attr["blockId"]);
$allowed_html_tags = ultimate_post()->ultp_allowed_html_tags();
$attr["loadMoreText"] = isset( $attr['loadMoreText'] ) ? wp_kses($attr["loadMoreText"], $allowed_html_tags) : 'Load More';

$page_post_id = (isset($attr['currentPostId']) &&  $attr['currentPostId'])  ? sanitize_html_class($attr['currentPostId']) : ultimate_post()->get_page_post_id(ultimate_post()->get_ID(), $attr['blockId']);
$self_post_id = 'data-selfpostid="' .( (isset($attr['currentPostId']) &&  $attr['currentPostId']) ? "yes" : "no"). '"';
$exclude_id = isset($curr_post_id) ? $curr_post_id  : "";
$wraper_after .= '<div class="ultp-loadmore">';
    if ( 1 != $pageNum ) {
        $wraper_after .= '<span class="ultp-loadmore-action" tabindex="0" role="button" data-pages="'.$pageNum.'" data-pagenum="1"  data-expost="'.$exclude_id.'" data-blockid="'.$attr['blockId'].'" data-blockname="ultimate-post_'.$block_name.'" data-postid="'.$page_post_id.'" '.ultimate_post()->get_builder_attr($attr['queryType']).$self_post_id. $adv_filter_dataset . '>'.$attr['loadMoreText'].' <span class="ultp-spin">'.ultimate_post()->svg_icon('refresh').'</span></span>';
    }
$wraper_after .= '</div>';