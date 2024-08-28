<?php
/**
 * Plugin Name: PostX
 * Description: <a href="https://www.wpxpo.com/postx/?utm_source=db-postx-plugin&utm_medium=details&utm_campaign=postx-dashboard">PostX</a> is the #1 Gutenberg Blocks plugin with 38+ free blocks that includes post gird, post list, post slider, carousel, news ticker, etc. Advanced capabilities like dynamic site building and design variations make it the best choice for creating News Magazine sites, and any kind of blog such as Personal Blogs, Travel Blogs, Fashion Blogs, Food Reviews, Recipe Blogs, etc.
 * Version:     4.1.5
 * Author:      Post Grid Team by WPXPO
 * Author URI:  https://www.wpxpo.com/postx/?utm_source=db-postx-plugin&utm_medium=details&utm_campaign=postx-dashboard
 * Text Domain: ultimate-post
 * License:     GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

defined( 'ABSPATH' ) || exit;

// Define
define('ULTP_VER', '4.1.5');
define('ULTP_URL', plugin_dir_url(__FILE__));
define('ULTP_BASE', plugin_basename(__FILE__));
define('ULTP_PATH', plugin_dir_path(__FILE__));
define( 'ULTP_HELLOBAR', '414' );


// Language Load
add_action( 'init', 'ultp_language_load' );
function ultp_language_load() {
    // template section wrapped inside a hook for netwrok site compatibility
    // Template
    require_once ULTP_PATH . 'classes/Templates.php';
    new \ULTP\Templates();
    load_plugin_textdomain( 'ultimate-post', false, basename(dirname(__FILE__))."/languages/" );
}

// Common Function
if ( ! function_exists( 'ultimate_post' ) ) {
    function ultimate_post() {
        require_once ULTP_PATH . 'classes/Functions.php';
        return \ULTP\Functions::get_instance();
    }
}

// Plugin Initialization
if ( ! class_exists( 'ULTP_Initialization' ) ) {
    require_once ULTP_PATH . 'classes/Initialization.php';
    new \ULTP\ULTP_Initialization();
}