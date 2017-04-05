<?php
/**
 * Init class
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce moodboard
 * @version 2.0.9
 */

if ( ! defined( 'YITH_mdbd' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_mdbd_Init' ) ) {
	/**
	 * Initiator class. Install the plugin database and load all needed stuffs.
	 *
	 * @since 1.0.0
	 */
	class YITH_mdbd_Init {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_mdbd_Init
		 * @since 2.0.0
		 */
		protected static $instance;

		/**
		 * CSS selectors used to style buttons.
		 *
		 * @var array
		 * @since 1.0.0
		 */
		public $rules;

		/**
		 * Front end colors options.
		 *
		 * @var array
		 * @since 1.0.0
		 */
		public $colors_options;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = '2.0.16';

		/**
		 * Plugin database version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $db_version = '2.0.0';

		/**
		 * Positions of the button "Add to moodboard"
		 *
		 * @var array
		 * @access private
		 * @since 1.0.0
		 */
		private $_positions;

		/**
		 * Store class yith_mdbd_Install.
		 *
		 * @var object
		 * @access private
		 * @since 1.0.0
		 */
		private $_yith_mdbd_install;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_mdbd_Init
		 * @since 2.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			define( 'YITH_mdbd_VERSION', $this->version );
			define( 'YITH_mdbd_DB_VERSION', $this->db_version );

			$this->_yith_mdbd_install = YITH_mdbd_Install();
			$this->_positions         = apply_filters( 'yith_mdbd_positions', array(
				'add-to-cart' => array( 'hook' => 'woocommerce_single_product_summary', 'priority' => 31 ),
				'thumbnails'  => array( 'hook' => 'woocommerce_product_thumbnails', 'priority' => 21 ),
				'summary'     => array( 'hook' => 'woocommerce_after_single_product_summary', 'priority' => 11 )
			) );
			$this->rules              = apply_filters( 'yith_mdbd_colors_rules', array(
				'add_to_moodboard'       => '.woocommerce a.add_to_moodboard.button.alt',

				'add_to_moodboard_hover' => '.woocommerce a.add_to_moodboard.button.alt:hover',

				'add_to_cart'           => '.woocommerce .moodboard_table a.add_to_cart.button.alt',

				'add_to_cart_hover'     => '.woocommerce .moodboard_table a.add_to_cart.button.alt:hover',

				'button_style_1'        => '.woocommerce a.button.ask-an-estimate-button,
                                            .woocommerce .hidden-title-form button,
                                            .yith-mdbd-moodboard-new .create-moodboard-button,
                                            .moodboard_manage_table tfoot button.submit-moodboard-changes,
                                            .yith-mdbd-moodboard-search-form button.moodboard-search-button',

				'button_style_1_hover'  => '.woocommerce a.button.ask-an-estimate-button:hover,
                                            .woocommerce .hidden-title-form button:hover,
                                            .yith-mdbd-moodboard-new .create-moodboard-button:hover,
                                            .moodboard_manage_table tfoot button.submit-moodboard-changes:hover,
                                            .yith-mdbd-moodboard-search-form button.moodboard-search-button:hover',

				'button_style_2'        => '.woocommerce .moodboard-title a.show-title-form,
                                            .woocommerce .hidden-title-form a.hide-title-form,
                                            .moodboard_manage_table tfoot a.create-new-moodboard',

				'button_style_2_hover'  => '.woocommerce .moodboard-title a.show-title-form:hover,
                                            .woocommerce .hidden-title-form a.hide-title-form:hover,
                                            .moodboard_manage_table tfoot a.create-new-moodboard:hover',

				'moodboard_table'        => '.woocommerce table.shop_table.moodboard_table',

				'headers'               => '.moodboard_table thead,
                                            .moodboard_table tfoot,
                                            .yith-mdbd-moodboard-new,
                                            .yith-mdbd-moodboard-search-form,
                                            .widget_yith-mdbd-lists ul.dropdown li.current a,
                                            .widget_yith-mdbd-lists ul.dropdown li a:hover,
                                            .selectBox-dropdown-menu.selectBox-options li.selectBox-selected a,
                                            .selectBox-dropdown-menu.selectBox-options li.selectBox-hover a'
			) );

			$db_colors = get_option( 'yith_mdbd_frontend_css_colors' );

			$this->colors_options = wp_parse_args(
				maybe_unserialize( $db_colors ),
				apply_filters(
					'yith_mdbd_colors_options', array(
						'add_to_moodboard'       => array( 'background' => '#333333', 'color' => '#FFFFFF', 'border_color' => '#333333' ),
						'add_to_moodboard_hover' => array( 'background' => '#4F4F4F', 'color' => '#FFFFFF', 'border_color' => '#4F4F4F' ),
						'add_to_cart'           => array( 'background' => '#333333', 'color' => '#FFFFFF', 'border_color' => '#333333' ),
						'add_to_cart_hover'     => array( 'background' => '#4F4F4F', 'color' => '#FFFFFF', 'border_color' => '#4F4F4F' ),
						'button_style_1'        => array( 'background' => '#333333', 'color' => '#FFFFFF', 'border_color' => '#333333' ),
						'button_style_1_hover'  => array( 'background' => '#4F4F4F', 'color' => '#FFFFFF', 'border_color' => '#4F4F4F' ),
						'button_style_2'        => array( 'background' => '#FFFFFF', 'color' => '#858484', 'border_color' => '#c6c6c6' ),
						'button_style_2_hover'  => array( 'background' => '#4F4F4F', 'color' => '#FFFFFF', 'border_color' => '#4F4F4F' ),
						'moodboard_table'        => array( 'background' => '#FFFFFF', 'color' => '#6d6c6c', 'border_color' => '#FFFFFF' ),
						'headers'               => array( 'background' => '#F4F4F4' )
					)
				)
			);

			if ( empty( $db_colors ) ) {
				update_option( 'yith_mdbd_frontend_css_colors', maybe_serialize( $this->colors_options ) );
			}

			if ( get_option( 'yith_mdbd_enabled' ) == 'yes' ) {
				add_action( 'init', array( $this, 'init' ), 0 );
				add_action( 'wp_head', array( $this, 'detect_javascript' ), 0 );
				add_action( 'wp_head', array( $this, 'add_button' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_and_stuffs' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				add_filter( 'body_class', array( $this, 'add_body_class' ) );

				// add YITH WooCommerce Frequently Bought Together Premium shortcode
				add_action( 'yith_mdbd_after_moodboard_form', array( $this, 'yith_wcfbt_shortcode' ), 10, 1 );

				// YITH mdbd Loaded
				do_action( 'yith_mdbd_loaded' );
			}
		}

		/**
		 * Initiator method.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function init() {
			// update cookie from old version to new one
			$this->_update_cookies();
			$this->_destroy_serialized_cookies();

			if ( is_user_logged_in() ) {
				YITH_mdbd()->details['user_id'] = get_current_user_id();

				//check whether any products are added to moodboard, then after login add to the moodboard if not added
				$cookie = yith_getcookie( 'yith_mdbd_products' );
				if( ! empty( $cookie ) && is_array( $cookie ) ) {
					foreach ( $cookie as $details ) {
						YITH_mdbd()->details['add_to_moodboard'] = $details['prod_id'];
						YITH_mdbd()->details['moodboard_id']     = $details['moodboard_id'];
						YITH_mdbd()->details['quantity']        = $details['quantity'];
						YITH_mdbd()->details['user_id']         = get_current_user_id();

						$ret_val = YITH_mdbd()->add();
					}
				}

				yith_destroycookie( 'yith_mdbd_products' );
			}
		}

		/**
		 * Add the "Add to moodboard" button. Needed to use in wp_head hook.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function add_button() {
			global $post;

			$this->_positions = apply_filters( 'yith_mdbd_positions', $this->_positions );

			if ( ! isset( $post ) || ! is_object( $post ) ) {
				return;
			}

			// Add the link "Add to moodboard"
			$position = get_option( 'yith_mdbd_button_position' );
			$position = empty( $position ) ? 'add-to-cart' : $position;

			if ( $position != 'shortcode' ) {
				add_action( $this->_positions[$position]['hook'], create_function( '', 'echo do_shortcode( "[yith_mdbd_add_to_moodboard]" );' ), $this->_positions[$position]['priority'] );
			}

			// Free the memory. Like it needs a lot of memory... but this is rock!
		}

		/**
		 * Add specific body class when the moodboard page is opened
		 *
		 * @param array $classes
		 *
		 * @return array
		 * @since 1.0.0
		 */
		public function add_body_class( $classes ) {
			$moodboard_page_id = yith_mdbd_object_id( get_option( 'yith_mdbd_moodboard_page_id' ) );

			if ( is_page( $moodboard_page_id ) ) {
				$classes[] = 'woocommerce-moodboard';
				$classes[] = 'woocommerce';
				$classes[] = 'woocommerce-page';
			}

			return $classes;
		}

		/**
		 * Enqueue styles, scripts and other stuffs needed in the <head>.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function enqueue_styles_and_stuffs() {
			global $woocommerce;

			$assets_path = str_replace( array( 'http:', 'https:' ), '', $woocommerce->plugin_url() ) . '/assets/';

			if( function_exists( 'WC' ) ){
				$woocommerce_base = WC()->template_path();
			}
			else{
				$woocommerce_base = WC_TEMPLATE_PATH;
			}

			$located = locate_template( array(
				$woocommerce_base . 'moodboard.css',
				'moodboard.css'
			) );

			wp_register_style( 'woocommerce_prettyPhoto_css', $assets_path . 'css/prettyPhoto.css', array(), '3.1.6' );
			wp_register_style( 'jquery-selectBox', YITH_mdbd_URL . 'assets/css/jquery.selectBox.css', array(), '1.2.0' );
			wp_register_style( 'yith-mdbd-main', YITH_mdbd_URL . 'assets/css/style.css', array(), $this->version );
			wp_register_style( 'yith-mdbd-user-main', str_replace( get_template_directory(), get_template_directory_uri(), $located ), array(), $this->version );
			wp_register_style( 'yith-mdbd-font-awesome', YITH_mdbd_URL . 'assets/css/font-awesome.min.css', array(), '4.3.0' );

			wp_enqueue_style( 'woocommerce_prettyPhoto_css' );
			wp_enqueue_style( 'jquery-selectBox' );

			if ( ! $located ) {
				wp_enqueue_style( 'yith-mdbd-main' );
			}
			else {
				wp_enqueue_style( 'yith-mdbd-user-main' );
			}

			wp_enqueue_style( 'yith-mdbd-font-awesome' );

			// Add frontend CSS for buttons
			$colors_styles = array();
			$frontend_css  = '';
			if ( get_option( 'yith_mdbd_frontend_css' ) == 'no' ) {
				foreach ( $this->colors_options as $name => $option ) {
					$colors_styles[$name] = '';

					foreach ( $option as $id => $value ) {
						$colors_styles[$name] .= str_replace( '_', '-', $id ) . ':' . $value . ';';
					}
				}

				foreach ( $this->rules as $id => $rule ) {
					$frontend_css .= $rule . '{' . $colors_styles[$id] . '}';
				}
			}

			?>
			<style>
				<?php
				echo get_option( 'yith_mdbd_custom_css' ) . $frontend_css;

				if( get_option( 'yith_mdbd_rounded_corners' ) == 'yes' ) {
					echo '.moodboard_table .add_to_cart, a.add_to_moodboard.button.alt { border-radius: 16px; -moz-border-radius: 16px; -webkit-border-radius: 16px; }';
				}
				?>
			</style>
			<script type="text/javascript">
				var yith_mdbd_plugin_ajax_web_url = '<?php echo admin_url('admin-ajax.php', 'relative') ?>';
			</script>
		<?php
		}

		/**
		 * Enqueue plugin scripts.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function enqueue_scripts() {
			global $woocommerce;

			if( function_exists( 'WC' ) ){
				$woocommerce_base = WC()->template_path();
			}
			else{
				$woocommerce_base = WC_TEMPLATE_PATH;
			}

			$located = locate_template( array(
				$woocommerce_base . 'moodboard.js',
				'moodboard.js'
			) );

			$assets_path = str_replace( array( 'http:', 'https:' ), '', $woocommerce->plugin_url() ) . '/assets/';
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_script( 'prettyPhoto', $assets_path . 'js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), '3.1.5', true );
			wp_enqueue_script( 'prettyPhoto-init', $assets_path . 'js/prettyPhoto/jquery.prettyPhoto.init' . $suffix . '.js', array( 'jquery','prettyPhoto' ), defined( 'WC_VERSION' ) ? WC_VERSION : WOOCOMMERCE_VERSION, true );
			wp_enqueue_script( 'jquery-selectBox', YITH_mdbd_URL . 'assets/js/jquery.selectBox.min.js', array( 'jquery' ), '1.2.0', true );
			wp_register_script( 'jquery-yith-mdbd', YITH_mdbd_URL . 'assets/js/jquery.yith-mdbd.js', array( 'jquery', 'jquery-selectBox' ), $this->version, true );
			wp_register_script( 'jquery-yith-mdbd-user', str_replace( get_template_directory(), get_template_directory_uri(), $located ), array( 'jquery', 'jquery-selectBox' ), $this->version, true );

			$yith_mdbd_l10n = array(
				'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
				'redirect_to_cart' => get_option( 'yith_mdbd_redirect_cart' ),
				'multi_moodboard' => get_option( 'yith_mdbd_multi_moodboard_enable' ) == 'yes' ? true : false,
				'hide_add_button' => apply_filters( 'yith_mdbd_hide_add_button', true ),
				'is_user_logged_in' => is_user_logged_in(),
				'ajax_loader_url' => YITH_mdbd_URL . 'assets/images/ajax-loader.gif',
				'remove_from_moodboard_after_add_to_cart' => get_option( 'yith_mdbd_remove_after_add_to_cart' ),
				'labels' => array(
					'cookie_disabled' => __( 'We are sorry, but this feature is available only if cookies are enabled on your browser.', 'yith-woocommerce-moodboard' ),
					'added_to_cart_message' => sprintf( '<div class="woocommerce-message">%s</div>', apply_filters( 'yith_mdbd_added_to_cart_message', __( 'Product correctly added to cart', 'yith-woocommerce-moodboard' ) ) )
				),
				'actions' => array(
					'add_to_moodboard_action' => 'add_to_moodboard',
					'remove_from_moodboard_action' => 'remove_from_moodboard',
					'move_to_another_moodboard_action' => 'move_to_another_wishlsit',
					'reload_moodboard_and_adding_elem_action'  => 'reload_moodboard_and_adding_elem'
				)
			);

			if ( ! $located ) {
				wp_enqueue_script( 'jquery-yith-mdbd' );
				wp_localize_script( 'jquery-yith-mdbd', 'yith_mdbd_l10n', $yith_mdbd_l10n );
			}
			else {
				wp_enqueue_script( 'jquery-yith-mdbd-user' );
				wp_localize_script( 'jquery-yith-mdbd-user', 'yith_mdbd_l10n', $yith_mdbd_l10n );
			}
		}

		/**
		 * Remove the class no-js when javascript is activated
		 *
		 * We add the action at the start of head, to do this operation immediatly, without gap of all libraries loading
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function detect_javascript() {
			if( ! defined( 'YIT' ) ):
				?>
				<script type="text/javascript">document.documentElement.className = document.documentElement.className + ' yes-js js_active js'</script>
			<?php
			endif;
		}

		/**
		 * Destroy serialize cookies, to prevent major vulnerability
		 *
		 * @return void
		 * @since 2.0.7
		 */
		private function _destroy_serialized_cookies(){
			$name = 'yith_mdbd_products';

			if ( isset( $_COOKIE[$name] ) && is_serialized( stripslashes( $_COOKIE[ $name ] ) ) ) {
				$_COOKIE[ $name ] = json_encode( array() );
				yith_destroycookie( $name );
			}
		}

		/**
		 * Update old moodboard cookies
		 *
		 * @return void
		 * @since 2.0.0
		 */
		private function _update_cookies(){
			$cookie = yith_getcookie( 'yith_mdbd_products' );
			$new_cookie = array();

			if( ! empty( $cookie ) ) {
				foreach ( $cookie as $item ) {
					if ( ! isset( $item['add-to-moodboard'] ) ) {
						return;
					}

					$new_cookie[] = array(
						'prod_id'     => $item['add-to-moodboard'],
						'quantity'    => isset( $item['quantity'] ) ? $item['quantity'] : 1,
						'moodboard_id' => false
					);
				}

				yith_setcookie( 'yith_mdbd_products', $new_cookie );
			}
		}

		/**
		 * Add Frequently Bought Together shortcode to moodboard page
		 *
		 * @param mixed $meta
		 * @author Francesco Licandro
		 */
		public function yith_wcfbt_shortcode( $meta ){

			if( ! ( defined( 'YITH_WFBT' ) && YITH_WFBT ) || get_option( 'yith_wfbt_enable_integration' ) == 'no' ) {
				return;
			}

			$products = YITH_mdbd()->get_products(
				array(
					'moodboard_id' => is_user_logged_in() ? $meta['ID'] : ''
				));

			$ids   = array();
			// take id of products in moodboard
			foreach( $products as $product ) {
				$ids[] = $product['prod_id'];
			}

			if( empty( $ids ) ) {
				return;
			}

			do_shortcode( '[yith_wfbt products="' . implode( ',', $ids ) . '"]' );
		}
	}
}

/**
 * Unique access to instance of YITH_mdbd_Init class
 *
 * @return \YITH_mdbd_Init
 * @since 2.0.0
 */
function YITH_mdbd_Init(){
	return YITH_mdbd_Init::get_instance();
}