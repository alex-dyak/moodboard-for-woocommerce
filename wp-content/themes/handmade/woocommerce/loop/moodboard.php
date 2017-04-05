<?php
if ( in_array( 'yith-woocommerce-moodboard/init.php',
		apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
     && ( get_option( 'yith_mdbd_enabled' ) == 'yes' )
) {
	echo do_shortcode( '[yith_mdbd_add_to_moodboard]' );
}
