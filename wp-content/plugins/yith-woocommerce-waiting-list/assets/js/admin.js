/**
 * admin.js
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Waiting List
 * @version 1.0.0
 */

jQuery(document).ready(function($) {
    "use strict";
    // replace email single back url
    $('.wc-admin-breadcrumb a').attr('href', yith_wcwtl_admin.email_tab);

    if (typeof $.fn.ajaxChosen != 'undefined') {
        $('select.ajax_chosen_select_product').ajaxChosen({
            method: 'GET',
            url: yith_wcwtl_admin.ajaxurl,
            dataType: 'json',
            afterTypeDelay: 100,
            minTermLength: 3,
            data: {
                action: 'woocommerce_json_search_products',
                security: yith_wcwtl_admin.security,
                default: ''
            }
        }, function (data) {

            var terms = {};

            $.each(data, function (i, val) {
                terms[i] = val;
            });

            return terms;
        });
    }

    // move success message in edit product
    var message = $(document).find('#yith-success-message'),
        wrap_h2 = message.siblings('.wrap').find('h2');

    if (message.length && wrap_h2.length) {
        message.remove();
        wrap_h2.after(message);
    }

    const processItem = function (button) {
        const   url = button.find('a').length > 0 ? button.find('a').attr('href') : button.attr('href'),
                title = button.data( 'title' ),
                message = button.data( 'message' ),
                continue_to = button.data( 'continue' );

        yith.ui.confirm(
            {
                title: title,
                message: message,
                confirmButtonType: 'delete',
                confirmButton    : continue_to,
                closeAfterConfirm: false,
                onConfirm: function () {
                    window.location.href = url;
                },
            }
        );
    }

    // Confirmation window
     $( document ).on( 'click',
         '.yith-require-confirmation-modal',
         function(e){
             e.preventDefault();
             e.stopPropagation();
             processItem( $(this) );
     })

    $( document ).on( 'click', '#yith-wcwtl-custom-tab #message .notice-dismiss', function(){
        $( this ).closest( '#message' ).remove();
    } )

    if( $( '#woocommerce_yith_waitlist_mail_instock_analytics_tracking' ).length > 0 ){
        $( '#woocommerce_yith_waitlist_mail_instock_analytics_tracking' ).closest( 'tr' ).addClass( 'yith-premium' );
    }


});