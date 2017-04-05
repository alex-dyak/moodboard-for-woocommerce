<?php
/**
 * moodboard pages template; load template parts basing on the url
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce moodboard
 * @version 2.0.5
 */

if ( ! defined( 'YITH_mdbd' ) ) {
	exit;
} // Exit if accessed directly

global $wpdb, $woocommerce;

?>
<div id="yith-mdbd-messages"></div>

<?php yith_mdbd_get_template( 'moodboard-' . $template_part . '.php', $atts ) ?>