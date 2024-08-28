<?php
namespace ULTP\blocks;

defined( 'ABSPATH' ) || exit;

class Post_Slider_2 {

    public function __construct() {
        add_action('init', array($this, 'register'));
    }

    public function get_attributes() {

        return array(
            'blockId' => '',
            'previewImg' => '',
            // General Setting
            'slidesToShow' => (object)['lg' =>'1', 'sm' =>'1', 'xs' =>'1'],
            'autoPlay' => true,
            'height' => (object)['lg' =>'550', 'unit' =>'px'],
            'slidesCenterPadding' => (object)['lg'=>'160', 'sm'=>'100', 'xs'=>'50',],
            'slidesTopPadding' => '0',
            'allItemScale' => (object)['lg'=>'.9'],
            'centerItemScale' => (object)['lg'=>'1.12'],
            'slideSpeed' => '3000',
            'sliderGap' => '',
            'dots' => true,
            'arrows' => true,
            'preLoader' => false,
            'fade' => false,
            'titleShow' => true,
            'titleStyle' => 'none',
            'headingShow' => false,
            'excerptShow' => false,
            'catShow' => true,
            'metaShow' => true,
            'readMore' => false,
            'contentTag' => 'div',
            'openInTab' => false,
            'notFoundMessage' => 'No Post Found',
            // Query Setting
            'layout' => 'slide1',
            'queryQuick' => '',
            'queryNumPosts' => (object)['lg'=>5],
            'queryNumber' => 5,
            'queryType' => 'post',
            'queryTax' => 'category',
            'queryTaxValue' => '[]',
            'queryRelation' => 'OR',
            'queryOrderBy' => 'date',
            'metaKey' => 'custom_meta_key',
            'queryOrder' => 'desc',
            // Include Remove from Version 2.5.4
            'queryInclude' => '',
            'queryExclude' => '[]',
            'queryAuthor' => '[]',
            'queryOffset' => '0',
            'queryExcludeTerm' => '[]',
            'queryExcludeAuthor' => '[]',
            'querySticky' => true,
            'queryUnique' => '',
            'queryPosts' => '[]',
            'queryCustomPosts' => '[]',
            // Arrow Style
            'arrowStyle' => 'leftAngle2#rightAngle2',
            
            // Heading Style
            'headingText' => 'Post Slider #1',
            'headingURL' => '',
            'headingBtnText' => 'View More',
            'headingStyle' => 'style1',
            'headingTag' => 'h2',
            'headingAlign' => 'left',
            'subHeadingShow' => false,
            'subHeadingText' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut sem augue. Sed at felis ut enim dignissim sodales.',
            'contentVerticalPosition' => 'bottomPosition',
            'contentHorizontalPosition' => 'centerPosition',
            'slideBgBlur' => '',
            
            // Image Style
            'imageShow' => true,
            'imgCrop' => 'full',
            'imgOverlay' => false,
            'imgBgbrightness' => '',
            'imgBgBlur' => '',
            'imgOverlayType' => 'default',
            'fallbackEnable' => true,
            'fallbackImg' => '',
            'imgSrcset' => false,
            'imgLazy' => false,
            
            // Read more Setting/Style
            'readMoreText' => '',
            'readMoreIcon' => 'rightArrowLg',
            
            // Title Setting/Style
            'titleTag' => 'h3',
            'titlePosition' => true,
            'titleLength' => 0,
            
            // Meta Setting/Style
            'metaPosition' => 'top',
            'metaStyle' => 'noIcon',
            'authorLink' => true,
            'metaSeparator' => 'dot',
            'metaList' => '["metaAuthor","metaDate"]',
            'metaMinText' => 'min read',
            'metaAuthorPrefix' => '',
            'metaDateFormat' => 'j M Y',
            
            // Category Setting/Style
            'maxTaxonomy'=> '30',
            'taxonomy' => 'category',
            'catStyle' => 'classic',
            'catPosition' => 'aboveTitle',
            'customCatColor' => false,
            'seperatorLink' => admin_url( 'edit-tags.php?taxonomy=category' ),
            'onlyCatColor' => false,
            
            // Excerpt Style
            'showSeoMeta' => false,
            'showFullExcerpt' => false,
            'excerptLimit' => 40,
            
            // Wrapper Style
            'advanceId' => '',
            'advanceZindex' => '',
            'hideExtraLarge' => false,
            'hideTablet' => false,
            'hideMobile' => false,
            'advanceCss' => '',
        );
    }

