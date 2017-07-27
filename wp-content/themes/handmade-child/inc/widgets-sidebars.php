<?php
/**
 * Widgets and Sidebars
 *
 * WordPress Widgets add content and features to your Sidebars. Examples are
 * the default widgets that come with WordPress; for post categories, tag
 * clouds, navigation, search, etc.
 *
 * Sidebar is a theme feature introduced with Version 2.2. It's basically a
 * vertical column provided by a theme for displaying information other than
 * the main content of the web page. Themes usually provide at least one
 * sidebar at the left or right of the content. Sidebars usually contain
 * widgets that an administrator of the site can customize.
 *
 * @link https://codex.wordpress.org/WordPress_Widgets
 * @link https://codex.wordpress.org/Widgets_API
 * @link https://codex.wordpress.org/Sidebars
 *
 * @package WordPress
 * @subpackage Handmade-Child
 */

if ( function_exists( 'register_sidebar' ) ) {
	/**
	 * Add Widget.
	 */
	function handmade_child_widgets_init() {

		register_widget( 'Product_Widget_Collections' );

	}
	add_action( 'widgets_init', 'handmade_child_widgets_init' );
}

/**
 * Class Product_Widget_Collections
 */
class Product_Widget_Collections extends WP_Widget {

	/**
	 * Sets up a new Collections widget instance.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'product_widget_collections',
			'description' => __( 'A list or dropdown of Collections.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'product_widget_collections', __( 'Product Collections' ), $widget_ops );
	}

	/**
	 * Outputs the content for the current Categories widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Categories widget instance.
	 */
	public function widget( $args, $instance ) {
		static $first_dropdown = true;

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Product Collections' ) : $instance['title'], $instance, $this->id_base );

		$c = ! empty( $instance['count'] ) ? '1' : '0';
		$h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
		$d = ! empty( $instance['dropdown'] ) ? '1' : '0';

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$cat_args = array(
			'orderby'      => 'name',
			'taxonomy'     => 'collections',
			'show_count'   => $c,
			'hierarchical' => $h
		);

		if ( $d ) {
			$dropdown_id = ( $first_dropdown ) ? 'cat' : "{$this->id_base}-dropdown-{$this->number}";
			$first_dropdown = false;

			echo '<label class="screen-reader-text" for="' . esc_attr( $dropdown_id ) . '">' . $title . '</label>';

			$cat_args['show_option_none'] = __( 'Select Collection' );
			$cat_args['id'] = $dropdown_id;

			/**
			 * Filter the arguments for the Collections widget drop-down.
			 *
			 * @since 2.8.0
			 *
			 * @see wp_dropdown_categories()
			 *
			 * @param array $cat_args An array of Collections widget drop-down arguments.
			 */
			wp_dropdown_categories( apply_filters( 'widget_categories_dropdown_args', $cat_args ) );
			?>

			<script type='text/javascript'>
				/* <![CDATA[ */
				(function() {
					var dropdown = document.getElementById( "<?php echo esc_js( $dropdown_id ); ?>" );
					function onCatChange() {
						if ( dropdown.options[ dropdown.selectedIndex ].value > 0 ) {
							location.href = "<?php echo home_url(); ?>/?cat=" + dropdown.options[ dropdown.selectedIndex ].value;
						}
					}
					dropdown.onchange = onCatChange;
				})();
				/* ]]> */
			</script>

		<?php
		} else {
			?>
			<ul class="u-list--plain">
				<?php
				$cat_args['title_li'] = '';

				/**
				 * Filter the arguments for the Collections widget.
				 *
				 * @since 2.8.0
				 *
				 * @param array $cat_args An array of Collections widget options.
				 */
				wp_list_categories( apply_filters( 'widget_categories_args', $cat_args ) );
				?>
			</ul>
		<?php
		}

		echo $args['after_widget'];
	}

	/**
	 * Handles updating settings for the current Collections widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;

		return $instance;
	}

	/**
	 * Outputs the settings form for the Categories widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = sanitize_text_field( $instance['title'] );
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
			<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown' ); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
			<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
	<?php
	}

}
