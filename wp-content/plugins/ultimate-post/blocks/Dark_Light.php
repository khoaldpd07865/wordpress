<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Dark_Light {
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes() {

        return array(
            'blockId' => '',
            'advanceId' => '',
            'layout' => 'layout1',
            'reverseSwitcher' => false,
            'enableText' => false,
            'textAppears' => 'both',
            'lightText' => 'Light Mode',
            'darkText' => 'Dark Mode',
            'iconType' => 'solid',
            'iconSize' => '24',
        );
    }

    public function register() {
        register_block_type( 'ultimate-post/dark-light',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function content($attr) {
        if ( ultimate_post()->is_lc_active() ) {
            $attr = wp_parse_args($attr, $this->get_attributes());

            $attr['className'] = isset($attr['className']) && $attr['className'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
            $attr['align'] = isset($attr['align']) && $attr['align'] ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
            $layout = isset($attr['layout']) ? sanitize_html_class( $attr['layout'] ) : 'layout1'; 
            $reverseSwitcher = isset($attr['reverseSwitcher']) && $attr['reverseSwitcher'] ? 'ultp-dl-reverse' : ''; 
            $enableText = isset($attr['enableText']) ? $attr['enableText'] == true : false; 
            $textAppears = isset($attr['textAppears']) ? sanitize_html_class($attr['textAppears']) : 'both'; 
            $iconType = isset($attr['iconType']) ? sanitize_html_class($attr['iconType']) : 'solid';
            $iconSize = isset($attr['iconSize']) ? sanitize_html_class($attr['iconSize']) : '24';

            $allowed_html_tags = ultimate_post()->ultp_allowed_html_tags();
            $lightText = isset($attr['lightText']) ? wp_kses($attr["lightText"], $allowed_html_tags) : 'Light Mode'; 
            $darkText = isset($attr['darkText']) ? wp_kses($attr["darkText"], $allowed_html_tags) : 'Dark Mode'; 
            $dlMode = isset($_COOKIE['ultplocalDLMode']) ? $_COOKIE['ultplocalDLMode'] : ultimate_post()->get_dl_mode();

            $dlIcons = array();
            $dlIcons['moon'] = '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22 14.27A10.14 10.14 0 1 1 9.73 2 8.84 8.84 0 0 0 22 14.27Z"/></svg>';
            $dlIcons['moon_line'] = '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M8.17 4.53A9.54 9.54 0 0 0 19.5 15.69a8.26 8.26 0 0 1-7.76 4.29 8.36 8.36 0 0 1-7.71-7.7 8.23 8.23 0 0 1 4.15-7.76m1-2.52c-.16 0-.32.03-.48.09a10.28 10.28 0 0 0 3.56 19.9c4.47 0 8.27-2.85 9.67-6.84a1.36 1.36 0 0 0-1.27-1.82c-.15 0-.31.03-.47.1a7.48 7.48 0 0 1-3.41.43 7.59 7.59 0 0 1-6.33-10.04A1.36 1.36 0 0 0 9.17 2Z" /></svg>';
            $dlIcons['sun'] = '<svg viewBox="0 0 24 24" ><g><path d="M12 18.36a6.36 6.36 0 1 0 0-12.72 6.36 6.36 0 0 0 0 12.72ZM12.98.96V2.8c0 .53-.43.95-.97.95h-.02a.96.96 0 0 1-.97-.95V.96c0-.53.43-.96.96-.96h.05c.53 0 .96.43.96.96ZM4.89 3.5l1.3 1.3c.38.38.37.98 0 1.36h-.01l-.01.02a.96.96 0 0 1-1.37 0l-1.3-1.3a.96.96 0 0 1 0-1.35l.04-.04a.96.96 0 0 1 1.35 0ZM.96 11.02H2.8c.53 0 .95.43.95.97v.02c0 .53-.42.97-.95.97H.96a.95.95 0 0 1-.96-.96v-.05c0-.53.43-.96.96-.96ZM3.5 19.11l1.3-1.3a.96.96 0 0 1 1.36 0v.01l.02.01c.38.38.39.99 0 1.37l-1.3 1.3a.96.96 0 0 1-1.35 0l-.04-.04a.96.96 0 0 1 0-1.35ZM11.02 23.04V21.2c0-.53.43-.95.97-.95h.02c.53 0 .97.42.97.95v1.84c0 .53-.43.96-.96.96h-.05a.95.95 0 0 1-.96-.96ZM19.11 20.5l-1.3-1.3a.96.96 0 0 1 0-1.36h.01l.01-.02a.96.96 0 0 1 1.37 0l1.3 1.3c.38.37.38.98 0 1.35l-.04.04a.96.96 0 0 1-1.35 0ZM23.04 12.98H21.2a.96.96 0 0 1-.95-.97v-.02c0-.53.42-.97.95-.97h1.84c.53 0 .96.43.96.96v.05c0 .53-.43.96-.96.96ZM20.5 4.89l-1.3 1.3a.96.96 0 0 1-1.36 0v-.01l-.02-.01a.96.96 0 0 1 0-1.37l1.3-1.3a.96.96 0 0 1 1.35 0l.04.04c.37.37.37.98 0 1.35Z"/></g><defs></defs></svg>';
            $dlIcons['sun_line'] = '<svg viewBox="0 0 24 24"><path d="M12 7.64a4.36 4.36 0 1 1-.01 8.73A4.36 4.36 0 0 1 12 7.64Zm0-2a6.35 6.35 0 1 0 0 12.71 6.35 6.35 0 0 0 0-12.7ZM12.98.96V2.8c0 .53-.43.96-.96.96h-.03a.96.96 0 0 1-.97-.96V.96c0-.53.43-.96.96-.96h.06c.52 0 .95.43.95.96ZM4.88 3.5l1.3 1.3c.38.38.38.98 0 1.36h-.01l-.01.02a.96.96 0 0 1-1.36.01L3.5 4.9a.96.96 0 0 1 0-1.35l.03-.04a.96.96 0 0 1 1.35 0ZM.96 11.02H2.8c.53 0 .96.43.96.96v.03c0 .53-.42.97-.96.97H.96a.96.96 0 0 1-.96-.96v-.06c0-.52.43-.95.96-.95ZM3.5 19.12l1.3-1.3a.96.96 0 0 1 1.38.02c.38.38.39.99.01 1.36l-1.3 1.3a.96.96 0 0 1-1.35 0l-.04-.03a.96.96 0 0 1 0-1.35ZM11.02 23.04V21.2c0-.53.43-.96.96-.96h.03c.53 0 .97.42.97.96v1.84c0 .53-.43.96-.96.96h-.06a.96.96 0 0 1-.95-.96ZM19.12 20.5l-1.3-1.3a.96.96 0 0 1 0-1.36h.01l.01-.02a.96.96 0 0 1 1.36-.01l1.3 1.3c.38.37.38.98 0 1.35l-.03.04a.96.96 0 0 1-1.35 0ZM23.04 12.98H21.2a.96.96 0 0 1-.96-.96v-.03c0-.53.42-.97.96-.97h1.84c.53 0 .96.43.96.96v.06c0 .52-.43.95-.96.95ZM20.5 4.88l-1.3 1.3a.96.96 0 0 1-1.36 0v-.01l-.02-.01a.96.96 0 0 1-.01-1.36l1.3-1.3a.96.96 0 0 1 1.35 0l.04.03c.38.37.38.98 0 1.35Z" /></svg>';


            $wraper_before = '';
            $block_name = 'dark-light';

            $wraper_before .= '<div '.($attr['advanceId'] ? 'id="'.sanitize_html_class($attr['advanceId']).'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.sanitize_html_class($attr["blockId"]).''.( $attr["align"] ? ' align' .$attr["align"] :'').''.($attr["className"] ? ' '.$attr["className"]:'').'">';
                $wraper_before .= '<div class="ultp-dark-light-block-wrapper ultp-block-wrapper '.$layout.'">';
                    ob_start();
                    ?>
                        <div class="ultp-dark-light-block-wrapper-content ultp-frontend <?php esc_attr_e($layout) ?>">
                            <div class="ultp-dl-after-before-con ultp-dl-light <?php echo $dlMode == 'ultplight' ? '' : 'inactive' ?> <?php esc_attr_e($reverseSwitcher) ?>" data-iconlay="<?php esc_attr_e($layout) ?>" data-iconsize="<?php esc_attr_e($iconSize) ?>" data-iconrev="<?php esc_attr_e($reverseSwitcher) ?>">
                                <?php if ( $enableText && $layout != 'layout7' && in_array($textAppears, ['left', 'both'] ) ) : ?>
                                    <div class="ultp-dl-before-after-text lightText"><?php echo  $lightText; ?></div>
                                <?php endif; ?>
                                <div class="ultp-dl-con ultp-light-con <?php esc_attr_e($reverseSwitcher) ?>">
                                    <div class="ultp-dl-svg-con">
                                        <div class="ultp-dl-svg"><?php echo $dlIcons[$iconType == 'line' ? 'sun_line' : 'sun'] ?></div>
                                    </div>
                                    <?php if ( in_array( $layout, ['layout5', 'layout6', 'layout7'] ) ) : ?>
                                        <div class="ultp-dl-text lightText">
                                            <?php if ( in_array($layout, ['layout5', 'layout6'] ) ) : ?>
                                                <div class="ultp-dl-democircle <?php esc_attr_e($layout == 'layout5' ? 'ultphidden' : '')?>"></div>
                                            <?php endif; ?>
                                            <?php if (  $layout == 'layout7' ) : ?>
                                                <?php echo $lightText; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ( $enableText && $layout != 'layout7' && in_array($textAppears, ['right', 'both'] ) ) : ?>
                                    <div class="ultp-dl-before-after-text <?php esc_attr_e($textAppears != 'both' ? 'lightText' : 'darkText')?>"><?php echo $textAppears != 'both' ? $lightText : $darkText; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="ultp-dl-after-before-con ultp-dl-dark <?php echo $dlMode == 'ultplight' ? 'inactive' : '' ?> <?php esc_attr_e($reverseSwitcher) ?>" data-iconlay="<?php esc_attr_e($layout) ?>" data-iconsize="<?php esc_attr_e($iconSize) ?>" data-iconrev="<?php esc_attr_e($reverseSwitcher) ?>">
                                <?php if ( $enableText && $layout != 'layout7' && in_array($textAppears, ['left', 'both'] ) ) : ?>
                                    <div class="ultp-dl-before-after-text <?php esc_attr_e($textAppears != 'both' ? 'darkText' : 'lightText')?>"><?php echo $textAppears != 'both' ? $darkText : $lightText; ?></div>
                                <?php endif; ?>
                                <div class="ultp-dl-con ultp-dark-con <?php esc_attr_e($reverseSwitcher) ?>">
                                    <?php if ( in_array( $layout, ['layout5', 'layout6', 'layout7'] ) ) : ?>
                                        <div class="ultp-dl-text darkText">
                                            <?php if ( in_array($layout, ['layout5', 'layout6'] ) ) : ?>
                                                <div class="ultp-dl-democircle <?php esc_attr_e($layout == 'layout5' ? 'ultphidden' : '')?>"></div>
                                            <?php endif; ?>
                                            <?php if (  $layout == 'layout7' ) : ?>
                                                <?php echo $darkText; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="ultp-dl-svg-con">
                                        <div class="ultp-dl-svg"><?php echo $dlIcons[$iconType == 'line' ? 'moon_line' : 'moon'] ?></div>
                                    </div>
                                </div>
                                <?php if ( $enableText && $layout != 'layout7' && in_array($textAppears, ['right', 'both'] ) ) : ?>
                                    <div class="ultp-dl-before-after-text darkText"><?php echo $darkText; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    $wraper_before .= ob_get_clean();
                $wraper_before .= '</div>';
            $wraper_before .= '</div>';

            return $wraper_before;
        } 
    }
}