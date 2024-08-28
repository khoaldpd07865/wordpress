<?php
add_filter('body_class','wsds_widget_add_class');
function wsds_widget_add_class($wsds_classes) {
		if ( is_active_widget(false, false, 'woocommerce_sale_discount_products', true) ) {
			$wsds_classes[] = 'woocommerce woocommerce-page';
		}
		return $wsds_classes;
}  

// register Widget Woocommerce_Sale_Discount_Products
add_action( 'widgets_init', function(){
	register_widget( 'Woocommerce_Sale_Discount_Products' );
});

class Woocommerce_Sale_Discount_Products extends WP_Widget {

	public function __construct() {
		
		$widget_ops = array(
		'classname' => 'woocommerce_sale_discount_products',
		'description' => __('A Widget to display woocommerce products list are on sale discount and future sale discount.','woocommerce-sale-discount-scheduler'),
		);
		parent::__construct( 'Woocommerce_Sale_Discount_Products', 'Woocommerce Sale Discount Products', $widget_ops );
	}
	         
	public function widget( $args, $instance ) {
		extract( $args );
		$wsds_title = (isset($instance['wsds_title'])) ? apply_filters('widget_title', $instance['wsds_title']) : '';
		$wsds_limit = (isset($instance['wsds_limit']) && $instance['wsds_limit'] > 0) ? $instance['wsds_limit']: -1;
		$wsds_on_sale = (isset($instance['wsds_limit'])) ? 'true' : 'false';
		$wsds_future_sale = (isset($instance['wsds_future_sale'])) ? 'true' : 'false';
		$flag = $flag_all = ""; ?>

		<?php echo $before_widget; ?>
		<?php 
		if(isset($wsds_title) && !empty($wsds_title)) 	echo $before_title . $wsds_title . $after_title;
		if($wsds_on_sale=='true') 		$flag = 1; 
		if($wsds_future_sale=='true')	$flag = 0;
		if($wsds_on_sale=='true' && $wsds_future_sale=='true')	$flag_all = 1;
		if($flag_all == 1) {
			$args = array(
			 'post_type'      => 'product',
			 'posts_per_page'=> $wsds_limit,
			 'meta_query' => 
				array(
					'relation' => 'AND',
					array(
						'key'     => 'wsds_schedule_sale_status',
						'value'   => 1,
						'compare' => '=',
					),
				)
			);
		}else{
			$args = array(
			 'post_type'      => 'product',
			 'posts_per_page'=> $wsds_limit,
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
			<ul class ="products columns-1"><?php
			while ( $loop->have_posts() ) : $loop->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile;
			?> </ul><?php
		} else {
			echo __( 'No products found', 'woocommerce-sale-discount-scheduler' );
		}
		wp_reset_postdata();
		echo $after_widget; 			
	}
	
	public function form( $instance ) {
		$wsds_title = (isset($instance['wsds_title'])) ? esc_attr($instance['wsds_title']) : '';
        $wsds_limit = (isset($instance['wsds_title'])) ? esc_attr($instance['wsds_limit']) : '';
        $wsds_on_sale = (isset($instance['wsds_on_sale'])) ? esc_attr($instance['wsds_on_sale']) : '';
        $wsds_future_sale = (isset($instance['wsds_future_sale'])) ? esc_attr($instance['wsds_future_sale']) : ''; ?>
            <p>
				<label for="<?php echo $this->get_field_id('wsds_title'); ?>"><?php esc_html_e('Title:','woocommerce-sale-discount-scheduler'); ?> 
					<input class="wsds_widget_title" id="<?php echo $this->get_field_id('wsds_title'); ?>" name="<?php echo $this->get_field_name('wsds_title'); ?>" type="text" value="<?php echo $wsds_title; ?>" />
				</label>
				<span class="wsds_note"><?php esc_html_e('Add text to show as widget title.','woocommerce-sale-discount-scheduler'); ?></span>
			</p>
     		<p>
				<label for="<?php echo $this->get_field_id('wsds_limit'); ?>"><?php esc_html_e('Limit:','woocommerce-sale-discount-scheduler'); ?> 
					<input class="wsds_widget_limit" id="<?php echo $this->get_field_id('wsds_limit'); ?>" name="<?php echo $this->get_field_name('wsds_limit'); ?>" type="number" value="<?php echo $wsds_limit; ?>" size="2" />
				</label>
				<span class="wsds_note"><?php esc_html_e('Set limit to display only number of products on widget. Default: No limit','woocommerce-sale-discount-scheduler'); ?></span>
			</p>
			<p>
				<input class="wsds_widget_on_sale" type="checkbox" <?php checked( $wsds_on_sale, 'on' ); ?> id="<?php echo $this->get_field_id( 'wsds_on_sale' ); ?>" name="<?php echo $this->get_field_name( 'wsds_on_sale' ); ?>" />
				<label for="<?php echo $this->get_field_id('wsds_on_sale'); ?>"><?php esc_html_e('On Sale','woocommerce-sale-discount-scheduler'); ?> 
				</label><br>
				<span class="wsds_note"><?php esc_html_e('Enable this to display "On Sale" products on widget.','woocommerce-sale-discount-scheduler'); ?></span>
			</p>
			<p>
				
				<input class="wsds_widget_future_sale" type="checkbox" <?php checked( $wsds_future_sale, 'on' ); ?> id="<?php echo $this->get_field_id( 'wsds_future_sale' ); ?>" name="<?php echo $this->get_field_name( 'wsds_future_sale' ); ?>" />
				<label for="<?php echo $this->get_field_id('wsds_future_sale'); ?>"><?php esc_html_e('Future Sale','woocommerce-sale-discount-scheduler'); ?> 
				</label><br>
				<span class="wsds_note"><?php esc_html_e('Enable this to display "Future Sale" products on widget.','woocommerce-sale-discount-scheduler'); ?></span>
			</p>
        
		<?php
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wsds_title'] = ( isset( $new_instance['wsds_title'] ) && ! empty( $new_instance['wsds_title'] ) ) ? strip_tags( $new_instance['wsds_title'] ) : '';
		$instance['wsds_limit'] = ( isset( $new_instance['wsds_limit'] ) && ! empty ( $new_instance['wsds_limit'] ) ) ? strip_tags($new_instance['wsds_limit'] ) : '';
		$instance['wsds_on_sale'] = ( isset( $new_instance['wsds_on_sale'] ) && ! empty ( $new_instance['wsds_on_sale'] ) ) ? strip_tags($new_instance['wsds_on_sale'] ) : '';
		$instance['wsds_future_sale'] = ( isset( $new_instance['wsds_future_sale'] ) && ! empty ( $new_instance['wsds_future_sale'] ) ) ? strip_tags($new_instance['wsds_future_sale'] ) : '';
		return $instance;
	}
}