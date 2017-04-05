<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	<input type="text" class="search-field" placeholder="<?php echo esc_attr_x( 'Search Products&hellip;', 'placeholder', 'g5plus-handmade' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'g5plus-handmade' ); ?>" />
	<button type="submit"><i class="fa fa-search"></i></button>
	<input type="hidden" name="post_type" value="product" />
</form>
