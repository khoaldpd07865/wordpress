<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Author_Box{
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function register() {
        register_block_type( 'ultimate-post/author-box',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' => array($this, 'content')
            )
        );
    }
    public function get_attributes() {
        return array(
            'blockId' => '',
            'layout' => 'layout1',

            /*============================
                Author Box Settings
            ============================*/
            'imgShow' => true,
            'writtenByShow' => true,
            'authorBioShow' => true,
            'metaShow' => true,
            'allPostLinkShow' => true,
            'authorBoxAlign' => 'center',


            
            /*============================
                Author Image Settings
            ============================*/
            'imgSize' => (object)['lg' =>'100'],
            'imgRatio' => '100',

            /*============================
                Written by Settings
            ============================*/
            'writtenByText' => 'Written by',
            
            /*============================
                Author Name Settings
            ============================*/
            'authorNameTag' => 'h4',

            /*============================
                Meta Setting/Style Settings
            ============================*/
            'metaPosition' => 'bottom',

            /*============================
                View all Post Button Settings
            ============================*/
            'viewAllPostText' => 'View All Posts',
            
            /*============================
                Advanced Settings
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

    public function content($attr, $noAjax) {
        $attr = wp_parse_args($attr, $this->get_attributes());

        $block_name = 'author_box';
        $author_bio = $wrapper_before = $wrapper_after = $content = '';

        $page_post_id = get_the_ID(); // ultimate_post()->get_ID();
        
        if($page_post_id){

            $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
            $attr['align'] = isset($attr['align']) && $attr['align'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
            $attr['advanceId'] = isset($attr['advanceId']) ? sanitize_html_class( $attr['advanceId'] ) : '';
            $attr['blockId'] = isset($attr['blockId']) ? sanitize_html_class( $attr['blockId'] ) : '';
            $attr['authorNameTag'] = in_array( $attr['authorNameTag'],  ultimate_post()->ultp_allowed_block_tags() ) ? $attr['authorNameTag'] : 'h4';
            $attr['layout'] = sanitize_html_class( $attr['layout'] );
            $attr['writtenByText'] = wp_kses($attr['writtenByText'], ultimate_post()->ultp_allowed_html_tags());

            $_post = get_post( $page_post_id );
            $post_author = get_userdata( $_post->post_author );

            // Author Image
            $author_image = '<div class="ultp-post-author-image-section">';
            $author_image .= get_avatar($post_author->ID, $attr['imgRatio']);
            $author_image .= '</div>';
            
            // Author Meta
            $author_meta = '<div class="ultp-post-author-meta">';
            $author_meta .= '<span class="ultp-total-post">' . count_user_posts($_post->post_author, $post_type = 'post') . ' Posts</span>';
            $author_meta .= '<span class="ultp-total-comment">' . get_comments_number($page_post_id) . ' Comments</span>';
            $author_meta .= '</div>';

            // Author Description
            if ($post_author->description) {
                $author_bio .= '<div class="ultp-post-author-bio">';
                $author_bio .= '<span class="ultp-post-author-bio-meta">' . $post_author->description . '</span>';
                $author_bio .= '</div>';
            }

            // Author Url
            if (get_author_posts_url($_post->post_author)) {
                $all_post_link = '<div class="ultp-author-post-link">';
                $all_post_link .= '<a class="ultp-author-post-link-text" href="'.get_author_posts_url( $_post->post_author ).'">'.$attr['viewAllPostText'].'</a>';
                $all_post_link .= '</div>';
            }
            
            $wrapper_before .= '<div '.( $attr['advanceId'] ? 'id="'.$attr['advanceId'].'" ':'' ).' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].( $attr["className"] ?' '.$attr["className"]:'' ).''.( $attr["align"] ? ' align' .$attr["align"]:'' ).'">';
                $wrapper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<div class="ultp-author-box ultp-author-box-'.$attr["layout"].'-content">';
                        $content .= ($attr['imgShow'] && $attr['layout'] !== 'layout4' ? $author_image : '');
                            $content .= '<div class="ultp-post-author-details">';
                                $content .= '<div class="ultp-post-author-title">';
                                    $content .= $attr["writtenByShow"] ? '<span class="ultp-post-author-written-by">'.$attr["writtenByText"].'</span>' : '';
                                    $content .= '<'.$attr['authorNameTag'].' class="ultp-post-author-name"><a href="'.get_author_posts_url( $_post->post_author ).'">'.$post_author->display_name.'</a></'.$attr['authorNameTag'].'>';
                                $content .= '</div>';
                                

                                $content .= ($attr["metaShow"] && $attr["metaPosition"] == 'top' ? $author_meta : '');
                                
                                
                                $content .= ($attr["authorBioShow"] && $author_bio  ? $author_bio : '');
                                $content .= ($attr["metaShow"] && $attr["metaPosition"] == 'bottom' ? $author_meta : '');
                                $content .= ($attr["allPostLinkShow"] ? $all_post_link : '');
                            $content .= '</div>';
                            $content .= ($attr['imgShow'] && $attr['layout'] === 'layout4' ? $author_image : '');
                        $content .= '</div>';
                $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';
        }

        return $wrapper_before.$content.$wrapper_after;
    }
}