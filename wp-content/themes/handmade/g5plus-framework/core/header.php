<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/1/2015
 * Time: 5:50 PM
 */
/*================================================
SITE LOADING
================================================== */
if (!function_exists('g5plus_site_loading')) {
	function g5plus_site_loading(){
        g5plus_get_template('site-loading');
	}
	add_action('g5plus_before_page_wrapper','g5plus_site_loading',5);
}
/*================================================
PAGE HEADING
================================================== */
if (!function_exists('g5plus_page_heading')) {
	function g5plus_page_heading() {
		g5plus_get_template('page-heading');
	}
	add_action('g5plus_before_page','g5plus_page_heading',5);
}
/*================================================
ARCHIVE HEADING
================================================== */
if (!function_exists('g5plus_archive_heading')) {
	function g5plus_archive_heading() {
		g5plus_get_template('archive-heading');
	}
	add_action('g5plus_before_archive','g5plus_archive_heading',5);
}

if (!function_exists('g5plus_archive_product_heading')) {
    function g5plus_archive_product_heading() {
        g5plus_get_template('archive-product-heading');
    }
    add_action('g5plus_before_archive_product','g5plus_archive_product_heading',5);
}

/*================================================
ABOVE HEADER
================================================== */
if (!function_exists('g5plus_page_top_drawer')) {
	function g5plus_page_top_drawer() {
		g5plus_get_template('top-drawer-template');
	}
	add_action('g5plus_before_page_wrapper_content','g5plus_page_top_drawer',10);
}

/*================================================
TOP BAR
================================================== */
if (!function_exists('g5plus_page_top_bar')) {
	function g5plus_page_top_bar() {
		g5plus_get_template('top-bar-template');
	}
	add_action('g5plus_before_page_wrapper_content','g5plus_page_top_bar',10);
}

/*================================================
HEADER
================================================== */
if (!function_exists('g5plus_page_header')) {
	function g5plus_page_header() {
		g5plus_get_template('header-template');
	}
	add_action('g5plus_before_page_wrapper_content','g5plus_page_header',15);
}