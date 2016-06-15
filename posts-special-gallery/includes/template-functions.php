<?php
/**
 * Count number of images in array
 *
 * @since 1.0
 * @return integer
 */
function special_image_gallery_count_images() {
	$images = get_post_meta( get_the_ID(), '_posts_special_gallery', true );
	$images = explode( ',', $images );
	$number = count( $images );
	return $number;
}
// To show View Gallery button on frontpage
function post_special_gallery( $content ) {
	if ( is_single()) {
		if ( has_post_thumbnail()) {
			global $post;
			$post_txt = (special_image_gallery_count_images() > 1) ? ' Photos' : ' Photo';
			$custom_content = '<div class="sp_gallery"><a  id="open-popup" href="#special-gallery-'.$post->ID.'" class="spl-gallery-link" data-class="post-gallery-lightbox"><div class="rel">View Gallery<br><em>'.special_image_gallery_count_images().' '.$post_txt.'</em></div></a></div>';
			$custom_content .= '<div id="special-gallery-'.$post->ID.'" class="mfp-hide">';
			$photos_id = special_image_gallery_get_image_ids();
			if ($photos_id) { 
				$i = 1;
				foreach ($photos_id as $photo_id) {
					$custom_content .= '<div class="special-gallery-content">';
					$custom_content .= '<div class="lightbox-header">';
					$custom_content .= '<div class="row full-width-row no-padding">';
					$custom_content .= '<div class="small-6 medium-2 columns">';
					$custom_content .= '</div>';
					$custom_content .= '<div class="small-6 medium-8 columns text-center show-for-large-up">';
					$custom_content .= '<aside class="ad_container_gallery_header"></aside>';
					$custom_content .= '</div>';
					$custom_content .= '<div class="small-6 medium-2 columns">';
					$custom_content .= '<button title="Close" class="lightbox-close"><span>x</span>Close</button>';
					$custom_content .= '</div>';
					$custom_content .= '</div></div>';
					$custom_content .= '<div class="row full-width-row no-padding">';
					$custom_content .= '<div class="small-12 medium-9 columns image text-center">';
					$custom_content .= wp_get_attachment_image( $photo_id, 'full' );
					$custom_content .= '<a href="#" class="arrow prev"><i class="fa fa-angle-left"></i></a>';
					$custom_content .= '<a href="#" class="arrow next"><i class="fa fa-angle-right"></i></a>';
					$custom_content .= '</div>';
					$custom_content .= '<div class="small-12 medium-3 columns image-text">';
					$custom_content .= '<aside class="meta">';
					$custom_content .= '<a href="#" class="arrow prev"><i class="fa fa-angle-left"></i></a>';
					$custom_content .= '<a href="#" class="arrow next"><i class="fa fa-angle-right"></i></a>';									
					$custom_content .= '<span><em>'.esc_attr($i) .'</em> '.' of '. esc_attr(special_image_gallery_count_images()).'</span>';
					$custom_content .= '</aside>';
					$custom_content .= '<h5>'.$post->post_title.'</h5>';
					if (get_post($photo_id)->post_title) {
						$custom_content .= '<h6>'.get_post($photo_id)->post_title.'</h6>';
					}
					$custom_content .= '<p>'.get_post($photo_id)->post_excerpt.'</p>';
					if (get_post($photo_id)->post_content) {
					$custom_content .= '<small>Source:'.get_post($photo_id)->post_content.'</small>';
					}
					$custom_content .= '</div>';
					$custom_content .= '</div>';
					$custom_content .= '</div>';
					$i++;
				}
			}
			$custom_content .= '</div>';
			$custom_content .= $content;
			return $custom_content;
		}
    } else {
        return $content;
    }
}
add_filter( 'the_content', 'post_special_gallery' );

/**
 * Retrieve attachment IDs
 *
 * @since 1.0
 * @return string
 */
function special_image_gallery_get_image_ids() {
	global $post;

	if( ! isset( $post->ID) )
		return;

	$attachment_ids = get_post_meta( $post->ID, '_posts_special_gallery', true );
	$attachment_ids = explode( ',', $attachment_ids );

	return array_filter( $attachment_ids );
}
?>