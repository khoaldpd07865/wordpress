<?php
namespace ULTP\blocks;

defined( 'ABSPATH' ) || exit;

class Taxonomy {
    
    public function __construct() {
        add_action('init', array($this, 'register'));
    }

    public function get_attributes() {
        return array(
            'blockId' => '',
            'previewImg' => '',
            // Layout
            'layout' => '1',
            // Query Setting
            'taxType' => 'regular',
            'taxSlug' => 'category',
            'taxValue' => '[]',
            'queryNumber' => 6,
            // General Setting
            'taxGridEn' => true,
            'columns' => (object)['lg' =>'1'],
            'rowGap' => (object)['lg' =>'20', 'unit' =>'px'],
            'titleShow' => true,
            'headingShow' => true,
            'excerptShow' => false,
            'countShow' => true,
            'openInTab' => false,
            'notFoundMessage' => 'No Taxonomy Found.',
            
            // Heading Setting/Style
            'headingText' => 'Post Taxonomy',
            'headingURL' => '',
            'headingBtnText' => 'View More',
            'headingStyle' => 'style9',
            'headingTag' => 'h2',
            'headingAlign' => 'left',
            'subHeadingShow' => false,
            'subHeadingText' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut sem augue. Sed at felis ut enim dignissim sodales.',
            'titleTag' => 'span',
            'contentTitleAlign' => 'left',
            'titlePosition' => true,
            'customTaxTitleColor' => false,
            'seperatorTaxTitleLink' => admin_url( 'edit-tags.php?taxonomy=category' ),
            
            // Content Setting/Style
            'excerptLimit' => 30,
            
            // Image Setting/Style
            'imgCrop' => (ultimate_post()->get_setting('disable_image_size') == 'yes' ? 'full' : 'ultp_layout_landscape'),
            
            // Separator
            'separatorShow' => false,
            
            // Custom Wrapper Style
            'customTaxColor' => false,
            'seperatorTaxLink' => admin_url( 'edit-tags.php?taxonomy=category' ),
            'TaxAnimation' => 'none',
            'advanceId' => '',
            'advanceZindex' => '',
            'hideExtraLarge' => false,
            'hideTablet' => false,
            'hideMobile' => false,
            'advanceCss' => '',
        );
    }

