<?php
class Random_Featured_Image_Admin {

	public static function options_page() {
		add_options_page(
				esc_html( 'Random Featured Image Options', 'random-featured-image' ),
				esc_html( 'Random Featured Image', 'random-featured-image' ),
				'manage_options',
				'msmrfi',
				array( 'Random_Featured_Image_Admin', 'options_page_callback')
				);
	}

	public static function options_page_callback() {
		
		if (!current_user_can('manage_options')) {
			return;
		}

		$options = get_option( 'msmrfi_options' );
		wp_enqueue_media();

		?>
		<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
		<?php
		// output security fields for the registered setting
		settings_fields( 'msmrfi' );
		do_settings_sections( 'msmrfi' );
		?>
		<div id="selected_images" style="display: -webkit-flex; display: flex; flex-wrap: wrap; margin: 20px 0; overflow: hidden;">
		<?php
		// output existing images
		
		// split id string to array
		if ( isset( $options['msmrfi_field_imageid'] ) && !empty( $options['msmrfi_field_imageid'] ) ) {
			$imageids = explode(",", $options['msmrfi_field_imageid']);
			// loop array and output images
			foreach ($imageids as $id) {
				$url = wp_get_attachment_image_url($id);
				?>
				<div class="msmimage" style="margin: 10px;"><img style="width:100px;" src="<?php echo $url; ?>"><br><a id="<?php echo $id; ?>" href="#remove"><?php esc_html_e( 'Remove', 'random-featured-image' ); ?></a></div>			
				<?php
			}
		}
		?>
		</div>
		<div style="clear:both;">
		<input id="upload_image_button" type="button" class="button" value="<?php esc_html_e( 'Upload/Select Images', 'random-featured-image' ); ?>">
		<?php
		submit_button( esc_html( 'Save Settings', 'random-featured-image') );
		?>
		</div>
		</form>
		</div>
		<?php
		
	}
	
	public static function settings_init() {

		register_setting( 
			'msmrfi',
			'msmrfi_options',
			array(
				'sanitize_callback' => array( 'Random_Featured_Image_Admin', 'sanitize' ) 
				)
			);
		
		add_settings_section(
			'msmrfi_section_images',
			esc_html( 'Images To Use', 'random-featured-image' ),
			array( 'Random_Featured_Image_Admin', 'msmrfi_section_images_cb' ),
			'msmrfi'
			);
		
		add_settings_field(
			'msmrfi_field_imageid',
			'Image IDs',
			array( 'Random_Featured_Image_Admin', 'msmrfi_field_imageid_cb' ),
			'msmrfi',
			'msmrfi_section_images',
			[
				'class' => 'hidden'
			]
		);
		
		
	}
	
	public static function msmrfi_section_images_cb( $args ) {

		echo '<p>' . esc_html( 'Set up the pool of random images to use.', 'random-featured-image' ) . '</p>';
		echo '<p>' . esc_html( 'TIP: You CAN add images more than once. The more times you add an image the greater the chance of it being randomly chosen.', 'random-featured-image' ) . '</p>';

	}
	
	public static function msmrfi_field_imageid_cb( $args ) {
		$options = get_option( 'msmrfi_options' );
		$output = '';
		if ( isset( $options['msmrfi_field_imageid'] ) && !empty( $options['msmrfi_field_imageid'] ) ) {
			$output = $options['msmrfi_field_imageid'];
		}
		?>
		<input type='text' id="msmrfi_field_imageid" name='msmrfi_options[msmrfi_field_imageid]' value='<?php echo $output; ?>'>
		<?php
		}

	public static function load_resources($hook) {
		if($hook != 'settings_page_msmrfi') {
			return;
		}
		wp_register_script( 'msmrfi.js', plugin_dir_url( __FILE__ ) . 'includes/msmrfi.js', array(), RANDOM_FEATURED_IMAGE_VERSION);
		// internationalise
		$translation_array = array(
			'remove_link' => __( 'Remove', 'random-featured-image' )
			);
		wp_localize_script( 'msmrfi.js', 'msmrfi_text', $translation_array );
		wp_enqueue_script( 'msmrfi.js');
	}
	
	public static function msmrfi_action_links( $links ) {
		$links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=msmrfi') ) .'">' . esc_html( 'Settings', 'random-featured-image' ) . '</a>';
		return $links;
	}
	
	public function sanitize( $input ) {
		$new_input = array();
		if( isset( $input['msmrfi_field_imageid'] ) ) {
			echo "sanitizing imageid field";
			$new_input['msmrfi_field_imageid'] = sanitize_text_field( $input['msmrfi_field_imageid'] );
		}
		return $new_input;
	}

}