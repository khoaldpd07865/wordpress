<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Comment_Count {
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes() {
        
        return array(
            'blockId'=> '',

            /*============================
                Post Comment Count Settings
            ============================*/
            'commentLabel'=> true,
            'commentIconShow'=> true,

            /*============================
                Comment Count Label Settings
            ============================*/
            'commentLabelText'=> 'comment ',
            "commentLabelAlign"=> "after",

            /*============================
                Comment Count Icon Settings
            ============================*/
            'commentIconStyle'=> 'commentCount1',
            
            /*============================
                Advance Setting
            ============================*/
            
            //--------------------------
            //  Advanced Settings
            //--------------------------
            'advanceId'=> '',
            'advanceZindex'=> '',
            'hideExtraLarge'=> false,
            'hideDesktop'=> false,
            'hideTablet'=> false,
            'hideMobile'=> false,
            'advanceCss'=> '',
        );
    }

    public function register() {
        register_block_type( 'ultimate-post/post-comment-count',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style' => 'ultp-blocks-editor-css',
                'render_callback'=> array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $attr = wp_parse_args($attr, $this->get_attributes());
        $block_name = 'post-comment-count';
        $wrapper_before = $wrapper_after = $content = '';

        $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
        $attr['align'] = isset($attr['align']) && $attr['align'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
        $attr['advanceId'] = isset($attr['advanceId']) ? sanitize_html_class( $attr['advanceId'] ) : '';
        $attr['blockId'] = isset($attr['blockId']) ? sanitize_html_class( $attr['blockId'] ) : '';
        $attr['commentLabelText'] = wp_kses($attr['commentLabelText'], ultimate_post()->ultp_allowed_html_tags());
        
        $comment_count = get_post_field('comment_count' , '');
        $wrapper_before .= '<div '.( $attr['advanceId'] ? 'id="'.$attr['advanceId'].'" ':'' ).' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].( $attr["className"] ?' '.$attr["className"]:'' ).''.( $attr["align"] ? ' align' .$attr["align"]:'' ).'">';
                $wrapper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<span class="ultp-comment-count">';
                        if ($attr["commentIconShow"] && ($attr["commentIconStyle"] != '')) {
                            $content .= ultimate_post()->svg_icon($attr["commentIconStyle"]); 
                        }
                        $content .= '<span>'.($comment_count ? $comment_count : 0).'</span>';
                        if ($attr["commentLabel"]) {
                            $content .= '<span class="ultp-comment-label">'.$attr["commentLabelText"].'</span>';
                        }
                    $content .= '</span>';
            $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';

        return $wrapper_before.$content.$wrapper_after;
    }
}