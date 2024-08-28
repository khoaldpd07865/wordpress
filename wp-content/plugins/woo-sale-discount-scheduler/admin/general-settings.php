<?php
$wsds_options = get_option('wsds_options');

$sale_start_label  = (isset($wsds_options['sale_start_label'])) ? $wsds_options['sale_start_label'] : __('This product will be on sale for {wsds_sale_price} after the following timer','woocommerce-sale-discount-scheduler');
$sale_end_label  = (isset($wsds_options['sale_end_label'])) ? $wsds_options['sale_end_label'] : __("Don't miss out! Sale ends after following timer","woocommerce-sale-discount-scheduler");
$discount_not_applied_label  = (isset($wsds_options['discount_not_applied_label'])) ? $wsds_options['discount_not_applied_label'] : __('Discount Not Applied: Set Regular Price greater than discount price','woocommerce-sale-discount-scheduler');
$wrapper_class  = (isset($wsds_options['wrapper_class'])) ? $wsds_options['wrapper_class'] : '';
?>
    <div class="wrap">
        <h2><?php _e('Woocommerce Sale Discount Scheduler Settings', 'woocommerce-sale-discount-scheduler'); ?></h2>
        <?php settings_errors(); ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'wsds-all-settings' ); ?>

            <div class="wsds-field-box">
                <div class="wsds-title">
                    <strong><?php _e('Sale will be start Label', 'woocommerce-sale-discount-scheduler'); ?></strong> 
                </div>
                <div class="wsds-label">
                    <input type="text" placeholder="" name="wsds_options[sale_start_label]" value="<?php _e($sale_start_label); ?>">
                    <p><?php echo wp_kses( __( "<i>Default text: <b>This product will be on sale for <code>{wsds_sale_price}</code> after the following timer</b></i>", "woocommerce-sale-discount-scheduler" ), array( 'code' => array(), 'i' => array(), 'b' => array() ) ); ?></p>
                    <p><?php echo wp_kses( __( "<i>Note: To display scheduled sale price use <code>{wsds_sale_price}</code> tag.</i>", "woocommerce-sale-discount-scheduler" ), array( 'code' => array(), 'i' => array(), 'b' => array() ) ); ?></p>
                </div>
            </div>

            <div class="wsds-field-box">
                <div class="wsds-title">
                    <strong><?php _e('Sale ends in Label', 'woocommerce-sale-discount-scheduler'); ?></strong> 
                </div>
                <div class="wsds-label">
                    <input type="text" placeholder="" name="wsds_options[sale_end_label]" value="<?php _e($sale_end_label); ?>">
                    <p><?php echo wp_kses( __( "<i>Default text: <b>Don't miss out! Sale ends after following timer</b></i>", "woocommerce-sale-discount-scheduler" ), array( 'i' => array(), 'b' => array() ) ); ?></p>
                </div>
            </div>

            <div class="wsds-field-box">
                <div class="wsds-title">
                    <strong><?php _e('Discount not applied Label', 'woocommerce-sale-discount-scheduler'); ?></strong> 
                </div>
                <div class="wsds-label">
                    <input type="text" placeholder="" name="wsds_options[discount_not_applied_label]" value="<?php _e($discount_not_applied_label); ?>">
                    <p><?php echo wp_kses( __( "<i>Default text: <b>Discount Not Applied: Set Regular Price greater than discount price</b></i>", "woocommerce-sale-discount-scheduler" ), array( 'i' => array(), 'b' => array() ) ); ?></p>
                </div>
            </div>

            <div class="wsds-field-box">
                <div class="wsds-title">
                    <strong><?php _e('Wrapper Class', 'woocommerce-sale-discount-scheduler'); ?></strong> 
                </div>
                <div class="wsds-label">
                    <input type="text" placeholder=".product.product-type-variable .woocommerce-variation" name="wsds_options[wrapper_class]" value="<?php _e($wrapper_class); ?>">
                    <p style="font-style: italic; color: red;">Give <code>comma (,)</code> after each target classes. <b>Examples:</b> <code>.product.product-type-variable</code>.</p>
                    <p><i><?php echo esc_html("Keep blank, if you haven't any issues with the sale countdown. This field is for fixing sale countdown compatibility issue.", 'woocommerce-sale-discount-scheduler'); ?></i></p>
                </div>
            </div>

            <div class="wsds-submit-btn">
                <?php submit_button( 'Save Settings' ); ?>
            </div>
        </form>
    </div>
<?php