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

return array(
	'premium' => array(
		'landing' => array(
			'type' => 'custom_tab',
			'action' => 'yith_mdbd_premium_tab',
			'hide_sidebar' => true
		)
	)
);