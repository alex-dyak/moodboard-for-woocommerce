<?php
/**
 * Color settings page
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce moodboard
 * @version 2.0.0
 */

if ( ! defined( 'YITH_mdbd' ) ) {
	exit;
} // Exit if accessed directly

$options = apply_filters( 'yith_mdbd_tab_options', YITH_mdbd_Admin_Init()->options );

return array(
	'colors' => array_merge(
		$options['styles'],
		array(
			'custom_color_panel' => array(
				'id' => 'yith_mdbd_color_panel',
				'type' => 'yith_mdbd_color_panel'
			)
		)
	)
);