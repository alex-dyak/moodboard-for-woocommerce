<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop,$g5plus_options,$g5plus_woocommerce_loop;

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;



// Extra post classes
$classes = array();
$classes[] = 'product-item-wrap';
$product_rating = $g5plus_woocommerce_loop['rating'];
if ($product_rating === '') {
	$product_rating = $g5plus_options['product_show_rating'];
}

if ($product_rating == 0) {
    $classes[] = 'rating-visible';
}



$product_quick_view = $g5plus_options['product_quick_view'];
if ($product_quick_view == 0) {
	$classes[] = 'quick-view-visible';
}

?>
<div <?php post_class( $classes ); ?>>
    <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
    <div class="product-item-inner">
        <div class="product-thumb">
            <?php
            /**
             * woocommerce_before_shop_loop_item_title hook
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             * @hooked g5plus_woocomerce_template_loop_link - 20
             *
             */
            do_action( 'woocommerce_before_shop_loop_item_title' );
            ?>
			<div class="product-actions">
				<?php
				/**
				 * g5plus_woocommerce_product_action hook
				 *
                 * @hooked g5plus_woocomerce_template_loop_compare - 5
                 * @hooked g5plus_woocomerce_template_loop_wishlist - 10
                 * @hooked g5plus_woocomerce_template_loop_quick_view - 15
				 * @hooked woocommerce_template_loop_add_to_cart - 20
				 */
				do_action( 'g5plus_woocommerce_product_actions' );
				?>
			</div>
        </div>
        <div class="product-info">
            <?php
            /**
             * woocommerce_after_shop_loop_item_title hook
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_product_title - 6
             * @hooked woocommerce_template_loop_price - 10
             */
            do_action( 'woocommerce_after_shop_loop_item_title' );
            ?>
        </div>

    </div>
</div>

