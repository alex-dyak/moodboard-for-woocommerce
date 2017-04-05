<?php
/**
 * Uninstall plugin
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce moodboard
 * @version 2.0.16
 */

// If uninstall not called from WordPress exit
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

function yith_mdbd_uninstall(){
    global $wpdb;

    // define local private attribute
    $wpdb->yith_mdbd_items = $wpdb->prefix . 'yith_mdbd';
    $wpdb->yith_mdbd_moodboards = $wpdb->prefix . 'yith_mdbd_lists';

    // Delete option from options table
    delete_option( 'yith_mdbd_version' );
    delete_option( 'yith_mdbd_db_version' );
    $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", 'yith_mdbd_%' ) );

    //delete pages created for this plugin
    wp_delete_post( get_option( 'yith-mdbd-pageid' ), true );

    //remove any additional options and custom table
    $sql = "DROP TABLE IF EXISTS `" . $wpdb->yith_mdbd_items . "`";
    $wpdb->query( $sql );
    $sql = "DROP TABLE IF EXISTS `" . $wpdb->yith_mdbd_moodboards . "`";
    $wpdb->query( $sql );
}



if ( ! is_multisite() ) {
    yith_mdbd_uninstall();
}
else {
    global $wpdb;
    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
    $original_blog_id = get_current_blog_id();

    foreach ( $blog_ids as $blog_id ) {
        switch_to_blog( $blog_id );
        yith_mdbd_uninstall();
    }

    switch_to_blog( $original_blog_id );
}