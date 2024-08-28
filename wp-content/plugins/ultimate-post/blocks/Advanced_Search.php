<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Advanced_Search {
    public function __construct() {
        add_action( 'init', array( $this, 'register' ) );
    }

    public function get_attributes() {
        return array(
            'advanceId' => '',
            'blockId' => '',
            'advanceCss' => '',
            // General Content Setting
            'searchFormStyle' => 'input1',
            'searchPopup' => false,
            'searchPopupIconStyle' => 'popup-icon1',
            'searchAjaxEnable' => true,
            'searchResultLayout' => 'res',
            'searchnoresult' => 'No Results Found',
            'searchPostType' => '',
            
            // Popup Canvas
            'popupAnimation' =>  'popup',
            
            'searchPopupPosition' => 'right',
            'popupCloseIconSeparator' => 'Close Icon Style',
            'windowpopupHeading' => true,
            'windowpopupText' => 'Search The Query',
            
            // Search Button Style
            'searchBtnEnable' => false,
            'btnNewTab' => false,
            'enableSearchPage' => true,
            'searchButtonText' => 'Search',
            'searchBtnText' => true,
            'searchBtnIcon' => true,
            'searchIconAfterText' => false,

            // Search Form Style
            'searchInputPlaceholder' => 'Search...',
            
            // Search Result Settings
            'resExcerptEnable' => true,
            'resCatEnable' => true,
            'resAuthEnable' => true,
            'resDateEnable' => true,
            'resImageEnable' => true,
            'resExcerptLimit' => '25',
            
            // Search Result Settings
            'moreResultsbtn' => true,
            'moreResultPosts' => 3,
            'moreResultsText' => 'View More Results',
        );
    }

    public function register() {
        register_block_type( 'ultimate-post/advanced-search',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' =>  array( $this, 'content' )
            )
        );
    }

    public function content($attr, $noAjax) {
        $wraper_before = $wraper_after = $content = $popup_content = '';
        $block_name = 'advanced-search';
        $is_active = ultimate_post()->is_lc_active(); 

        if ( $is_active ) {
            $attr = wp_parse_args($attr, $this->get_attributes());

            $attr['blockId'] = sanitize_html_class(  $attr['blockId'] );
            $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
            $attr['align'] = isset($attr['align']) && $attr['align'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
            $attr['popupAnimation'] = sanitize_html_class( $attr['popupAnimation'] );
            $attr['btnNewTab'] = $attr['btnNewTab'] == true;
            $attr['searchAjaxEnable'] = $attr['searchAjaxEnable'] == true;
            $attr['enableSearchPage'] = $attr['enableSearchPage'] == true;
            $attr['resImageEnable'] = $attr['resImageEnable'] == true;
            $attr['resAuthEnable'] = $attr['resAuthEnable'] == true;
            $attr['resDateEnable'] = $attr['resDateEnable'] == true;
            $attr['resExcerptEnable'] = $attr['resExcerptEnable'] == true;
            $attr['moreResultsbtn'] = $attr['moreResultsbtn'] == true;
            $attr['resCatEnable'] = $attr['resCatEnable'] == true;
            $attr['resExcerptLimit'] = sanitize_html_class( $attr['resExcerptLimit'] );
            $attr['moreResultPosts'] = sanitize_html_class( $attr['moreResultPosts'] );
            $attr['searchPopupPosition'] = sanitize_html_class( $attr['searchPopupPosition'] );
            $attr['searchFormStyle'] = sanitize_html_class( $attr['searchFormStyle'] );
            $attr['searchPopupIconStyle'] = sanitize_html_class( $attr['searchPopupIconStyle'] );

            $allowed_html_tags = ultimate_post()->ultp_allowed_html_tags();
            $attr['windowpopupText'] = wp_kses($attr['windowpopupText'], $allowed_html_tags);
            $attr['searchnoresult'] = wp_kses($attr['searchnoresult'], $allowed_html_tags);
            $attr['moreResultsText'] = wp_kses($attr['moreResultsText'], $allowed_html_tags);
            $attr['searchButtonText'] = wp_kses($attr['searchButtonText'], $allowed_html_tags);
            $attr['searchInputPlaceholder'] = wp_kses($attr['searchInputPlaceholder'], $allowed_html_tags);
            
            $data_var = "data-searchPostType=".json_decode(wp_json_encode($attr['searchPostType']));
            $wraper_before .= '<div '.($attr['advanceId']?'id="'.sanitize_html_class( $attr['advanceId'] ).'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.sanitize_html_class( $attr["blockId"] ).''.( $attr["align"] ? ' align' .$attr["align"]:'').''.( $attr["className"] ? ' '. $attr["className"] :'').'">';
                $wraper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<div class="ultp-search-container ultp-search-frontend'.($attr['searchPopup'] ? ' ultp-search-animation-'. $attr['popupAnimation']  :'').'"  data-ajax="'.$attr['searchAjaxEnable'].'" data-gosearch="'.$attr['enableSearchPage'].'" data-enablenewtab="'.$attr['btnNewTab'].'" data-blockId="'.$attr['blockId'].'" 
                    data-image="'.$attr['resImageEnable'].'"  data-author='.$attr['resAuthEnable'].' data-date="'.$attr['resDateEnable'].'" data-excerpt="'.$attr['resExcerptEnable'].'" data-excerptLimit="'.$attr['resExcerptLimit'].'" data-allresult="'.$attr['moreResultsbtn'].'" data-catEnable="'.$attr['resCatEnable'].'"  data-postno="'.$attr['moreResultPosts'].'" '.($attr['searchPopup'] ? 'data-popuptype="'.$attr['popupAnimation'].'" ' : '').' '.($attr['searchAjaxEnable'] ? 'data-noresultext="'.$attr['searchnoresult'].'" ' : '').' '.($attr['moreResultsbtn'] ? 'data-viewmoretext="'.$attr['moreResultsText'].'" ' : '').' data-popupposition="'.$attr['searchPopupPosition'].'" '.$data_var.'>';
                        if ( $attr['searchPopup'] ) {
                            $content .= $this->renderSearchButton($attr['searchPopupIconStyle'], $attr['searchBtnText'], $attr['searchBtnIcon'] ,$attr['searchButtonText']);
                        }
                        if ( $attr['searchPopup'] == false ) {
                            $content .= $this->renderSearchForm($attr['searchFormStyle'], $attr['searchBtnText'], $attr['searchBtnIcon'], $attr);
                        }
                        if ( $attr['searchPopup'] ) {
                            $popup_content .= '<div class="ultp-search-canvas">';
                            
                                $popup_content .= '<div class="ultp-canvas-header">';
                                    if($attr['windowpopupHeading']){
                                        $popup_content .= '<div class="ultp-search-popup-heading">'.$attr['windowpopupText'].'</div>';
                                    }
                                    $popup_content .= $this->renderSearchForm($attr['searchFormStyle'], $attr['searchBtnText'], $attr['searchBtnIcon'], $attr);
                                $popup_content .= '</div>';
                                $popup_content .= '<div class="ultp-popupclose-icon">'.ultimate_post()->svg_icon('close_line').'</div>';
                            $popup_content .= '</div>';
                            if($attr['popupAnimation'] == 'popup'){
                                $content .= $popup_content;
                            } else {
                                $content .= $popup_content;
                            }
                        }
                    $content .= '</div>'; 
                $wraper_after .= '</div>';
            $wraper_after .= '</div>';
            return $wraper_before.$content.$wraper_after;
        }
    }

    public function renderSearchButton( $style, $textEnable = true, $iconEnable = true, $searchButtonText = '' ) {
        $textShow = $textEnable && $style != "popup-icon1";
        $result = '';
        $result .= '<div class=" '.($style ? 'ultp-searchpopup-icon ultp-searchbtn-'.$style : 'ultp-search-button').'">';
        $result .= ($style || $iconEnable) ? ultimate_post()->svg_icon('search_line') : '';
        $result .= $textShow ? '<span class="ultp-search-button__text"> '.$searchButtonText.' </span>' : '';
        $result .= '</div>';
        return $result;
    }

    public function renderSearchForm( $searchFormStyle, $searchBtnText, $searchBtnIcon, $attr ) {
        $dt = is_search() ? get_search_query(true) : '';
        $searchForm = '';
        $searchForm .= '<div class="ultp-searchform-content ultp-searchform-'.$searchFormStyle.'">';
        $searchForm .= '<div class="ultp-search-inputwrap"> <input type="text" value="'.$dt.'" class="ultp-searchres-input"  placeholder="'.$attr['searchInputPlaceholder'].'"/> <span class="ultp-search-clear" data-blockid="'.$attr["blockId"].'">'.ultimate_post()->svg_icon('close_line').'</span> </div>';
            $searchForm .= $this->renderSearchButton(false, $attr['searchBtnText'], $attr['searchBtnIcon'] ,$attr['searchButtonText']);
        $searchForm .= '</div>';
        return $searchForm;
    }
}