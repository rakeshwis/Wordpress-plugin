<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.


/**
 * Add meta boxes to selected post types
 *
 * @since 1.0
 */
function posts_special_gallery_add_meta_box() {

     add_meta_box( 'posts_sepecial_gallery', apply_filters( 'posts_sepecial_gallery_meta_box_title', __( 'Post Special Gallery', 'posts_sepecial_gallery' ) ), 'posts_sepecial_gallery_metabox', 'post', apply_filters( 'posts_sepecial_gallery_meta_box_context', 'side' ), apply_filters( 'posts_sepecial_gallery_meta_box_priority', 'high' ) );

    
}
add_action( 'add_meta_boxes', 'posts_special_gallery_add_meta_box' );



/**
 * Render gallery metabox
 *
 * @since 1.0
 */
function posts_sepecial_gallery_metabox() {

    global $post;
?>

    <div id="gallery_images_containers"  style="min-height:100px;height:auto;">

        <ul class="gallery_images">
            <?php

    $image_gallery = get_post_meta( $post->ID, '_posts_special_gallery', true );
    $attachments = array_filter( explode( ',', $image_gallery ) );

    if ( $attachments )
        foreach ( $attachments as $attachment_id ) {
	        echo '<li class="image attachment details" data-attachment_id="' . $attachment_id . '"><div class="attachment-preview"><div class="thumbnail">
                            ' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '</div>
                            <a href="#" class="delete check" style="display:block !important;" title="' . __( 'Remove image', 'posts-sepecial-gallery' ) . '"><div class="media-modal-icon"></div></a>
                           
                        </div></li>';
        }
?>
        </ul>


        <input type="hidden" id="image_gallery" name="image_gallery" value="<?php echo esc_attr( $image_gallery ); ?>" />
        <?php wp_nonce_field( 'posts_sepecial_gallery', 'posts_sepecial_gallery' ); ?>
		<p class="add_gallery_images" style="float:left;width:100%">
			<a href="#" class="button button-primary right">Add Gallery</a>
		</p>

    </div>

   
    <?php

    // options don't exist yet, set to checked by default
    if ( ! get_post_meta( get_the_ID(), '_posts_sepecial_gallery_link_images', true ) )
        $checked = ' checked="checked"';
    else
        $checked = posts_sepecial_gallery_has_linked_images() ? checked( get_post_meta( get_the_ID(), '_posts_sepecial_gallery_link_images', true ), 'on', false ) : '';

?>

    <?php
    /**
     * Props to WooCommerce for the following JS code
     */
?>
    <script type="text/javascript">
        jQuery(document).ready(function($){

            // Uploading files
            var image_gallery_frame;
            var $image_gallery_ids = $('#image_gallery');
            var $gallery_images = $('#gallery_images_containers ul.gallery_images');

            jQuery('.add_gallery_images').on( 'click', 'a', function( event ) {

                var $el = $(this);
                var attachment_ids = $image_gallery_ids.val();

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if ( image_gallery_frame ) {
                    image_gallery_frame.open();
                    return;
                }

                // Create the media frame.
                image_gallery_frame = wp.media.frames.downloadable_file = wp.media({
                    // Set the title of the modal.
                    title: '<?php _e( 'Add Images to Gallery', 'easy-image-gallery' ); ?>',
                    button: {
                        text: '<?php _e( 'Add to gallery', 'easy-image-gallery' ); ?>',
                    },
                    multiple: true
                });

                // When an image is selected, run a callback.
                image_gallery_frame.on( 'select', function() {

                    var selection = image_gallery_frame.state().get('selection');

                    selection.map( function( attachment ) {

                        attachment = attachment.toJSON();

                        if ( attachment.id ) {
                            attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

                             $gallery_images.append('\
                                <li class="image attachment details" data-attachment_id="' + attachment.id + '">\
                                    <div class="attachment-preview">\
                                        <div class="thumbnail">\
                                            <img src="' + attachment.url + '" />\
                                        </div>\
                                       <a href="#" class="delete check" title="Remove image"><div class="media-modal-icon"></div></a>\
                                    </div>\
                                </li>');

                        }

                    } );

                    $image_gallery_ids.val( attachment_ids );
                });

                // Finally, open the modal.
                image_gallery_frame.open();
            });

            // Image ordering
            $gallery_images.sortable({
                items: 'li.image',
                cursor: 'move',
                scrollSensitivity:40,
                forcePlaceholderSize: true,
                forceHelperSize: false,
                helper: 'clone',
                opacity: 0.65,
                placeholder: 'eig-metabox-sortable-placeholder',
                start:function(event,ui){
                    ui.item.css('background-color','#f6f6f6');
                },
                stop:function(event,ui){
                    ui.item.removeAttr('style');
                },
                update: function(event, ui) {
                    var attachment_ids = '';

                    $('#gallery_images_containers ul li.image').css('cursor','default').each(function() {
                        var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                        attachment_ids = attachment_ids + attachment_id + ',';
                    });

                    $image_gallery_ids.val( attachment_ids );
                }
            });

            // Remove images
            $('#gallery_images_containers').on( 'click', 'a.delete', function() {

                $(this).closest('li.image').remove();

                var attachment_ids = '';

                $('#gallery_images_containers ul li.image').css('cursor','default').each(function() {
                    var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                    attachment_ids = attachment_ids + attachment_id + ',';
                });

                $image_gallery_ids.val( attachment_ids );

                return false;
            } );

        });
    </script>
    <?php
}

/**
 * Save function
 *
 * @since 1.0
 */
function posts_special_gallery_save_post( $post_id ) {

   if ( isset( $_POST[ 'image_gallery' ] ) && !empty( $_POST[ 'image_gallery' ] ) ) {

        $attachment_ids = sanitize_text_field( $_POST['image_gallery'] );

        // turn comma separated values into array
        $attachment_ids = explode( ',', $attachment_ids );

        // clean the array
        $attachment_ids = array_filter( $attachment_ids  );

        // return back to comma separated list with no trailing comma. This is common when deleting the images
        $attachment_ids =  implode( ',', $attachment_ids );

        update_post_meta( $post_id, '_posts_special_gallery', $attachment_ids );
    } else {
        delete_post_meta( $post_id, '_posts_special_gallery' );
    }

    // link to larger images
    if ( isset( $_POST[ 'posts_special_gallery_link_images' ] ) )
        update_post_meta( $post_id, '_posts_special_gallery_link_images', $_POST[ 'posts_special_gallery_link_images' ] );
    else
        update_post_meta( $post_id, '_posts_special_gallery_link_images', 'off' );

    do_action( 'posts_special_gallery_save_post', $post_id );
}
add_action( 'save_post', 'posts_special_gallery_save_post' );