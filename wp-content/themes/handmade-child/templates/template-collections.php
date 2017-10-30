<?php
/*
* Template Name: Collections Template
*/

// Доделать пагинацию.
?>
<?php get_header();

if ( get_query_var( 'paged' ) ){
	$paged = get_query_var('paged');
}
else if ( get_query_var( 'page' ) ){
	$paged = get_query_var( 'page' );
}
else {
	$paged = 1;
}
$per_page = 12;
$number_of_pages = count( get_terms( 'collections',array('hide_empty'=>'0') ) );
$offset = $per_page * ( $paged - 1) ;

// Setup the arguments to pass in
$args = array(
    'offset'     => $offset,
    'number'     => $per_page,
    'hide_empty' =>'0'
);

    // Gather the series
$terms = get_terms( 'collections', $args );
?>
<div class="container clearfix">
    <div class="row row-flex">
<?php
// Loop through and display the series
if ( ! empty( $terms ) ) {

	foreach ( $terms as $term ) {
		$img = get_term_meta( $term->term_id, 'wpcf-collection-featured-img', TRUE );
		$img_name = explode('.jpg' , $img );
		$img_collection_size = $img_name[0] . '-300x300.jpg';

      ?>
        <div class="col-md-4 col-sm-4 col-xs-12 collection-item">
            <a class="hover-name" href="<?php echo get_term_link( (int)$term->term_id, 'collections' ); ?>"
               data-title="<?php echo $term->name; ?>">
                <img class="img-link" src="<?php echo $img_collection_size; ?>"
                     alt="<?php echo $term->name; ?>">
            </a>
        </div>
		<?php
	}
}
?>
    </div>
  <?php
  $big = 999999;
  echo '<div class="collection-pagination">';
  echo paginate_links( array(
    'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
    'format'  => '?paged=%#%',
    'current' => $paged,
    'total'   => ceil( $number_of_pages / $per_page )
  ) );
  echo '</div>';
  ?>
</div>
<?php get_footer(); ?>

