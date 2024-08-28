
<table class="ms-settings-table border-0">
    <tbody>
            <?php
            $aFields = array(
                'type' => array(
                    'priority' => 0,
                    'type' => 'slider-lib',
                    'value' => $this->slider->get_setting('type'),
                    'options' => array(
                        'flex' => array('label' => "FlexSlider"),
                        'responsive' => array('label' => "R. Slides"),
                        'nivo' => array('label' => "Nivo Slider"),
                        'coin' => array('label' => "Coin Slider")
                    )
                ),
                'mainOptions' => array(
                    'priority' => 1,
                    'type' => 'highlight',
                    'value' => esc_html__( 'Main Options', 'ml-slider' )
                ),
                'width' => array(
                    'priority' => 10,
                    'type' => 'number',
                    'size' => 3,
                    'min' => 0,
                    'max' => 9999,
                    'step' => 1,
                    'value' => $this->slider->get_setting('width'),
                    'label' => __("Width", "ml-slider"),
                    'class' => 'coin flex responsive nivo',
                    'helptext' => __("Slideshow width", "ml-slider"),
                    'after' => __("px", "ml-slider")
                ),
                'height' => array(
                    'priority' => 20,
                    'type' => 'number',
                    'size' => 3,
                    'min' => 0,
                    'max' => 9999,
                    'step' => 1,
                    'value' => $this->slider->get_setting('height'),
                    'label' => __("Height", "ml-slider"),
                    'class' => 'coin flex responsive nivo',
                    'helptext' => __("Slideshow height", "ml-slider"),
                    'after' => __("px", "ml-slider")
                ),
                'effect' => array(
                    'priority' => 30,
                    'type' => 'select',
                    'value' => $this->slider->get_setting('effect'),
                    'label' => __("Transition Effect", "ml-slider"),
                    'class' => 'effect coin flex responsive nivo width w-40',
                    'helptext' => __("This animation is used when changing slides.", "ml-slider"),
                    'dependencies' => array(
                        array(
                            'show' => 'easing', // Show Easing setting
                            'when' => 'slide' // When Effect is 'slide'
                        ),
                        array(
                            'show' => 'firstSlideFadeIn',
                            'when' => 'fade'
                        )
                    ),
                    'options' => array(
                        'random' => array(
                            'class' => 'option coin nivo',
                            'label' => __("Random", "ml-slider")
                        ),
                        'swirl' => array(
                            'class' => 'option coin',
                            'label' => __("Swirl", "ml-slider")
                        ),
                        'rain' => array(
                            'class' => 'option coin',
                            'label' => __("Rain", "ml-slider")
                        ),
                        'straight' => array(
                            'class' => 'option coin',
                            'label' => __("Straight", "ml-slider")
                        ),
                        'sliceDown' => array(
                            'class' => 'option nivo',
                            'label' => __("Slice Down", "ml-slider")
                        ),
                        'sliceUp' => array(
                            'class' => 'option nivo',
                            'label' => __("Slice Up", "ml-slider")
                        ),
                        'sliceUpLeft' => array(
                            'class' => 'option nivo',
                            'label' => __("Slice Up Left", "ml-slider")
                        ),
                        'sliceUpDown' => array(
                            'class' => 'option nivo',
                            'label' => __("Slide Up Down", "ml-slider")
                        ),
                        'sliceUpDownLeft' => array(
                            'class' => 'option nivo',
                            'label' => __("Slice Up Down Left", "ml-slider")
                        ),
                        'fade' => array(
                            'class' => 'option nivo flex responsive',
                            'label' => __("Fade", "ml-slider")
                        ),
                        'fold' => array(
                            'class' => 'option nivo',
                            'label' => __("Fold", "ml-slider")
                        ),
                        'slideInRight' => array(
                            'class' => 'option nivo',
                            'label' => __("Slide in Right", "ml-slider")
                        ),
                        'slideInLeft' => array(
                            'class' => 'option nivo',
                            'label' => __("Slide in Left", "ml-slider")
                        ),
                        'boxRandom' => array(
                            'class' => 'option nivo',
                            'label' => __("Box Random", "ml-slider")
                        ),
                        'boxRain' => array(
                            'class' => 'option nivo',
                            'label' => __("Box Rain", "ml-slider")
                        ),
                        'boxRainReverse' => array(
                            'class' => 'option nivo',
                            'label' => __("Box Rain Reverse", "ml-slider")
                        ),
                        'boxRainGrow' => array(
                            'class' => 'option nivo',
                            'label' => __("Box Rain Grow", "ml-slider")
                        ),
                        'boxRainGrowReverse' => array(
                            'class' => 'option nivo',
                            'label' => __("Box Rain Grow Reverse", "ml-slider")
                        ),
                        'slide' => array(
                            'class' => 'option flex',
                            'label' => __("Slide", "ml-slider")
                        )
                    ),
                ),
                'links' => array(
                    'priority' => 50,
                    'type' => 'checkbox',
                    'label' => __("Arrows", "ml-slider"),
                    'class' => 'option coin flex nivo responsive',
                    'checked' => $this->slider->get_setting(
                        'links'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => __(
                        "Show the previous/next arrows",
                        "ml-slider"
                    ),
                    'dependencies' => array(
                        array(
                            'show' => 'mobileArrows_smartphone',
                            'when' => true
                        )
                    )
                ),
                'navigation' => array(
                    'priority' => 60,
                    'type' => 'navigation',
                    'label' => __("Navigation", "ml-slider"),
                    'class' => 'option coin flex nivo responsive',
                    'value' => $this->slider->get_setting('navigation'),
                    'helptext' => __(
                        "Show the slide navigation bullets",
                        "ml-slider"
                    ),
                    'options' => array(
                        'false' => array(
                            'label' => __("Hidden", "ml-slider")
                        ),
                        'true' => array(
                            'label' => __("Dots", "ml-slider")
                        ),
                        'thumbs' => array(
                            'label' => __("Thumbnail", "ml-slider"),
                            'addon_required' => true
                        ),
                        'filmstrip' => array(
                            'label' => __("Filmstrip", "ml-slider"),
                            'addon_required' => true
                        ),
                    ),
                    'dependencies' => array(
                        array(
                            'show' => 'mobileNavigation_smartphone',
                            'when' => array(
                                'true',
                                'thumbs', 
                                'filmstrip'
                            )
                        )
                    )
                ),
                'fullWidth' => array(
                    'priority' => 570,
                    'type' => 'checkbox',
                    'label' => esc_html__("100% width", "ml-slider"),
                    'class' => 'option flex nivo responsive',
                    'checked' => $this->slider->get_setting(
                        'fullWidth'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => esc_html__(
                        "Stretch the slideshow output to fill it's parent container",
                        "ml-slider"
                    )
                ),
            );

            $aFields = apply_filters(
                'metaslider_basic_settings',
                $aFields,
                $this->slider
            );

            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $this->build_settings_rows($aFields);
            ?>
            
            <tr class="empty-row-spacing">
                <td colspan="2"></td>
            </tr>
            <tr class="highlight">
                <td colspan="2">
                    <?php esc_html_e( 'Theme', 'ml-slider' ) ?>
                </td>
            </tr>
            <tr class="empty-row-spacing">
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <metaslider-theme-viewer
                            theme-directory-url="<?php
                            echo esc_url(METASLIDER_THEMES_URL); ?>"
                    ></metaslider-theme-viewer>
                </td>
            </tr>
            <tr class="empty-row-spacing">
                <td colspan="2"></td>
            </tr>
            
<?php
    // Mobile options
    if ( !isset( $global_settings['mobileSettings'] ) 
        || ( isset( $global_settings['mobileSettings'] ) && true == $global_settings['mobileSettings'] )  
    ) {
        $default_settings   = get_site_option( 'metaslider_default_settings' );
        $breakpoints = array(
            'smartphone' => isset( $default_settings['smartphone'] ) ? (int) $default_settings['smartphone'] : 320,
            'tablet' => isset( $default_settings['tablet'] ) ? (int) $default_settings['tablet'] : 768,
            'laptop' => isset( $default_settings['laptop'] ) ? (int) $default_settings['laptop'] : 1024,
            'desktop' => isset( $default_settings['desktop'] ) ? (int) $default_settings['desktop'] : 1440
        );

        $aFields = array(
            'advancedOptions' => array(
                'priority' => 0,
                'type' => 'highlight',
                'value' => esc_html__( 'Mobile Options', 'ml-slider' )
            ),
            'mobileArrows' => array(
                'priority' => 1,
                'type' => 'mobile',
                'label' => __("Hide arrows on", "ml-slider"),
                'options' => array(
                    'smartphone' => array(
                        'checked' => $this->slider->get_setting('mobileArrows_smartphone') == 'true' ? 'checked' : '',
                        'helptext' => sprintf( 
                            __( 
                                'When enabled this setting will hide the arrows on screen widths less than %spx', 
                                'ml-slider'
                            ), 
                            $breakpoints['tablet'] 
                        )
                    ),
                    'tablet' => array(
                        'checked' => $this->slider->get_setting('mobileArrows_tablet') == 'true' ? 'checked' : '',
                        'helptext' => sprintf( 
                            __( 
                                'When enabled this setting will hide the arrows on screen widths of %1$spx to %2$spx', 
                                'ml-slider'
                            ), 
                            $breakpoints['tablet'],
                            $breakpoints['laptop'] - 1
                        )
                    ),
                    'laptop' => array(
                        'checked' => $this->slider->get_setting('mobileArrows_laptop') == 'true' ? 'checked' : '',
                        'helptext' => sprintf( 
                            __( 
                                'When enabled this setting will hide the arrows on screen widths of %1$spx to %2$spx', 
                                'ml-slider'
                            ), 
                            $breakpoints['laptop'],
                            $breakpoints['desktop'] - 1
                        )
                    ),
                    'desktop' => array(
                        'checked' => $this->slider->get_setting('mobileArrows_desktop') == 'true' ? 'checked' : '',
                        'helptext' => sprintf( 
                            __( 
                                'When enabled this setting will hide the arrows on screen widths equal to or greater than %spx', 
                                'ml-slider'
                            ), 
                            $breakpoints['desktop'] 
                        )
                    ),
                ),
            ),
            'mobileNavigation' => array(
                'priority' => 2,
                'type' => 'mobile',
                'label' => __("Hide navigation on", "ml-slider"),
                'options' => array(
                    'smartphone' => array(
                        'checked' => $this->slider->get_setting('mobileNavigation_smartphone') == 'true' ? 'checked' : '',
                        'helptext' => sprintf( 
                            __( 
                                'When enabled this setting will hide the navigation on screen widths less than %spx', 
                                'ml-slider'
                            ), 
                            $breakpoints['tablet'] 
                        )
                    ),
                    'tablet' => array(
                        'checked' => $this->slider->get_setting('mobileNavigation_tablet') == 'true' ? 'checked' : '',
                        'helptext' => sprintf( 
                            __( 
                                'When enabled this setting will hide the navigation on screen widths of %1$spx to %2$spx', 
                                'ml-slider'
                            ), 
                            $breakpoints['tablet'],
                            $breakpoints['laptop'] - 1
                        )
                    ),
                    'laptop' => array(
                        'checked' => $this->slider->get_setting('mobileNavigation_laptop') == 'true' ? 'checked' : '',
                        'helptext' => sprintf( 
                            __( 
                                'When enabled this setting will hide the navigation on screen widths of %1$spx to %2$spx', 
                                'ml-slider'
                            ), 
                            $breakpoints['laptop'],
                            $breakpoints['desktop'] - 1
                        )
                    ),
                    'desktop' => array(
                        'checked' => $this->slider->get_setting('mobileNavigation_desktop') == 'true' ? 'checked' : '',
                        'helptext' => sprintf( 
                            __( 
                                'When enabled this setting will hide the navigation on screen widths equal to or greater than %spx', 
                                'ml-slider'
                            ), 
                            $breakpoints['desktop'] 
                        )
                    ),
                )
            ),
        );
        $aFields = apply_filters('metaslider_mobile_settings', $aFields, $this->slider);
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $this->build_settings_rows($aFields);

    }
?>
<tr class="empty-row-spacing">
    <td colspan="2"></td>
</tr>
            <?php
            // Advanced options
            $aFields = array(
                'advancedOptions' => array(
                    'priority' => 3,
                    'type' => 'highlight',
                    'value' => esc_html__( 'Advanced Options', 'ml-slider' )
                ),
                'center' => array(
                    'priority' => 10,
                    'type' => 'checkbox',
                    'label' => esc_html__("Center align", "ml-slider"),
                    'class' => 'option coin flex nivo responsive',
                    'checked' => $this->slider->get_setting(
                        'center'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => esc_html__(
                        "Center align the slideshow in the available space on your website.",
                        "ml-slider"
                    )
                ),
                'autoPlay' => array(
                    'priority' => 20,
                    'type' => 'checkbox',
                    'label' => esc_html__("Auto play", "ml-slider"),
                    'class' => 'option flex nivo responsive coin',
                    'checked' => 'true' == $this->slider->get_setting(
                        'autoPlay'
                    ) ? 'checked' : '',
                    'helptext' => esc_html__(
                        "Transition between slides automatically",
                        "ml-slider"
                    ),
                    'dependencies' => array(
                        array(
                            'show' => 'loop', // Show Loop
                            'when' => true // When Auto play is true
                        )
                    )
                ),
                'loop' => array(
                    'priority' => 25,
                    'type' => 'select',
                    'label' => __("Loop", "ml-slider"),
                    'class' => 'option flex nivo',
                    'helptext' => __('If you choose "Continuously", the slides will loop infinitely. If you choose "Stop on first slide", the slideshow will stop on the first slide after showing all the items. If you choose "Stop on last slide", the slides will stop on the last slide.', 'ml-slider'),
                    'value' => $this->slider->get_setting('loop'),
                    'options' => array(
                        'continuously' => array('label' => __("Continuously", "ml-slider"), 'class' => ''),
                        'stopOnLast' => array('label' => __("Stop on Last Slide", "ml-slider"), 'class' => ''),
                        'stopOnFirst' => array('label' => __("Stop on First Slide", "ml-slider"), 'class' => ''),
                    )
                ),
                'smartCrop' => array(
                    'priority' => 30,
                    'type' => 'select',
                    'label' => esc_html__("Image Crop", "ml-slider"),
                    'class' => 'option coin flex nivo responsive',
                    'value' => $this->slider->get_setting('smartCrop'),
                    'options' => array(
                        'true' => array(
                            'label' => esc_html__(
                                "Smart Crop",
                                "ml-slider"
                            ),
                            'class' => ''
                        ),
                        'false' => array(
                            'label' => esc_html__(
                                "Standard",
                                "ml-slider"
                            ),
                            'class' => ''
                        ),
                        'disabled' => array(
                            'label' => esc_html__(
                                "Disabled",
                                "ml-slider"
                            ),
                            'class' => ''
                        ),
                        'disabled_pad' => array(
                            'label' => esc_html__(
                                "Disabled (Smart Pad)",
                                "ml-slider"
                            ),
                            'class' => 'option flex'
                        ),
                    ),
                    'helptext' => esc_html__(
                        "Smart Crop ensures your responsive slides are cropped to a ratio that results in a consistent slideshow size",
                        "ml-slider"
                    )
                ),
                'smoothHeight' => array(
                    'priority' => 35,
                    'type' => 'checkbox',
                    'label' => __("Smooth Height", "ml-slider"),
                    'class' => 'option flex',
                    'checked' => $this->slider->get_setting(
                        'smoothHeight'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => __(
                        "Allow navigation to follow the slide's height smoothly",
                        "ml-slider"
                    )
                ),
                'carouselMode' => array(
                    'priority' => 40,
                    'type' => 'checkbox',
                    'label' => esc_html__("Carousel mode", "ml-slider"),
                    'class' => 'option flex showNextWhenChecked',
                    'checked' => $this->slider->get_setting(
                        'carouselMode'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => esc_html__(
                        "Display multiple slides at once. Slideshow output will be 100% wide. Carousel Mode only uses the 'Slide' Effect.",
                        "ml-slider"
                    ),
                    'dependencies' => array(
                        array(
                            'show' => 'infiniteLoop', // Show Infinite loop
                            'when' => true // When Arrows is true
                        )
                    )
                ),
                'infiniteLoop' => array(
                    'priority' => 43,
                    'type' => 'checkbox',
                    'label' => esc_html__("Loop Continuously (Beta)", "ml-slider"),
                    'class' => 'option flex',
                    'checked' => $this->slider->get_setting(
                        'infiniteLoop'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => esc_html__(
                        "Infinite loop of slides when Carousel Mode is enabled. This option disables arrows and navigation.",
                        "ml-slider"
                    ),
                    'dependencies' => array(
                        array(
                            'show' => 'autoPlay', // Show autoplay
                            'when' => false // When infinte loop is false
                        )
                    )
                ),
                'carouselMargin' => array(
                    'priority' => 45,
                    'min' => 0,
                    'max' => 9999,
                    'step' => 1,
                    'type' => 'number',
                    'label' => esc_html__("Carousel margin", "ml-slider"),
                    'class' => 'option flex',
                    'value' => $this->slider->get_setting('carouselMargin'),
                    'helptext' => esc_html__(
                        "Pixel margin between slides in carousel.",
                        "ml-slider"
                    ),
                    'after' => esc_html__("px", "ml-slider")
                ),
                'firstSlideFadeIn' => array(
                    'priority' => 47,
                    'type' => 'checkbox',
                    'label' => esc_html__("Fade in", "ml-slider"),
                    'class' => 'option flex',
                    'checked' => 'true' == $this->slider->get_setting(
                        'firstSlideFadeIn'
                    ) ? 'checked' : '',
                    'helptext' => esc_html__(
                        'This adds an animation when the slideshow loads. It only uses the "Fade" transition effect',
                        "ml-slider"
                    ),
                ),
                'random' => array(
                    'priority' => 50,
                    'type' => 'checkbox',
                    'label' => esc_html__("Random", "ml-slider"),
                    'class' => 'option coin flex nivo responsive',
                    'checked' => $this->slider->get_setting(
                        'random'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => esc_html__(
                        "Randomise the order of the slides",
                        "ml-slider"
                    )
                ),
                'hoverPause' => array(
                    'priority' => 60,
                    'type' => 'checkbox',
                    'label' => esc_html__("Hover pause", "ml-slider"),
                    'class' => 'option coin flex nivo responsive',
                    'checked' => $this->slider->get_setting(
                        'hoverPause'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => esc_html__(
                        "Pause the slideshow when hovering over slider, then resume when no longer hovering.",
                        "ml-slider"
                    )
                ),
                'reverse' => array(
                    'priority' => 70,
                    'type' => 'checkbox',
                    'label' => esc_html__("Reverse", "ml-slider"),
                    'class' => 'option flex',
                    'checked' => $this->slider->get_setting(
                        'reverse'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => esc_html__(
                        "Reverse the animation direction",
                        "ml-slider"
                    )
                ),
                'touch' => array(
                    'priority' => 80,
                    'type' => 'checkbox',
                    'label' => esc_html__("Touch Swipe", "ml-slider"),
                    'class' => 'option flex',
                    'checked' => 'true' == $this->slider->get_setting(
                        'touch'
                    ) ? 'checked' : '',
                    'helptext' => esc_html__(
                        "Allow touch swipe navigation of the slider on touch-enabled devices",
                        "ml-slider"
                    )
                ),
                'delay' => array(
                    'priority' => 85,
                    'type' => 'number',
                    'size' => 3,
                    'min' => 500,
                    'max' => 10000,
                    'step' => 100,
                    'value' => $this->slider->get_setting('delay'),
                    'label' => esc_html__("Slide delay", "ml-slider"),
                    'class' => 'option coin flex responsive nivo',
                    'helptext' => esc_html__(
                        "How long to display each slide, in milliseconds",
                        "ml-slider"
                    ),
                    'after' => esc_html_x(
                        "ms",
                        "Short for milliseconds",
                        "ml-slider"
                    )
                ),
                'animationSpeed' => array(
                    'priority' => 90,
                    'type' => 'number',
                    'size' => 3,
                    'min' => 0,
                    'max' => 2000,
                    'step' => 100,
                    'value' => $this->slider->get_setting('animationSpeed'),
                    'label' => esc_html__("Transition Speed", "ml-slider"),
                    'class' => 'option flex responsive nivo',
                    'helptext' => esc_html__(
                        'Choose the speed of the animation in milliseconds. You can select the animation in the "Effect" field.',
                        'ml-slider'
                    ),
                    'after' => esc_html_x(
                        "ms",
                        "Short for milliseconds",
                        "ml-slider"
                    )
                ),
                'slices' => array(
                    'priority' => 100,
                    'type' => 'number',
                    'size' => 3,
                    'min' => 0,
                    'max' => 20,
                    'step' => 1,
                    'value' => $this->slider->get_setting('slices'),
                    'label' => esc_html__("Number of slices", "ml-slider"),
                    'class' => 'option nivo',
                    'helptext' => esc_html__("Number of slices", "ml-slider"),
                ),
                'spw' => array(
                    'priority' => 110,
                    'type' => 'number',
                    'size' => 3,
                    'min' => 0,
                    'max' => 20,
                    'step' => 1,
                    'value' => $this->slider->get_setting('spw'),
                    'label' => esc_html__(
                            "Number of squares",
                            "ml-slider"
                        ) . " (" . esc_html__("Width", "ml-slider") . ")",
                    'class' => 'option nivo',
                    'helptext' => esc_html__("Number of squares", "ml-slider"),
                    'after' => ''
                ),
                'sph' => array(
                    'priority' => 120,
                    'type' => 'number',
                    'size' => 3,
                    'min' => 0,
                    'max' => 20,
                    'step' => 1,
                    'value' => $this->slider->get_setting('sph'),
                    'label' => esc_html__(
                            "Number of squares",
                            "ml-slider"
                        ) . " (" . esc_html__("Height", "ml-slider") . ")",
                    'class' => 'option nivo',
                    'helptext' => esc_html__("Number of squares", "ml-slider"),
                    'after' => ''
                ),
                'direction' => array(
                    'priority' => 130,
                    'type' => 'select',
                    'label' => esc_html__("Slide direction", "ml-slider"),
                    'class' => 'option flex',
                    'helptext' => esc_html__(
                        'Select the direction that slides will move. Vertical will not work if "Carousel mode" is enabled or "Effect" is set to "Fade".',
                        'ml-slider'
                    ),
                    'value' => $this->slider->get_setting('direction'),
                    'options' => array(
                        'horizontal' => array(
                            'label' => esc_html__(
                                "Horizontal",
                                "ml-slider"
                            ),
                            'class' => ''
                        ),
                        'vertical' => array(
                            'label' => esc_html__(
                                "Vertical",
                                "ml-slider"
                            ),
                            'class' => ''
                        ),
                    )
                ),
                'easing' => array(
                    'priority' => 140,
                    'type' => 'select',
                    'label' => esc_html__("Easing", "ml-slider"),
                    'class' => 'option flex',
                    'helptext' => esc_html__(
                        "Easing adds gradual acceleration and deceleration to slide transitions, rather than abrupt starts and stops. Easing only uses the 'Slide' Effect.",
                        "ml-slider"
                    ),
                    'value' => $this->slider->get_setting('easing'),
                    'options' => $this->get_easing_options()
                ),
                'prevText' => array(
                    'priority' => 150,
                    'type' => 'text',
                    'label' => esc_html__("Previous text", "ml-slider"),
                    'class' => 'option coin flex responsive nivo',
                    'helptext' => esc_html__(
                        "Set the text for the 'previous' direction item",
                        "ml-slider"
                    ),
                    'value' => $this->slider->get_setting(
                        'prevText'
                    ) == 'false' ? '' : $this->slider->get_setting('prevText')
                ),
                'nextText' => array(
                    'priority' => 160,
                    'type' => 'text',
                    'label' => esc_html__("Next text", "ml-slider"),
                    'class' => 'option coin flex responsive nivo',
                    'helptext' => esc_html__(
                        "Set the text for the 'next' direction item",
                        "ml-slider"
                    ),
                    'value' => $this->slider->get_setting(
                        'nextText'
                    ) == 'false' ? '' : $this->slider->get_setting('nextText')
                ),
                'sDelay' => array(
                    'priority' => 170,
                    'type' => 'number',
                    'size' => 3,
                    'min' => 0,
                    'max' => 500,
                    'step' => 10,
                    'value' => $this->slider->get_setting('sDelay'),
                    'label' => esc_html__("Square delay", "ml-slider"),
                    'class' => 'option coin',
                    'helptext' => esc_html__(
                        "Delay between squares in ms",
                        "ml-slider"
                    ),
                    'after' => esc_html_x(
                        "ms",
                        "Short for milliseconds",
                        "ml-slider"
                    )
                ),
                'opacity' => array(
                    'priority' => 180,
                    'type' => 'text',
                    'value' => $this->slider->get_setting('opacity'),
                    'label' => esc_html__("Opacity", "ml-slider"),
                    'class' => 'option coin',
                    'helptext' => esc_html__(
                        "Opacity of title and navigation, between 0 and 1",
                        "ml-slider"
                    ),
                    'after' => ''
                ),
                'titleSpeed' => array(
                    'priority' => 190,
                    'type' => 'number',
                    'size' => 3,
                    'min' => 0,
                    'max' => 10000,
                    'step' => 100,
                    'value' => $this->slider->get_setting('titleSpeed'),
                    'label' => esc_html__("Caption speed", "ml-slider"),
                    'class' => 'option coin',
                    'helptext' => esc_html__(
                        "Set the fade in speed of the caption",
                        "ml-slider"
                    ),
                    'after' => esc_html_x(
                        "ms",
                        "Short for milliseconds",
                        "ml-slider"
                    )
                ),
                'accessibilityOptions' => array(
                    'priority' => 193,
                    'type' => 'highlight',
                    'value' => esc_html__( 'Accessibility Options', 'ml-slider' ),
                    'topspacing' => true
                ),
                'keyboard' => array(
                    'priority' => 194,
                    'type' => 'checkbox',
                    'label' => esc_html__("Keyboard Controls", "ml-slider"),
                    'class' => 'option coin flex nivo responsive',
                    'checked' => 'true' == $this->slider->get_setting(
                        'keyboard'
                    ) ? 'checked' : '',
                    'helptext' => esc_html__(
                        "Use arrow keys to get to the next slide.",
                        "ml-slider"
                    )
                ),
                'ariaLive' => array(
                    'priority' => 195,
                    'type' => 'checkbox',
                    'label' => esc_html__("ARIA Live", "ml-slider"),
                    'class' => 'option flex',
                    'checked' => $this->slider->get_setting(
                        'ariaLive'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => esc_html__(
                        "If Autoplay is enabled, this causes screen readers to announce that the slides are changing.",
                        "ml-slider"
                    )
                ),
                'tabIndex' => array(
                    'priority' => 196,
                    'type' => 'checkbox',
                    'label' => esc_html__("Tabindex for navigation", "ml-slider"),
                    'class' => 'option flex',
                    'checked' => $this->slider->get_setting(
                        'tabIndex'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => esc_html__(
                        "This helps make the slideshow navigation more accessible.",
                        "ml-slider"
                    )
                ),
                'developerOptions' => array(
                    'priority' => 197,
                    'type' => 'highlight',
                    'value' => esc_html__( 'Developer Options', 'ml-slider' ),
                    'topspacing' => true
                ),
                'cssClass' => array(
                    'priority' => 200,
                    'type' => 'text',
                    'label' => esc_html__("CSS classes", "ml-slider"),
                    'class' => 'option coin flex responsive nivo',
                    'helptext' => esc_html__(
                        "Enter custom CSS classes to apply to the slider wrapper. Separate multiple classes with a space.",
                        "ml-slider"
                    ),
                    'value' => $this->slider->get_setting(
                        'cssClass'
                    ) == 'false' ? '' : $this->slider->get_setting('cssClass')
                ),
                'printCss' => array(
                    'priority' => 210,
                    'type' => 'checkbox',
                    'label' => esc_html__("Print CSS", "ml-slider"),
                    'class' => 'option coin flex responsive nivo useWithCaution',
                    'checked' => $this->slider->get_setting(
                        'printCss'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => esc_html__(
                        "Uncheck this if you would like to include your own CSS",
                        "ml-slider"
                    )
                ),
                'printJs' => array(
                    'priority' => 220,
                    'type' => 'checkbox',
                    'label' => esc_html__("Print JS", "ml-slider"),
                    'class' => 'option coin flex responsive nivo useWithCaution',
                    'checked' => $this->slider->get_setting(
                        'printJs'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => esc_html__(
                        "Uncheck this if you would like to include your own Javascript",
                        "ml-slider"
                    )
                ),
                'noConflict' => array(
                    'priority' => 230,
                    'type' => 'checkbox',
                    'label' => esc_html__("No conflict mode", "ml-slider"),
                    'class' => 'option flex',
                    'checked' => $this->slider->get_setting(
                        'noConflict'
                    ) == 'true' ? 'checked' : '',
                    'helptext' => esc_html__(
                        "Delay adding the flexslider class to the slideshow",
                        "ml-slider"
                    )
                )
            );

            $aFields = apply_filters(
                'metaslider_advanced_settings',
                $aFields,
                $this->slider
            );

            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $this->build_settings_rows($aFields);
            ?>
            <tr class="empty-row-spacing">
                <td colspan="2"></td>
            </tr>
            <tr class="highlight">
                <td colspan="2">
                    <?php esc_html_e( 'Shortcode', 'ml-slider' ) ?>
                </td>
            </tr>
            <tr class="empty-row-spacing">
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php include METASLIDER_PATH . "admin/views/pages/parts/shortcode.php"; ?>
                </td>
            </tr>
            <tr class="empty-row-spacing">
                <td colspan="2"></td>
            </tr>
            <?php
            // Show the restore button if there are trashed posts
            // Also, render but hide the link in case we want to show
            // it when the user deletes their first slide
            $count = count(metaslider_has_trashed_slides($this->slider->id));
            if ( ! metaslider_viewing_trashed_slides( $this->slider->id ) ) {
            ?>
            <tr class="highlight trashed-slides-cont" style="<?php echo ! $count ? 'display: none;' : ''  ?>">
                <td colspan="2">
                    <?php esc_html_e( 'Trashed slides', 'ml-slider' ) ?>
                </td>
            </tr>
            <tr class="empty-row-spacing trashed-slides-cont" style="<?php echo ! $count ? 'display: none;' : ''  ?>">
                <td colspan="2"></td>
            </tr>
            <tr class="trashed-slides-cont" style="<?php echo ! $count ? 'display: none;' : ''  ?>">
                <td colspan="2">
                    <a class="restore-slide-link text-blue-dark hover:text-orange"
                        href="<?php
                        echo esc_url(
                            admin_url(
                                "admin.php?page=metaslider&id={$this->slider->id}&show_trashed=true"
                            )
                        ); ?>">
                        <i><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-trash-2"><polyline
                                        points="3 6 5 6 21 6"/><path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line
                                        x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg></i> 
                        <?php esc_attr_e( 'View trashed slides', 'ml-slider' ); ?> 
                        <?php echo $count ? '(' . (int) $count . ')' : '' ?>
                    </a>
                </td>
            </tr>
            <tr class="empty-row-spacing trashed-slides-cont" style="<?php echo ! $count ? 'display: none;' : ''  ?>">
                <td colspan="2"></td>
            </tr>
            <?php
            }
            ?>
    </tbody>
</table>