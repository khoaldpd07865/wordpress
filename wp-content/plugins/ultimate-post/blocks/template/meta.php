<?php
defined( 'ABSPATH' ) || exit;

if ( $attr['metaShow'] ) {
    $metaCondition;
    switch ($block_name) {
        case 'post-grid-5':
            $metaCondition = $attr['layout'] == 'layout3' && $idx == 3 || $idx == 0;
            break;
        case 'post-grid-7':
            $metaCondition = $attr['layout'] == 'layout4' && $idx == 3 || $idx == 0;
            break;
        default:
        $metaCondition = $idx == 0;
    }

    $attr["metaAuthorPrefix"] = wp_kses($attr["metaAuthorPrefix"],  ultimate_post()->ultp_allowed_html_tags());
    $attr['metaSeparator'] = sanitize_html_class( $attr['metaSeparator'] );
    $attr['metaStyle'] = sanitize_html_class( $attr['metaStyle'] );
    $attr['className'] = isset( $attr['className'] ) ? sanitize_html_class( $attr['className'] ) : '';


    $avatar         = get_avatar_url($user_id);
    $meta = $metaCondition ? $attr['metaList'] : ( isset($attr['metaListSmall']) ? $attr['metaListSmall'] : $attr['metaList'] );
    $meta = json_decode($meta);
    $meta = is_array($meta) ? $meta : [];
    
    $authorImgOnly = in_array('metaAuthor', $meta) ? '<span class="ultp-block-author"><img loading="lazy" class="ultp-meta-author-img" src="'.$avatar.'" alt="'.__("By","ultimate-post").'" /></span>' : '';
    $authorImg     = in_array('metaAuthor', $meta) ? '<span class="ultp-block-author"><img loading="lazy" class="ultp-meta-author-img" src="'.$avatar.'" alt="'.__("By","ultimate-post").'" />'.$attr['metaAuthorPrefix'].'<a href="'.($attr['authorLink']?$author_link:'javascript:void(0)').'">'.$display_name .'</a></span>' : '';
    $authorIcon    = in_array('metaAuthor', $meta) ? '<span class="ultp-block-author">'.ultimate_post()->svg_icon('user').'<a href="'.($attr['authorLink']?$author_link:'javascript:void(0)').'">'.$display_name.'</a></span>' : '';
    $authorBy      = in_array('metaAuthor', $meta) ? '<span class="ultp-block-author">'.$attr['metaAuthorPrefix'].'<a href="'.($attr['authorLink']?$author_link:'javascript:void(0)').'">'.$display_name.'</a></span>' : '';
    
    // Author Filter Hook
    $authorImgOnly = apply_filters( 'postx_loop_author_image_only', $authorImgOnly, $user_id, $post_id, isset($attr["className"]) ? $attr["className"] : '');
    $authorImg = apply_filters( 'postx_loop_author_image', $authorImg, $user_id, $post_id, isset($attr["className"]) ? $attr["className"] : '');
    $authorIcon = apply_filters( 'postx_loop_author_icon', $authorIcon, $user_id, $post_id, isset($attr["className"]) ? $attr["className"] : '');
    $authorBy = apply_filters( 'postx_loop_author_by', $authorBy, $user_id, $post_id, isset($attr["className"]) ? $attr["className"] : '');

    $date          = in_array('metaDate', $meta) ? '<span class="ultp-block-date">'.$time.'</span>' : '';
    $dateIcon      = in_array('metaDate', $meta) ? '<span class="ultp-block-date">'.ultimate_post()->svg_icon('calendar').$time.'</span>' : '';
    $dateModified          = in_array('metaDateModified', $meta) ? '<span class="ultp-block-date">'.$timeModified.'</span>' : '';
    $dateModifiedIcon      = in_array('metaDateModified', $meta) ? '<span class="ultp-block-date">'.ultimate_post()->svg_icon('calendar').$timeModified.'</span>' : '';
    $comments      = in_array('metaComments', $meta) ? '<span class="ultp-post-comment">'.$comment.esc_html__(" comments", "ultimate-post").'</span>' : '';
    $commentsIcon  = in_array('metaComments', $meta) ? '<span class="ultp-post-comment">'.ultimate_post()->svg_icon('comment').$comment.'</span>' : '';
    $views         = in_array('metaView', $meta) ? '<span class="ultp-post-view">'.$view.esc_html__(" views", "ultimate-post").'</span>' : '';
    $viewIcon      = in_array('metaView', $meta) ? '<span class="ultp-post-view">'.ultimate_post()->svg_icon('eye').$view.'</span>' : '';
    $postTime      = in_array('metaTime', $meta) ? '<span class="ultp-post-time">'.$post_time.esc_html__(" ago", "ultimate-post").'</span>' : '';
    $postTimeIcon  = in_array('metaTime', $meta) ? '<span class="ultp-post-time">'.ultimate_post()->svg_icon('clock').$post_time.esc_html__(" ago", "ultimate-post").'</span>' : '';
    $reading       = in_array('metaRead', $meta) ? '<span class="ultp-post-read">'.$reading_time.'</span>' : '';
    $readingIcon   = in_array('metaRead', $meta) ? '<span class="ultp-post-read">'.ultimate_post()->svg_icon('book').$reading_time.'</span>' : '';

    $post_loop .= '<div class="ultp-block-meta ultp-block-meta-'.$attr['metaSeparator'].' ultp-block-meta-'.$attr['metaStyle'].'">';
        if ($attr['metaStyle'] == 'noIcon') { $post_loop .= $authorBy.$date.$dateModified.$comments.$views.$reading.$postTime; }
        if ($attr['metaStyle'] == 'icon') { $post_loop .= $authorIcon.$dateIcon.$dateModifiedIcon.$commentsIcon.$viewIcon.$postTimeIcon.$readingIcon; }
        if ($attr['metaStyle'] == 'style2') { $post_loop .= $authorBy.$dateIcon.$dateModifiedIcon.$commentsIcon.$viewIcon.$postTimeIcon.$readingIcon; }
        if ($attr['metaStyle'] == 'style3') { $post_loop .= $authorImg.$dateIcon.$dateModifiedIcon.$commentsIcon.$viewIcon.$postTimeIcon.$readingIcon; }
        if ($attr['metaStyle'] == 'style4') { $post_loop .= $authorIcon.$dateIcon.$dateModifiedIcon.$commentsIcon.$viewIcon.$postTimeIcon.$readingIcon; }
        if ($attr['metaStyle'] == 'style5') { $post_loop .= '<div class="ultp-meta-media">'.$authorImgOnly.'</div> <div class="ultp-meta-body">'.$authorBy.$dateIcon.$dateModifiedIcon.$commentsIcon.$viewIcon.$postTimeIcon.$readingIcon.'</div>'; }
        if ($attr['metaStyle'] == 'style6') { $post_loop .= $authorImgOnly.$authorBy.$dateIcon.$dateModifiedIcon.$commentsIcon.$viewIcon.$postTimeIcon.$readingIcon; }
    $post_loop .= '</div>';
}