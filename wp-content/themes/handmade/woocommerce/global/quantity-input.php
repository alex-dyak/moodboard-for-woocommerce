<?php
/**
 * Product quantity inputs
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.5.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
?>
<div class="quantity">
	<div class="quantity-inner">
		<button class="btn-number" data-type="minus">
			<i class="pe-7s-less"></i>
		</button>
		<input type="text" step="<?php echo esc_attr($step); ?>"
		       <?php if (is_numeric($min_value)) : ?>min="<?php echo esc_attr($min_value); ?>"<?php endif; ?>
		       <?php if (is_numeric($max_value)) : ?>max="<?php echo esc_attr($max_value); ?>"<?php endif; ?>
		       name="<?php echo esc_attr($input_name); ?>" value="<?php echo esc_attr($input_value); ?>"
		       title="<?php _ex('Qty', 'Product quantity input tooltip', 'g5plus-handmade') ?>" class="input-text qty text"
		       size="4"/>
		<button class="btn-number" data-type="plus">
			<i class="pe-7s-plus"></i>
		</button>
	</div>
</div>
