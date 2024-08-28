<?php
/**
 * YITH WooCommerce Waiting List Product Info Template
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\WaitiList
 */


if ( ! isset( $product ) || ! $product instanceof WC_Product ) {
	exit;
}

$product_title = $product->get_title();
$price         = $product->get_price();
$show_thumb    = 'yes' === $email->get_option( 'show_thumb' );

if ( $show_thumb ) {
	$dimensions = wc_get_image_size( 'woocommerce_thumbnail' );
	$height     = esc_attr( $dimensions['height'] );
	$width      = esc_attr( $dimensions['width'] );
	$src_image  = $product->get_image_id() ? wp_get_attachment_image_src(
		$product->get_image_id(),
		'woocommerce_thumbnail'
	) : false;
	$src        = is_array( $src_image ) ? current( $src_image ) : wc_placeholder_img_src();

	$image = '<a href="' . $product->get_permalink() . '"><img src="' . $src . '" height="' . $height . '" width="' . $width . '" /></a>';
} else {
	$image = '';
}


?>

<div class="yith-wcwtl-product-info 
<?php
echo $show_thumb ? 'image-yes' : 'image-no';
echo isset( $is_promotional ) ? ' promotional' : '';
?>
">
	<?php if ( $show_thumb ) : ?>
		<div class="wrap-image">
			<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
		</div>
	<?php endif; ?>
	<div class="wrap-title">
		<p class="product-title"><?php echo esc_html( $product_title ); ?></p>
		<p class="product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
		<?php $url = $product->get_permalink(); ?>
		<a href="<?php echo esc_url( $url ); ?>" class="action-button"> <?php echo esc_html_x( 'Shop now', '[EMAIL: In stock] Button label', 'yith-woocommerce-waiting-list' ); ?> </a>
	</div>
</div>
