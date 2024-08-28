jQuery(function( $ ) {

    $( document ).on( 'click', '.toggle-settings', function( e ){
        e.preventDefault();
        $( this ).closest( '.yith-wcwtl-row' ).toggleClass( 'active' );
        const target = $( this ).data( 'target' );
        $( '#'+target ).slideToggle();

    } )

    $( document ).on( 'click', '.yith-wcwtl-save-settings', function( e ){
        e.preventDefault();
        $( this ).closest( 'form' ).find( '.wp-switch-editor.switch-html' ).trigger('click');
        const email_key = $( this.closest( '.email-settings' ) ).attr( 'id' );
        const data = {
            'action' : 'yith_wcwtl_save_email_settings',
            'params' : $( this ).closest( 'form' ).serialize(),
            'email_key' : email_key,
            'nonce'     : $( this ).data( 'nonce' )
        }
        $.ajax( {
            type    : "POST",
            data    : data,
            url     : yith_wcwtl_admin.ajaxurl,
            success : function ( response ) {
                const row_active = $( '.yith-wcwtl-row.active' );
                row_active.find( '.email-settings' ).slideToggle();
                row_active.toggleClass( 'active' );
            },
        });
    } )

    $( document ).on( 'change', '#yith-wcwtl-email-status', function(){
        const data = {
            'action'    : 'yith_wcwtl_save_mail_status',
            'enabled'   : $(this).val(),
            'email_key' : $(this).closest('.yith-plugin-fw-onoff-container ').data('email_key'),
            'nonce'     : $(this).closest('.yith-plugin-fw-onoff-container ').data( 'nonce' )
        }

        $.ajax( {
            type    : "POST",
            data    : data,
            url     : yith_wcwtl_admin.ajaxurl,
            success : function ( response ) {

            },
        });

    } )


});