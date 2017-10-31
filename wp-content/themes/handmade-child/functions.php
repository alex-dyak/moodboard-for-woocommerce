<?php
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_styles', 1000 );
function child_theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'g5plus_framework_style' ) );
}

add_action( 'after_setup_theme', 'g5plus_child_theme_setup');
function g5plus_child_theme_setup(){
    $language_path = get_stylesheet_directory() .'/languages';
    if(is_dir($language_path)){
        load_child_theme_textdomain('g5plus-handmade', $language_path );
    }
}

// Include Widgets and Sidebars.
require_once( get_stylesheet_directory() . '/inc/widgets-sidebars.php' );

add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args' );
function custom_woocommerce_get_catalog_ordering_args( $args ) {

	$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

	if ( 'name_list' == $orderby_value ) {
		$args['orderby'] = 'title';
		$args['order'] = 'ASC';
		$args['meta_key'] = '';
	}

	return $args;

}

add_filter( 'woocommerce_default_catalog_orderby_options', 'custom_woocommerce_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby' );

function custom_woocommerce_catalog_orderby( $sortby ) {
	$sortby['name_list'] = 'По имени';

	return $sortby;
}

// Remove and add products columns.
add_filter( 'manage_edit-product_columns', 'new_product_column',11 );
function new_product_column($columns){

	//remove column
	unset( $columns['wp-statistics'] );
	unset( $columns['g5plus_product_new'] );
	//unset( $columns['g5plus_product_hot'] );
	unset( $columns['featured'] );
	unset( $columns['product_type'] );

	//add column
	$columns['collections'] = __( 'Коллекция');

	return $columns;
}

// Adding the data for each collections column.
add_action( 'manage_product_posts_custom_column' , 'product_list_column_content', 10, 2 );
function product_list_column_content( $column, $postid ) {
	global $post;
	$terms                    = get_the_terms( $post->ID, 'collections' );
	if ( $terms ) {
		$product_collections_name = '';
		switch ( $column ) {
			case 'collections' :
				foreach ( $terms as $term ) {
					$product_collections_name = $term->name;
					break;
				}
				echo $product_collections_name;
				break;
		}
	}
}

add_filter('manage_edit-product_sortable_columns', 'add_views_sortable_column');
function add_views_sortable_column($sortable_columns){
	$sortable_columns['collections'] = array('collections', 'desc');

	return $sortable_columns;
}


add_action( 'restrict_manage_posts', 'collections_admin_posts_filter_restrict_manage_posts' );
/**
 * Create the filter dropdown.
 *
 * @return void
 */
function collections_admin_posts_filter_restrict_manage_posts(){
	global $typenow;
	if( $typenow == 'product' ){
		$taxes = array( 'collections' );
		foreach ( $taxes as $tax ) {
			$current_tax = isset( $_GET[$tax] ) ? $_GET[$tax] : '';
			$tax_obj = get_taxonomy($tax);

			$terms = get_terms($tax);
			if(count($terms) > 0) {
				echo "<select name='$tax' id='$tax' class='postform'>";
				echo "<option value=''>Все Коллекции</option>";
				foreach ($terms as $term) {
					echo '<option value='. $term->slug, $current_tax == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
				}
				echo "</select>";
			}
		}
	}

}

// Rename form fields.
add_filter( 'woocommerce_default_address_fields' , 'override_default_address_fields' );
function override_default_address_fields( $address_fields ) {

	unset($address_fields['company']);
	unset($address_fields['address_2']);
	unset($address_fields['state']);
	unset($address_fields['address_2']);
	// @ for postcode
	//$address_fields['postcode']['label'] = __('Номер отделения', 'woocommerce');

	return $address_fields;
}

add_filter( 'woocommerce_product_tabs', 'woo_reorder_tabs', 98 );
function woo_reorder_tabs( $tabs ) {

	$tabs['reviews']['priority'] = 15;			// Reviews third
	$tabs['reviews']['title'] = __('Отзывы', 'woocommerce');
	$tabs['reviews']['callback'] = 'comments_template';
	$tabs['description']['priority'] = 10;			// Description second
	$tabs['additional_information']['priority'] = 5;	// Additional information first

	return $tabs;
}

// Uncheck different address checkbox.
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );

// Redirect after logout.
add_action('wp_logout','auto_redirect_after_logout');
function auto_redirect_after_logout(){
  wp_redirect( home_url() );
  exit();
}

add_filter( 'woocommerce_my_account_my_address_formatted_address', function( $args, $customer_id, $name ){
	// the phone is saved as billing_phone and shipping_phone
	$args['phone'] = get_user_meta( $customer_id, $name . '_phone', true );
	$args['email'] = get_user_meta( $customer_id, $name . '_email', true );
	return $args;
}, 10, 3 );

// modify the address formats
add_filter( 'woocommerce_localisation_address_formats', function( $formats ){
	foreach ( $formats as $key => &$format ) {
		// put a break and then the phone after each format.
		$format .= "\n{phone}";
		$format .= "\n{email}";
	}
	return $formats;
} );

// add the replacement value
add_filter( 'woocommerce_formatted_address_replacements', function( $replacements, $args ){
	// we want to replace {phone} in the format with the data we populated
	if ( array_key_exists('phone', $args ) ) {
		$replacements['{phone}'] = $args['phone'];
	} else {
		$replacements['{phone}'] = '';
	}

	if ( array_key_exists('email', $args ) ) {
		$replacements['{email}'] = $args['email'];
	} else {
		$replacements['{email}'] = '';
	}

	return $replacements;
}, 10, 2 );

/**
 * Adds a meta box to the product editing screen
 */
function prfx_custom_meta() {
	add_meta_box( 'prfx_meta', __( 'Рекомендованный товар', 'prfx-textdomain' ), 'prfx_meta_callback', 'product', 'side' );
}
add_action( 'add_meta_boxes', 'prfx_custom_meta' );

/**
 * Outputs the content of the meta box
 */
function prfx_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
	$prfx_stored_meta = get_post_meta( $post->ID );
	?>

	<p>
		<label for="_recommended" class="prfx-row-title"><?php _e( 'Рекомендовать', 'prfx-textdomain' )?></label>
		<input type="checkbox" name="_recommended" id="_recommended"
		       <?php if ( isset($prfx_stored_meta['_recommended'][0]) &&  $prfx_stored_meta['_recommended'][0] == true ) { ?>checked="checked"<?php } ?> />
	</p>

	<?php
}

/**
 * Saves the custom meta input
 */
function prfx_meta_save( $post_id ) {

	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'prfx_nonce' ] ) && wp_verify_nonce( $_POST[ 'prfx_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}

	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ '_recommended' ] ) ) {
		update_post_meta( $post_id, '_recommended',  $_POST[ '_recommended' ] );
	}
    else {
      update_post_meta( $post_id, '_recommended',  '' );
    }

}
add_action( 'save_post', 'prfx_meta_save' );
