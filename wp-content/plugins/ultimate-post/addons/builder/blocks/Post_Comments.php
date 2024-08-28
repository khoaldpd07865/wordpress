<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Comments{
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes() {

        return array(
            'blockId' => '',
            'layout' => 'layout1',
            /*============================
                Post Comment Settings
            ============================*/
            //  Comments Form Heading
            'replyHeading' => true,
            'leaveRepText' => 'Leave a Reply',
            
            /*============================
                Comments Form Input
            ============================*/
            "inputPlaceHolder" => "Express your thoughts, idea or write a feedback by clicking here & start an awesome comment",
            
            /*============================
                Comments Form label style
            ============================*/
            'inputLabel' => true,
            "cmntInputText" => "Comment's",
            "nameInputText" => "Name",
            "emailInputText" => "Email",
            "webInputText" => "Website Url",
            'cookiesEnable' => true,
            'cookiesText' => "Save my name, email, and website in this browser for the next time I comment.",
            
            /*============================
                Submit Button Style
            ============================*/
            'subBtnText' => 'Post Comment',

            /*============================
                Comment Reply Style
            ============================*/
            // Title and total Comment Count
            'replyText' => 'Comments Text',
            'commentCount' => true,
    
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
        register_block_type( 'ultimate-post/post-comments',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'=> 'ultp-blocks-editor-css',
                'render_callback' => array($this, 'content')
            )
        );    
    }    
    
    public function content($attr, $noAjax) {
        $attr = wp_parse_args($attr, $this->get_attributes());
        $block_name = 'post-comments';
        $wrapper_before = $wrapper_after = $wrapper_content = '';

        if(is_single()){

            $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
            $attr['align'] = isset($attr['align']) && $attr['align'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
            $attr['advanceId'] = isset($attr['advanceId']) ? sanitize_html_class( $attr['advanceId'] ) : '';
            $attr['blockId'] = isset($attr['blockId']) ? sanitize_html_class( $attr['blockId'] ) : '';
            $attr['layout'] = sanitize_html_class( $attr['layout'] );
            
            $allowed_html_tags = ultimate_post()->ultp_allowed_html_tags();
            $attr['nameInputText'] = wp_kses($attr['nameInputText'], $allowed_html_tags);
            $attr['emailInputText'] = wp_kses($attr['emailInputText'], $allowed_html_tags);
            $attr['webInputText'] = wp_kses($attr['webInputText'], $allowed_html_tags);
            $attr['cmntInputText'] = wp_kses($attr['cmntInputText'], $allowed_html_tags);
            $attr['cookiesText'] = wp_kses($attr['cookiesText'], $allowed_html_tags);
            $attr['inputPlaceHolder'] = wp_kses($attr['inputPlaceHolder'], $allowed_html_tags);
            $attr['subBtnText'] = wp_kses($attr['subBtnText'], $allowed_html_tags);
            $attr['leaveRepText'] = wp_kses($attr['leaveRepText'], $allowed_html_tags);
            $attr['replyText'] = wp_kses($attr['replyText'], $allowed_html_tags);

            
            $commenter = wp_get_current_commenter();
            $req = get_option( 'require_name_email' );
            $aria_req = ( $req ? " aria-required='true'" : '' );

            $auth_label = $attr['inputLabel'] ? '<label for="author">' . __( ''.$attr["nameInputText"].'' ) . '' .( $req ? '<span class="required">*</span>' : '' )  . '</label>' : '';
            $email_label = $attr['inputLabel'] ? '<label for="email">' . __( ''.$attr["emailInputText"].'' ) . '' . ( $req ? '<span class="required">*</span>' : '' ).'</label>'  : '';
            $url_label = $attr['inputLabel'] ? '<label for="url">' . __( ''.$attr["webInputText"].'', 'domainreference' ) . '</label>' : '';
            $comment_label = $attr['inputLabel'] ? '<label for="comment">' . __( ''.$attr["cmntInputText"].'' ) . '</label>' : '';
            $cookies_label = $attr['cookiesEnable'] ? '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"/><label for="wp-comment-cookies-consent">'.$attr["cookiesText"].'</label></p>' : '';

            $comments_args = array(
                'comment_field' => '<div class="comment-form-comment ultp-comment-input ultp-field-control">' .$comment_label.'<textarea class="hi" id="comment" name="comment" placeholder="'.$attr["inputPlaceHolder"].'" cols="45" rows="8" aria-required="true"></textarea></div>',
                'fields' => apply_filters( 'comment_form_default_fields', array(
                        'author' =>'<div class="ultp-field-control ultp-comment-name">'.$auth_label.'<input id="author" placeholder="Your Name (No Keywords)" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
                        'email'=> '<div class="ultp-field-control ultp-comment-email">'.$email_label.'<input id="email" placeholder="your-real-email@example.com" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ).'" size="30"' . $aria_req . ' /></div>',
                        'url' => '<div class="ultp-field-control ultp-comment-website">'.$url_label.'<input id="url" name="url" placeholder="http://your-site-name.com" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>',
                        'cookies'=> $cookies_label
                    )
                ),
                'class_submit'        => 'ultp-comment-btn',
                'comment_notes_after' => '',
                'submit_button'       => '<input name="%1$s" type="submit" id="%2$s" className="%3$s ultp-comment-btn" value="'.$attr['subBtnText'].'" />',
                'class_form'          => 'ultp-comment-form',
                'title_reply'         => ($attr['replyHeading'] ? '<div class="crunchify-text ultp-comments-title">'.$attr['leaveRepText'].'</div>' : ''),
                'class_container'     => 'ultp-comment-form-container'
            );

            $arg = array(
                'walker'            => null,
                'max_depth'         => '',
                'style'             => 'ul',
                'callback'          => null,
                'end-callback'      => null,
                'type'              => 'comment',
                'page'              => '',
                'per_page'          => '',
                'avatar_size'       => 32,
                'reverse_top_level' => true,
                'reverse_children'  => '',
                'format'            => current_theme_supports( 'html5', 'comment-list' ) ? 'html5' : 'xhtml',
                'short_ping'        => false,
                'echo'              => true,
            );

            $comments = get_comments(array( 'post_id' => get_the_ID() ));

            $wrapper_before .= '<div '.($attr['advanceId'] ? 'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].( $attr["className"] ?' '.$attr["className"]:'').''.( $attr["align"] ? ' align' .$attr["align"]:'').'">';
                $wrapper_before .= '<div class="ultp-block-wrapper  ultp-block-comments ultp-comments-'.$attr['layout'].'">';
                    if ($attr["commentCount"] && count($comments) > 0) {
                        $wrapper_content .= '<div class="ultp-comment-reply-heading">';    
                            $wrapper_content .= count($comments).' '.$attr['replyText'];
                        $wrapper_content .= '</div>';
                    }
                    $wrapper_content.= '<div class="ultp-builder-comment-reply">';
                        ob_start();
                        wp_list_comments($arg, $comments);
                        $wrapper_content .=  ob_get_clean();                
                    $wrapper_content .= '</div>';
                    $wrapper_content .= '<div class="ultp-builder-comments">';
                        ob_start();
                        comment_form( $comments_args );
                        $wrapper_content .= ob_get_clean();
                    $wrapper_content .= '</div>';
                $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';
        }

        return $wrapper_before.$wrapper_content.$wrapper_after;
    }
}