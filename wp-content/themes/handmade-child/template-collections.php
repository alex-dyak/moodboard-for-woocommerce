<?php
/*
* Template Name: Collections Template
*/
?>
// Доделать пагинацию.
<?php get_header(); ?>
<div class="container clearfix">
    <div class="row clearfix">
<?php
$args  = array(
	'taxonomy'   => 'collections',
	'hide_empty' => TRUE,
);
$terms = get_terms( $args );
if ( ! empty( $terms ) ) {
	echo '<div class="col-md-9">';
	echo '<div class="clearfix layout-container">';
	echo '<div class="clearfix columns-3">';

	foreach ( $terms as $term ) {
		$img = get_term_meta( $term->term_id, 'wpcf-collection-featured-img',
			TRUE );
		?>
        <div class="col-md-4 collection-item">
            <a href="<?php echo get_term_link( (int)$term->term_id, 'collections' ); ?>"><img src="<?php echo $img; ?>" width="300"
                                       height="300" border="0"
                                       alt="<?php echo $term->name; ?>"></a>
        </div>
		<?php
	}
	echo '</div></div></div>';
}
?>
    </div>
</div>
<?php get_footer(); ?>

