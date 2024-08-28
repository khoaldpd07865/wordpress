<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Social_Share {
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes() {
        return array(
            'blockId' => '',
            'repetableField' => [
                [ 'type' => 'facebook', 'enableLabel' => true, 'label' => 'Facebook', 'iconColor' => '#fff', 'iconColorHover' => '#d2d2d2', 'shareBg' => '#4267B2', 'bgHoverColor' => '#f5f5f5' ],
                [ 'type' => 'twitter', 'enableLabel' => true, 'label' => 'Twitter',  'iconColor' => '#fff', 'iconColorHover' => '#d2d2d2', 'shareBg' => '#1DA1F2', 'bgHoverColor' => '#f5f5f5'   ],
                [ 'type' => 'pinterest', 'enableLabel' => true, 'label' => 'Pinterest',  'iconColor' => '#fff', 'iconColorHover' => '#d2d2d2', 'shareBg' => '#E60023', 'bgHoverColor' => '#f5f5f5' ],
                [ 'type' => 'linkedin', 'enableLabel' => true, 'label' => 'Linkedin',  'iconColor' => '#fff', 'iconColorHover' => '#d2d2d2', 'shareBg' => '#0A66C2', 'bgHoverColor' => '#f5f5f5' ],
                [ 'type' => 'mail', 'enableLabel' => true,'label' => 'Mail', 'iconColor' => '#fff','iconColorHover' => '#d2d2d2','shareBg' => '#EA4335','bgHoverColor' => '#f5f5f5' ],
            ],
            /*============================
                Social Share item style 
            ============================*/
            'disInline' => true,
            
            /*============================
                Post Social Share Label Style
            ============================*/
            'shareLabelShow' => true,
            'shareLabelStyle' => 'style1',
            'shareCountShow' => true,
            'shareCountLabel' => 'Shares',

            /*============================
                Post Social Share sticky position Style
            ============================*/
            'enableSticky' => false,
            'stopSticky' => false,

            //--------------------------
            //  Advanced Settings
            //--------------------------
            'advanceId' => '',
            'advanceZindex' => '',
            'hideExtraLarge' => false,
            'hideDesktop' => false,
            'hideTablet' => false,
            'hideMobile' => false,
            'advanceCss' => '',
        );
    }
    public function register() {
        register_block_type( 'ultimate-post/post-social-share',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' => array($this, 'content')
            )
        );
    }

    public function share_link($key = 'facebook', $post_link = '') {
        $shareLink = [
            'facebook' => 'https://www.facebook.com/sharer.php?u='.$post_link,
            'twitter' => 'https://twitter.com/intent/tweet?url='.$post_link,
            'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url='.$post_link,
            'pinterest' => 'https://pinterest.com/pin/create/link?url='.$post_link,
            'whatsapp' => 'https://api.whatsapp.com/send?text='.$post_link,
            'messenger' => 'https://www.facebook.com/dialog/send?app_id=1904103319867886&amp;link='.$post_link.'&amp;redirect_uri='.$post_link,
            'mail' => 'mailto:?body='.$post_link,
            'reddit' => 'https://www.reddit.com/submit?url='.$post_link,
            'skype' => 'https://web.skype.com/share?url='.$post_link,
        ];
        return $shareLink[$key];
    }
    
    public function content($attr, $noAjax) {
        $attr = wp_parse_args($attr, $this->get_attributes());
        $block_name = 'post_share';
        
        $wrapper_before = $wrapper_after = $wrapper_content = '';

        $post_id = get_the_ID();
        $post_link =home_url(esc_url_raw($_SERVER['REQUEST_URI'])); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
        $total_share = get_post_meta($post_id, 'share_count', true);
        $total_share = $total_share ? $total_share : 0;

        $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
        $attr['align'] = isset($attr['align']) && $attr['align'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
        $attr['advanceId'] = isset($attr['advanceId']) ? sanitize_html_class( $attr['advanceId'] ) : '';
        $attr['blockId'] = isset($attr['blockId']) ? sanitize_html_class( $attr['blockId'] ) : '';
        $attr['shareLabelStyle'] = isset($attr['shareLabelStyle']) ? sanitize_html_class( $attr['shareLabelStyle'] ) : '';
        $allowed_html_tags = ultimate_post()->ultp_allowed_html_tags();
        $attr['shareCountLabel'] = wp_kses($attr['shareCountLabel'], $allowed_html_tags);
        
        $wrapper_before .= '<div '.($attr['advanceId'] ? 'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].( $attr["className"] ?' '.$attr["className"]:'').''.( $attr["align"] ? ' align' .$attr["align"]:'').'">';
            $wrapper_before .= '<div class="ultp-block-wrapper">';
                $wrapper_content .= '<div class="ultp-post-share">';
                    $wrapper_content .= '<div class="ultp-post-share-layout ultp-inline-'.($attr["disInline"]?'true':'false').' '.($attr['stopSticky'] && $attr['enableSticky'] ? 'ultp-disable-sticky-footer' : '').'">';
                        if ($attr["shareLabelShow"]) {
                            $wrapper_content .= '<div class="ultp-post-share-count-section ultp-post-share-count-section-'.$attr["shareLabelStyle"].'">';
                                if ($attr["shareLabelStyle"] != 'style2' && $attr["shareCountShow"]) {
                                    $wrapper_content .= '<span class="ultp-post-share-count">'.$total_share.'</span>';
                                }
                                if ($attr["shareLabelStyle"] == 'style2') {
                                    $wrapper_content .= '<span class="ultp-post-share-icon-section">'.ultimate_post()->svg_icon('share').'</span>';
                                }
                                if ($attr["shareLabelStyle"] != 'style2' && $attr["shareCountLabel"]) {
                                    $wrapper_content .= '<span class="ultp-post-share-label">'.$attr["shareCountLabel"].'</span>';
                                }
                            $wrapper_content .= '</div>';
                        }
                        $wrapper_content .= '<div class="ultp-post-share-item-inner-block" postId="'.$post_id.'" count="'.$total_share.'">';

                            foreach ($attr["repetableField"] as $key => $value) {
                                $value["type"] = sanitize_html_class( $value["type"] );
                                $value['label'] = wp_kses($value['label'], $allowed_html_tags);
                                $wrapper_content .= '<div class="ultp-post-share-item ultp-repeat-'.sanitize_html_class( $key ).' ultp-social-'.$value["type"].'">';
                                    $wrapper_content .= '<a href="javascript:" class="ultp-post-share-item-'.$value["type"].'" url="'.$this->share_link($value['type'], $post_link).'">';
                                        $wrapper_content .= '<span class="ultp-post-share-item-icon">'.ultimate_post()->svg_icon($value['type']).'</span>';
                                        $wrapper_content .= ''.$value['enableLabel'] ? '<span class="ultp-post-share-item-label">'.$value['label'].'</span>' : "".' ';
                                    $wrapper_content .= '</a>';
                                $wrapper_content .= '</div>';
                            }
                        $wrapper_content .= '</div>';
                    $wrapper_content .= '</div>';
                $wrapper_content .= '</div>';
            $wrapper_after .= '</div>';
        $wrapper_after .= '</div>';

        return $wrapper_before.$wrapper_content.$wrapper_after;
    }
}