    public function register() {
        register_block_type( 'ultimate-post/ultp-taxonomy',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style' => 'ultp-blocks-editor-css',
                'render_callback' => array($this, 'content')
            )
        );
    }

    public function content( $attr, $noAjax ) {
        $attr = wp_parse_args( $attr, $this->get_attributes() );

        if ( ! $noAjax ) {
            $paged = is_front_page() ? get_query_var('page') : get_query_var('paged');
            $attr['paged'] = $paged ? $paged : 1;
        }

        $block_name = 'ultp-taxonomy';
        $wraper_before = $wraper_after = $post_loop = '';
        $recent_posts = ultimate_post()->get_category_data( json_decode($attr['taxValue']), $attr['queryNumber'], $attr['taxType'], $attr['taxSlug'] );

        if ( ! empty( $recent_posts ) ) {

            $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
            $attr['imgCrop'] = isset($attr['imgCrop']) && $attr['imgCrop'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['imgCrop']) : '';
            $attr['advanceId'] = isset($attr['advanceId']) ? sanitize_html_class( $attr['advanceId'] ) : '';
            $attr['blockId'] = isset($attr['blockId']) ? sanitize_html_class( $attr['blockId'] ) : '';
            $attr['titleTag'] = in_array( $attr['titleTag'],  ultimate_post()->ultp_allowed_block_tags() ) ? $attr['titleTag'] : 'span';
            $attr['layout'] = sanitize_html_class( $attr['layout'] );
            $attr['TaxAnimation'] = sanitize_html_class( $attr['TaxAnimation'] );

            $wraper_before .= '<div '.( $attr['advanceId'] ? 'id="'.$attr['advanceId'].'" ':'' ).' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].' '.( $attr["className"] ? $attr["className"]:'' ).'">';
                $wraper_before .= '<div class="ultp-block-wrapper">';
                    $wraper_before .= ultimate_post()->loading(); // Loading
                    
                    if ( $attr['headingShow'] ) {
                        $wraper_before .= '<div class="ultp-heading-filter">';
                            $wraper_before .= '<div class="ultp-heading-filter-in">';
                                include ULTP_PATH.'blocks/template/heading.php'; // Heading
                            $wraper_before .= '</div>';
                        $wraper_before .= '</div>';
                    }

                    $wraper_before .= '<div class="ultp-block-items-wrap">';
                        $wraper_before .= '<ul class="ultp-taxonomy-items '.(isset($attr["TaxAnimation"])? ' ultp-taxonomy-animation-' .$attr["TaxAnimation"]:'').' ultp-taxonomy-column-'.sanitize_html_class( json_decode(wp_json_encode($attr['columns']), true)['lg'] ).' ultp-taxonomy-layout-'.$attr['layout'].'">';
                        
                        foreach ( $recent_posts as $value ) {
                            $_style = ( ($attr["customTaxColor"] && $value['color'])  ? ' style="background-color:'.$value['color'].';"' : '');
                            $_style_color = ((in_array($attr['layout'], [1,4,5]) && $value['color'] && $attr["customTaxTitleColor"] ) ? ' style="color:'.$value['color'].';"' : '');
                            $_style_title_bg = ((in_array($attr['layout'], [7,8]) && $value['color'] && $attr["customTaxTitleColor"] ) ? ' style="background:'.$value['color'].';"' : '');
                            $post_loop .= '<li class="ultp-block-item ultp-taxonomy-item">';
                                $style = in_array($attr['layout'], [2,3,6,7,8]) ? 'style="'.($value['image'] ? 'background-image: url('.$value['image'][ $attr['imgCrop']].')' : 'background-color:'.$value['color']).'"' : '';
                                $name = ($attr['titleShow'] && $value['name']) ? '<'.$attr['titleTag'].' class="ultp-taxonomy-name" '.$_style_color.'>'.$value['name'].'</'.$attr['titleTag'].'>' : '';
                                $count = ($attr['countShow'] && $value['count']) ? '<span class="ultp-taxonomy-count" '.$_style_color.'>'.$value['count'].'</span>' : '';
                                $excerpt = ($attr['excerptShow'] && $value['desc']) ? '<div class="ultp-taxonomy-desc">'.$value['desc'].'</div>' : '';
                                $post_loop .= '<a href="'.$value['url'].'" '.($attr['layout'] != 3 ? $style : '').'>';
                                    switch ( $attr['layout'] ) {
                                        case 1:
                                            $post_loop .= $name.$count.$excerpt;
                                            break;
                                        case 2:
                                            $post_loop .= '<div class="ultp-taxonomy-lt2-overlay"'.$_style.'></div><div class="ultp-taxonomy-lt2-content">'.$name.'<span class="ultp-taxonomy-bar"></span>'.$count.'</div>'.$excerpt;
                                            break;
                                        case 3:
                                            $post_loop .= '<div class="ultp-taxonomy-lt3-img" '.$style.'></div><div class="ultp-taxonomy-lt3-overlay"'.$_style.'></div><div class="ultp-taxonomy-lt3-content">'.$name.'<span class="ultp-taxonomy-bar"></span>'.$count.'</div>'.$excerpt;
                                            break;
                                        case 4:
                                            $img = $value['image'] ? '<img src="'.$value['image'][ $attr['imgCrop']].'" alt="'.$value['name'].'"/>' : '';
                                            $post_loop .= $img.'<div class="ultp-taxonomy-lt4-content">'.$name.$count.'</div>'.$excerpt;
                                            break;
                                            case 5:
                                                $img = $value['image'] ? '<img src="'.$value['image'][ $attr['imgCrop']].'" alt="'.$value['name'].'"/>' : '';
                                            $post_loop .= $img.'<span class="ultp-taxonomy-lt5-content">'.$name.$count.$excerpt.'</span>';
                                            break;
                                        case 6:
                                            $post_loop .= '<div class="ultp-taxonomy-lt6-overlay"'.$_style.'></div>'.$name.$count.$excerpt;
                                            break;
                                        case 7:
                                            $post_loop .= '<div class="ultp-taxonomy-lt7-overlay"'.$_style.'></div><'.$attr['titleTag'].' class="ultp-taxonomy-name" '.$_style_title_bg.'>'.$value['name'].$count.'</'.$attr['titleTag'].'>'.$excerpt;
                                            break;
                                        case 8:
                                            $post_loop .= '<div class="ultp-taxonomy-lt8-overlay"'.$_style.'></div><'.$attr['titleTag'].' class="ultp-taxonomy-name" '.$_style_title_bg.'>'.$value['name'].$count.'</'.$attr['titleTag'].'>'.$excerpt;
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                $post_loop .= '</a>';
                            $post_loop .= '</li>';
                        }
                        
                        $wraper_after .= '</ul>';
                    $wraper_after .= '</div>';
                $wraper_after .= '</div>';
            $wraper_after .= '</div>';
            wp_reset_query();
        } else {
            $wraper_before .= ultimate_post()->get_no_result_found_html( $attr['notFoundMessage'] );
        }

        return $noAjax ? $post_loop : $wraper_before.$post_loop.$wraper_after;
    }
}