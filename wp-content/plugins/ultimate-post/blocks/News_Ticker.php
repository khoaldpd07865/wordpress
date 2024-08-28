<?php
namespace ULTP\blocks;

defined( 'ABSPATH' ) || exit;

class News_Ticker {
    
    public function __construct() {
        add_action( 'init', array( $this, 'register' ) );
    }

    public function get_attributes() {
        return array(
            'blockId' => '',
            'previewImg' => '',
            // Label Style
            'tickLabelColor' => '#fff',
            'tickLabelBg' => '#1974d2',
            'tickLabelTypo' => (object)['openTypography'=>1,'size'=>(object)['lg'=>'16', 'unit'=>'px'], 'spacing'=>(object)[ 'lg'=>'0', 'unit'=>'px'], 'height'=>(object)[ 'lg'=>'27', 'unit'=>'px'],'decoration'=>'none','transform' => '','family'=>'','weight'=>'500'],
            'tickLabelPadding' => '15',
            'tickLabelSpace' => (object)['lg'=>'160'],
            'tickShapeStyle' => 'normal',
            // Ticker body  Style
            'tickerContentHeight' => '45',
            'tickBodyColor' => '#444',
            'tickBodyHovColor' => '#7d7d7d',
            'tickBodyListColor' => '#037fff',
            'tickerBodyBg' => '#eeeeee',
            'tickBodyTypo' => (object)['openTypography'=>1,'size'=>(object)['lg'=>'16', 'unit'=>'px'], 'spacing'=>(object)[ 'lg'=>'0', 'unit'=>'px'], 'height'=>(object)[ 'lg'=>'16', 'unit'=>'px'],'decoration'=>'none','transform' => '','family'=>'','weight'=>'500'],
            'tickBodyBorderColor' => '',
            'tickBodyBorder' => (object)['openBorder'=>0,'disableColor'=> true, 'width' => (object)[ 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0],'type' => 'solid' ],
            'tickBodyRadius' => (object)['lg' =>'', 'unit' =>'px'],
            'tickTxtStyle' => 'normal',
            'tickImgWidth' => '30',
            'tickImgSpace' => '10',
            'tickImgRadius' => (object)['lg' =>(object)['top' => "30",'bottom' => "30",'left' => "30",'right' => "30", 'unit' =>'px']],
            'tickBodySpace' => (object)['lg'=>'21'],
            // Body Time Badge Style
            'tickTimeBadge' => 'Time Badge',
            'timeBadgeColor' => '#fff',
            'timeBadgeBg' => (object)['openColor' => 1, 'type' => 'color', 'color' => '#444'],
            'timeBadgeTypo' => (object)['openTypography'=>1,'size'=>(object)['lg'=>'12', 'unit'=>'px'], 'spacing'=>(object)[ 'lg'=>'0', 'unit'=>'px'], 'height'=>(object)[ 'lg'=>'16', 'unit'=>'px'],'decoration'=>'none','transform' => '','family'=>'','weight'=>'500'],
            'timeBadgeRadius' => (object)['lg' =>(object)['top' => "100",'bottom' => "100",'left' => "100",'right' => "100", 'unit' =>'px']],
            'timeBadgePadding' => (object)['lg'=>(object)['top'=>3,'bottom'=>3, 'left'=>6, 'right'=>6, 'unit'=>'px']],
            // Icon Navigator
            'TickNavStyle' => 'nav1',
            'TickNavIconStyle' => 'Angle2',
            'TickNavColor' => '#fff',
            'TickNavBg' => (object)['openColor' => 1, 'type' => 'color', 'color' => '#037fff'],
            'TickNavHovColor' => '#d0d0d0f4',
            'TickNavHovBg' => (object)['openColor' => 1, 'type' => 'color', 'color' => '#53a0ff'],
            // Pause Style
            'TickNavPause' => 'Pause Icon Style',
            'PauseColor' => '#fff',
            'PauseHovColor' => '#d0d0d0f4',
            'PauseBg' => (object)['openColor' => 1, 'type' => 'color', 'color' => '#2163ff'],
            'PauseHovBg' => (object)['openColor' => 1, 'type' => 'color', 'color' => '#53a0ff'],
            // Query Setting
            'queryQuick' => '',
            'queryNumPosts' => (object)['lg'=> 4],
            'queryNumber' => [ // for compatibility since v.2.5.4
                'type' => 'string',
                'default' =>4,
            ],
            'queryType' => 'post',
            'queryTax' => 'category',
            'queryTaxValue' => '[]',
            'queryRelation' => 'OR',
            'queryOrderBy' => 'date',
            'metaKey' => 'custom_meta_key',
            'queryOrder' => 'desc',
            'queryExclude' => '',
            'queryAuthor' => '[]',
            'queryOffset' => '0',
            'queryExcludeTerm' => '[]',
            'queryExcludeAuthor' => '[]',
            'querySticky' => true,
            'queryUnique' => '',
            'queryPosts' => '[]',
            'queryCustomPosts' => '[]',
            // General Setting
            'tickerType' => 'vertical',
            'tickerPositionEna' => false,
            'tickerPosition' => 'up',
            'tickerHeading' => true,
            'tickTimeShow' => true,
            'tickImageShow' => false,
            'navControlToggle' => true,
            'controlToggle' => true,
            'pauseOnHover' => true,
            'tickerDirectionVer' => 'up',
            'tickerDirectionHorizon' => 'left',
            'tickerSpeed' => '4000',
            'marqueSpeed' => '10',
            'tickerSpeedTypewriter' => '50',
            'tickerAnimation' => 'slide',
            'typeAnimation' => 'fadein',
            'openInTab' => false,
            // Heading Setting/Style
            'headingText' => 'News Ticker',
            // Advanced
            'advanceId' => '',
            'advanceZindex' => '',
            'hideExtraLarge' => false,
            'hideTablet' => false,
            'hideMobile' => false,
            'advanceCss' => '',
        );
    }

