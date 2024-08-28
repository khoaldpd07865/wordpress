<?php
namespace ULTP\blocks;

defined( 'ABSPATH' ) || exit;

class Heading{
    
    public function __construct() {
        add_action('init', array($this, 'register' ) );
    }

    public function get_attributes() {

        return array(
            'blockId' => '',
            // Heading Setting/Style
            'headingText' => 'This is a Heading Example',
            'headingURL' => '',
            'openInTab' => false,
            'headingBtnText' => 'View More',
            'headingStyle' => 'style9',
            'headingTag' => 'h2',
            'headingAlign' => 'left',
            'subHeadingShow' => false,
            'subHeadingText' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut sem augue. Sed at felis ut enim dignissim sodales.',
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
        register_block_type( 'ultimate-post/heading',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' =>  array( $this, 'content' )
            )
        );
    }

    public function content( $attr, $noAjax ) {
        $attr = wp_parse_args( $attr, $this->get_attributes() );
        
        $wraper_before = '';
        $block_name = 'heading';
        $attr['headingShow'] = true;
        $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
        $attr['align'] = isset($attr['align']) && $attr['align'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
        $attr['advanceId'] = isset($attr['advanceId']) ? sanitize_html_class( $attr['advanceId'] ) : '';
        $attr['blockId'] = isset($attr['blockId']) ? sanitize_html_class( $attr['blockId'] ) : '';

        $wraper_before .= '<div '.($attr['advanceId'] ? 'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].''.($attr["align"] ? ' align' .$attr["align"]:'').''.( $attr["className"] ?' '.$attr["className"]:'').'">';
            $wraper_before .= '<div class="ultp-block-wrapper">';
                include ULTP_PATH . 'blocks/template/heading.php';
            $wraper_before .= '</div>';
        $wraper_before .= '</div>';

        return $wraper_before;
    }
}