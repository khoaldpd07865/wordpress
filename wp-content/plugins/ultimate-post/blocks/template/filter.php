<?php
defined( 'ABSPATH' ) || exit;

$attr["filterType"] = sanitize_html_class($attr["filterType"]);
$attr["blockId"] = sanitize_html_class($attr["blockId"]);
$allowed_html_tags = ultimate_post()->ultp_allowed_html_tags();
$attr["filterText"] = isset($attr['filterText']) && $attr['filterText'] ? wp_kses($attr["filterText"], $allowed_html_tags) : '';
$attr['filterMobileText'] = isset($attr['filterMobileText']) && $attr['filterMobileText'] ? esc_html($attr['filterMobileText']) : '';

$page_post_id = (isset($attr['currentPostId']) &&  $attr['currentPostId'])  ? sanitize_html_class($attr["currentPostId"]) : ultimate_post()->get_page_post_id(ultimate_post()->get_ID(), $attr['blockId']);
$self_post_id = 'data-selfpostid="' .( (isset($attr['currentPostId']) &&  $attr['currentPostId']) ? "yes" : "no"). '"';
$wraper_before .= '<div class="ultp-filter-wrap" data-taxtype='.$attr['filterType'].' data-blockid="'.$attr['blockId'].'" data-blockname="ultimate-post_'.$block_name.'" data-postid="'.$page_post_id.'"'.$self_post_id.'>';
    $wraper_before .= ultimate_post()->filter($attr['filterText'], $attr['filterType'], $attr['filterValue'], $attr['filterMobileText'], $attr['filterMobile']);
$wraper_before .= '</div>';