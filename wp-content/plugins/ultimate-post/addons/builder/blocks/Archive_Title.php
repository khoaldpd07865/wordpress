<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Archive_Title{
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes() {

        return array(
            'blockId' =>  '',

            /*============================
                General Settings
            ============================*/
            'layout' =>  '1',
            // 'contentAlign' =>  "left",
            'titleShow' =>  true,
            'excerptShow' =>  true,
            'prefixShow' =>  false,
            'showImage' =>  false,
            
            /*============================
                Title Setting/Style
            ============================*/
            'titleTag' =>  'h1',
            'customTaxTitleColor' =>  false,
            'seperatorTaxTitleLink' =>  admin_url( 'edit-tags.php?taxonomy=category' ),
            
            
            /*============================
                Prefix Setting/Style
            ============================*/
            'prefixText' =>  'Sample Prefix Text',
            'prefixTop' => false,
            

            
            /*============================
                Custom Wrapper Style
            ============================*/
            'customTaxColor' =>  false,
            'seperatorTaxLink' =>  admin_url( 'edit-tags.php?taxonomy=category' ),
            
            /*============================
                Advance Settings
            ============================*/
            'advanceId' =>  '',
            'advanceZindex' =>  '',

            //---------------------
            // Advanced > Responsive 
            //---------------------
            'hideExtraLarge' =>  false,
            'hideTablet' =>  false,
            'hideMobile' =>  false,
            'advanceCss' =>  '',
        );
    }

    public function register() {
        register_block_type( 'ultimate-post/archive-title',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function get_data() {
        if (is_admin()) {
            // For Demonstration Purpose
            return [
                'title' => 'Archive Title',
                'image' => ULTP_URL.'assets/img/builder-fallback.jpg',
                'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam molestie aliquet molestie.',
                'color' => '#037fff'
            ];
        } else {
            $_title = $_image = $_desc = $_color = '';
            if (is_archive()) {
                if (is_category() || is_tag() || is_tax()) {
                    $obj = get_queried_object();
                    $attachment_id = get_term_meta( $obj->term_id, 'ultp_category_image', true );
                    $_title = $obj->name;
                    $_image = $attachment_id ? wp_get_attachment_url($attachment_id) : '';
                    $_desc = $obj->description;
                    $_color = get_term_meta( $obj->term_id, 'ultp_category_color', true );
                } else if (is_date()) {
                    $_title = is_year() ? get_the_date('Y') : (is_month() ? get_the_date('F Y') : (is_day() ? get_the_date('F j, Y') : '' ));
                } else if (is_author()) {
                    $_title = get_the_author_meta( 'display_name' );
                    $_image = get_avatar_url( get_the_author_meta( 'ID' ) );
                    $_desc = get_the_author_meta( 'description' );
                }
            } else if (is_search()) {
                $_title = get_search_query();
            }
            return ['title' => $_title, 'image' => $_image, 'desc' => $_desc, 'color' => $_color];   
        }
    }


    public function content($attr, $noAjax) {
        $attr = wp_parse_args($attr, $this->get_attributes());

        // Dummy
        $data = $this->get_data();
        $wraper_before = $wraper_after = $post_loop = '';
        $block_name = 'archive-title';

        $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
        $attr['align'] = isset($attr['align']) && $attr['align'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
        $attr['advanceId'] = isset($attr['advanceId']) ? sanitize_html_class( $attr['advanceId'] ) : '';
        $attr['titleTag'] = in_array( $attr['titleTag'],  ultimate_post()->ultp_allowed_block_tags() ) ? $attr['titleTag'] : 'h1';
        $attr['layout'] = sanitize_html_class( $attr['layout'] );
        $attr['prefixText'] = wp_kses($attr['prefixText'], ultimate_post()->ultp_allowed_html_tags());

        $wraper_before .= '<div '.( $attr['advanceId'] ? 'id="'.$attr['advanceId'].'" ':'' ).' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].''.( $attr["align"] ? ' align' .$attr["align"]:'' ).''.( $attr["className"] ?' '.$attr["className"]:'' ).'">';
            $wraper_before .= '<div class="ultp-block-wrapper">';
            $wraper_before .= '<div class="ultp-block-archive-title ultp-archive-layout-'.$attr['layout'].'">';

            $style = $attr['layout'] == '2' ? ($data['image'] ? 'style="background-image: url('.$data['image'].')' : 'style="background-color:'.($data['color'] ? $data['color'] : '#28303d')).'"' : '';
            $prefix = ($attr['prefixShow'] && $attr['prefixText']) ? '<span class="ultp-archive-prefix">'.$attr['prefixText'].'</span> ' : '';

            $name = ($attr['titleShow'] && $data['title']) ? '<'.$attr['titleTag'].' class="ultp-archive-name" '.( ( $data['color'] && $attr['customTaxTitleColor'] ) ? 'style="color: '.$data['color'].'"' : '').'>'.$prefix.$data['title'].'</'.$attr['titleTag'].'>' : '';
            
            $excerpt = ($attr['excerptShow'] && $data['desc']) ? '<div class="ultp-archive-desc">'.$data['desc'].'</div>' : '';

                // Prefix
                switch ($attr['layout']) {
                    case 1:
                        $img = ($attr['showImage'] && $data['image']) ? '<img class="ultp-archive-image" src="'.$data['image'].'" alt="'.$data['title'].'"/>' : '';
                        $post_loop .= $img.$name.$excerpt;
                        break;
                    case 2:
                        $style_overlay = $attr['customTaxColor'] ? 'style="background-color: '.$data['color'].'"' : '';
                        $post_loop .= '<div class="ultp-archive-content" '.$style.'><div class="ultp-archive-overlay" '.$style_overlay.'></div>'.$name.$excerpt.'</div>';
                        break;
                }
            
            $wraper_after .= '</div>';
            $wraper_after .= '</div>';
        $wraper_after .= '</div>';

        return $wraper_before.$post_loop.$wraper_after;
    }

}