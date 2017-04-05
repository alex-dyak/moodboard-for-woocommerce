<?php
/**
* Plugin Name: YITH WooCommerce Moodboard
* Description: YITH WooCommerce Moodboard allows you to add moodboard functionality to your e-commerce.
*/

/*  Copyright 2013  Your Inspiration Themes  (email : plugins@yithemes.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

if ( ! defined( 'YITH_mdbd' ) ) {
    define( 'YITH_mdbd', true );
}

if ( ! defined( 'YITH_mdbd_URL' ) ) {
    define( 'YITH_mdbd_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'YITH_mdbd_DIR' ) ) {
    define( 'YITH_mdbd_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YITH_mdbd_INC' ) ) {
    define( 'YITH_mdbd_INC', YITH_mdbd_DIR . 'includes/' );
}

if ( ! defined( 'YITH_mdbd_INIT' ) ) {
    define( 'YITH_mdbd_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_mdbd_FREE_INIT' ) ) {
    define( 'YITH_mdbd_FREE_INIT', plugin_basename( __FILE__ ) );
}

/* Plugin Framework Version Check */
if( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_mdbd_DIR . 'plugin-fw/init.php' ) ) {
    require_once( YITH_mdbd_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YITH_mdbd_DIR  );

if( ! function_exists( 'yith_moodboard_constructor' ) ) {
    function yith_moodboard_constructor() {

        load_plugin_textdomain( 'yith-woocommerce-moodboard', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

        // Load required classes and functions
        require_once( YITH_mdbd_INC . 'functions.yith-mdbd.php' );
        require_once( YITH_mdbd_INC . 'class.yith-mdbd.php' );
        require_once( YITH_mdbd_INC . 'class.yith-mdbd-init.php' );
        require_once( YITH_mdbd_INC . 'class.yith-mdbd-install.php' );

        if ( is_admin() ) {
            require_once( YITH_mdbd_INC . 'class.yith-mdbd-admin-init.php' );
        }

        if ( get_option( 'yith_mdbd_enabled' ) == 'yes' ) {
            require_once( YITH_mdbd_INC . 'class.yith-mdbd-ui.php' );
            require_once( YITH_mdbd_INC . 'class.yith-mdbd-shortcode.php' );
        }

        // Let's start the game!

        /**
         * @deprecated
         */
        global $yith_mdbd;
        $yith_mdbd = YITH_mdbd();
    }
}
add_action( 'yith_mdbd_init', 'yith_moodboard_constructor' );

if( ! function_exists( 'yith_moodboard_install' ) ) {
    function yith_moodboard_install() {

        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }

        if ( ! function_exists( 'WC' ) ) {
            add_action( 'admin_notices', 'yith_mdbd_install_woocommerce_admin_notice' );
        }
        elseif( defined( 'YITH_mdbd_PREMIUM' ) ) {
            add_action( 'admin_notices', 'yith_mdbd_install_free_admin_notice' );
            deactivate_plugins( plugin_basename( __FILE__ ) );
        }
        else {
            do_action( 'yith_mdbd_init' );
        }
    }
}
add_action( 'plugins_loaded', 'yith_moodboard_install', 11 );

if( ! function_exists( 'yith_mdbd_install_woocommerce_admin_notice' ) ) {
    function yith_mdbd_install_woocommerce_admin_notice() {
        ?>
        <div class="error">
            <p><?php _e( 'YITH WooCommerce moodboard is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-moodboard' ); ?></p>
        </div>
    <?php
    }
}

if( ! function_exists( 'yith_mdbd_install_free_admin_notice' ) ){
    function yith_mdbd_install_free_admin_notice() {
        ?>
        <div class="error">
            <p><?php _e( 'You can\'t activate the free version of YITH WooCommerce moodboard while you are using the premium one.', 'yith-woocommerce-moodboard' ); ?></p>
        </div>
    <?php
    }
}