    public function register() {
        register_block_type( 'ultimate-post/post-slider-2',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' => array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $attr = wp_parse_args($attr, $this->get_attributes());
        global $unique_ID;

        if (!$noAjax) {
            $paged = is_front_page() ? get_query_var('page') : get_query_var('paged');
            $attr['paged'] = $paged ? $paged : 1;
        }
        $is_active = ultimate_post()->is_lc_active(); 
        
        if ( $is_active ) {
            $block_name = 'post-slider-2';
            $page_post_id = ultimate_post()->get_ID();
            $wraper_before = $wraper_after = $post_loop = '';
            $attr['queryNumber'] = ultimate_post()->get_post_number(5, $attr['queryNumber'], $attr['queryNumPosts']);
            $recent_posts = new \WP_Query( ultimate_post()->get_query( $attr ) );
            $pageNum = ultimate_post()->get_page_number($attr, $recent_posts->found_posts);
            // Dummy Img Url
            $dummy_url = ULTP_URL.'assets/img/ultp-fallback-img.png';

            $slides = is_object($attr['slidesToShow']) ? json_decode(wp_json_encode($attr['slidesToShow']),true) : $attr['slidesToShow'];

            $centerPadd = is_object($attr['slidesCenterPadding']) ? json_decode(wp_json_encode($attr['slidesCenterPadding']),true) : $attr['slidesCenterPadding'];
            if ($recent_posts->have_posts() ) {
                $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
                $attr['align'] = isset($attr['align']) && $attr['align'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
                $attr['advanceId'] = isset($attr['advanceId']) ? sanitize_html_class( $attr['advanceId'] ) : '';
                $attr['blockId'] = isset($attr['blockId']) ? sanitize_html_class( $attr['blockId'] ) : '';
                $attr['contentTag'] = in_array( $attr['contentTag'],  ultimate_post()->ultp_allowed_block_tags() ) ? $attr['contentTag'] : 'div';
                $attr['layout'] = sanitize_html_class( $attr['layout'] );
                $attr['imgOverlayType'] = sanitize_html_class( $attr['imgOverlayType'] );
                $attr['slideSpeed'] = sanitize_html_class( $attr['slideSpeed'] );
                $attr['arrows'] =  $attr['arrows'] == true ;
                $attr['dots'] =  $attr['dots'] == true ;
                $attr['autoPlay'] =  $attr['autoPlay'] == true ;
                $attr['fade'] =  $attr['fade'] == true ;
                $attr['readMoreText'] = wp_kses($attr['readMoreText'], ultimate_post()->ultp_allowed_html_tags());
                $attr['contentVerticalPosition'] = sanitize_html_class( $attr['contentVerticalPosition'] );
                $attr['contentHorizontalPosition'] = sanitize_html_class( $attr['contentHorizontalPosition'] );

                $wraper_before .= '<div '.( $attr['advanceId'] ? 'id="'.$attr['advanceId'].'" ':'' ).' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].''.( $attr["align"] ? ' align' .$attr["align"]:'' ).''.( $attr["className"] ?' '.$attr["className"]:'' ).'">';
                    $wraper_before .= '<div class="ultp-block-wrapper">';
                        if ($attr['headingShow']) {
                            $wraper_before .= '<div class="ultp-heading-filter">';
                                $wraper_before .= '<div class="ultp-heading-filter-in">';
                                    include ULTP_PATH.'blocks/template/heading.php';
                                $wraper_before .= '</div>';
                            $wraper_before .= '</div>';
                        }
                        $wraper_before .= '<div class="ultp-block-items-wrap ultp-slide-'.$attr['layout'].'" data-layout="'.$attr['layout'].'"  data-arrows="'.$attr['arrows'].'" data-dots="'.$attr['dots'].'" data-autoplay="'.$attr['autoPlay'].'" data-slidespeed="'.$attr['slideSpeed'].'" data-fade="'.$attr['fade'].'" data-slidelg="'.(isset($slides['lg']) ? sanitize_html_class( $slides['lg'] ) : 1).'" data-slidesm="'.(isset($slides['sm']) ? sanitize_html_class( $slides['sm'] ) : 1).'" data-slidexs="'.(isset($slides['xs']) ? sanitize_html_class( $slides['xs'] ) : 1).'" 
                        data-paddlg="'.(isset($centerPadd["lg"]) ? sanitize_html_class( $centerPadd["lg"] ) : 100 ).'" data-paddsm="'.(isset($centerPadd["sm"]) ? sanitize_html_class( $centerPadd["sm"] ) : 100 ).'" data-paddxs="'.(isset($centerPadd["xs"]) ? sanitize_html_class( $centerPadd['xs'] ) : 50).'">';
                            $idx = $noAjax ? 1 : 0;
                            while ( $recent_posts->have_posts() ): $recent_posts->the_post();
                                
                                include ULTP_PATH.'blocks/template/data.php';

                                if ($attr['queryUnique']) {
                                    $unique_ID[$attr['queryUnique']][] = $post_id;
                                }

                                $post_loop .= '<'.$attr['contentTag'].' class="ultp-block-item post-id-'.$post_id.'">';
                                    if ( $attr['preLoader'] ) {
                                        $post_loop .= '<div class="ultp-post-slider-loader-container">';
                                            $post_loop .= ultimate_post()->loading();
                                        $post_loop .= '</div>';
                                    }   
                                    $post_loop .= '<div>';
                                    $post_loop .= '<div class="ultp-block-slider-wrap">';

                                        $post_loop .= '<div class="ultp-block-image-inner">';
                                            if ( $attr['imageShow'] ) {
                                                if ( $post_thumb_id || $attr['fallbackEnable'] ) {
                                                    $post_loop .= '<div class="ultp-block-image '.($attr["imgOverlay"] ? ' ultp-block-image-overlay ultp-block-image-'.$attr["imgOverlayType"].' ultp-block-image-'.$attr["imgOverlayType"].$idx : '' ).'">';
                                                        $post_loop .= '<a href="'.$titlelink.'" '.($attr['openInTab'] ? 'target="_blank"' : '').'>';
                                                        // Post Image Id
                                                        $block_img_id = $post_thumb_id ? $post_thumb_id : ($attr['fallbackEnable'] && isset($attr['fallbackImg']['id']) ? $attr['fallbackImg']['id'] : '');
                                                        // Post Image 
                                                        if($post_thumb_id || ($attr['fallbackEnable'] && $block_img_id)) {
                                                            $post_loop .= ultimate_post()->get_image($block_img_id, $attr['imgCrop'], '', $title, $attr['imgSrcset'], $attr['imgLazy']);
                                                        } else {
                                                            $post_loop .= '<img  src="'.$dummy_url.'" alt="dummy-img" />';
                                                        }
                                                    $post_loop .= '</a></div>'; //.ultp-block-image    
                                                }
                                            }
                                        $post_loop .= '</div>'; //.ultp-block-image-inner                  

                                        $post_loop .= '<div class="ultp-block-content ultp-block-content-'.$attr['contentVerticalPosition'].' ultp-block-content-'.$attr['contentHorizontalPosition'].'">';
                                            $post_loop .= '<div class="ultp-block-content-inner">';
                                                
                                                include ULTP_PATH.'blocks/template/category.php';
                                                $post_loop .= $category;

                                                if ( $title && $attr['titleShow'] && $attr['titlePosition'] ) {
                                                    include ULTP_PATH . 'blocks/template/title.php';
                                                }
                                                if ( $attr['metaPosition'] =='top' ) {
                                                    include ULTP_PATH . 'blocks/template/meta.php';
                                                }
                                                if ( $title && $attr['titleShow'] && ! $attr['titlePosition'] ) {
                                                    include ULTP_PATH . 'blocks/template/title.php';
                                                }
                                                if ( $attr['excerptShow'] ) {
                                                    $post_loop .= '<div class="ultp-block-excerpt">'.ultimate_post()->get_excerpt($post_id, $attr['showSeoMeta'], $attr['showFullExcerpt'], $attr['excerptLimit']).'</div>';
                                                }
                                                if ( $attr['readMore'] ) {
                                                    $post_loop .= '<div class="ultp-block-readmore"><a aria-label="'.$title.'" href="'.$titlelink.'" '.($attr['openInTab'] ? 'target="_blank"' : '').'>'.($attr['readMoreText'] ? $attr['readMoreText'] : esc_html__( "Read More", "ultimate-post" )).ultimate_post()->svg_icon($attr['readMoreIcon']).'</a></div>';
                                                }
                                                if ( $attr['metaPosition'] =='bottom' ) {
                                                    include ULTP_PATH . 'blocks/template/meta.php';
                                                }
                                            $post_loop .= '</div>';
                                        $post_loop .= '</div>';

                                    $post_loop .= '</div>';
                                    $post_loop .= '</div>';
                                $post_loop .= '</'.$attr['contentTag'].'>';
                            endwhile;
                        $wraper_after .= '</div>';
                        
                        if ( $attr['arrows'] ) {
                            $wraper_after .= '<div class="ultp-slick-nav" style="display:none">';
                                $nav = explode( '#', $attr['arrowStyle'] );
                                $wraper_after .= '<div class="ultp-slick-prev"><div class="slick-arrow slick-prev">'.ultimate_post()->svg_icon($nav[0]).'</div></div>';
                                $wraper_after .= '<div class="ultp-slick-next"><div class="slick-arrow slick-next">'.ultimate_post()->svg_icon($nav[1]).'</div></div>';
                            $wraper_after .= '</div>';
                        }
                    $wraper_after .= '</div>';
                $wraper_after .= '</div>';
                wp_reset_query();
            } else {
                $wraper_before .= ultimate_post()->get_no_result_found_html( $attr['notFoundMessage'] );
            }
        
            return $noAjax ? $post_loop : $wraper_before.$post_loop.$wraper_after;
        }
    }

}