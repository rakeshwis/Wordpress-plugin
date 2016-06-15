<?php
// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );

/**
 * Register style sheet.
 */
function register_plugin_styles() {
	//JS
	wp_register_script( 'magnific-popup', plugins_url( 'posts-special-gallery/includes/js/jquery.magnific-popup.js' ));
	//CSS
	wp_register_style( 'posts-special-gallery', plugins_url( 'posts-special-gallery/includes/css/special-image-gallery.css' ) );
	wp_enqueue_style( 'posts-special-gallery' );
	wp_enqueue_script( 'magnific-popup' );
}

/**
 * JS
 *
 * @since 1.0
 */
function special_image_gallery_js() {
	?>
	
	<script>
	  jQuery(document).ready(function() {
		jQuery('a.spl-gallery-link').each(function() {
					var _this = jQuery(this),
						eclass = (jQuery(this).data('class') ? jQuery(this).data('class') : ''),
						items = [],
						target = jQuery( _this.attr('href') );
						target.find('.special-gallery-content').each(function() {
							items.push({
								src: jQuery(this) 
							});
						});
					
					_this.on('click', function() {
						jQuery.magnificPopup.open({
							midClick: true,
							mainClass: 'mfp ' + eclass,
							alignTop: true,
							closeBtnInside: true,
							items: items,
							gallery: {
								enabled: true
							},
							closeMarkup: '<button title="%title%" class="mfp-close"></button>',
							callbacks: {
								open: function() {
									jQuery(".lightbox-close").on('click',function(){
										jQuery.magnificPopup.instance.close();
										return false;           
									});
									
									jQuery(".arrow.prev").on('click',function(){
										jQuery.magnificPopup.instance.prev();
										return false;           
									});
									
									jQuery(".arrow.next").on('click',function(){
										jQuery.magnificPopup.instance.next();
										return false;
									});
								}
							}
						});
						return false;
					});
					
				});
	  });
	
	</script>
<?php }
add_action( 'wp_footer', 'special_image_gallery_js', 20 );
