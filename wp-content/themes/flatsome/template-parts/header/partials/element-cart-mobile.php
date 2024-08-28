<?php
/**
 * Mobile cart element.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.18.3
 */

if ( is_woocommerce_activated() && flatsome_is_wc_cart_available() ) {
  // Get Cart replacement for catalog_mode
  if(flatsome_option('catalog_mode')) { get_template_part('template-parts/header/partials/element','cart-replace'); return;}
  $cart_style = flatsome_option('header_cart_style');
  $custom_cart_content = flatsome_option('html_cart_header');
  $icon_style = flatsome_option('cart_icon_style');
  $icon = flatsome_option('cart_icon');
  $disable_mini_cart = apply_filters( 'flatsome_disable_mini_cart', is_cart() || is_checkout() );
  if ( $disable_mini_cart ) {
    $cart_style = 'link';
  }

	$link_atts = array(
		'href'  => is_customize_preview() ? '#' : esc_url( wc_get_cart_url() ), // Prevent none link mode to navigate in customizer.
		'class' => 'header-cart-link ' . get_flatsome_icon_class( $icon_style, 'small' ),
		'title' => esc_attr__( 'Cart', 'woocommerce' ),
	);

	if ( $cart_style !== 'link' ) {
		$link_atts['class']     .= ' off-canvas-toggle nav-top-link';
		$link_atts['data-open']  = '#cart-popup';
		$link_atts['data-class'] = 'off-canvas-cart';
		$link_atts['data-pos']   = 'right';
	}

	if ( fl_woocommerce_version_check( '7.8.0' ) && ! wp_script_is( 'wc-cart-fragments' ) ) {
		wp_enqueue_script( 'wc-cart-fragments' );
	}
?>
<li class="cart-item has-icon">

<?php if($icon_style && $icon_style !== 'plain') { ?><div class="header-button"><?php } ?>

		<a <?php echo flatsome_html_atts( $link_atts ); ?>>

<?php
if(flatsome_option('custom_cart_icon')) { ?>
  <span class="image-icon header-cart-icon" data-icon-label="<?php echo WC()->cart->cart_contents_count; ?>">
    <img class="cart-img-icon" alt="<?php _e('Cart', 'woocommerce'); ?>" src="<?php echo do_shortcode(flatsome_option('custom_cart_icon')); ?>"/>
  </span>
<?php }
else { ?>
  <?php if(!$icon_style) { ?>
  <span class="cart-icon image-icon">
    <strong><?php echo WC()->cart->cart_contents_count; ?></strong>
  </span>
  <?php } else { ?>
  <i class="icon-shopping-<?php echo $icon;?>"
    data-icon-label="<?php echo WC()->cart->cart_contents_count; ?>">
  </i>
  <?php } ?>
<?php }  ?>
</a>
<?php if($icon_style && $icon_style !== 'plain') { ?></div><?php } ?>

<?php if ( $cart_style !== 'off-canvas' && $cart_style !== 'link' ) { ?>

  <!-- Cart Sidebar Popup -->
  <div id="cart-popup" class="mfp-hide">
  <div class="cart-popup-inner inner-padding<?php echo get_theme_mod( 'header_cart_sticky_footer', 1 ) ? ' cart-popup-inner--sticky' : ''; ?>">
      <div class="cart-popup-title text-center">
          <span class="heading-font uppercase"><?php _e('Cart', 'woocommerce'); ?></span>
          <div class="is-divider"></div>
      </div>
      <?php the_widget( 'WC_Widget_Cart', array( 'title' => '' ) ); ?>
      <?php if($custom_cart_content) {
        echo '<div class="header-cart-content">'.do_shortcode($custom_cart_content).'</div>'; }
      ?>
       <?php do_action('flatsome_cart_sidebar'); ?>
  </div>
  </div>

<?php } ?>
</li>
<?php } ?>
