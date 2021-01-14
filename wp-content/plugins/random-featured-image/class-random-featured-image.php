<?php
class Random_Featured_Image {

	public static function init() {
		// https://codex.wordpress.org/Plugin_API/Action_Reference
 		add_action( 'publish_post', array( 'Random_Featured_Image', 'check_random_featured_image' ) );
 		add_action( 'post_updated', array( 'Random_Featured_Image', 'check_random_featured_image' ) );
		
	}

	public static function check_random_featured_image( $post_id ) {
		// check if a featured image exists
		$featured_image_id = get_post_meta( $post_id, $key = '_thumbnail_id', $single = true );
	
		if ( !wp_is_post_revision( $post_id ) ) {
			if ( empty( $featured_image_id ) ) {
				// no featured - need to set a random one
				$random_featured_id = self::create_random_featured_id();
				update_post_meta( $post_id, $meta_key = '_thumbnail_id', $meta_value = $random_featured_id );
			}
		}
		
	}

	private static function create_random_featured_id() {
		$random_featured_id = 0;
		
		// get options setting for images to use (array of image ID's)
		$options = get_option( 'msmrfi_options' );
		// split id string to array
		$imageids = explode(",", $options['msmrfi_field_imageid']);

		// randomly pick one
		$random_featured_id = $imageids[array_rand($imageids, 1)];
		
		return $random_featured_id;
	}
	
}