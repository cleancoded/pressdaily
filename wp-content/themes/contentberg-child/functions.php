<?php
/**
 * ContentBerg Child Theme functions.php
 *
 * Please refer to contentberg/functions.php about framework setup.
 */

/**
 * Enqueue the CSS. Please note the CSS order is as follows:
 *
 *  - contentberg/style.css
 *  - contentberg/css/skin-XYZ.css
 *  - contentberg-child/style.css
 *  - Inline Custom CSS from Customize
 */
function my_ts_enqueue_parent() {

	wp_enqueue_style(
		'contentberg-core', 
		get_template_directory_uri() . '/style.css', 
		array(), 
		Bunyad::options()->get_config('theme_version')
	);
}

function my_ts_enqueue_child() {

	wp_enqueue_style(
		'contentberg-child', 
		get_stylesheet_uri(),
		Bunyad::options()->get_config('theme_version')
	);
}

// Enqueue parent CSS at priority 9 as skin and other CSS generates at priority 10
add_action('wp_enqueue_scripts', 'my_ts_enqueue_parent', 9);

// Change 11 to 100 to make it enqueue AFTER Custom CSS from Customize
add_action('wp_enqueue_scripts', 'my_ts_enqueue_child', 11);

// Disable parent CSS enqueue
add_filter('bunyad_enqueue_core_css', '__return_false');