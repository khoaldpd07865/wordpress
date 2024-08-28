<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Category {
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes() {

        return array(
            'blockId' => '',

            /*============================
                Post Category Setting
            ============================*/
            'catLabelShow' => true,
            'catIconShow' => true,
            'catSeparator' => ',',
            'catAlign' => (object)[],

            /*============================
                Categories Label Settings
            ============================*/
            'catLabel' => 'Category : ',
            
            /*============================
                Categories Icon Style
            ============================*/
            'catIconStyle' => '',

            /*============================
                Advance Setting
            ============================*/
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
        register_block_type( 'ultimate-post/post-category',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style' => 'ultp-blocks-editor-css',
                'render_callback' => array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $attr = wp_parse_args($attr, $this->get_attributes());
        $block_name = 'post-category';
        $wrapper_before = $wrapper_after = $content = '';

        $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
        $attr['align'] = isset($attr['align']) && $attr['align'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
        $attr['advanceId'] = isset($attr['advanceId']) ? sanitize_html_class( $attr['advanceId'] ) : '';
        $attr['blockId'] = isset($attr['blockId']) ? sanitize_html_class( $attr['blockId'] ) : '';
        $allowed_html_tags = ultimate_post()->ultp_allowed_html_tags();
        $attr['catLabel'] = wp_kses($attr['catLabel'], $allowed_html_tags);
        $attr['catSeparator'] = wp_kses($attr['catSeparator'], $allowed_html_tags);

        $categories = get_the_category();
        if (!empty($categories)) {
            $wrapper_before .= '<div '.($attr['advanceId'] ? 'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].( $attr["className"] ? ' '.$attr["className"]:'' ).''.( $attr["align"] ? ' align' .$attr["align"]:'' ).'">';
                $wrapper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<div class="ultp-builder-category">';
                        if($attr["catIconShow"]){
                            $content .= ultimate_post()->svg_icon(''.$attr["catIconStyle"].'');
                        }
                        if ($attr['catLabelShow'] ) { 
                            $content .= '<div class="cat-builder-label">'.$attr['catLabel'].'</div>';
                        }
                        $content .= '<div class="cat-builder-content">';
                            foreach ($categories as $key => $category) {
                                $content .= ( ($key > 0 && $attr['catSeparator']) ? ' '.$attr['catSeparator']:'').'<a class="ultp-category-list" href="'.get_term_link($category->term_id).'">'.$category->name.'</a>';
                            }
                        $content .= '</div>';
                    $content .= '</div>';
                $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';
        }

        return $wrapper_before.$content.$wrapper_after;
    }
}