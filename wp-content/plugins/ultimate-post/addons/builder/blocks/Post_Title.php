<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Title{
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes() {

        return array(
            'blockId' =>  '',

            /*============================
                Post Title Settings
            ============================*/
            'titleTag' =>  'h1',

            //--------------------------
            //  Advanced Style
            //--------------------------
            'advanceId' =>  '',
            'advanceZindex' =>  '',
            'hideExtraLarge' =>  false,
            'hideDesktop' =>  false,
            'hideTablet' =>  false,
            'hideMobile' =>  false,
            'advanceCss' =>  '',
        );
    }
    public function register() {
        register_block_type( 'ultimate-post/post-title',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' =>  array($this, 'content')
            )
        );
    }
    public function content($attr, $noAjax) {
        $attr = wp_parse_args($attr, $this->get_attributes());
        $block_name = 'post-title';
        $wrapper_before = $wrapper_after = $content = '';

        $post_title = get_the_title();

        if ($post_title) {
            $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
            $attr['align'] = isset($attr['align']) && $attr['align'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
            $attr['advanceId'] = isset($attr['advanceId']) ? sanitize_html_class( $attr['advanceId'] ) : '';
            $attr['blockId'] = isset($attr['blockId']) ? sanitize_html_class( $attr['blockId'] ) : '';
            $attr['titleTag'] = in_array( $attr['titleTag'],  ultimate_post()->ultp_allowed_block_tags() ) ? $attr['titleTag'] : 'h1';
            
            $wrapper_before .= '<div '.( $attr['advanceId'] ? 'id="'.$attr['advanceId'].'" ':'' ).' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].( $attr["className"] ?' '.$attr["className"] : '' ).''.( $attr["align"] ? ' align' .$attr["align"] : '' ).'">';
                $wrapper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<'.$attr['titleTag'].' class="ultp-builder-title">'.$post_title.'</'.$attr['titleTag'].'>';
                $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';
        }
        return $wrapper_before.$content.$wrapper_after;
    }
}