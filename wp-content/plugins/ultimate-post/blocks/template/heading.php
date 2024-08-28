<?php
defined( 'ABSPATH' ) || exit;

$attr["headingURL"] = esc_url($attr["headingURL"]);
$attr["headingStyle"] = sanitize_html_class($attr["headingStyle"]);
$attr["headingAlign"] = sanitize_html_class($attr["headingAlign"]);
$allowed_html_tags = ultimate_post()->ultp_allowed_html_tags();
$attr["headingText"] = wp_kses($attr["headingText"], $allowed_html_tags);
$attr["headingBtnText"] = wp_kses($attr["headingBtnText"], $allowed_html_tags);
$attr["subHeadingText"] = wp_kses($attr["subHeadingText"], $allowed_html_tags);
$attr['headingTag'] = in_array($attr['headingTag'],  ultimate_post()->ultp_allowed_block_tags() ) ? $attr['headingTag'] : 'h2';

if ( $attr['headingShow'] ) {
    $new_tab = isset($attr["openInTab"]) && $attr["openInTab"] == true ? 'target="_blank"' : '';
    $wraper_before .= '<div class="ultp-heading-wrap ultp-heading-'.$attr["headingStyle"].' ultp-heading-'.$attr["headingAlign"].'">';
        if ( $attr['headingURL'] ) {
            $wraper_before .= '<'.$attr['headingTag'].' class="ultp-heading-inner"><a href="'.$attr["headingURL"].'" '.$new_tab.'><span>'.$attr["headingText"].'</span></a></'.$attr['headingTag'].'>';
        } else {
            $wraper_before .= '<'.$attr['headingTag'].' class="ultp-heading-inner"><span>'.$attr["headingText"].'</span></'.$attr['headingTag'].'>';
        }
        if ( $attr['headingStyle'] == 'style11' && $attr['headingURL'] && $attr['headingBtnText'] ) {
            $wraper_before .= '<a class="ultp-heading-btn" href="'.$attr['headingURL'].'" '.$new_tab.'>'.$attr["headingBtnText"].ultimate_post()->svg_icon('rightAngle2').'</a>';
        }
        if ( $attr['subHeadingShow'] ) {
            $wraper_before .= '<div class="ultp-sub-heading"><div class="ultp-sub-heading-inner">'.$attr['subHeadingText'].'</div></div>';
        }
    $wraper_before .= '</div>';
}