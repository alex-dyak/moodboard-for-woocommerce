<?php
/**
 * Add to moodboard template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce moodboard
 * @version 2.0.0
 */

if ( ! defined( 'YITH_mdbd' ) ) {
	exit;
} // Exit if accessed directly

global $product;
?>

<div class="yith-mdbd-add-to-moodboard add-to-moodboard-<?php echo $product_id ?>">
	<?php if( ! ( $disable_moodboard && ! is_user_logged_in() ) ): ?>
	    <div class="yith-mdbd-add-button <?php echo ( $exists && ! $available_multi_moodboard ) ? 'hide': 'show' ?>" style="display:<?php echo ( $exists && ! $available_multi_moodboard ) ? 'none': 'block' ?>">

	        <?php yith_mdbd_get_template( 'add-to-moodboard-' . $template_part . '.php', $atts ); ?>

	    </div>

	    <div class="yith-mdbd-moodboardaddedbrowse hide" style="display:none;">
	        <span class="feedback"><?php echo $product_added_text ?></span>
	        <a href="<?php echo esc_url( $moodboard_url )?>" rel="nofollow">
	            <?php echo apply_filters( 'yith-mdbd-browse-moodboard-label', $browse_moodboard_text )?>
	        </a>
	    </div>

	    <div class="yith-mdbd-moodboardexistsbrowse <?php echo ( $exists && ! $available_multi_moodboard ) ? 'show' : 'hide' ?>" style="display:<?php echo ( $exists && ! $available_multi_moodboard ) ? 'block' : 'none' ?>">
	        <span class="feedback"><?php echo $already_in_wishslist_text ?></span>
	        <a href="<?php echo esc_url( $moodboard_url ) ?>" rel="nofollow">
	            <?php echo apply_filters( 'yith-mdbd-browse-moodboard-label', $browse_moodboard_text )?>
	        </a>
	    </div>

	    <div style="clear:both"></div>
	    <div class="yith-mdbd-moodboardaddresponse"></div>
	<?php else: ?>
		<a href="<?php echo esc_url( add_query_arg( array( 'moodboard_notice' => 'true', 'add_to_moodboard' => $product_id ), get_permalink( wc_get_page_id( 'myaccount' ) ) ) )?>" rel="nofollow" class="<?php echo str_replace( 'add_to_moodboard', '', $link_classes ) ?>" >
			<?php echo $icon ?>
			<?php echo $label ?>
		</a>
	<?php endif; ?>

</div>

<div class="clear"></div>