<?php
/*
* Template Name: Moodboard Template
*/
?>

<?php get_header(); ?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
<link rel="stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css">

<style>
	.product-thumbnail {
		width: 200px;
		height: 200px;
	}

	#box {
		width: 100%;
		height: 800px;
		border: 2px dotted black;
		background-color: #ccc;
		padding:10px;
	}
	#section
	{
		width: 550px;
		margin:10px 0;
		display: inline-table;
	}
</style>

<script>
	zindex = 11;

	$(function() {

		$('.product-thumbnail').draggable({});

		$('#box').droppable({
			drop: function() {
				//$('.product-thumbnail').css("width", "200px");
			}
		});

	});

</script>


<?php
global $woocommerce;
global $product;
// Get cart items.
$cart = WC()->cart->get_cart();

// Get Wishlist items.
$user_id = get_current_user_id();
$wishlists = YITH_WCWL()->get_wishlists();
if ( ! empty( $wishlists ) && isset( $wishlists[0] ) ) {
	$wishlist_id = $wishlists[0]['wishlist_token'];
} else {
	$wishlist_id = false;
}
$query_args = array();
$query_args[ 'wishlist_token' ] = $wishlist_id;
$query_args[ 'wishlist_visibility' ] = 'visible';
$wishlist_items = YITH_WCWL()->get_products( $query_args );
?>


<?php
if ( $cart ) : ?>

<h2><?php _e('Товары из корзины', 'handmade-child'); ?></h2>
<table>
	<tr>
			<?php
			foreach ( $cart as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				$attachment_ids[0] = get_post_thumbnail_id( $product_id );
				$attachment = wp_get_attachment_image_src($attachment_ids[0], 'medium' );
			}
			?>

				<td class="product-thumbnail" onmousedown="style.zIndex = zindex++">
				<img class="dragElement"
				     src="<?php echo $attachment[0] ; ?>"
				     id="<?php  echo $product_id; ?>"
					/>
				</td>
			<?php } ?>

	</tr>
</table>
<?php endif; ?>

	<?php	if ( count( $wishlist_items ) > 0 ) {
			$added_items = array();
		?>
		<h2><?php _e('Товары из списка желаний', 'handmade-child'); ?></h2>
		<table>
			<tr>
				<?php
					foreach ( $wishlist_items as $item ) {
						global $product;

						$item['prod_id'] = yit_wpml_object_id( $item['prod_id'],
							'product', TRUE );

						if ( in_array( $item['prod_id'], $added_items ) ) {
							continue;
						}

						$added_items[] = $item['prod_id'];
						$product = wc_get_product( $item['prod_id'] );
						$attachment_ids[0] = get_post_thumbnail_id( $item['prod_id'] );
						$attachment = wp_get_attachment_image_src( $attachment_ids[0], 'medium' );

						if ( $product && $product->exists() ) {
							?>
							<td class="product-thumbnail" onmousedown="style.zIndex = zindex++">
								<img class="dragElement"
								     src="<?php echo $attachment[0]; ?>"
								     id="<?php echo $item['prod_id']; ?>"
								     />
							</td>
						<?php
						}
					}
				}
				?>

			</tr>
		</table>


<!--<div id="box" ondragenter="dragEnter(event)" ondrop="dragDrop(event)" ondragover="dragOver(event)"></div>-->
<div id="box"></div>

<?php get_footer(); ?>
