<?php
/*
Plugin Name: Random Featured Image
Description: On posting (or updating) a post sets the featured image randomly to one of a set of images selected or uploaded via the media library.
Plugin URI: https://millservicesmarketing.com/wordpress-plugins/random-featured-image/
Author: Mill Services
Version: 1.0.0
Author URI: http://millservicesmarketing.com/
Text Domain: random-featured-image

============================================================================================================
This software is provided "as is" and any express or implied warranties, including, but not limited to, the
implied warranties of merchantibility and fitness for a particular purpose are disclaimed. In no event shall
the copyright owner or contributors be liable for any direct, indirect, incidental, special, exemplary, or
consequential damages(including, but not limited to, procurement of substitute goods or services; loss of
use, data, or profits; or business interruption) however caused and on any theory of liability, whether in
contract, strict liability, or tort(including negligence or otherwise) arising in any way out of the use of
this software, even if advised of the possibility of such damage.
============================================================================================================
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}
define( 'RANDOM_FEATURED_IMAGE_VERSION', '1.0.0' );
define( 'RANDOM_FEATURED_IMAGE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// wp_doing_cron() introduced in WP4.8
if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
	require_once( RANDOM_FEATURED_IMAGE_PLUGIN_DIR . 'class-random-featured-image.php' );
	add_action( 'init', array( 'Random_Featured_Image', 'init' ) );
}
if ( is_admin() ) {
	require_once( RANDOM_FEATURED_IMAGE_PLUGIN_DIR . 'class-random-featured-image-admin.php' );
	add_action( 'admin_menu', array( 'Random_Featured_Image_Admin', 'options_page' ) );
	add_action( 'admin_init', array( 'Random_Featured_Image_Admin', 'settings_init') );
	add_action( 'admin_enqueue_scripts', array( 'Random_Featured_Image_Admin', 'load_resources' ) );
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( 'Random_Featured_Image_Admin', 'msmrfi_action_links' ) );
}
