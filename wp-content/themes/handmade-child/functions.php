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
	unset( $columns['g5plus_product_hot'] );
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


add_filter( 'woocommerce_default_address_fields' , 'override_default_address_fields' );
function override_default_address_fields( $address_fields ) {
	// @ for postcode
	$address_fields['postcode']['label'] = __('Номер отделения', 'woocommerce');

	return $address_fields;
}

add_filter( 'woocommerce_product_tabs', 'woo_reorder_tabs', 98 );
function woo_reorder_tabs( $tabs ) {

	$tabs['reviews']['priority'] = 15;			// Reviews third
	$tabs['description']['priority'] = 10;			// Description second
	$tabs['additional_information']['priority'] = 5;	// Additional information first

	return $tabs;
}

// Uncheck different address checkbox.
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );
