<?php
/**
 * Install file
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce moodboard
 * @version 2.0.10
 */

if ( !defined( 'YITH_mdbd' ) ) { exit; } // Exit if accessed directly

if( !function_exists( 'yith_mdbd_is_moodboard' ) ){
    /**
     * Check if we're printing moodboard shortcode
     *
     * @param string $path
     * @param array $var
     * @return bool
     * @since 2.0.0
     */
    function yith_mdbd_is_moodboard(){
        global $yith_mdbd_is_moodboard;

        return $yith_mdbd_is_moodboard;
    }
}

if( !function_exists( 'yith_mdbd_is_moodboard_page' ) ){
    /**
     * Check if current page is moodboard
     *
     * @return bool
     * @since 2.0.13
     */
    function yith_mdbd_is_moodboard_page(){
        $moodboard_page_id = yith_mdbd_object_id( get_option( 'yith_mdbd_moodboard_page_id' ) );

        if( ! $moodboard_page_id ){
            return false;
        }

        return is_page( $moodboard_page_id );
    }
}

if( !function_exists( 'yith_mdbd_locate_template' ) ) {
    /**
     * Locate the templates and return the path of the file found
     *
     * @param string $path
     * @param array $var
     * @return void
     * @since 1.0.0
     */
    function yith_mdbd_locate_template( $path, $var = NULL ){
        global $woocommerce;

        if( function_exists( 'WC' ) ){
            $woocommerce_base = WC()->template_path();
        }
        elseif( defined( 'WC_TEMPLATE_PATH' ) ){
            $woocommerce_base = WC_TEMPLATE_PATH;
        }
        else{
            $woocommerce_base = $woocommerce->plugin_path() . '/templates/';
        }

    	$template_woocommerce_path =  $woocommerce_base . $path;
        $template_path = '/' . $path;
        $plugin_path = YITH_mdbd_DIR . 'templates/' . $path;
    	
    	$located = locate_template( array(
            $template_woocommerce_path, // Search in <theme>/woocommerce/
            $template_path,             // Search in <theme>/
        ) );

        if( ! $located && file_exists( $plugin_path ) ){
            return apply_filters( 'yith_mdbd_locate_template', $plugin_path, $path );
        }

        return apply_filters( 'yith_mdbd_locate_template', $located, $path );
    }
}

if( !function_exists( 'yith_mdbd_get_template' ) ) {
    /**
     * Retrieve a template file.
     * 
     * @param string $path
     * @param mixed $var
     * @param bool $return
     * @return void
     * @since 1.0.0
     */
    function yith_mdbd_get_template( $path, $var = null, $return = false ) {
        $located = yith_mdbd_locate_template( $path, $var );
        
        if ( $var && is_array( $var ) ) 
    		extract( $var );
                               
        if( $return )
            { ob_start(); }   
                                                                     
        // include file located
        include( $located );
        
        if( $return )
            { return ob_get_clean(); }
    }
}

if( !function_exists( 'yith_mdbd_count_products' ) ) {
    /**
     * Retrieve the number of products in the moodboard.
     *
     * @param $moodboard_token string Optional moodboard token
     * 
     * @return int
     * @since 1.0.0
     */
    function yith_mdbd_count_products( $moodboard_token = false ) {
        return YITH_mdbd()->count_products( $moodboard_token );
    }
}

if( !function_exists( 'yith_mdbd_count_all_products' ) ) {
    /**
     * Retrieve the number of products in all the moodboards.
     *
     * @return int
     * @since 2.0.13
     */
    function yith_mdbd_count_all_products() {
        return YITH_mdbd()->count_all_products();
    }
}

if( !function_exists( 'yith_mdbd_count_add_to_moodboard' ) ){
    /**
     * Count number of times a product was added to users moodboards
     *
     * @return int Number of times the product was added to moodboards
     * @since 2.0.13
     */
    function yith_mdbd_count_add_to_moodboard( $product_id = false ){
        return YITH_mdbd()->count_add_to_moodboard( $product_id );
    }
}

if( !function_exists( 'yith_frontend_css_color_picker' ) ) {
    /**
     * Output a colour picker input box.
     * 
     * This function is not of the plugin YITH mdbd. It is from WooCommerce.
     * We redeclare it only because it is needed in the tab "Styles" where it is not available.
     * The original function name is woocommerce_frontend_css_colorpicker and it is declared in
     * wp-content/plugins/woocommerce/admin/settings/settings-frontend-styles.php
     *
     * @access public
     * @param mixed $name
     * @param mixed $id
     * @param mixed $value
     * @param string $desc (default: '')
     * @return void
     */
    function yith_frontend_css_color_picker( $name, $id, $value, $desc = '' ) {
    	global $woocommerce;

        $value = ! empty( $value ) ? $value : '#ffffff';

        echo '<div  class="color_box">
                  <table><tr><td>
                  <strong>' . $name . '</strong>
       		      <input name="' . esc_attr( $id ). '" id="' . $id . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick colorpickpreview" style="background-color: ' . $value . '" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
       		      </td></tr></table>
       		  </div>';
    
    }
}

if( !function_exists( 'yith_setcookie' ) ) {
    /**
     * Create a cookie.
     * 
     * @param string $name
     * @param mixed $value
     * @return bool
     * @since 1.0.0
     */
    function yith_setcookie( $name, $value = array(), $time = null ) {
        $time = $time != null ? $time : time() + apply_filters( 'yith_mdbd_cookie_expiration', 60 * 60 * 24 * 30 );
        
        //$value = maybe_serialize( stripslashes_deep( $value ) );
        $value = json_encode( stripslashes_deep( $value ) );
        $expiration = apply_filters( 'yith_mdbd_cookie_expiration_time', $time ); // Default 30 days

        $_COOKIE[ $name ] = $value;
	    wc_setcookie( $name, $value, $expiration, false );
    }
}

if( !function_exists( 'yith_getcookie' ) ) {
    /**
     * Retrieve the value of a cookie.
     * 
     * @param string $name
     * @return mixed
     * @since 1.0.0
     */
    function yith_getcookie( $name ) {
        if( isset( $_COOKIE[$name] ) ) {
	        return json_decode( stripslashes( $_COOKIE[$name] ), true );
        }
        
        return array();
    }
}

if( !function_exists( 'yith_usecookies' ) ) {
    /**
     * Check if the user want to use cookies or not.
     * 
     * @return bool
     * @since 1.0.0
     */
    function yith_usecookies() {
        return get_option( 'yith_mdbd_use_cookie' ) == 'yes' ? true : false;
    }
}

if( !function_exists ( 'yith_destroycookie' ) ) {
    /**
     * Destroy a cookie.
     * 
     * @param string $name
     * @return void
     * @since 1.0.0
     */
    function yith_destroycookie( $name ) {
        yith_setcookie( $name, array(), time() - 3600 );
    }
}

if( !function_exists( 'yith_mdbd_object_id' ) ){
    /**
     * Retrieve translated page id, if wpml is installed
     *
     * @param $id int Original page id
     * @return int Translation id
     * @since 1.0.0
     */
    function yith_mdbd_object_id( $id ){
        if( function_exists( 'wpml_object_id_filter' ) ){
            return wpml_object_id_filter( $id, 'page', true );
        }
        elseif( function_exists( 'icl_object_id' ) ){
            return icl_object_id( $id, 'page', true );
        }
        else{
            return $id;
        }
    }
}