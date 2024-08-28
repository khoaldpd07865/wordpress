<?php
if(!defined('ABSPATH')) exit;
function wsds_get_post_meta_options()
{
	global $post_id;
	return get_post_meta( $post_id, 'wsds_schedule_sale_meta',true);
}

// Get Product IDs
function wsds_get_schedule_product_list($status){
	global $post;
	$product_ids=array();
	$args = array(
	'posts_per_page' => -1,
    'post_type'  => 'product',
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key'     => 'wsds_schedule_sale_status',
            'value'   => 1,
            'compare' => '=',
        ),
        array(
            'key'     => 'wsds_schedule_sale_mode',
            'value'   => $status,
			'compare' => '=',		
			),
		)
	);
	
	$wsds_products= get_posts( $args );	
	foreach ($wsds_products as $wsds_product)
	{ 	
		array_push($product_ids,$wsds_product->ID);		
	}	
	return $product_ids;
}

// Success message
function  success_option_msg_wsds($msg)
{
	return ' <div class="notice notice-success wsds-success-msg is-dismissible"><p>'. $msg . '</p></div>';
}

// Error message
function  failure_option_msg_wsds($msg)
{
	return '<div class="notice notice-error wsds-error-msg is-dismissible"><p>' . $msg . '</p></div>';
}