<?php
/**
 * The template for displaying product category thumbnails within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="product-category product-item-wrap">

	<?php do_action( 'woocommerce_before_subcategory', $category ); ?>
		<div class="product-category-inner">
		<?php
			/**
			 * woocommerce_before_subcategory_title hook
			 *
			 * @hooked woocommerce_subcategory_thumbnail - 10
			 */
			do_action( 'woocommerce_before_subcategory_title', $category );
		?>

				<div class="text-center">
					<a class="p-color-bg" href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">
						<?php echo esc_html($category->name); ?>
						<i class="pe-7s-right-arrow"></i>
					</a>
				</div>

		<?php
			/**
			 * woocommerce_after_subcategory_title hook
			 */
			do_action( 'woocommerce_after_subcategory_title', $category );
		?>
		</div>
	<?php do_action( 'woocommerce_after_subcategory', $category ); ?>

</div>
