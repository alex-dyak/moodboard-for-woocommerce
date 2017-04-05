<?php
/**
 * Main class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce moodboard
 * @version 2.0.9
 */

if ( ! defined( 'YITH_mdbd' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_mdbd' ) ) {
    /**
     * WooCommerce moodboard
     *
     * @since 1.0.0
     */
    class YITH_mdbd {
        /**
         * Single instance of the class
         *
         * @var \YITH_mdbd
         * @since 2.0.0
         */
        protected static $instance;

        /**
         * Errors array
         * 
         * @var array
         * @since 1.0.0
         */
        public $errors;

        /**
         * Last operation token
         *
         * @var string
         * @since 2.0.0
         */
        public $last_operation_token;
        
        /**
         * Details array
         * 
         * @var array
         * @since 1.0.0
         */
        public $details;
        
        /**
         * Messages array
         * 
         * @var array
         * @since 1.0.0
         */
        public $messages;

        /**
         * Returns single instance of the class
         *
         * @return \YITH_mdbd
         * @since 2.0.0
         */
        public static function get_instance(){
            if( is_null( self::$instance ) ){
                self::$instance = new self( $_REQUEST );
            }

            return self::$instance;
        }
        
        /**
         * Constructor.
         * 
         * @param array $details
         * @return \YITH_mdbd
         * @since 1.0.0
         */
        public function __construct( $details ) {
            $this->details = $details;                
            $this->mdbd_init = YITH_mdbd_Init();
            if( is_admin() ){
                $this->mdbd_admin_init = YITH_mdbd_Admin_Init();
            }

            add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );

            // add rewrite rule
            add_action( 'init', array( $this, 'add_rewrite_rules' ), 0 );
            add_filter( 'query_vars', array( $this, 'add_public_query_var' ) );

            add_action( 'init', array( $this, 'add_to_moodboard' ) );
            add_action( 'wp_ajax_add_to_moodboard', array( $this, 'add_to_moodboard_ajax' ) );
            add_action( 'wp_ajax_nopriv_add_to_moodboard', array( $this, 'add_to_moodboard_ajax' ) );

            add_action( 'init', array( $this, 'remove_from_moodboard' ) );
            add_action( 'wp_ajax_remove_from_moodboard', array( $this, 'remove_from_moodboard_ajax' ) );
            add_action( 'wp_ajax_nopriv_remove_from_moodboard', array( $this, 'remove_from_moodboard_ajax' ) );

	        add_action( 'wp_ajax_reload_moodboard_and_adding_elem', array( $this, 'reload_moodboard_and_adding_elem_ajax' ) );
	        add_action( 'wp_ajax_nopriv_reload_moodboard_and_adding_elem', array( $this, 'reload_moodboard_and_adding_elem_ajax' ) );

            add_action( 'woocommerce_add_to_cart', array( $this, 'remove_from_moodboard_after_add_to_cart' ) );
            add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'redirect_to_cart' ), 10, 2 );

	        add_action( 'yith_mdbd_before_moodboard_title', array( $this, 'print_notices' ) );

	        add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'yith_wfbt_redirect_after_add_to_cart' ), 10, 1 );

	        // add filter for font-awesome compatibility
	        add_filter( 'option_yith_mdbd_add_to_moodboard_icon', array( $this, 'update_font_awesome_classes' ) );
	        add_filter( 'option_yith_mdbd_add_to_cart_icon', array( $this, 'update_font_awesome_classes' ) );
        }

        /* === PLUGIN FW LOADER === */

        /**
         * Loads plugin fw, if not yet created
         *
         * @return void
         * @since 2.0.0
         */
        public function plugin_fw_loader() {
            if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
                global $plugin_fw_data;
                if( ! empty( $plugin_fw_data ) ){
                    $plugin_fw_file = array_shift( $plugin_fw_data );
                    require_once( $plugin_fw_file );
                }
            }
        }

        /* === ITEMS METHODS === */
        
        /**
         * Check if the product exists in the moodboard.
         * 
         * @param int $product_id
         * @return bool
         * @since 1.0.0
         */
        public function is_product_in_moodboard( $product_id, $moodboard_id = false ) {
            global $wpdb;
                
            $exists = false;
                
    		if( is_user_logged_in() ) {		
    			$sql = "SELECT COUNT(*) as `cnt` FROM `{$wpdb->yith_mdbd_items}` WHERE `prod_id` = %d AND `user_id` = %d";
                $sql_args = array(
                    $product_id,
                    $this->details['user_id']
                );

                if( $moodboard_id != false ){
                    $sql .= " AND `moodboard_id` = %d";
                    $sql_args[] = $moodboard_id;
                }
                elseif( $default_moodboard = $this->get_moodboards( array( 'user_id' => get_current_user_id(), 'is_default' => 1 ) ) ){
                    $default_moodboard_id = $default_moodboard[0]['ID'];

                    $sql .= " AND `moodboard_id` = %d";
                    $sql_args[] = $default_moodboard_id;
                }
                else{
                    $sql .= " AND `moodboard_id` IS NULL";
                }

    			$results = $wpdb->get_var( $wpdb->prepare( $sql, $sql_args ) );
    			$exists = (bool) ( $results > 0 );
    		}
            else {
                $moodboard = yith_getcookie( 'yith_mdbd_products' );

                foreach( $moodboard as $key => $item ){
                    if( $item['moodboard_id'] == $moodboard_id && $item['prod_id'] == $product_id ){
                        $exists = true;
                    }
                }
    		}
            
            return apply_filters( 'yith_mdbd_is_product_in_moodboard', $exists, $product_id, $moodboard_id );
        }
        
        /**
         * Add a product in the moodboard.
         * 
         * @return string "error", "true" or "exists"
         * @since 1.0.0
         */
        public function add() {
            global $wpdb;
            $prod_id = ( isset( $this->details['add_to_moodboard'] ) && is_numeric( $this->details['add_to_moodboard'] ) ) ? $this->details['add_to_moodboard'] : false;
            $moodboard_id = ( isset( $this->details['moodboard_id'] ) && strcmp( $this->details['moodboard_id'], 0 ) != 0 ) ? $this->details['moodboard_id'] : false;
            $quantity = ( isset( $this->details['quantity'] ) ) ? ( int ) $this->details['quantity'] : 1;
            $user_id = ( ! empty( $this->details['user_id'] ) ) ? $this->details['user_id'] : false;
            $moodboard_name = ( ! empty( $this->details['moodboard_name'] ) ) ? $this->details['moodboard_name'] : '';

            do_action( 'yith_mdbd_adding_to_moodboard', $prod_id, $moodboard_id, $user_id );

            // filtering params
            $prod_id = apply_filters( 'yith_mdbd_adding_to_moodboard_prod_id', $prod_id );
            $moodboard_id = apply_filters( 'yith_mdbd_adding_to_moodboard_moodboard_id', $moodboard_id );
            $quantity = apply_filters( 'yith_mdbd_adding_to_moodboard_quantity', $quantity );
            $user_id = apply_filters( 'yith_mdbd_adding_to_moodboard_user_id', $user_id );
            $moodboard_name = apply_filters( 'yith_mdbd_adding_to_moodboard_moodboard_name', $moodboard_name );

            if ( $prod_id == false ) {
                $this->errors[] = __( 'An error occurred while adding products to the moodboard.', 'yith-woocommerce-moodboard' );
                return "error";
            }

            //check for existence,  product ID, variation ID, variation data, and other cart item data
            if( strcmp( $moodboard_id, 'new' ) != 0 && $this->is_product_in_moodboard( $prod_id, $moodboard_id ) ) {
                if( $moodboard_id != false ){
                    $moodboard = $this->get_moodboard_detail( $moodboard_id );
                    $this->last_operation_token = $moodboard['moodboard_token'];
                }
                else{
                    $this->last_operation_token = false;
                }

                return "exists";
            }

            if( $user_id != false ) {

                $insert_args = array(
                    'prod_id' => $prod_id,
                    'user_id' => $user_id,
                    'quantity' => $quantity,
                    'dateadded' => date( 'Y-m-d H:i:s' )
                );

                if( ! empty( $moodboard_id ) && strcmp( $moodboard_id, 'new' ) != 0 ){
                    $insert_args[ 'moodboard_id' ] = $moodboard_id;

                    $moodboard = $this->get_moodboard_detail( $insert_args[ 'moodboard_id' ] );
                    $this->last_operation_token = $moodboard['moodboard_token'];
                }
                elseif( strcmp( $moodboard_id, 'new' ) == 0 ){
                    if( function_exists( 'YITH_mdbd_Premium' ) ){
                        $response = YITH_mdbd_Premium()->add_moodboard();
                    }
                    else{
                        $response = $this->add_moodboard();
                    }

                    if( $response == "error" ){
                        return "error";
                    }
                    else{
                        $insert_args[ 'moodboard_id' ] = $response;

                        $moodboard = $this->get_moodboard_detail( $insert_args[ 'moodboard_id' ] );
                        $this->last_operation_token = $moodboard['moodboard_token'];
                    }
                }
                elseif( empty( $moodboard_id ) ){
                    $moodboard_id = $this->generate_default_moodboard( $user_id );
                    $insert_args[ 'moodboard_id' ] = $moodboard_id;

                    if( $this->is_product_in_moodboard( $prod_id, $moodboard_id ) ){
                        return "exists";
                    }
                }

                $result = $wpdb->insert( $wpdb->yith_mdbd_items, $insert_args );

                if( $result ){
                    if( $this->last_operation_token ) {
                        delete_transient( 'yith_mdbd_moodboard_count_' . $this->last_operation_token );
                    }

                    if( $user_id ) {
                        delete_transient( 'yith_mdbd_user_default_count_' . $user_id );
                        delete_transient( 'yith_mdbd_user_total_count_' . $user_id );
                    }
                }
            }
            else {
                $cookie = array(
                    'prod_id' => $prod_id,
                    'quantity' => $quantity,
                    'moodboard_id' => $moodboard_id
                );

                $moodboard = yith_getcookie( 'yith_mdbd_products' );
                $found = $this->is_product_in_moodboard( $prod_id, $moodboard_id );

                if( ! $found ){
                    $moodboard[] = $cookie;
                }

                yith_setcookie( 'yith_mdbd_products', $moodboard );

                $result = true;
            }

            if( $result ) {
                do_action( 'yith_mdbd_added_to_moodboard', $prod_id, $moodboard_id, $user_id );
                return "true";
            }
            else {
                $this->errors[] = __( 'An error occurred while adding products to moodboard.', 'yith-woocommerce-moodboard' );
                return "error";
            }
        }
        
        /**
         * Remove an entry from the moodboard.
         *
         * @return bool
         * @since 1.0.0
         */
        public function remove( $id = false ) {
            global $wpdb;

            if( ! empty( $id ) ) {
                _deprecated_argument( 'YITH_mdbd->remove()', '2.0.0', __( 'The "Remove" option now does not require any parameter' ) );
            }

            $prod_id = ( isset( $this->details['remove_from_moodboard'] ) && is_numeric( $this->details['remove_from_moodboard'] ) ) ? $this->details['remove_from_moodboard'] : false;
            $moodboard_id = ( isset( $this->details['moodboard_id'] ) && is_numeric( $this->details['moodboard_id'] ) ) ? $this->details['moodboard_id'] : false;
            $user_id = ( ! empty( $this->details['user_id'] ) ) ? $this->details['user_id'] : false;

            if( $prod_id == false ){
                return false;
            }

            if ( is_user_logged_in() ) {
                $sql = "DELETE FROM {$wpdb->yith_mdbd_items} WHERE user_id = %d AND prod_id = %d";
                $sql_args = array(
                    $user_id,
                    $prod_id
                );

                if( empty( $moodboard_id ) ){
                    $moodboard_id = $this->generate_default_moodboard( get_current_user_id() );
                }

                $moodboard = $this->get_moodboard_detail( $moodboard_id );
                $this->last_operation_token = $moodboard['moodboard_token'];

                $sql .= " AND moodboard_id = %d";
                $sql_args[] = $moodboard_id;

                $result = $wpdb->query( $wpdb->prepare( $sql, $sql_args ) );

                if ( $result ) {
                    if( $this->last_operation_token ) {
                        delete_transient( 'yith_mdbd_moodboard_count_' . $this->last_operation_token );
                    }

                    if( $user_id ) {
                        delete_transient( 'yith_mdbd_user_default_count_' . $user_id );
                        delete_transient( 'yith_mdbd_user_total_count_' . $user_id );
                    }

                    return true;
                }
                else {
                    $this->errors[] = __( 'An error occurred while removing products from the moodboard', 'yith-woocommerce-moodboard' );
                    return false;
                }
            }
            else {
                $moodboard = yith_getcookie( 'yith_mdbd_products' );

                foreach( $moodboard as $key => $item ){
                    if( $item['moodboard_id'] == $moodboard_id && $item['prod_id'] == $prod_id ){
                        unset( $moodboard[ $key ] );
                    }
                }

                yith_setcookie( 'yith_mdbd_products', $moodboard );

                return true;
            }
        }

        /**
         * Retrieve the number of products in the moodboard.
         *
         * @return int
         * @since 1.0.0
         */
        public function count_products( $moodboard_token = false ) {
            global $wpdb;

            if( is_user_logged_in() || $moodboard_token != false ) {
                if( ! empty( $moodboard_token ) ) {
                    $count = get_transient( 'yith_mdbd_moodboard_count_' . $moodboard_token );
                }
                else{
                    $count = get_transient( 'yith_mdbd_user_default_count_' . get_current_user_id() );
                }

                if( false === $count ){
                    $sql  = "SELECT COUNT( i.`prod_id` ) AS `cnt`
                        FROM `{$wpdb->yith_mdbd_items}` AS i
                        LEFT JOIN `{$wpdb->yith_mdbd_moodboards}` AS l ON l.ID = i.moodboard_id
                        INNER JOIN `{$wpdb->posts}` AS p ON i.`prod_id` = p.`ID`
                        INNER JOIN `{$wpdb->postmeta}` AS pm ON p.`ID` = pm.`post_id`
                        WHERE p.`post_type` = %s AND p.`post_status` = %s AND pm.`meta_key` = %s AND pm.`meta_value` = %s";
                    $args = array(
                        'product',
                        'publish',
                        '_visibility',
                        'visible '
                    );

                    if ( ! empty( $moodboard_token ) ) {
                        $sql .= " AND l.`moodboard_token` = %s";
                        $args[] = $moodboard_token;
                    } else {
                        $sql .= " AND l.`is_default` = %d AND l.`user_id` = %d";
                        $args[] = 1;
                        $args[] = get_current_user_id();
                    }

                    $query = $wpdb->prepare( $sql, $args );
                    $count = $wpdb->get_var( $query );

                    $transient_name = ! empty( $moodboard_token ) ? ( 'yith_mdbd_moodboard_count_' . $moodboard_token ) : ( 'yith_mdbd_user_default_count_' . get_current_user_id() );
                    set_transient( $transient_name, $count, WEEK_IN_SECONDS );
                }

                return $count;
            }
            else {
                $cookie = yith_getcookie( 'yith_mdbd_products' );

                $existing_products = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} AS p LEFT JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id WHERE post_type = %s AND post_status = %s AND pm.meta_key = %s AND pm.meta_value = %s", array( 'product', 'publish', '_visibility', 'visible' ) ) );
                $moodboard_products = array();

                if( ! empty( $cookie ) ){
                    foreach( $cookie as $elem ){
                        $moodboard_products[] = $elem['prod_id'];
                    }
                }

                $moodboard_products = array_intersect( $moodboard_products, $existing_products );

                return count( $moodboard_products );
            }
        }

        /**
         * Count all user items in moodboards
         *
         * @return int Count of items added all over moodboard from current user
         * @since 2.0.12
         */
        public function count_all_products() {
            global $wpdb;

            if( is_user_logged_in() ) {
                $user_id = get_current_user_id();

                if( false === $count = get_transient( 'yith_mdbd_user_total_count_' . $user_id ) ) {
                    $sql = "SELECT COUNT( i.`prod_id` ) AS `cnt`
                        FROM `{$wpdb->yith_mdbd_items}` AS i
                        INNER JOIN `{$wpdb->posts}` AS p ON i.`prod_id` = p.`ID`
                        INNER JOIN `{$wpdb->postmeta}` AS pm ON p.`ID` = pm.`post_id`
                        WHERE i.`user_id` = %d AND i.`prod_id` IN (
                            SELECT ID
                            FROM {$wpdb->posts} AS p
                            WHERE p.`post_type` = %s AND p.`post_status` = %s
                        )";

                    $query = $wpdb->prepare( $sql, array( $user_id, 'product', 'publish' ) );
                    $count = $wpdb->get_var( $query );

                    set_transient( 'yith_mdbd_user_total_count_' . $user_id, $count, WEEK_IN_SECONDS );
                }

                return $count;
            }
            else {
                $cookie = yith_getcookie( 'yith_mdbd_products' );

                $existing_products = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} AS p LEFT JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id WHERE post_type = %s AND post_status = %s AND pm.meta_key = %s AND pm.meta_value = %s", array( 'product', 'publish', '_visibility', 'visible' ) ) );
                $moodboard_products = array();

                if( ! empty( $cookie ) ){
                    foreach( $cookie as $elem ){
                        $moodboard_products[] = $elem['prod_id'];
                    }
                }

                $moodboard_products = array_intersect( $moodboard_products, $existing_products );

                return count( $moodboard_products );
            }
        }

        /**
         * Count number of times a product was added to users moodboards
         *
         * @param $product_id int|bool Product id; false will force method to use global product
         *
         * @return int Number of times the product was added to moodboard
         * @since 2.0.13
         */
        public function count_add_to_moodboard( $product_id = false ) {
            global $product, $wpdb;

            $product_id = ! ( $product_id ) ? $product->id : $product_id;

            if( ! $product_id ){
                return 0;
            }

            $query = "SELECT COUNT( DISTINCT( user_id ) ) FROM {$wpdb->yith_mdbd_items} WHERE prod_id = %d";
            $res = $wpdb->get_var( $wpdb->prepare( $query, $product_id ) );

            return $res;
        }

        /**
         * Retrieve elements of the moodboard for a specific user
         *
         * @return array
         * @since 2.0.0
         */
        public function get_products( $args = array() ) {
            global $wpdb;

            $default = array(
                'user_id' => ( is_user_logged_in() ) ? get_current_user_id(): false,
                'product_id' => false,
                'moodboard_id' => false, //moodboard_id for a specific moodboard, false for default, or all for any moodboard
                'moodboard_token' => false,
                'moodboard_visibility' => 'all', // all, visible, public, shared, private
                'is_default' => false,
                'id' => false, // only for table select
                'limit' => false,
                'offset' => 0
            );

            $args = wp_parse_args( $args, $default );
            extract( $args );

            if( ! empty( $user_id ) || ! empty( $moodboard_token ) ) {
                $sql = "SELECT *
                        FROM `{$wpdb->yith_mdbd_items}` AS i
                        LEFT JOIN {$wpdb->yith_mdbd_moodboards} AS l ON l.`ID` = i.`moodboard_id`
                        INNER JOIN {$wpdb->posts} AS p ON p.ID = i.prod_id
                        INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.ID
                        WHERE 1 AND p.post_type = %s AND p.post_status = %s AND pm.meta_key = %s AND pm.meta_value = %s";

                $sql_args = array(
                    'product',
                    'publish',
                    '_visibility',
                    'visible'
                );

                if( ! empty( $user_id ) ){
                    $sql .= " AND i.`user_id` = %d";
                    $sql_args[] = $user_id;
                }

                if( ! empty( $product_id ) ){
                    $sql .= " AND i.`prod_id` = %d";
                    $sql_args[] = $product_id;
                }

                if( ! empty( $moodboard_id ) ){
                    $sql .= " AND i.`moodboard_id` = %d";
                    $sql_args[] = $moodboard_id;
                }
                elseif( empty( $moodboard_id ) && empty( $moodboard_token ) && $is_default != 1 ){
                    $sql .= " AND i.`moodboard_id` IS NULL";
                }

                if( ! empty( $moodboard_token ) ){
                    $sql .= " AND l.`moodboard_token` = %s";
                    $sql_args[] = $moodboard_token;
                }

                if( ! empty( $moodboard_visibility ) && $moodboard_visibility != 'all' ){
                    switch( $moodboard_visibility ){
                        case 'visible':
                            $sql .= " AND ( l.`moodboard_privacy` = %d OR l.`moodboard_privacy` = %d )";
                            $sql_args[] = 0;
                            $sql_args[] = 1;
                            break;
                        case 'public':
                            $sql .= " AND l.`moodboard_privacy` = %d";
                            $sql_args[] = 0;
                            break;
                        case 'shared':
                            $sql .= " AND l.`moodboard_privacy` = %d";
                            $sql_args[] = 1;
                            break;
                        case 'private':
                            $sql .= " AND l.`moodboard_privacy` = %d";
                            $sql_args[] = 2;
                            break;
                        default:
                            $sql .= " AND l.`moodboard_privacy` = %d";
                            $sql_args[] = 0;
                            break;
                    }
                }

                if( $is_default !== false ){
                    if( ! empty( $user_id ) ){
                        $this->generate_default_moodboard( $user_id );
                    }

                    $sql .= " AND l.`is_default` = %d";
                    $sql_args[] = $is_default;
                }

                if( ! empty( $id ) ){
                    $sql .= " AND `i.ID` = %d";
                    $sql_args[] = $id;
                }

                $sql .= " GROUP BY i.prod_id, l.ID";

                if( ! empty( $limit ) ){
                    $sql .= " LIMIT " . $offset . ", " . $limit;
                }

                $moodboard = $wpdb->get_results( $wpdb->prepare( $sql, $sql_args ), ARRAY_A );
            }
            else{
                $moodboard = yith_getcookie( 'yith_mdbd_products' );

                foreach( $moodboard as $key => $cookie ){
                    $existing_products = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} AS p LEFT JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id WHERE post_type = %s AND post_status = %s AND pm.meta_key = %s AND pm.meta_value = %s", array( 'product', 'publish', '_visibility', 'visible' ) ) );

                    if( ! in_array( $cookie['prod_id'], $existing_products ) ){
                        unset( $moodboard[ $key ] );
                    }

                    if( ! empty( $product_id ) && $cookie['prod_id'] != $product_id ){
                        unset( $moodboard[ $key ] );
                    }

                    if( ( ! empty( $moodboard_id ) && $moodboard_id != 'all' ) && $cookie['moodboard_id'] != $moodboard_id ){
                        unset( $moodboard[ $key ] );
                    }
                }

                if( ! empty( $limit ) ){
                    $moodboard = array_slice( $moodboard, $offset, $limit );
                }
            }

            return $moodboard;
        }

        /**
         * Count product occurrencies in users moodboards
         *
         * @param $product_id int
         *
         * @return int
         * @since 2.0.0
         */
        public function count_product_occurrencies( $product_id ) {
            global $wpdb;
            $sql = "SELECT COUNT(*) FROM {$wpdb->yith_mdbd_items} WHERE `prod_id` = %d";

            return $wpdb->get_var( $wpdb->prepare( $sql, $product_id ) );
        }

        /**
         * Retrieve details of a product in the moodboard.
         *
         * @param int $id
         * @param string $request_from
         * @return array
         * @since 1.0.0
         */
        public function get_product_details( $product_id, $moodboard_id = false ) {
            return $this->get_products(
                array(
                    'prod_id' => $product_id,
                    'moodboard_id' => $moodboard_id
                )
            );
        }

        /**
         * Returns an array of users that created and populated a public moodboard
         *
         * @param $search array Array of arguments for the search
         * @return array
         * @since 2.0.0
         */
        public function get_users_with_moodboard( $args = array() ){
            global $wpdb;

            $default = array(
                'search' => false,
                'limit' => false,
                'offset' => 0
            );

            $args = wp_parse_args( $args, $default );
            extract( $args );

            $sql = "SELECT DISTINCT i.user_id
                    FROM {$wpdb->yith_mdbd_items} AS i
                    LEFT JOIN {$wpdb->yith_mdbd_moodboards} AS l ON i.moodboard_id = l.ID";

            if( ! empty( $search ) ){
                $sql .= " LEFT JOIN `{$wpdb->users}` AS u ON l.`user_id` = u.ID";
                $sql .= " LEFT JOIN `{$wpdb->usermeta}` AS umn ON umn.`user_id` = u.`ID`";
                $sql .= " LEFT JOIN `{$wpdb->usermeta}` AS ums ON ums.`user_id` = u.`ID`";
            }

            $sql .= " WHERE l.moodboard_privacy = %d";
            $sql_args = array( 0 );

            if( ! empty( $search ) ){
                $sql .= " AND ( umn.`meta_key` LIKE %s AND ums.`meta_key` LIKE %s AND ( u.`user_email` LIKE %s OR u.`user_login` LIKE %s OR umn.`meta_value` LIKE %s OR ums.`meta_value` LIKE %s ) )";
                $sql_args[] = 'first_name';
                $sql_args[] = 'last_name';
                $sql_args[] = "%" . $search . "%";
                $sql_args[] = "%" . $search . "%";
                $sql_args[] = "%" . $search . "%";
                $sql_args[] = "%" . $search . "%";
            }

            if( ! empty( $limit ) ){
                $sql .= " LIMIT " . $offset . ", " . $limit;
            }

            $res = $wpdb->get_col( $wpdb->prepare( $sql, $sql_args ) );
            return $res;
        }

        /**
         * Count users that have public moodboards
         *
         * @param $search string
         * @return int
         * @since 2.0.0
         */
        public function count_users_with_moodboards( $search  ){
            return count( $this->get_users_with_moodboard( array( 'search' => $search ) ) );
        }

        /* === moodboardS METHODS === */

        /**
         * Add a new moodboard for the user.
         *
         * @return string "error", "exists" or id of the inserted moodboard
         * @since 2.0.0
         */
        public function add_moodboard() {
            $user_id = ( ! empty( $this->details['user_id'] ) ) ? $this->details['user_id'] : false;

            if( $user_id == false ){
                $this->errors[] = __( 'You need to log in before creating a new moodboard', 'yith-woocommerce-moodboard' );
                return "error";
            }

            return $this->generate_default_moodboard( $user_id );
        }

        /**
         * Update moodboard with arguments passed as second parameter
         *
         * @param $moodboard_id int
         * @param $args array Array of parameters to user in $wpdb->update
         * @return bool
         * @since 2.0.0
         */
        public function update_moodboard( $moodboard_id, $args = array() ) {
            return false;
        }

        /**
         * Delete indicated moodboard
         *
         * @param $moodboard_id int
         * @return bool
         * @since 2.0.0
         */
        public function remove_moodboard( $moodboard_id ) {
            return false;
        }

        /**
         * Checks if a moodboard with the given slug is already in the db
         *
         * @param string $moodboard_slug
         * @param int    $user_id
         * @return bool
         * @since 2.0.0
         */
        public function moodboard_exists( $moodboard_slug, $user_id ) {
            global $wpdb;
            $sql = "SELECT COUNT(*) AS `cnt` FROM `{$wpdb->yith_mdbd_moodboards}` WHERE `moodboard_slug` = %s AND `user_id` = %d";
            $sql_args = array(
                $moodboard_slug,
                $user_id
            );

            $res = $wpdb->get_var( $wpdb->prepare( $sql, $sql_args ) );

            return (bool) ( $res > 0 );
        }

        /**
         * Retrieve all the moodboard matching speciefied arguments
         *
         * @return array
         * @since 2.0.0
         */
        public function get_moodboards( $args = array() ){
            global $wpdb;

            $default = array(
                'id' => false,
                'user_id' => ( is_user_logged_in() ) ? get_current_user_id(): false,
                'moodboard_slug' => false,
                'moodboard_name' => false,
                'moodboard_token' => false,
                'moodboard_visibility' => 'all', // all, visible, public, shared, private
                'user_search' => false,
                'is_default' => false,
                'orderby' => 'ID',
                'order' => 'DESC',
                'limit' =>  false,
                'offset' => 0,
	            'show_empty' => true
            );

            $args = wp_parse_args( $args, $default );
            extract( $args );

            $sql = "SELECT l.*";

            if( ! empty( $user_search ) ){
                $sql .= ", u.user_email, umn.meta_value AS first_name, ums.meta_value AS last_name";
            }

            $sql .= " FROM `{$wpdb->yith_mdbd_moodboards}` AS l";

            if( ! empty( $user_search ) || $orderby == 'user_login' ) {
                $sql .= " LEFT JOIN `{$wpdb->users}` AS u ON l.`user_id` = u.ID";
            }

            if( ! empty( $user_search ) ){
                $sql .= " LEFT JOIN `{$wpdb->usermeta}` AS umn ON umn.`user_id` = u.`ID`";
                $sql .= " LEFT JOIN `{$wpdb->usermeta}` AS ums ON ums.`user_id` = u.`ID`";
            }

            $sql .= " WHERE 1";

            if( ! empty( $user_id ) ){
                $sql .= " AND l.`user_id` = %d";

                $sql_args = array(
                    $user_id
                );
            }

            if( ! empty( $user_search ) ){
                $sql .= " AND ( umn.`meta_key` LIKE %s AND ums.`meta_key` LIKE %s AND ( u.`user_email` LIKE %s OR umn.`meta_value` LIKE %s OR ums.`meta_value` LIKE %s ) )";
                $sql_args[] = 'first_name';
                $sql_args[] = 'last_name';
                $sql_args[] = "%" . $user_search . "%";
                $sql_args[] = "%" . $user_search . "%";
                $sql_args[] = "%" . $user_search . "%";
            }

            if( $is_default !== false ){
                $sql .= " AND l.`is_default` = %d";
                $sql_args[] = $is_default;
            }

            if( ! empty( $id ) ){
                $sql .= " AND l.`ID` = %d";
                $sql_args[] = $id;
            }

            if( $moodboard_slug !== false ){
                $sql .= " AND l.`moodboard_slug` = %s";
                $sql_args[] = sanitize_title_with_dashes( $moodboard_slug );
            }

            if( ! empty( $moodboard_token ) ){
                $sql .= " AND l.`moodboard_token` = %s";
                $sql_args[] = $moodboard_token;
            }

            if( ! empty( $moodboard_name ) ){
                $sql .= " AND l.`moodboard_name` LIKE %s";
                $sql_args[] = "%" . $moodboard_name . "%";
            }

            if( ! empty( $moodboard_visibility ) && $moodboard_visibility != 'all' ){
                switch( $moodboard_visibility ){
                    case 'visible':
                        $sql .= " AND ( l.`moodboard_privacy` = %d OR l.`is_public` = %d )";
                        $sql_args[] = 0;
                        $sql_args[] = 1;
                        break;
                    case 'public':
                        $sql .= " AND l.`moodboard_privacy` = %d";
                        $sql_args[] = 0;
                        break;
                    case 'shared':
                        $sql .= " AND l.`moodboard_privacy` = %d";
                        $sql_args[] = 1;
                        break;
                    case 'private':
                        $sql .= " AND l.`moodboard_privacy` = %d";
                        $sql_args[] = 2;
                        break;
                    default:
                        $sql .= " AND l.`moodboard_privacy` = %d";
                        $sql_args[] = 0;
                        break;
                }
            }

	        if( ! $show_empty ){
		        $sql .= " AND l.`ID` IN ( SELECT moodboard_id FROM {$wpdb->yith_mdbd_items} )";
	        }

            $sql .= " ORDER BY " . $orderby . " " . $order;

            if( ! empty( $limit ) ){
                $sql .= " LIMIT " . $offset . ", " . $limit;
            }

            if( ! empty( $sql_args ) ){
                $sql = $wpdb->prepare( $sql, $sql_args );
            }

            $lists = $wpdb->get_results( $sql, ARRAY_A );

            return $lists;
        }

        /**
         * Returns details of a moodboard, searching it by moodboard id
         *
         * @param $moodboard_id int
         * @return array
         * @since 2.0.0
         */
        public function get_moodboard_detail( $moodboard_id ) {
            global $wpdb;

            $sql = "SELECT * FROM {$wpdb->yith_mdbd_moodboards} WHERE `ID` = %d";
            return $wpdb->get_row( $wpdb->prepare( $sql, $moodboard_id ), ARRAY_A );
        }

        /**
         * Returns details of a moodboard, searching it by moodboard token
         *
         * @param $moodboard_id int
         * @return array
         * @since 2.0.0
         */
        public function get_moodboard_detail_by_token( $moodboard_token ) {
            global $wpdb;

            $sql = "SELECT * FROM {$wpdb->yith_mdbd_moodboards} WHERE `moodboard_token` = %s";
            return $wpdb->get_row( $wpdb->prepare( $sql, $moodboard_token ), ARRAY_A );
        }

        /**
         * Generate default moodboard for a specific user, adding all NULL items of the user to it
         *
         * @param $user_id int
         * @return int Default moodboard id
         * @since 2.0.0
         */
        public function generate_default_moodboard( $user_id ){
            global $wpdb;

            $moodboards = $this->get_moodboards( array(
                'user_id' => $user_id,
                'is_default' => 1
            ) );

            if( ! empty( $moodboards ) ){
                $default_user_moodboard = $moodboards[0]['ID'];
                $this->last_operation_token = $moodboards[0]['moodboard_token'];
            }
            else{
                $token = $this->generate_moodboard_token();
                $this->last_operation_token = $token;

                $wpdb->insert( $wpdb->yith_mdbd_moodboards, array(
                    'user_id' => $user_id,
                    'moodboard_slug' => '',
                    'moodboard_token' => $token,
                    'moodboard_name' => '',
                    'moodboard_privacy' => 0,
                    'is_default' => 1
                ) );

                $default_user_moodboard = $wpdb->insert_id;
            }

            $sql = "UPDATE {$wpdb->yith_mdbd_items} SET moodboard_id = %d WHERE user_id = %d AND moodboard_id IS NULL";
            $sql_args = array(
                $default_user_moodboard,
                $user_id
            );

            $wpdb->query( $wpdb->prepare( $sql, $sql_args ) );

            return $default_user_moodboard;
        }

        /**
         * Generate a token to visit moodboard
         *
         * @return string token
         * @since 2.0.0
         */
        public function generate_moodboard_token(){
            global $wpdb;
            $count = 0;
            $sql = "SELECT COUNT(*) FROM `{$wpdb->yith_mdbd_moodboards}` WHERE `moodboard_token` = %s";

            do {
                $dictionary = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $nchars = 12;
                $token = "";

                for( $i = 0; $i <= $nchars - 1; $i++ ){
                    $token .= $dictionary[ mt_rand( 0, strlen( $dictionary ) - 1 ) ];
                }

                $count = $wpdb->get_var( $wpdb->prepare( $sql, $token ) );
            }
            while( $count != 0 );

            return $token;
        }

        /* === GENERAL METHODS === */

        /**
         * Add rewrite rules for moodboard
         *
         * @return void
         * @since 2.0.0
         */
        public function add_rewrite_rules() {
            global $wp_query;
            $moodboard_page_id = isset( $_POST['yith_mdbd_moodboard_page_id'] ) ? $_POST['yith_mdbd_moodboard_page_id'] : get_option( 'yith_mdbd_moodboard_page_id' );
	        $moodboard_page_id = yith_mdbd_object_id( $moodboard_page_id );

            if( empty( $moodboard_page_id ) ){
                return;
            }

            $moodboard_page = get_post( $moodboard_page_id );
	        $moodboard_page_slug = $moodboard_page ? $moodboard_page->post_name : false;

            if ( empty( $moodboard_page_slug ) ){
                return;
            }

            add_rewrite_rule( '(([^/]+/)*' . $moodboard_page_slug . ')(/(.*))?/page/([0-9]{1,})/?$', 'index.php?pagename=$matches[1]&moodboard-action=$matches[4]&paged=$matches[5]', 'top' );
            add_rewrite_rule( '(([^/]+/)*' . $moodboard_page_slug . ')(/(.*))?/?$', 'index.php?pagename=$matches[1]&moodboard-action=$matches[4]', 'top' );
        }

        /**
         * Adds public query var for moodboard
         *
         * @param $public_var array
         * @return array
         * @since 2.0.0
         */
        public function add_public_query_var( $public_var ) {
            $public_var[] = 'moodboard-action';
            $public_var[] = 'moodboard_id';

            return $public_var;
        }
        
        /**
         * Get all errors in HTML mode or simple string.
         * 
         * @param bool $html
         * @return string
         * @since 1.0.0
         */
        public function get_errors( $html = true ) {
            return implode( ( $html ? '<br />' : ', ' ), $this->errors );
        }
        
        /**
         * Build moodboard page URL.
         * 
         * @return string
         * @since 1.0.0
         */
        public function get_moodboard_url( $action = 'view' ) {
            global $sitepress;
            $moodboard_page_id = yith_mdbd_object_id( get_option( 'yith_mdbd_moodboard_page_id' ) );

            if( get_option( 'permalink_structure' ) && ! defined( 'ICL_PLUGIN_PATH' ) ) {
	            $moodboard_permalink = trailingslashit( get_the_permalink( $moodboard_page_id ) );
	            $base_url = trailingslashit( $moodboard_permalink . $action );
            }
            else{
                $base_url = get_the_permalink( $moodboard_page_id );
                $action_params = explode( '/', $action );
                $params = array();

                if( isset( $action_params[1] ) ){
                    $action = $action_params[0];
                    $params['moodboard-action'] = $action;

                    if( $action == 'view' ){
                        $params['moodboard_id'] = $action_params[1];
                    }
                    elseif( $action == 'user' ){
                        $params['user_id'] = $action_params[1];
                    }
                }
                else{
                    $params['moodboard-action'] = $action;
                }

                $base_url = add_query_arg( $params, $base_url );
            }

            if( defined( 'ICL_PLUGIN_PATH' ) && $sitepress->get_current_language() != $sitepress->get_default_language() ){
		        $base_url = add_query_arg( 'lang', $sitepress->get_current_language(), $base_url );
	        }

            return apply_filters( 'yith_mdbd_moodboard_page_url', esc_url_raw( $base_url ) );
        }

        /**
         * Build the URL used to remove an item from the moodboard.
         *
         * @param int $item_id
         * @return string
         * @since 1.0.0
         */
        public function get_remove_url( $item_id ) {
            return esc_url( add_query_arg( 'remove_from_moodboard', $item_id ) );
        }
        
        /**
         * Build the URL used to add an item in the moodboard.
         *
         * @return string
         * @since 1.0.0
         */
        public function get_addtomoodboard_url() {
            global $product;
            	
            return esc_url( add_query_arg( 'add_to_moodboard', $product->id ) );
        }
        
        /**
         * Build the URL used to add an item to the cart from the moodboard.
         * 
         * @param int $id
         * @param int $user_id
         * @return string
         * @since 1.0.0
         */
        public function get_addtocart_url( $id, $user_id = '' ) {
            _deprecated_function( 'YITH_mdbd::get_addtocart_url', '2.0.0' );

            //$product = $yith_mdbd->get_product_details( $id );
            if ( function_exists( 'get_product' ) )    
                $product = get_product( $id );
            else
                $product = new WC_Product( $id );
                
            if ( $product->product_type == 'variable' ) {
                return get_permalink( $product->id );
            }
            
    		$url = YITH_mdbd_URL . 'add-to-cart.php?moodboard_item_id=' . rtrim( $id, '_' );
    		
    		if( $user_id != '' ) {
    			$url .= '&id=' . $user_id;
    		}
            
    		return $url;
    	}

        /**
         * Build the URL used for an external/affiliate product.
         *
         * @deprecated
         * @param $id
         * @return string
         */
        public function get_affiliate_product_url( $id ) {
            _deprecated_function( 'YITH_mdbd::get_affiliate_product_url', '2.0.0' );
            $product = get_product( $id );
            return get_post_meta( $product->id, '_product_url', true );
        }
        
        /**
         * Build an URL with the nonce added.
         * 
         * @param string $action
         * @param string $url
         * @return string
         * @since 1.0.0
         */
        public function get_nonce_url( $action, $url = '' ) {
            return esc_url( add_query_arg( '_n', wp_create_nonce( 'yith-mdbd-' . $action ), $url ) );
        }

	    /**
	     * Prints wc notice for moodboard pages
	     *
	     * @return void
	     * @since 2.0.5
	     */
	    public function print_notices() {
		    global $woocommerce;

		    // Start moodboard page printing
		    if( function_exists( 'wc_print_notices' ) ) {
			    wc_print_notices();
		    }
		    elseif( method_exists( $woocommerce, 'show_message' ) ){
			    $woocommerce->show_messages();
		    }
	    }

	    /* === FONTAWESOME FIX === */

	    /**
	     * Modernize font-awesome class, for old moodboard users
	     *
	     * @param $class string Original font-awesome class
	     * @return string Filtered font-awesome class
	     * @since 2.0.2
	     */
	    public function update_font_awesome_classes( $class ) {
		    $exceptions = array(
			    'icon-envelope' => 'fa-envelope-o',
			    'icon-star-empty' => 'fa-star-o',
			    'icon-ok' => 'fa-check',
			    'icon-zoom-in' => 'fa-search-plus',
			    'icon-zoom-out' => 'fa-search-minus',
			    'icon-off' => 'fa-power-off',
			    'icon-trash' => 'fa-trash-o',
			    'icon-share' => 'fa-share-square-o',
			    'icon-check' => 'fa-check-square-o',
			    'icon-move' => 'fa-arrows',
			    'icon-file' => 'fa-file-o',
			    'icon-time' => 'fa-clock-o',
			    'icon-download-alt' => 'fa-download',
			    'icon-download' => 'fa-arrow-circle-o-down',
			    'icon-upload' => 'fa-arrow-circle-o-up',
			    'icon-play-circle' => 'fa-play-circle-o',
			    'icon-indent-left' => 'fa-dedent',
			    'icon-indent-right' => 'fa-indent',
			    'icon-facetime-video' => 'fa-video-camera',
			    'icon-picture' => 'fa-picture-o',
			    'icon-plus-sign' => 'fa-plus-circle',
			    'icon-minus-sign' => 'fa-minus-circle',
			    'icon-remove-sign' => 'fa-times-circle',
			    'icon-ok-sign' => 'fa-check-circle',
			    'icon-question-sign' => 'fa-question-circle',
			    'icon-info-sign' => 'fa-info-circle',
			    'icon-screenshot' => 'fa-crosshairs',
			    'icon-remove-circle' => 'fa-times-circle-o',
			    'icon-ok-circle' => 'fa-check-circle-o',
			    'icon-ban-circle' => 'fa-ban',
			    'icon-share-alt' => 'fa-share',
			    'icon-resize-full' => 'fa-expand',
			    'icon-resize-small' => 'fa-compress',
			    'icon-exclamation-sign' => 'fa-exclamation-circle',
			    'icon-eye-open' => 'fa-eye',
			    'icon-eye-close' => 'fa-eye-slash',
			    'icon-warning-sign' => 'fa-warning',
			    'icon-folder-close' => 'fa-folder',
			    'icon-resize-vertical' => 'fa-arrows-v',
			    'icon-resize-horizontal' => 'fa-arrows-h',
			    'icon-twitter-sign' => 'fa-twitter-square',
			    'icon-facebook-sign' => 'fa-facebook-square',
			    'icon-thumbs-up' => 'fa-thumbs-o-up',
			    'icon-thumbs-down' => 'fa-thumbs-o-down',
			    'icon-heart-empty' => 'fa-heart-o',
			    'icon-signout' => 'fa-sign-out',
			    'icon-linkedin-sign' => 'fa-linkedin-square',
			    'icon-pushpin' => 'fa-thumb-tack',
			    'icon-signin' => 'fa-sign-in',
			    'icon-github-sign' => 'fa-github-square',
			    'icon-upload-alt' => 'fa-upload',
			    'icon-lemon' => 'fa-lemon-o',
			    'icon-check-empty' => 'fa-square-o',
			    'icon-bookmark-empty' => 'fa-bookmark-o',
			    'icon-phone-sign' => 'fa-phone-square',
			    'icon-hdd' => 'fa-hdd-o',
			    'icon-hand-right' => 'fa-hand-o-right',
			    'icon-hand-left' => 'fa-hand-o-left',
			    'icon-hand-up' => 'fa-hand-o-up',
			    'icon-hand-down' => 'fa-hand-o-down',
			    'icon-circle-arrow-left' => 'fa-arrow-circle-left',
			    'icon-circle-arrow-right' => 'fa-arrow-circle-right',
			    'icon-circle-arrow-up' => 'fa-arrow-circle-up',
			    'icon-circle-arrow-down' => 'fa-arrow-circle-down',
			    'icon-fullscreen' => 'fa-arrows-alt',
			    'icon-beaker' => 'fa-flask',
			    'icon-paper-clip' => 'fa-paperclip',
			    'icon-sign-blank' => 'fa-square',
			    'icon-pinterest-sign' => 'fa-pinterest-square',
			    'icon-google-plus-sign' => 'fa-google-plus-square',
			    'icon-envelope-alt' => 'fa-envelope',
			    'icon-comment-alt' => 'fa-comment-o',
			    'icon-comments-alt' => 'fa-comments-o'
		    );

		    if( in_array( $class, array_keys( $exceptions ) ) ){
			    $class = $exceptions[ $class ];
		    }

		    $class = str_replace( 'icon-', 'fa-', $class );

		    return $class;
	    }

        /* === REQUEST HANDLING METHODS === */

        /**
         * Adds an element to moodboard when default AJAX method cannot be used
         *
         * @return void
         * @since 2.0.0
         */
        public function add_to_moodboard(){
            // add item to moodboard when javascript is not enabled
            if( isset( $_GET['add_to_moodboard'] ) ) {
                $this->add();
            }
        }

        /**
         * Removes an element from moodboard when default AJAX method cannot be used
         *
         * @return void
         * @since 2.0.0
         */
        public function remove_from_moodboard(){
            // remove item from moodboard when javascript is not enabled
            if( isset( $_GET['remove_from_moodboard'] ) ){
                $this->remove();
            }
        }

        /**
         * Removes an element after add to cart, if option is enabled in panel
         *
         * @return void
         * @since 2.0.0
         */
        public function remove_from_moodboard_after_add_to_cart() {
            if( get_option( 'yith_mdbd_remove_after_add_to_cart' ) == 'yes' ){
                if( isset( $_REQUEST['remove_from_moodboard_after_add_to_cart'] ) ) {

                    $this->details['remove_from_moodboard'] = $_REQUEST['remove_from_moodboard_after_add_to_cart'];

                    if ( isset( $_REQUEST['moodboard_id'] ) ) {
                        $this->details['moodboard_id'] = $_REQUEST['moodboard_id'];
                    }
                }
                elseif( yith_mdbd_is_moodboard() ){
                    $this->details['remove_from_moodboard'] = $_REQUEST['add-to-cart'];

                    if ( isset( $_REQUEST['moodboard_id'] ) ) {
                        $this->details['moodboard_id'] = $_REQUEST['moodboard_id'];
                    }
                }

                $this->remove();
            }
        }

        /**
         * Redirect to cart after "Add to cart" button pressed on moodboard table
         *
         * @param $url string Original redirect url
         * @return string Redirect url
         * @since 2.0.0
         */
        public function redirect_to_cart( $url, $product ) {
	        global $yith_mdbd_moodboard_token;

	        $moodboard = $this->get_moodboard_detail_by_token( $yith_mdbd_moodboard_token );
	        $moodboard_id = $moodboard['ID'];

            if( $product->is_type( 'simple' ) && get_option( 'yith_mdbd_redirect_cart' ) == 'yes' ){
                if( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && yith_mdbd_is_moodboard() ){
                    $url = add_query_arg( 'add-to-cart', $product->id, function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : WC()->cart->get_cart_url() );
                }
            }

            if( ! $product->is_type( 'external' ) && get_option( 'yith_mdbd_remove_after_add_to_cart' ) == 'yes' ){
                if( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && yith_mdbd_is_moodboard() ) {
                    $url = add_query_arg(
	                    array(
		                    'remove_from_moodboard_after_add_to_cart' => $product->id,
		                    'moodboard_id' => $moodboard_id,
		                    'moodboard_token' => $yith_mdbd_moodboard_token
	                    ),
	                    $url
                    );
                }
            }

            return apply_filters( 'yit_mdbd_add_to_cart_redirect_url', esc_url( $url ) );
        }

        /**
         * AJAX: add to moodboard action
         * 
         * @return void
         * @since 1.0.0
         */
        public function add_to_moodboard_ajax() {
            $return = $this->add();
            $message = '';
            $user_id = isset( $this->details['user_id'] ) ? $this->details['user_id'] : false;
            $moodboards = array();

            if( $return == 'true' ){
                $message = apply_filters( 'yith_mdbd_product_added_to_moodboard_message', get_option( 'yith_mdbd_product_added_text' ) );
            }
            elseif( $return == 'exists' ){
                $message = apply_filters( 'yith_mdbd_product_already_in_moodboard_message', get_option( 'yith_mdbd_already_in_moodboard_text' ) );
            }
            elseif( count( $this->errors ) > 0 ){
                $message = apply_filters( 'yith_mdbd_error_adding_to_moodboard_message', $this->get_errors() );
            }

            if( $user_id != false ){
                $moodboards = $this->get_moodboards( array( 'user_id' => $user_id ) );
            }

            wp_send_json(
                array(
                    'result' => $return,
                    'message' => $message,
                    'user_moodboards' => $moodboards,
                    'moodboard_url' => $this->get_moodboard_url( 'view' . ( isset( $this->last_operation_token ) ? ( '/' . $this->last_operation_token ) : false ) ),
                )
            );
        }
        
        /**
         * AJAX: remove from moodboard action
         * 
         * @return void
         * @since 1.0.0
         */
        public function remove_from_moodboard_ajax() {
            $moodboard_token = isset( $this->details['moodboard_token'] ) ? $this->details['moodboard_token'] : false;
            $count = $this->count_products( $moodboard_token );
            $message = '';

            if( $count != 0 ) {
                if ( $this->remove() ) {
                    $message = apply_filters( 'yith_mdbd_product_removed_text', __( 'Product successfully removed.', 'yith-woocommerce-moodboard' ) );
                    $count --;
                }
                else {
                    $message = apply_filters( 'yith_mdbd_unable_to_remove_product_message', __( 'Error. Unable to remove the product from the moodboard.', 'yith-woocommerce-moodboard' ) );
                }
            }
            else{
                $message = apply_filters( 'yith_mdbd_no_product_to_remove_message', __( 'No products were added to the moodboard', 'yith-woocommerce-moodboard' ) );
            }

            wc_add_notice( $message );

            $atts = array( 'moodboard_id' => $moodboard_token );
            if( isset( $this->details['pagination'] ) ){
                $atts['pagination'] = $this->details['pagination'];
            }

            if( isset( $this->details['per_page'] ) ){
                $atts['per_page'] = $this->details['per_page'];
            }

            echo YITH_mdbd_Shortcode::moodboard( $atts );
            die();
        }

	    /*******************************************
	     * INTEGRATION WC Frequently Bought Together
	     *******************************************/

	    /**
	     * AJAX: reload moodboard and adding elem action
	     *
	     * @return void
	     * @since 1.0.0
	     */
	    public function reload_moodboard_and_adding_elem_ajax() {

		    $return     = $this->add();
		    $message    = '';
		    $type_msg   = 'success';

		    if( $return == 'true' ){
			    $message = apply_filters( 'yith_mdbd_product_added_to_moodboard_message', get_option( 'yith_mdbd_product_added_text' ) );
		    }
		    elseif( $return == 'exists' ){
			    $message = apply_filters( 'yith_mdbd_product_already_in_moodboard_message', get_option( 'yith_mdbd_already_in_moodboard_text' ) );
			    $type_msg = 'error';
		    }
		    else {
			    $message = apply_filters( 'yith_mdbd_product_removed_text', __( 'An error as occurred.', 'yith-woocommerce-moodboard' ) );
			    $type_msg = 'error';
		    }

		    $moodboard_token = isset( $this->details['moodboard_token'] ) ? $this->details['moodboard_token'] : false;

		    $atts = array( 'moodboard_id' => $moodboard_token );
		    if( isset( $this->details['pagination'] ) ){
			    $atts['pagination'] = $this->details['pagination'];
		    }

		    if( isset( $this->details['per_page'] ) ){
			    $atts['per_page'] = $this->details['per_page'];
		    }

		    ob_start();

		    wc_add_notice( $message, $type_msg );

		    echo '<div>'. YITH_mdbd_Shortcode::moodboard( $atts ) . '</div>';

		    echo ob_get_clean();
		    die();

	    }

	    /**
	     * redirect after add to cart from YITH WooCommerce Frequently Bought Together Premium shortcode
	     *
	     * @since 1.0.0
	     */
	    public function yith_wfbt_redirect_after_add_to_cart( $url ){
		    if( ! isset( $_REQUEST['yith_wfbt_shortcode'] ) ) {
			    return $url;
		    }

            $cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : WC()->cart->get_cart_url();

			return get_option( 'yith_mdbd_redirect_cart' ) == 'yes' ? $cart_url : $this->get_moodboard_url();
	    }
    }
}

/**
 * Unique access to instance of YITH_mdbd class
 *
 * @return \YITH_mdbd
 * @since 2.0.0
 */
function YITH_mdbd(){
    return YITH_mdbd::get_instance();
}