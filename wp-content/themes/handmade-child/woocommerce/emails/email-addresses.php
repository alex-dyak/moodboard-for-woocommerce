<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';

$shipping_method = $order->get_shipping_method();

?><table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top;" border="0">
	<tr>
        <?php
        if( $shipping_method == 'Доставка Укр Почта' ) :
        ?>
		<td class="td" style="text-align: center; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" valign="top" width="50%">
			<h3><?php _e( 'Укр Почта', 'woocommerce' ); ?></h3>

			<p class="text"><?php echo $order->get_formatted_billing_address(); ?></p>
		</td>
        <?php elseif ( $shipping_method == 'Доставка Новая Почта' ) : ?>
		<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && ( $shipping = $order->get_formatted_shipping_address() ) ) : ?>
			<td class="td" style="text-align: center; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" valign="top" width="50%">
				<h3><?php _e( 'Новая Почта', 'woocommerce' ); ?></h3>

				<p class="text"><?php echo $shipping; ?></p>
               <?php if( ! empty( $_POST['shipping_phone'] ) ): ?>
                <p class="text"><?php echo __( 'Телефон: ', 'woocommerce' ) . $_POST['shipping_phone']; ?></p>
               <?php endif; ?>
              <?php if ( ! empty( $_POST['shipping_post_number'] ) ) : ?>
                   <p class="text"><?php echo __( 'Номер отделения: ', 'woocommerce' ) . $_POST['shipping_post_number']; ?></p>
                <?php endif; ?>
			</td>
		<?php endif; ?>
		<?php endif; ?>
	</tr>
</table>
