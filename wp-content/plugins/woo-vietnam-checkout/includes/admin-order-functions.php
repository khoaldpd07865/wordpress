<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if(!class_exists('devvnDateRange')) {
    class devvnDateRange
    {
        private $post_type_allow = array('shop_order');

        function __construct()
        {
            if (devvn_vietnam_shipping()->get_options('active_filter_order')) {
                if (devvn_vietnam_shipping()->hpos_enabled()) {
                    //hpos
                    add_action( 'woocommerce_order_list_table_restrict_manage_orders', array($this, 'form_hpos'), 10, 2 );
                    add_filter('woocommerce_shop_order_list_table_prepare_items_query_args', array($this, 'filterquery_hpos'));
                }else {
                    // if you do not want to remove default "by month filter", remove/comment this line
                    add_filter('months_dropdown_results', array($this, 'devvn_remove_month_filter'), 10, 2);

                    // include CSS/JS, in our case jQuery UI datepicker
                    add_action('admin_enqueue_scripts', array($this, 'jqueryui'));

                    // HTML of the filter
                    add_action('restrict_manage_posts', array($this, 'form'));

                    // the function that filters posts
                    add_action('pre_get_posts', array($this, 'filterquery'));
                }
            }
        }

        function form_hpos($order_type, $which){

            if($order_type != 'shop_order') return;

            if ( 'top' === $which ) {
                $this->form_html();
            }

        }

        function form_html(){
            $from = (isset($_GET['devvnDateFrom']) && $_GET['devvnDateFrom']) ? $_GET['devvnDateFrom'] : '';
            $to = (isset($_GET['devvnDateTo']) && $_GET['devvnDateTo']) ? $_GET['devvnDateTo'] : '';
            $devvnbillingState = (isset($_GET['devvnbillingState']) && $_GET['devvnbillingState']) ? $_GET['devvnbillingState'] : '';
            $devvnbillingCity = (isset($_GET['devvnbillingCity']) && $_GET['devvnbillingCity']) ? $_GET['devvnbillingCity'] : '';

            ?>
            <style>
                input[name="devvnDateFrom"], input[name="devvnDateTo"] {
                    line-height: 28px;
                    height: 28px;
                    margin: 0;
                    width: 125px;
                }
                .post-type-shop_order .tablenav select#devvnbillingState + span.select2-container {
                    max-width: 155px !important;
                }
            </style>

            <input type="text" name="devvnDateFrom" placeholder="Từ ngày" value="<?php echo esc_attr($from); ?>"/>
            <input type="text" name="devvnDateTo" placeholder="Đến ngày" value="<?php echo esc_attr($to); ?>"/>
            <?php
            $country = new WC_Countries;
            $vn_state = $country->get_states('VN');
            if($vn_state && is_array($vn_state)):
                ?>
                <select name="devvnbillingState" id="devvnbillingState">
                    <option value="">Lọc theo tỉnh thành</option>
                    <?php foreach($vn_state as $k=>$v):?>
                        <option value="<?php echo esc_attr($k);?>" <?php selected($k, $devvnbillingState);?>><?php echo $v;?></option>
                    <?php endforeach;?>
                </select>
            <?php endif;?>

            <script>
                jQuery(function ($) {
                    var from = $('input[name="devvnDateFrom"]'),
                        to = $('input[name="devvnDateTo"]');

                    $('input[name="devvnDateFrom"], input[name="devvnDateTo"]').datepicker({dateFormat: "yy-mm-dd"});
                    from.on('change', function () {
                        to.datepicker('option', 'minDate', from.val());
                    });
                    to.on('change', function () {
                        from.datepicker('option', 'maxDate', to.val());
                    });

                });
            </script>
            <?php
        }

        function filterquery_hpos($order_query_args){

            $date_after = isset($_GET['devvnDateFrom']) ? sanitize_text_field($_GET['devvnDateFrom']) : '';
            $date_before = isset($_GET['devvnDateTo']) ? sanitize_text_field($_GET['devvnDateTo']) : '';

            if(!$date_before && $date_after) $date_after = date_i18n('Y-m-d', strtotime($date_after . ' - 1 day'));
            if(!$date_after && $date_before) $date_before = date_i18n('Y-m-d', strtotime($date_before . ' + 1 day'));

            if($date_before) $order_query_args['date_before'] = $date_before;
            if($date_after) $order_query_args['date_after'] = $date_after;

            if(isset($_GET['devvnbillingState']) && $_GET['devvnbillingState']){
                $order_query_args['billing_state'] = sanitize_text_field($_GET['devvnbillingState']);

                if(isset($_GET['devvnbillingCity']) && $_GET['devvnbillingCity']){
                    $order_query_args['billing_city'] = sanitize_text_field($_GET['devvnbillingCity']);
                }
            }

            return $order_query_args;
        }

        function devvn_remove_month_filter($months, $post_type)
        {
            if (in_array($post_type, $this->post_type_allow))
                return array();
            return $months;
        }

        function jqueryui()
        {
            global $typenow;
            if (in_array($typenow, $this->post_type_allow)) {
                wp_enqueue_style('jquery-ui-style', DEVVN_DWAS_URL . 'assets/css/jquery-ui.min.css');
                wp_enqueue_script('jquery-ui-datepicker');
            }
        }

        function form()
        {
            global $typenow;
            if (in_array($typenow, $this->post_type_allow)) {
                $from = (isset($_GET['devvnDateFrom']) && $_GET['devvnDateFrom']) ? $_GET['devvnDateFrom'] : '';
                $to = (isset($_GET['devvnDateTo']) && $_GET['devvnDateTo']) ? $_GET['devvnDateTo'] : '';
                $devvnbillingState = (isset($_GET['devvnbillingState']) && $_GET['devvnbillingState']) ? $_GET['devvnbillingState'] : '';

                ?>
                <style>
                    input[name="devvnDateFrom"], input[name="devvnDateTo"] {
                        line-height: 28px;
                        height: 28px;
                        margin: 0;
                        width: 125px;
                    }

                    .post-type-shop_order .tablenav select#devvnbillingState + span.select2-container {
                        max-width: 155px !important;
                    }
                </style>

                <input type="text" name="devvnDateFrom" placeholder="Từ ngày" value="<?php echo esc_attr($from); ?>"/>
                <input type="text" name="devvnDateTo" placeholder="Đến ngày" value="<?php echo esc_attr($to); ?>"/>
                <?php
                $country = new WC_Countries;
                $vn_state = $country->get_states('VN');
                if ($vn_state && is_array($vn_state)):
                    ?>
                    <select name="devvnbillingState" id="devvnbillingState">
                        <option value="">Lọc theo tỉnh thành</option>
                        <?php foreach ($vn_state as $k => $v): ?>
                            <option
                                value="<?php echo $k; ?>" <?php selected($k, $devvnbillingState); ?>><?php echo $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>

                <script>
                    jQuery(function ($) {
                        var from = $('input[name="devvnDateFrom"]'),
                            to = $('input[name="devvnDateTo"]');

                        $('input[name="devvnDateFrom"], input[name="devvnDateTo"]').datepicker({dateFormat: "yy-mm-dd"});
                        from.on('change', function () {
                            to.datepicker('option', 'minDate', from.val());
                        });
                        to.on('change', function () {
                            from.datepicker('option', 'maxDate', to.val());
                        });
                        $('#devvnbillingState').select2();
                    });
                </script>
                <?php
            }
        }

        function filterquery($admin_query)
        {
            global $pagenow, $typenow;
            if (
                is_admin()
                && $admin_query->is_main_query()
                && in_array($pagenow, array('edit.php', 'upload.php'))
                && in_array($typenow, $this->post_type_allow)
                && (!empty($_GET['devvnDateFrom']) || !empty($_GET['devvnDateTo']))
            ) {

                $admin_query->set(
                    'date_query',
                    array(
                        'after' => isset($_GET['devvnDateFrom']) ? $_GET['devvnDateFrom'] : '', // any strtotime()-acceptable format!
                        'before' => isset($_GET['devvnDateTo']) ? $_GET['devvnDateTo'] : '',
                        'inclusive' => true, // include the selected days as well
                        'column' => 'post_date' // 'post_modified', 'post_date_gmt', 'post_modified_gmt'
                    )
                );
            }
            if (
                is_admin()
                && $admin_query->is_main_query()
                && in_array($pagenow, array('edit.php', 'upload.php'))
                && in_array($typenow, $this->post_type_allow)
                && (!empty($_GET['devvnbillingState']))
            ) {
                $meta_query = $admin_query->get('meta_query');
                if ($meta_query && is_array($meta_query)):
                    $meta_query['relation'] = 'AND';
                    $meta_query[] = array(
                        'key' => '_billing_state',
                        'value' => sanitize_text_field($_GET['devvnbillingState']),
                    );
                else:
                    $meta_query = array(
                        array(
                            'key' => '_billing_state',
                            'value' => sanitize_text_field($_GET['devvnbillingState']),
                        )
                    );
                endif;
                $admin_query->set('meta_query', $meta_query);
            }
            return $admin_query;
        }

    }

    new devvnDateRange();
}