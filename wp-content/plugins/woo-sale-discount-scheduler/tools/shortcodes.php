<?php 
add_filter( 'body_class', 'wsds_shortcode_add_class' );
function wsds_shortcode_add_class( $wsds_classes ) {
    global $post;

    if( isset($post->post_content) && has_shortcode( $post->post_content, 'wsds_schedule_sale_discount' ) ) {
        $wsds_classes[] = 'woocommerce woocommerce-page';
    }
	
    return $wsds_classes;
}

function wsds_schedule_sale_discount_shortcode($atts)
{
	global $post; 
	$time = time();
	extract(shortcode_atts(array(
    'limit' => '',
    'on_sale'=>'',
    'future_sale'=>'',
    'columns'=>''), $atts));

    $flag = "";
    $flag_all = "";
    $columns = $atts['columns'];
    $on_sale = $atts['on_sale'];
    $future_sale = $atts['future_sale'];
    if($on_sale=='true'){
        $flag=1; 
    }
    if($future_sale=='true'){
        $flag=0; 
    }
    if($on_sale=='true' && $future_sale=='true'){
        $flag_all=1; 
    }
    if($columns=='')
    {
        $columns=3;
    }
    if($flag_all==1)
    {
        $args = array(
            'post_type'      => 'product',
            'posts_per_page'=> $atts['limit'],
            'meta_query' => 
                array(
                        'relation' => 'AND',
                        array(
                            'key'     => 'wsds_schedule_sale_status',
                            'value'   => 1,
                            'compare' => '=',
                        ),
                        
                ),
			);
    }else{
        $args = array(
            'post_type'      => 'product',
            'posts_per_page'=> $atts['limit'],
            'meta_query' => 
            array(
                'relation' => 'AND',
                array(
                    'key'     => 'wsds_schedule_sale_status',
                    'value'   => 1,
                    'compare' => '=',
                ),
                array(
                    'key'     => 'wsds_schedule_sale_mode',
                    'value'   => $flag,
                    'compare' => '=',		
                ),
            ),
        );
    }

    $loop = new WP_Query( $args );
    if ( $loop->have_posts() ) { ?>
        <ul class="products columns-<?php echo $columns; ?>">
            <?php
            while ( $loop->have_posts() ) : $loop->the_post();				
                wc_get_template_part( 'content', 'product' );				
            endwhile;
            ?> 
        </ul>
        <?php
    } else {
        esc_html_e( 'No products found','woocommerce-sale-discount-scheduler' );
    }
    wp_reset_postdata();
}
add_shortcode('wsds_schedule_sale_discount','wsds_schedule_sale_discount_shortcode');