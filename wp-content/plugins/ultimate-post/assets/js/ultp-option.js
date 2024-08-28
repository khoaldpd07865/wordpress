(function($) {
    'use strict';

    // -------------------------------
    // -------- Custom Font ----------
    // -------------------------------
    $(".ultp-font-variation-action").on('click', function(e) {
        const content = $('.ultp-custom-font-copy')[0].outerHTML;;
        $(this).before( content.replace("ultp-custom-font-copy", "ultp-custom-font ultp-font-open") );
    });
    $(document).on('click', ".ultp-custom-font-close", function(e) {
        $(this).closest('.ultp-custom-font-container').removeClass('ultp-font-open');
    });
    $(document).on('click', ".ultp-custom-font-edit", function(e) {
        $(this).closest('.ultp-custom-font-container').addClass('ultp-font-open');
    });
    $(document).on('click', ".ultp-custom-font-delete", function(e) {
        $(this).closest('.ultp-custom-font').remove();
    });
    $(document).on('click', '.ultp-font-upload', function(e) {
        const that = $(this);
        $(this).addClass('rty')
        const ultpCustomFont = wp.media({
            title: 'Add Font',
            button: { text: 'Add New Font' },
            multiple: false,
        }).on(
            'select',
            function () { 
                const attachment = ultpCustomFont.state().get( 'selection' ).first().toJSON();
                that.closest('.ultp-font-file-list').find('input').val(attachment.url)
            }
        )
        .open();
    });
    // -------------------------------
    // -------------------------------
    // -------------------------------

    $(document).ready(function() {
        $(document).on('DOMSubtreeModified', function() {
            $('.ultp-color-picker').wpColorPicker({
                change: function(e, ui){
                    $(this).closest('.ultp-settings-field').find('.ultp-color-code').val(e.target.value)
                }
            });
            $('.ultp-color-code').bind("change keyup input",function() {
                $(this).closest('.ultp-settings-field').find('.wp-color-result').css("background-color", $(this).val())
            });
            $('input[name=disable_google_font]').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#postx-regenerate-css').addClass('active')
                } else {
                    $('#postx-regenerate-css').removeClass('active')
                }
            });
        });
        
    });

    // *************************************
    // Add target blank for upgrade button
    // *************************************
    const postxMenuHref = $('#toplevel_page_ultp-settings > a').attr('href');
    if ( postxMenuHref.indexOf("?page=ultp-settings") > 0 ) {
        $('#toplevel_page_ultp-settings > a').attr('href' , postxMenuHref+'#home' )
    }

    $('#toplevel_page_ultp-settings ul > li > a').each(function (e) {
        if ($(this).attr('href') && $(this).attr('href').indexOf("?page=ultp-settings") > 0) {
            
            if ($(this).hasClass('wp-first-item') != false) {
                $(this).attr('href' , $(this).attr('href')+'#home' )
            }
            
            if (ultp_option_panel.settings) {
                if ( $(this).attr('href').indexOf('#builder') > 0 && ultp_option_panel.settings?.ultp_builder != 'true') {
                    $(this).hide();
                }
                if ($(this).attr('href').indexOf('#custom-font') > 0 && ultp_option_panel.settings?.ultp_custom_font != 'true') {
                    $(this).hide();   
                }
                if ($(this).attr('href').indexOf('#saved-templates') > 0 && ultp_option_panel.settings?.ultp_templates != 'true') {
                    $(this).hide(); 
                }
            }

            let hasID = $(this).attr('href').indexOf('#')
            $(this).attr('id', 'postx-submenu-'+(hasID > 0 ? $(this).attr('href').split('#')[1] : 'home'))

            if($(this).attr('href').indexOf("?ultp=plugins") > 0) {
                $(this).attr('target', '_blank');
            }
        }
        if($(this).attr('href').indexOf("?page=go_postx_pro") > 0) {
            $(this).attr('target', '_blank');
        }   
    });
    

    $(document).on('click', '.ultp-popup-close', function(e){
        if (!$(this).hasClass('popup-center')) {
            $(this).closest('.ultp-popup-container').removeClass('active');
        }
    });
    
    $(document).on('click', '.ultp-block-settings', function(e){
        $(this).parent().find('.ultp-popup-container').addClass('active');
    });
    
    const actionBtn = $('.page-title-action');
    const savedBtn = $(".ultp-saved-templates-action");
    if (savedBtn.length > 0 ) {
        if (savedBtn.data())
        actionBtn.addClass('ultp-save-templates-pro').text( savedBtn.data('text') )
        actionBtn.attr( 'href', savedBtn.data('link') )
        actionBtn.attr( 'target', '_blank' )
    }

    // *************************************
    // Add URL for PostX
    // *************************************
    $(document).on('click', '#plugin-information-content ul > li > a', function(e) {
        const URL = $(this).attr('href');
        if (URL.includes('downloads/gutenberg-post-blocks-pro')) {
            e.preventDefault();
            window.open("https://www.wpxpo.com/postx/");
        }
    });

    // *************************************
    // PostX Builder Metabox Settings
    // *************************************
    const selector = $('.postx-meta-sidebar-position select');
    function changeSidebar() {
        if (selector.length > 0) {
            if (selector.val() == 'left' || selector.val() == 'right') {
                $('.postx-meta-sidebar-widget').show();
            } else {
                $('.postx-meta-sidebar-widget').hide();
            }
        }
    }
    changeSidebar();
    selector.on('change', function() {changeSidebar()});

    // Settings Option
    if ('?page=ultp-settings' == window.location.search) {
        const hash = window.location.hash
        if (hash) {
            if (hash.indexOf('demoid') < 0) {
                $('#toplevel_page_ultp-settings ul li').removeClass('current');
                $('#toplevel_page_ultp-settings ul li a[href$='+hash+']').closest('li').addClass('current');
                if (hash == '#home') {
                    $('#toplevel_page_ultp-settings ul li.wp-first-item').addClass('current');
                } else {
                    $('#toplevel_page_ultp-settings ul li a[href$='+hash+']').addClass('current');
                }
            }
        }
    }

    $(document).on('click', '#ultp-dashboard-ultp-settings-tab li a, #toplevel_page_ultp-settings ul li a', function(e) {
        let value = $(this).attr('href')
        if (value) {
            value = value.split('#');
            if (typeof value[1] != 'undefined' && value[1].indexOf('demoid') < 0 && value[1]) {
                $('#toplevel_page_ultp-settings ul li a').closest('ul').find('li').removeClass('current');
                $(this).closest('li').addClass('current'); // Submenu click
                $('#toplevel_page_ultp-settings ul li a[href$='+value[1]+']').closest('li').addClass('current'); // Dash Nav Menu click
                if (value[1] == 'home') {
                    $('#toplevel_page_ultp-settings ul li.wp-first-item').addClass('current');
                }
            }
        }
    });


    $('.page-title-action').on('click', function(e) {
        if ($('.ultp-pro-needed').length > 0) {
            const href = $(this).attr('href')
            if (href.indexOf('post_type=ultp_builder') > 0) {
                e.preventDefault();
                $('.ultp-popup-container').addClass('active');
            }
        }
    });
    $('.ultp-popup-close').on('click', function(e) {
       $(this).closest('.ultp-popup-container').removeClass('active')
    });
    // *************************************
    // Ultp Builder Image 
    // *************************************
    // If any Default Video Have not Caption Default caption should be empty
    $('#ultp-add-input').on('change', function(e) {
        const inputVal = $(this).val(); 
        if(inputVal == ''){
            jQuery('#ultp-add-caption').val('');
        }
    });

    $('.ultp-add-media').click(() => {
        let videoView = jQuery('.ultp-video-src > source');
        let ultpFeatVideo = wp.media({
            title: 'Insert Video',
            button: {
                text: 'Add New Image'
            },
            multiple: false,
            library: {
                type : 'video',
            }
        }).on(
            'select',
            function () { 
                let attachment = ultpFeatVideo.state().get( 'selection' ).first().toJSON();
                jQuery('#ultp-add-input').val(attachment.url);
                jQuery('#ultp-add-caption').val(attachment.caption);
                // videoView.attr('src', attachment.url);
            }
        )
        .open();
    })

    // *************************************
    // Disable Google Font Action
    // *************************************
    
    $(document).on('click', '#postx-regenerate-css', function(e) {
        const that = $(this)
        $.ajax({
            url: ultp_option_panel.ajax,
            type: 'POST',
            data: {
                action: 'disable_google_font',
                wpnonce: ultp_option_panel.security
            },
            beforeSend: function() {
                that.addClass('ultp-spinner');
            },
            success: function(res) {
				if (res.success) {
                    that.find('.ultp-text').text(res.data);
				}
            },
            complete:function() {
                that.removeClass('ultp-spinner');
            },
            error: function(xhr) {
                console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
            },
        });
    });

    // saved template back button 
    $(document).ready(function() {
        if($('.block-editor-page') && ultp_option_panel.post_type == 'ultp_templates') {
            setTimeout(function() {
                if ($('.edit-post-fullscreen-mode-close').length > 0) {
                    $('.edit-post-fullscreen-mode-close')[0].href = ultp_option_panel.saved_template_url
                }
            }, 1);
        }
    })
    
})( jQuery );