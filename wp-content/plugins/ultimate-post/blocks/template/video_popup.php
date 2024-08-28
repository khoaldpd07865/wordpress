<?php
defined( 'ABSPATH' ) || exit;

$post_loop .= '<div class="ultp-video-modal">';
    $post_loop .= '<div class="ultp-video-modal__overlay"></div>';
    if ( $attr['vidIconEnable'] ) {
        $post_loop .= '<span class="ultp-video-close"></span>';
    }
    $post_loop .= '<div class="ultp-video-modal__Wrapper">';
        $post_loop .= '<div class="ultp-video-modal__content">';
            if ( $attr['enablePopupTitle'] ) {
                $post_loop .= '<a href="'.$titlelink.'">'.$title.'</a>';
            }
            $post_loop .= '<div class="ultp-loader-container"><div class="ultp-popup-loader"></div></div>';
            $post_loop .= $post_video ? ultimate_post()->get_embeded_video($post_video, true, true, false, true, true, false, true, array()) : '';
        $post_loop .= '</div>';
    $post_loop .= '</div>';
$post_loop .= '</div>';