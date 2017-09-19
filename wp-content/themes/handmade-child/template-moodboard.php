<?php
/*
* Template Name: Moodboard Template
*/
?>

<?php get_header(); ?>

<style>
	td {
		width: 64px;
		height: 64px;
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
	function dragStart(e) {
		e.dataTransfer.setData("text/plain",e.target.id);
	}

	function dragEnter(e) {
		event.preventDefault();
		return true;
	}

	function dragDrop(e) {
		var data = e.dataTransfer.getData("text/plain");
		e.target.appendChild(document.getElementById(data));
	}

	function dragOver(e) {
		event.preventDefault();
	}


	// Movement inside Box.
	var ball = document.getElementById('ball');

	ball.onmousedown = function(e) {

		var coords = getCoords(ball);
		var shiftX = e.pageX - coords.left;
		var shiftY = e.pageY - coords.top;

		ball.style.position = 'absolute';
		document.body.appendChild(ball);
		moveAt(e);

		ball.style.zIndex = 1000; // над другими элементами

		function moveAt(e) {
			ball.style.left = e.pageX - shiftX + 'px';
			ball.style.top = e.pageY - shiftY + 'px';
		}

		document.onmousemove = function(e) {
			moveAt(e);
		};

		ball.onmouseup = function() {
			document.onmousemove = null;
			ball.onmouseup = null;
		};

	}

	ball.ondragstart = function() {
		return false;
	};


	function getCoords(elem) { // кроме IE8-
		var box = elem.getBoundingClientRect();

		return {
			top: box.top + pageYOffset,
			left: box.left + pageXOffset
		};

	}
</script>


<?php
global $woocommerce;
// Get cart items.
$cart = WC()->cart->get_cart();
// Get Wishlist items.
$query_args = array();
$wishlist_items = YITH_WCWL()->get_products( $query_args );

?>

<div id="section" ondragenter="dragEnter(event)" ondrop="dragDrop(event)" ondragover="dragOver(event)">
	<table border="1">
		<tr>
			<td><img src="http://magazin.loc/wp-content/uploads/2017/04/5.png" id="ball" draggable="true" ondragstart="dragStart(event)"
			         style="cursor: pointer; position: absolute; left: 248px; top: 129px; z-index: 1000;"/></td>
		<?php
		if ( $cart ) {
			?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				$attachment_ids[0] = get_post_thumbnail_id( $product_id );
				$attachment = wp_get_attachment_image_src($attachment_ids[0], 'medium' );
			}
			?>
			<td>
				<img src="<?php echo $attachment[0] ; ?>" id="<?php  echo $product_id; ?>" draggable="true" ondragstart="dragStart(event)" />
			</td>
			<?php
			}
		}
		if( count( $wishlist_items ) > 0 ) :
		$added_items = array();
		foreach( $wishlist_items as $item ) :
		global $product;

		$item['prod_id'] = yit_wpml_object_id ( $item['prod_id'], 'product', true );

		if( in_array( $item['prod_id'], $added_items ) ){
			continue;
		}

		$added_items[] = $item['prod_id'];
		$product = wc_get_product( $item['prod_id'] );
		$attachment_ids[0] = get_post_thumbnail_id(  $item['prod_id'] );
		$attachment = wp_get_attachment_image_src($attachment_ids[0], 'medium' );

		if( $product && $product->exists() ) :
		?>

			<td class="product-thumbnail">
				<img src="<?php echo $attachment[0] ; ?>" id="<?php  echo  $item['prod_id']; ?>" draggable="true" ondragstart="dragStart(event)" />
			</td>

			<?php endif;
		endforeach;
		endif; ?>

		</tr>
	</table>
</div>

<div id="box" ondragenter="dragEnter(event)" ondrop="dragDrop(event)" ondragover="dragOver(event)"></div>
<?php get_footer(); ?>
