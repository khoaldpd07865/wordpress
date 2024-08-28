<?php
/**
 * Template Action.
 * 
 * @package ULTP\Templates
 * @since v.1.0.0
 */
namespace ULTP;

defined( 'ABSPATH' ) || exit;

/**
 * Templates class.
 */
class Templates {

  	/**
	 * Setup class.
	 *
	 * @since v.1.0.0
	 */
    public function __construct() {
        add_filter( 'template_include', array( $this, 'set_template_callback' ) );
		add_filter( 'theme_page_templates', array( $this, 'add_template_callback' ) );
    }

    /**
	 * Include Template File
     * 
     * @since v.1.0.0
     * @param STRING | Attachment 
	 * @return STRING | Template File Path
	 */
    public function set_template_callback( $template ) {
		if ( is_singular() ) {
			global $post;
            if ( get_post_meta( $post->ID, '_wp_page_template', true ) === 'ultp_page_template' ) {
                $template = ULTP_PATH . 'classes/template-without-title.php';
            }
		}
		return $template;
    }

	/**
	 * Add A Page Template
     * 
     * @since v.1.0.0
     * @param ARRAY | Page Template List
	 * @return ARRAY | Page Template List as Array
	 */
    public function add_template_callback( $templates ) {
		$templates['ultp_page_template'] = __( 'PostX Template (Without Title)', 'ultimate-post' );
		return $templates;
	}
}