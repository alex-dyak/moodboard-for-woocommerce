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
