<?php
/*
Plugin Name: Posts Special Gallery
Description: An easy to use image gallery with drag & drop re-ordering
Version: 1.1.4
Author: Rooprai
Text Domain: posts-special-gallery
License: GPL-2.0+
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Posts_Special_Gallery' ) ) {

	/**
	 * PHP5 constructor method.
	 *
	 * @since 1.0
	*/
	class Posts_Special_Gallery {

		public function __construct() {			
			add_action( 'plugins_loaded', array( $this, 'constants' ));
			add_action( 'plugins_loaded', array( $this, 'includes') );
		}
		
		public function constants() {

			if ( !defined( 'SPECIAL_IMAGE_GALLERY_DIR' ) )
				define( 'SPECIAL_IMAGE_GALLERY_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

			if ( !defined( 'SPECIAL_IMAGE_GALLERY_URL' ) )
			    define( 'SPECIAL_IMAGE_GALLERY_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

			if ( ! defined( 'SPECIAL_IMAGE_GALLERY_VERSION' ) )
			    define( 'SPECIAL_IMAGE_GALLERY_VERSION', '1.1.4' );

			if ( ! defined( 'SPECIAL_IMAGE_GALLERY_INCLUDES' ) )
			    define( 'SPECIAL_IMAGE_GALLERY_INCLUDES', SPECIAL_IMAGE_GALLERY_DIR . trailingslashit( 'includes' ) );

		}
		/**
		* Loads the initial files needed by the plugin.
		*
		* @since 1.0
		*/
		public function includes() {
			require_once( SPECIAL_IMAGE_GALLERY_INCLUDES . 'template-functions.php' );
			require_once( SPECIAL_IMAGE_GALLERY_INCLUDES . 'metabox.php' );
			require_once( SPECIAL_IMAGE_GALLERY_INCLUDES . 'scripts.php' );
		}

	}
}

$posts_special_gallery = new Posts_Special_Gallery();
