<?php
add_action( 'add_meta_boxes', 'fspw_add_new_metabox' );
function fspw_add_new_metabox(){
    //MetaBox thứ 1 ---------------------------------------
    $id = 'fspw_add_new_metabox'; 
    $title = __( 'Flash sale', WS247_FSPW_TEXTDOMAIN );
    $func_callback = 'fspw_add_new_metabox_cb';
    $arr_post_type = array('product');
    $context = 'side';
    $priority = 'low';
    add_meta_box( $id, $title, $func_callback, $arr_post_type, $context, $priority, $callback_args);
}

//Step 2
function fspw_add_new_metabox_cb($post){
    $fspw_flash_sale = get_post_meta($post->ID, 'fspw_flash_sale', true );
    $fspw_flash_sold = get_post_meta($post->ID, 'fspw_flash_sold', true );
    ?>
    
   <div class="wpshare247-group-item">
        <div><label for="wpshare247_field"><strong><?php _e( 'Sale total', WS247_FSPW_TEXTDOMAIN );?></strong></label></div>
        <input style="width:100%" name="fspw_flash_sale" type="text" value="<?php echo esc_html($fspw_flash_sale); ?>" placeholder="60" />
    </div>

    <div class="wpshare247-group-item">
        <div><label for="wpshare247_field"><strong><?php _e( 'Sold total', WS247_FSPW_TEXTDOMAIN );?></strong></label></div>
        <input style="width:100%" name="fspw_flash_sold" type="text" value="<?php echo esc_html($fspw_flash_sold); ?>" placeholder="30" />
    </div>
    <?php
}

//Step 3
function fspw_save_post_product_postdata( $post_id ) {
    if ( array_key_exists( 'fspw_flash_sale', $_POST ) ) {
        update_post_meta(
            $post_id,
            'fspw_flash_sale',
            wp_unslash($_POST['fspw_flash_sale'])
        );
    }


    if ( array_key_exists( 'fspw_flash_sold', $_POST ) ) {
        update_post_meta(
            $post_id,
            'fspw_flash_sold',
            wp_unslash($_POST['fspw_flash_sold'])
        );
    }
}
add_action( 'save_post_product', 'fspw_save_post_product_postdata' );