<?php
if( !class_exists('Ws247_Fspw_Theme') ):

	class Ws247_Fspw_Theme{
		/**
		 * Constructor
		 */
		function __construct() {
			$this->init();
		}
		
		public function init(){

			add_action( 'woocommerce_after_shop_loop_item', array($this,'add_sold_event'), 10 );
			
			$hide_product_page = Ws247_fspw::class_get_option('hide_product_page'); 
			if($hide_product_page != 'on'){
				add_action( 'woocommerce_single_product_summary', array($this,'add_sold_event'), 61 );
			}
			
		}

		public function get_product(){
			return  wc_get_product();
		}
		
		public function add_sold_event(){
			$product = $this->get_product(); 
		  	$fspw_flash_sale = (int)get_post_meta($product->get_id(), 'fspw_flash_sale', true );
		  	$fspw_flash_sold = (int)get_post_meta($product->get_id(), 'fspw_flash_sold', true );


		  	$bar_text = Ws247_fspw::class_get_option('bar_text'); 
		  	$sold_out_text = Ws247_fspw::class_get_option('sold_out_text');
		  	$bar_bg_color = Ws247_fspw::class_get_option('bar_bg_color');
		  	if(!$bar_bg_color){
		  		$bar_bg_color = '#ff5e0099';
		  	}

		  	
		  	$sold_out_bg_color = Ws247_fspw::class_get_option('sold_out_bg_color');
		  	if(!$sold_out_bg_color){
		  		$sold_out_bg_color = '#ff5e00';
		  	}

		  	
		  	$hide_sold_out = Ws247_fspw::class_get_option('hide_sold_out');

		  	if($fspw_flash_sale && $fspw_flash_sold>=0 && $fspw_flash_sold<=$fspw_flash_sale){
		  		$new_width = ( $fspw_flash_sold / $fspw_flash_sale) * 100;

		  		if($new_width == 100 && $sold_out_text){
		  			$bar_text = $sold_out_text;
		  		}

		  		if($new_width==100 && $hide_sold_out=='on') return '';
		  ?>
			  <div class="tbay-shock-price">
			    <div class="tbay_add_sold_event deals__qty" style="background:<?php echo esc_attr( $bar_bg_color );?>">
			      <div class="deals__progress" style="width: <?php echo esc_attr( $new_width );?>%; background:<?php echo esc_attr( $sold_out_bg_color );?>;"></div>
			        <span><?php echo esc_attr( $bar_text ); ?> <?php echo esc_attr( $fspw_flash_sold ).'/'.esc_attr( $fspw_flash_sale );?></span>
			        <img src="<?php echo WS247_FSPW_PLUGIN_INC_ASSETS_URL; ?>/fire.svg">
			    </div>
			  </div>
		  <?php
		  }
		}
	
	//End class------------------------
	}
	
	//Init
	$Ws247_Fspw_Theme = new Ws247_Fspw_Theme();
	
endif;