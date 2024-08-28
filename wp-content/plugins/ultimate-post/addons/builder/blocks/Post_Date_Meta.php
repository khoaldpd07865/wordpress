<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Date_Meta {
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes() {
        
        return array(
            'blockId' => '',
            
            /*============================
                Post Date Meta Setting
            ============================*/
            "prefixEnable" => false,
            'metaDateIconShow' => true,
            "dateFormat" => "updated",
            'metaDateFormat' => 'M j, Y',

            /*============================
                Post Date Meta Label
            ============================*/
            'datePubLabel' => 'Publish Date',
            'dateUpLabel' => 'Updated Date',
            
            /*============================
                Post Date Meta icon style
            ============================*/
            'metaDateIconStyle' => 'date1',
            
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
        register_block_type( 'ultimate-post/post-date-meta',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' => array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $attr = wp_parse_args($attr, $this->get_attributes());
        $block_name = 'post-date-meta';

        $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
        $attr['align'] = isset($attr['align']) && $attr['align'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
        $attr['advanceId'] = isset($attr['advanceId']) ? sanitize_html_class( $attr['advanceId'] ) : '';
        $attr['blockId'] = isset($attr['blockId']) ? sanitize_html_class( $attr['blockId'] ) : '';
        $allowed_html_tags = ultimate_post()->ultp_allowed_html_tags();
        $attr['datePubLabel'] = wp_kses($attr['datePubLabel'], $allowed_html_tags);
        $attr['dateUpLabel'] = wp_kses($attr['dateUpLabel'], $allowed_html_tags);
        
        $wrapper_before = $wrapper_after = $content = '';

            $wrapper_before .= '<div '.($attr['advanceId'] ? 'id="'.$attr['advanceId'].'" ':'').' class=" wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].( $attr["className"] ? ' '.$attr["className"]:'').''.( $attr["align"] ? ' align' .$attr["align"]:'' ).'">';
                $wrapper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<div class="ultp-date-meta">';
                        if ($attr["prefixEnable"]) {
                            $content .= '<span class="ultp-date-meta-prefix">';   
                                if($attr['dateFormat'] == "publish"){
                                    $content .= $attr['datePubLabel']; 
                                } else {
                                    $content .= $attr['dateUpLabel']; 
                                }

                            $content .= '</span>';    
                        }
                        if ($attr["metaDateIconShow"] && $attr["metaDateIconStyle"]) {
                            $content .= '<span class="ultp-date-meta-icon">';   
                                $content .= ultimate_post()->svg_icon($attr["metaDateIconStyle"]); 
                            $content .= '</span>';
                        }
                        if ($attr['metaDateFormat']) {
                            $content .= '<span class="ultp-date-meta-format">';   
                                if ($attr['dateFormat'] == 'updated') {
                                    $content .= get_the_modified_date(ultimate_post()->get_format($attr["metaDateFormat"])); 
                                } else {
                                    $content .= get_the_date(ultimate_post()->get_format($attr["metaDateFormat"])); 
                                }
                                
                            $content .= '</span>';
                        }
                    $content .= '</div>';
                $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';
        
        return $wrapper_before.$content.$wrapper_after;
    }
}