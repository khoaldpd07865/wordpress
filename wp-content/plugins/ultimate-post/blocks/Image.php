<?php
namespace ULTP\blocks;

defined( 'ABSPATH' ) || exit;

class Image {
    
    public function __construct() {
        add_action( 'init', array( $this, 'register' ) );
    }
    public function get_attributes() {
        return array(
            'blockId' => '',
            'previewImg' => '',
            // Image Settings
            'imageUpload' => (object)[ 'id'=>'999999', 'url' => ULTP_URL.'assets/img/ultp-placeholder.jpg' ],
            'darkImgEnable' => false,
            'darkImage' => (object)[ 'id'=>'999999', 'url' => ULTP_URL.'assets/img/ultp-placeholder.jpg' ],
            'linkType' => 'link',
            'imgLink' => '',
            'linkTarget' => '_blank',
            'imgAlt' => 'Image',
            'imgAlignment' => ['lg' => 'left'],
            'imgCrop' => 'full',
            'imgAnimation' => 'none',
            'imgMargin' => (object)['lg'=>''],
            'imgOverlay' => false,
            'imgOverlayType' => 'default',
            'imgSrcset' => false,
            'imgLazy' => false,
            // Heading Setting/Style
            'headingText' => 'This is a Image Example',
            'headingEnable' => false,
            'alignment' => ['lg' => 'left'],
            // Button Settings
            'buttonEnable' => false,
            'btnText' => 'Free Download',
            'btnLink' => '#',
            'btnTarget' => '_blank',
            'btnPosition' => 'centerCenter',
            // Style
               
            'advanceId' => '',
            'advanceZindex' => '',
            'hideExtraLarge' => false,
            'hideTablet' => false,
            'hideMobile' => false,
            'advanceCss' => '',
        );
    }

    public function register() {
        register_block_type( 'ultimate-post/image',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' => array( $this, 'content' )
            )
        );
    }

    public function content( $attr, $noAjax ) {
        $attr = wp_parse_args( $attr, $this->get_attributes() );
        
        $wraper_before = '';
        $block_name = 'image';
        $attr['headingShow'] = true;
        $darkImageArr = (array)$attr['darkImage'];
        $darkImg = [
            'enable'=> $attr['darkImgEnable'],
            'url'=> isset($darkImageArr['url']) ? $darkImageArr['url'] : ULTP_URL.'assets/img/ultp-placeholder.jpg',
            'srcset' => $attr['imgSrcset'] ? ' srcset="'.esc_attr(wp_get_attachment_image_srcset($darkImageArr['id'])).'"' : ''
        ];

        $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
        $attr['align'] = isset($attr['align']) && $attr['align'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
        $attr['advanceId'] = isset($attr['advanceId']) ? sanitize_html_class( $attr['advanceId'] ) : '';
        $attr['blockId'] = isset($attr['blockId']) ? sanitize_html_class( $attr['blockId'] ) : '';
        $attr['imgAnimation'] = sanitize_html_class( $attr['imgAnimation'] );
        $attr['imgOverlayType'] = sanitize_html_class( $attr['imgOverlayType'] );
        $allowed_html_tags = ultimate_post()->ultp_allowed_html_tags();
        $attr['btnText'] = wp_kses($attr['btnText'], $allowed_html_tags);
        $attr['headingText'] = wp_kses($attr['headingText'], $allowed_html_tags);


        $wraper_before .= '<div '.($attr['advanceId'] ? 'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].''.($attr["align"] ? ' align' .$attr["align"]:'').''.($attr["className"] ?' '.$attr["className"]:'').'">';
            $wraper_before .= '<div class="ultp-block-wrapper">';
                $wraper_before .= '<figure class="ultp-image-block-wrapper">';
                    $wraper_before .= '<div class="ultp-image-block ultp-image-block-'.$attr['imgAnimation'].($attr["imgOverlay"] ? ' ultp-image-block-overlay ultp-image-block-'.$attr["imgOverlayType"] : '' ).'">';
                        // Single Image
                        $img_arr = (array)$attr['imageUpload'];
                        $img_url = ( isset($img_arr['size']) && isset($img_arr['size'][$attr['imgCrop']]) ) ? ( isset($img_arr['size'][$attr['imgCrop']]['url']) ? $img_arr['size'][$attr['imgCrop']]['url'] : $img_arr['size'][$attr['imgCrop']] ) : $img_arr['url'];
                        if ( ! empty( $img_arr ) ) {
                            $srcset_data = $attr['imgSrcset'] ? ' srcset="'.esc_attr(wp_get_attachment_image_srcset($img_arr['id'])).'"' : '';
                            if ( $attr['linkType'] == 'link' && $attr['imgLink'] ) {
                                $wraper_before .= '<a href="'.esc_url($attr['imgLink']).'" target="'.sanitize_html_class( $attr['linkTarget'] ).'">'.ultimate_post()->get_image_html($img_url, 'full', 'ultp-image', $attr['imgAlt'], $attr['imgLazy'], $darkImg, $srcset_data).'</a>';
                            } else {
                             // $wraper_before .= ultimate_post()->get_image_html($img_arr['url'], 'full', 'ultp-image', $attr['imgAlt'], $attr['imgLazy'], $darkImg);
                                $wraper_before .= ultimate_post()->get_image_html( $img_url, 'full', 'ultp-image', $attr['imgAlt'], $attr['imgLazy'], $darkImg, $srcset_data );
                            }
                        }
                        if ( $attr['btnLink'] && $attr['linkType'] == 'button' ) {
                            $wraper_before .= '<div class="ultp-image-button ultp-image-button-'.sanitize_html_class( $attr['btnPosition']).'"><a href="'.esc_url($attr['btnLink']).'" target="'.sanitize_html_class($attr['btnTarget']).'">'.$attr['btnText'].'</a></div>';
                        }
                    $wraper_before .= '</div>';
                    if ( $attr['headingEnable'] == 1 ) {
                        $wraper_before .= '<figcaption class="ultp-image-caption">'.$attr['headingText'].'</figcaption>';
                    }
                $wraper_before .= '</figure>';
            $wraper_before .= '</div>';
        $wraper_before .= '</div>';

        return $wraper_before;
    }
}