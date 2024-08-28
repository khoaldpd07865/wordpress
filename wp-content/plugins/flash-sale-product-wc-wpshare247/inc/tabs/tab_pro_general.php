<table id="tab_pro_general" class="form-table aeiwooc-tab active">
    <!--List field here .....-->
    
    <tr valign="top">
        <th scope="row"><?php esc_html_e("Bar text", WS247_FSPW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'bar_text'; 
            $field = Ws247_fspw::create_option_prefix($field_name);
            $bar_text_val = Ws247_fspw::class_get_option($field_name);
            ?>
            <input placeholder="<?php esc_html_e("Bar text", WS247_FSPW_TEXTDOMAIN); ?>" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($bar_text_val); ?>" />
        </td>
    </tr>


    <tr valign="top">
        <th scope="row"><?php esc_html_e("Sold out", WS247_FSPW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'sold_out_text'; 
            $field = Ws247_fspw::create_option_prefix($field_name);
            $sold_out_text_val = Ws247_fspw::class_get_option($field_name);
            ?>
            <input placeholder="<?php esc_html_e("Sold out", WS247_FSPW_TEXTDOMAIN); ?>" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($sold_out_text_val); ?>" />
        </td>
    </tr>


    <tr valign="top">
        <th scope="row"><?php _e("Bar background color", WS247_FSPW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'bar_bg_color';
            $field = Ws247_fspw::create_option_prefix($field_name);
            $val = Ws247_fspw::class_get_option($field_name);
            ?>
            <input value="<?php echo esc_attr($val); ?>" class="colorpicker" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><?php _e("Sold out background color", WS247_FSPW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'sold_out_bg_color';
            $field = Ws247_fspw::create_option_prefix($field_name);
            $val = Ws247_fspw::class_get_option($field_name);
            ?>
            <input value="<?php echo esc_attr($val); ?>" class="colorpicker" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />
        </td>
    </tr>


    <tr valign="top">
        <th scope="row"><?php _e("Hide when sold out", WS247_FSPW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'hide_sold_out';
            $field = Ws247_fspw::create_option_prefix($field_name);
            $val = Ws247_fspw::class_get_option($field_name);
            $checked = '';
            if($val=='on'){
                $checked = 'checked';
            }
            ?>
           <input type="checkbox" <?php echo esc_attr($checked); ?>  id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><?php _e("Hide product page", WS247_FSPW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'hide_product_page';
            $field = Ws247_fspw::create_option_prefix($field_name);
            $val = Ws247_fspw::class_get_option($field_name);
            $checked = '';
            if($val=='on'){
                $checked = 'checked';
            }
            ?>
           <input type="checkbox" <?php echo esc_attr($checked); ?>  id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />
        </td>
    </tr>
    
</table>