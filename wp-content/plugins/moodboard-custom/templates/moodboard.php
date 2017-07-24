<?php
/*
Part of Name: Custom Moodboard
Description: The moodboard template for the Moodboard plugin.
Version: 1.0
*/
defined( 'WOWMOODBOARD' ) or die( 'No direct access to this file allowed.' );


//if ( isset( $edit ) && $edit )
//{
	// Prepare the css class to edit the moodboard
	$canvasclass = 'woweditcanvas';
//}
//else
//{
//	// Prepare the css class to simply show the moodboard
//	$canvasclass = 'wowcanvas';
//}

?>

<div id='moodboard'>	
<?php // if ( isset( $edit ) && $edit ) : ?>
	<div id="editmode">   
    	<input type="checkbox" id="switcheditmode" >
        <label for="switcheditmode"><?php echo translate( 'Hide Edit-mode' ); ?></label>
    </div>
    <div id="wow-edit-panel" style='display:block;'>
	<div id='wowtabs' class='ui-tabs ui-widget ui-widget-content ui-corner-all'>
    	<ul class='ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all'>
            <?php if ( !isset( $this->UploadActive ) || $this->UploadActive ) : ?>
            <li class='wowtab'>
            	<a class='ui-tabs-anchor' href='#uploader'><?php echo translate( 'Upload' ); ?></a>
            </li>
            <?php endif ?>
		</ul>

        <?php if ( !isset( $this->UploadActive ) || $this->UploadActive ) : ?>
        <div id='uploader' class='ui-tabs-panel ui-widget-content' style='display:none;'>
    			<p><?php echo translate( "Your browser doesn't have Flash, Silverlight or HTML5 support." ); ?></p>
                <script type='text/javascript'>
				document.addEventListener("DOMContentLoaded", function(event) 
				{
					wowupload( 	<?php echo json_encode( wp_create_nonce( 'media-form' ) ); ?>, 
								<?php echo json_encode( admin_url( 'async-upload.php' ) ); ?>, 
								<?php echo json_encode( includes_url( ) ); ?>
					)
				});
				</script>
		</div>
        <?php endif ?>
	</div>
    <div id='imagescroller' class="scroll-pane">
    	<label> 
			<button id='clearsearchresults' onclick='resetImageResults()' style='display:none;'><?php echo translate( 'Clear Results' ); ?></button>
        </label>
   		<div id='wowcanvasimages' class="scroll-content">
            <?php
            $args = array(
              'post_type' => 'product',
              'posts_per_page' => -1
            );
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) {
              while ( $query->have_posts() ) : $query->the_post();
	              global $product;
	              $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id(), 'full' ) );
	              ?>
                <div class="scroll-content-item">
                      <img id="imageresult-<?php the_id(); ?>" src="<?php echo get_the_post_thumbnail_url( get_the_id(), 'thumbnail' ); ?>" class="ui-draggable ui-draggable-handle">
                </div>
             <?php endwhile;
            }
            wp_reset_postdata();
            ?>
    	</div>
		<div class="scroll-bar-wrap ui-widget-content ui-corner-bottom">
			<div class="scroll-bar" style='display:none;'></div>
		</div>
    </div>
    <?php
    	if ( isset( $_REQUEST[ 'file' ] ) ) { 
    		check_admin_referer( "wowmoodboard" );
 			echo absint( $_REQUEST[ 'file' ] );
		}
	?>
    </div>
<?php// endif ?>
	<div id='wowcanvas' class='<?php echo $canvasclass; ?>'>
    	<div id='loading'><img src='<?php echo plugins_url( '/assets/images/ajax-loader.gif', dirname(__FILE__) ); ?>' alt='Loading Moodboard' /></div>
    </div>
   
</div>
<script type='text/javascript'>
document.addEventListener("DOMContentLoaded", function(event)
{
	<?php if ( isset( $edit ) && $edit ) : ?>
	loadEditMode();
	<?php endif ?>

	initMoodboard( <?php echo json_encode( wp_create_nonce( 'wowcanvas-security'.$postid ) ); ?>, <?php echo json_encode( $postid ); ?> );

	<?php if ( isset( $edit ) && $edit ) : ?>
	CreateScrollbar();
	<?php endif ?>

});
</script>