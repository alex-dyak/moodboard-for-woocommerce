<?php
/**
 * General settings page
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce moodboard
 * @version 2.0.0
 */

if ( ! defined( 'YITH_mdbd' ) ) {
	exit;
} // Exit if accessed directly


$options = apply_filters( 'yith_mdbd_tab_options', YITH_mdbd_Admin_Init()->options );
$premium_options = isset( $options['premium'] ) ? $options['premium'] : array();

$options['general_settings']['section_general_settings_videobox']['default']['button']['href'] = YITH_mdbd_Admin_Init()->get_premium_landing_uri();

return array(
	'settings' => array_merge( $options['general_settings'], $options['socials_share'], $options['yith_wfbt_integration'], $premium_options )
);