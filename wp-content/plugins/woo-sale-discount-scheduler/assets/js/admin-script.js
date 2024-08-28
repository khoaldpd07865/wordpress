jQuery(document).ready(function(){
    jQuery('body').on('change','.wsds_variation_field_group .wsds_variation_sale_status',function(){
        var selected = jQuery(this).val();
        if(selected == '1'){
            jQuery(this).parents('.wsds_variation_field_group').removeClass('wsds-disable-status');
        }else{
            jQuery(this).parents('.wsds_variation_field_group').addClass('wsds-disable-status');
        }
    });
});