<?php
defined( 'ABSPATH' ) || exit;

$attr['titleTag'] = in_array($attr['titleTag'],  ultimate_post()->ultp_allowed_block_tags() ) ? $attr['titleTag'] : 'h3';
$post_loop .= '<'.$attr['titleTag'].' class="ultp-block-title '.($attr['titleStyle']=='none' ? '' : ' ultp-title-'.sanitize_html_class( $attr['titleStyle'] )).'"><a href="'.$titlelink.'" '.($attr['openInTab'] ? 'target="_blank"' : '').'>'.esc_html($title).'</a></'.$attr['titleTag'].'>';