    public function register() {
        register_block_type( 'ultimate-post/news-ticker',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' => array( $this, 'content' )
            )
        );
    }

    public function content( $attr, $noAjax ) {
        $attr = wp_parse_args( $attr, $this->get_attributes() );
        global $unique_ID;

        if ( ! $noAjax ) {
            $paged = is_front_page() ? get_query_var('page') : get_query_var('paged');
            $attr['paged'] = $paged ? $paged : 1;
        }

        $block_name = 'news-ticker';
        $page_post_id = ultimate_post()->get_ID();
        $wraper_before = $wraper_after = $post_loop = '';

        $attr['queryNumber'] = ultimate_post()->get_post_number(4, $attr['queryNumber'], $attr['queryNumPosts']);
        $recent_posts = new \WP_Query( ultimate_post()->get_query( $attr ) );
        
        $arrowLeft = '';
        $arrowRight = '';
        if ( $attr['TickNavIconStyle'] != "icon2" ) {
            $arrowLeft .=  ultimate_post()->svg_icon('left'.$attr['TickNavIconStyle']);
            $arrowRight .= ultimate_post()->svg_icon('right'.$attr['TickNavIconStyle']);
        }

        $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
        $attr['advanceId'] = isset($attr['advanceId']) ? sanitize_html_class( $attr['advanceId'] ) : '';
        $attr['blockId'] = isset($attr['blockId']) ? sanitize_html_class( $attr['blockId'] ) : '';
        $attr['TickNavStyle'] = sanitize_html_class( $attr['TickNavStyle'] );
        $attr['TickNavIconStyle'] = sanitize_html_class( $attr['TickNavIconStyle'] );
        $attr['tickerType'] = sanitize_html_class( $attr['tickerType'] );
        $attr['headingText'] = sanitize_html_class( $attr['headingText'] );
        $attr['typeAnimation'] = sanitize_html_class( $attr['typeAnimation'] );
        $attr['tickerAnimation'] = sanitize_html_class( $attr['tickerAnimation'] );
        $attr['tickerDirectionVer'] = sanitize_html_class( $attr['tickerDirectionVer'] );
        $attr['tickerDirectionHorizon'] = sanitize_html_class( $attr['tickerDirectionHorizon'] );
        $attr['tickerSpeed'] = sanitize_html_class( $attr['tickerSpeed'] );
        $attr['marqueSpeed'] = sanitize_html_class( $attr['marqueSpeed'] );
        $attr['tickTxtStyle'] = sanitize_html_class( $attr['tickTxtStyle'] );
        $attr['pauseOnHover'] = isset($attr['pauseOnHover']) ? $attr['pauseOnHover'] == true : true ;


        if ( $recent_posts->have_posts() ) {
            $wraper_before .= '<div '.($attr['advanceId'] ? 'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].''.($attr["className"] ? ' '.$attr["className"]:'').'">';
                $wraper_before .= '<div class="ultp-block-wrapper ultp-news-sticky">';
                    $wraper_before .= '<div class="ultp-block-items-wrap">';

                        $post_loop .= '<div class="ultp-news-ticker-'.$attr['TickNavStyle'].' ultp-nav-'.$attr['TickNavIconStyle'].'  ultp-newsTicker-wrap ultp-newstick-'.$attr['tickerType'].' ">';
                            if ( $attr['tickerHeading'] && $attr['headingText'] ) {
                                $post_loop .= '<div class="ultp-news-ticker-label">'.$attr['headingText'].'</div>';
                            }

                            $post_loop .= '<div class="ultp-news-ticker-box ultp-animation-'.($attr['tickerType'] == 'typewriter' ? $attr['typeAnimation'] : $attr['tickerAnimation'] ).' ultp-sliderDir-'.($attr['tickerType'] == 'vertical' ? $attr['tickerDirectionVer'] : $attr['tickerDirectionHorizon'] ).'">';
                                $speed = $attr['tickerType'] != 'marquee' ? $attr['tickerSpeed'] : $attr['marqueSpeed'];
                                $post_loop .= '<ul class="ultp-news-ticker" data-type="'.$attr['tickerType'].'" data-hover="'.$attr['pauseOnHover'].'" data-direction="'.($attr['tickerType'] == 'vertical' ? $attr['tickerDirectionVer'] : $attr['tickerDirectionHorizon'] ).'" data-speed="'.$speed.'">';
                                while ( $recent_posts->have_posts() ): $recent_posts->the_post();                  
                                    $post_id        = get_the_ID();
                                    $title          = get_the_title( $post_id );
                                    $titlelink      = get_permalink( $post_id );
                                    if ( $attr['queryUnique'] ) {
                                        $unique_ID[$attr['queryUnique']][] = $post_id;
                                    }
                                    $typeStyle = $style = '';
                                    if ( $attr['tickerType'] != 'marquee' && $attr['tickerType'] != 'typewriter' && $attr['tickerAnimation'] == "fadeout" ) {
                                        $style .= 'animation-delay:'.($speed - 1000).'ms';
                                    }
                                    if ( $attr['tickerType'] == 'typewriter' && $attr['typeAnimation'] == "fadeout" ) {
                                        $style .= 'animation-delay:'.($speed - 1000).'ms';
                                    }
                                    if ( $attr['tickerType'] == 'typewriter' ) {
                                        $typeStyle .= ' animation-duration:'.($speed - 1000).'ms';
                                    }
                                    $post_loop .= '<li style='.$style.'>';
                                        $post_loop .= '<div class="ultp-list-'.$attr['tickTxtStyle'].'">';
                                            $post_loop .= '<a '.($attr['openInTab'] ? 'target="_blank"' : '').' href="'.$titlelink.'">';
                                                if ($attr['tickImageShow'] && has_post_thumbnail()) {
                                                    $post_loop .= ultimate_post()->get_image( get_post_thumbnail_id( $post_id ), 'thumbnail', '', $title, '' , '' );
                                                }
                                                $post_loop .= '<span style="'.$typeStyle.'" class="title-text" data-text="'.$title.'">'.$title.'</span>';
                                                $post_loop .= '</a>';
                                            if ($attr['tickTimeShow'] && ($attr['tickerType'] != 'typewriter' ) ) {
                                                $post_loop .= '<span class="ultp-ticker-timebadge">'.human_time_diff(get_the_time('U'),current_time('U')).' ago</span>';
                                            }
                                        $post_loop .= '</div>';
                                    $post_loop .= '</li>';
                                endwhile;
                                $post_loop .= '</ul>';
                            $post_loop .= '</div>';

                            if ( $attr['navControlToggle'] &&  $attr['TickNavStyle'] ) {
                                $post_loop .= '<div class="ultp-news-ticker-controls ultp-news-ticker-vertical-controls">';
                                        $post_loop .= '<button aria-label="'.__("Show Previous Post", "ultimate-post").'" class="ultp-news-ticker-arrow ultp-news-ticker-prev prev">'.$arrowLeft.'</button>';
                                        if ($attr['controlToggle'] && ( $attr['TickNavStyle']  == "nav1" ||  $attr['TickNavStyle']  == "nav3" ||  $attr['TickNavStyle']  == "nav4" )) {
                                            $post_loop .= '<button aria-label="'.__("Pause Current Post", "ultimate-post").'" id="ultp-pause-btn" class="ultp-news-ticker-pause pause"></button>';
                                        }
                                        $post_loop .= '<button aria-label="'.__("Show Next Post", "ultimate-post").'" class="ultp-news-ticker-arrow ultp-news-ticker-next next">'.$arrowRight.'</button>';
                                $post_loop .= '</div>';
                            }
                        $post_loop .= '</div>';
                    $wraper_after .= '</div>';
                $wraper_after .= '</div>';
            $wraper_after .= '</div>';

            wp_reset_query();
        }

        return $noAjax ? $post_loop : $wraper_before.$post_loop.$wraper_after;
    }
}