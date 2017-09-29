<?php
/**
 * Shortcodes class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce moodboard
 * @version 1.1.5
 */

if ( ! defined( 'YITH_mdbd' ) ) { exit; } // Exit if accessed directly

if( ! class_exists( 'YITH_mdbd_Shortcode' ) ) {
	/**
	 * YITH mdbd Shortcodes
	 *
	 * @since 1.0.0
	 */
	class YITH_mdbd_Shortcode {
		/**
		 * Print the moodboard HTML.
		 *
		 * @since 1.0.0
		 */
		public static function moodboard( $atts, $content = null ) {
			global $yith_mdbd_is_moodboard, $yith_mdbd_moodboard_token;
			$atts = shortcode_atts( array(
				'per_page' => 5,
				'pagination' => 'no',
				'moodboard_id' => false
			), $atts );

			$available_views = apply_filters( 'yith_mdbd_available_moodboard_views', array( 'view', 'user' ) );

			extract( $atts );

			// retrieve options from query string
			$action_params = get_query_var( 'moodboard-action', false );
			$action_params = explode( '/', apply_filters( 'yith_mdbd_current_moodboard_view_params', $action_params ) );
			$action = ( isset( $action_params[0] ) ) ? $action_params[0] : 'view';

			$user_id = isset( $_GET['user_id'] ) ? $_GET['user_id'] : false;

			// init params needed to load correct tempalte
			$additional_params = array();
			$template_part = 'view';

			/* === moodboard TEMPLATE === */
			if(
				empty( $action ) ||
				( ! empty( $action ) && ( $action == 'view' || $action == 'user' ) ) ||
				( ! empty( $action ) && ( $action == 'manage' || $action == 'create' ) && get_option( 'yith_mdbd_multi_moodboard_enable', false ) != 'yes' ) ||
				( ! empty( $action ) && ! in_array( $action, $available_views ) ) ||
				! empty( $user_id )
			){
				/*
				 * someone is requesting a moodboard
				 * -if user is not logged in..
				 *  -and no moodboard_id is passed, cookie moodboard is loaded
				 *  -and a moodboard_id is passed, checks if moodboard is public or shared, and shows it only in this case
				 * -if user is logged in..
				 *  -and no moodboard_id is passed, default moodboard is loaded
				 *  -and a moodboard_id is passed, checks owner of the moodboard
				 *   -if moodboard is of the logged user, shows it
				 *   -if moodboard is of another user, checks if moodboard is public or shared, and shows it only in this case (if user is admin, can see all moodboards)
				*/

				if( empty( $moodboard_id ) ) {
					if ( ! empty( $action ) && $action == 'user' ) {
						$user_id = isset( $action_params[1] ) ? $action_params[1] : false;
						$user_id = ( ! $user_id ) ? get_query_var( $user_id, false ) : $user_id;
						$user_id = ( ! $user_id ) ? get_current_user_id() : $user_id;

						$moodboards = YITH_mdbd()->get_moodboards( array( 'user_id' => $user_id, 'is_default' => 1 ) );

						if ( ! empty( $moodboards ) && isset( $moodboards[0] ) ) {
							$moodboard_id = $moodboards[0]['moodboard_token'];
						} else {
							$moodboard_id = false;
						}
					} else {
						$moodboard_id = isset( $action_params[1] ) ? $action_params[1] : false;
						$moodboard_id = ( ! $moodboard_id ) ? get_query_var( 'moodboard_id', false ) : $moodboard_id;
					}
				}

				$yith_mdbd_moodboard_token = $moodboard_id;

				$is_user_owner = false;
				$query_args = array();

				if( ! empty( $user_id ) ){
					$query_args[ 'user_id' ] = $user_id;
					$query_args[ 'is_default' ] = 1;

					if( get_current_user_id() == $user_id ){
						$is_user_owner = true;
					}
				}
				elseif( ! is_user_logged_in() ){
					if( empty( $moodboard_id ) ){
						$query_args[ 'moodboard_id' ] = false;
						$is_user_owner = true;
					}
					else{
						$is_user_owner = false;

						$query_args[ 'moodboard_token' ] = $moodboard_id;
						$query_args[ 'moodboard_visibility' ] = 'visible';
					}
				}
				else{
					if( empty( $moodboard_id ) ){
						$query_args[ 'user_id' ] = get_current_user_id();
						$query_args[ 'is_default' ] = 1;
						$is_user_owner = true;
					}
					else{
						$moodboard = YITH_mdbd()->get_moodboard_detail_by_token( $moodboard_id );
						$is_user_owner = $moodboard['user_id'] == get_current_user_id();

						$query_args[ 'moodboard_token' ] = $moodboard_id;

						if( ! empty( $moodboard ) && $moodboard['user_id'] != get_current_user_id() ){
							$query_args[ 'user_id' ] = false;
							if( ! current_user_can( 'manage_options' ) ){
								$query_args[ 'moodboard_visibility' ] = 'visible';
							}
						}
					}
				}

				// counts number of elements in moodboard for the user
				$count = YITH_mdbd()->count_products( $moodboard_id );

				// sets current page, number of pages and element offset
				$current_page = max( 1, get_query_var( 'paged' ) );

				// sets variables for pagination, if shortcode atts is set to yes
				$pagination = '';
				$per_page = '';
				if( $pagination == 'yes' && $count > 1 ){
					$pages = ceil( $count / $per_page );

					if( $current_page > $pages ){
						$current_page = $pages;
					}

					$offset = ( $current_page - 1 ) * $per_page;

					if( $pages > 1 ){
						$page_links = paginate_links( array(
							'base' => esc_url( add_query_arg( array( 'paged' => '%#%' ), YITH_mdbd()->get_moodboard_url( 'view' . '/' . $moodboard_id ) ) ),
							'format' => '?paged=%#%',
							'current' => $current_page,
							'total' => $pages,
							'show_all' => true
						) );
					}

					$query_args[ 'limit' ] = $per_page;
					$query_args[ 'offset' ] = $offset;
				}

				if( empty( $moodboard_id ) ){
					$moodboards = YITH_mdbd()->get_moodboards( array( 'user_id' => get_current_user_id(), 'is_default' => 1 ) );
					if( ! empty( $moodboards ) ){
						$moodboard_id = $moodboards[0]['moodboard_token'];
					}
				}

				// retrieve items to print
				$moodboard_items = YITH_mdbd()->get_products( $query_args );

				// retrieve moodboard information
				$moodboard_meta = YITH_mdbd()->get_moodboard_detail_by_token( $moodboard_id );

				// retireve moodboard title
				$default_moodboard_title = get_option( 'yith_mdbd_moodboard_title' );

				if( $moodboard_meta['is_default'] == 1 ) {
					$moodboard_title = $default_moodboard_title;
				}
				else{
					$moodboard_title = $moodboard_meta['moodboard_name'];
				}

				// retrieve estimate options
				$show_ask_estimate_button = get_option( 'yith_mdbd_show_estimate_button' ) == 'yes';
				$ask_estimate_url = false;
				if( $show_ask_estimate_button ){
					$ask_estimate_url = esc_url( wp_nonce_url(
						add_query_arg(
							'ask_an_estimate',
							!empty( $moodboard_meta['moodboard_token'] ) ? $moodboard_meta['moodboard_token'] : 'false',
							YITH_mdbd()->get_moodboard_url( 'view' . ( $moodboard_meta['is_default'] != 1 ? '/' . $moodboard_meta['moodboard_token'] : '' ) )
						),
						'ask_an_estimate',
						'estimate_nonce'
					) );
				}

				// retrieve share options
				$share_facebook_enabled = get_option( 'yith_mdbd_share_fb' ) == 'yes';
				$share_twitter_enabled = get_option( 'yith_mdbd_share_twitter' ) == 'yes';
				$share_pinterest_enabled = get_option( 'yith_mdbd_share_pinterest' ) == 'yes';
				$share_googleplus_enabled = get_option( 'yith_mdbd_share_googleplus' ) == 'yes';
				$share_email_enabled = get_option( 'yith_mdbd_share_email' ) == 'yes';

				$show_date_added = get_option( 'yith_mdbd_show_dateadded' ) == 'yes';
				$show_add_to_cart = get_option( 'yith_mdbd_add_to_cart_show' ) == 'yes';
				$repeat_remove_button = get_option( 'yith_mdbd_repeat_remove_button' ) == 'yes';

				$share_enabled = $share_facebook_enabled || $share_twitter_enabled || $share_pinterest_enabled || $share_googleplus_enabled || $share_email_enabled;

				$additional_params = array(
					'count' => $count,
					'moodboard_items' => $moodboard_items,
					'moodboard_meta' => $moodboard_meta,
					'page_title' => $moodboard_title,
					'default_wishlsit_title' => $default_moodboard_title,
					'current_page' => $current_page,
					'page_links' => isset( $page_links ) ? $page_links : false,
					'is_user_logged_in' => is_user_logged_in(),
					'is_user_owner' => $is_user_owner,
					'show_price' => get_option( 'yith_mdbd_price_show' ) == 'yes',
					'show_dateadded' => $show_date_added,
					'show_ask_estimate_button' => $show_ask_estimate_button,
					'ask_estimate_url' => $ask_estimate_url,
					'show_stock_status' => get_option( 'yith_mdbd_stock_show' ) == 'yes',
					'show_add_to_cart' => $show_add_to_cart,
					'add_to_cart_text' => get_option( 'yith_mdbd_add_to_cart_text' ),
					'price_excl_tax' => get_option( 'woocommerce_tax_display_cart' ) == 'excl',
					'template_part' => $template_part,
					'share_enabled' => $share_enabled,
					'additional_info' => false,
					'available_multi_moodboard' => false,
					'show_cb' => false,
					'repeat_remove_button' => $repeat_remove_button,
					'show_last_column' => ( $show_date_added && is_user_logged_in() ) || $show_add_to_cart || $repeat_remove_button,
					'users_moodboards' => array()
				);

				if( $share_enabled ){
					$share_title = apply_filters( 'yith_mdbd_socials_share_title', __( 'Share on:', 'yith-woocommerce-moodboard' ) );
					$share_link_url = ( ! empty( $moodboard_id ) ) ? YITH_mdbd()->get_moodboard_url( 'view' . '/' . $moodboard_id ) : YITH_mdbd()->get_moodboard_url( 'user' . '/' . get_current_user_id() );
					$share_links_title = apply_filters( 'plugin_text', urlencode( get_option( 'yith_mdbd_socials_title' ) ) );
					$share_twitter_summary = urlencode( str_replace( '%moodboard_url%', '', get_option( 'yith_mdbd_socials_text' ) ) );
					$share_summary = urlencode( str_replace( '%moodboard_url%', $share_link_url, get_option( 'yith_mdbd_socials_text' ) ) );
					$share_image_url = urlencode( get_option( 'yith_mdbd_socials_image_url' ) );

					$share_atts = array(
						'share_facebook_enabled' => $share_facebook_enabled,
						'share_twitter_enabled' => $share_twitter_enabled,
						'share_pinterest_enabled' => $share_pinterest_enabled,
						'share_googleplus_enabled' => $share_googleplus_enabled,
						'share_email_enabled' => $share_email_enabled,
						'share_title' => $share_title,
						'share_link_url' => $share_link_url,
						'share_link_title' => $share_links_title,
						'share_twitter_summary' => $share_twitter_summary,
						'share_summary' => $share_summary,
						'share_image_url' => $share_image_url
					);

					$additional_params['share_atts'] = $share_atts;
				}
			}

			$additional_params = apply_filters( 'yith_mdbd_moodboard_params', $additional_params, $action, $action_params, $pagination, $per_page );
			$additional_params['template_part'] = isset( $additional_params['template_part'] ) ? $additional_params['template_part'] : $template_part;

			$atts = array_merge(
				$atts,
				$additional_params
			);

			// adds attributes list to params to extract in template, so it can be passed through a new get_template()
			$atts['atts'] = $atts;

			// apply filters for add to cart buttons
			add_filter( 'woocommerce_loop_add_to_cart_link', array( 'YITH_mdbd_UI', 'alter_add_to_cart_button' ), 10, 2 );

			// sets that we're in the moodboard template
			$yith_mdbd_is_moodboard = true;

			$template = yith_mdbd_get_template( 'moodboard.php', $atts, true );

			// we're not in moodboard template anymore
			$yith_mdbd_is_moodboard = false;
			$yith_mdbd_moodboard_token = null;

			// remove filters for add to cart buttons
			remove_filter( 'woocommerce_loop_add_to_cart_link', array( 'YITH_mdbd_UI', 'alter_add_to_cart_button' ) );

			return apply_filters( 'yith_mdbd_moodboardh_html', $template, array(), true );
		}

		/**
		 * Return "Add to moodboard" button.
		 *
		 * @since 1.0.0
		 */
		public static function add_to_moodboard( $atts, $content = null ) {
			global $product;

			// product object
			$current_product = ( isset( $atts['product_id'] ) ) ? wc_get_product( $atts['product_id'] ) : false;
			$current_product = $current_product ? $current_product : $product;

			$template_part = 'button';

			// labels & icons settings
			$label_option = get_option( 'yith_mdbd_add_to_moodboard_text' );
			$icon_option = get_option( 'yith_mdbd_add_to_moodboard_icon' ) != 'none' ? get_option( 'yith_mdbd_add_to_moodboard_icon' ) : '';

			$label = apply_filters( 'yith_mdbd_button_label', $label_option );
			$icon = apply_filters( 'yith_mdbd_button_icon', $icon_option );

			$browse_moodboard = get_option( 'yith_mdbd_browse_moodboard_text' );

			$already_in_moodboard = get_option( 'yith_mdbd_already_in_moodboard_text' );

			$product_added = get_option( 'yith_mdbd_product_added_text' );

			// button class
			$classes = apply_filters( 'yith_mdbd_add_to_moodboard_button_classes', get_option( 'yith_mdbd_use_button' ) == 'yes' ? 'add_to_moodboard single_add_to_moodboard button alt' : 'add_to_moodboard' );

			// default moodboard id
			$default_moodboards = is_user_logged_in() ? YITH_mdbd()->get_moodboards( array( 'is_default' => true ) ) : false;

			if( ! empty( $default_moodboards ) ){
				$default_moodboard = $default_moodboards[0]['ID'];
			}
			else{
				$default_moodboard = false;
			}

			// exists in default moodboard
			$exists = YITH_mdbd()->is_product_in_moodboard( $current_product->get_id(), $default_moodboard );

			// get moodboard url
			$moodboard_url = YITH_mdbd()->get_moodboard_url();

			// get product type
			$product_type = $current_product->get_type();

			$additional_params = array(
				'moodboard_url' => $moodboard_url,
				'exists' => $exists,
				'product_id' => $current_product->get_id(),
				'product_type' => $product_type,
				'label' => $label,
				'browse_moodboard_text' => $browse_moodboard,
				'already_in_wishslist_text' => $already_in_moodboard,
				'product_added_text' => $product_added,
				'icon' => $icon,
				'link_classes' => $classes,
				'available_multi_moodboard' => false,
				'disable_moodboard' => false
			);

			$additional_params = apply_filters( 'yith_mdbd_add_to_moodboard_params', $additional_params );
			$additional_params['template_part'] = isset( $additional_params['template_part'] ) ? $additional_params['template_part'] : $template_part;

			$atts = shortcode_atts(
				$additional_params,
				$atts
			);

			$atts['icon'] = ! empty( $atts['icon'] ) ? '<i class="fa ' . $atts['icon'] . '"></i>' : '';

			// adds attributes list to params to extract in template, so it can be passed through a new get_template()
			$atts['atts'] = $atts;

			$template = yith_mdbd_get_template( 'add-to-moodboard.php', $atts, true );

			return apply_filters( 'yith_mdbd_add_to_moodboardh_button_html', $template, $moodboard_url, $product_type, $exists );
		}
	}
}

add_shortcode( 'yith_mdbd_moodboard', array( 'YITH_mdbd_Shortcode', 'moodboard' ) );
add_shortcode( 'yith_mdbd_add_to_moodboard', array( 'YITH_mdbd_Shortcode', 'add_to_moodboard' ) );