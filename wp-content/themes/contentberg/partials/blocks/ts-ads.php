<?php
/**
 * Call Advertisement Block for Visual Composer
 */

if (!empty($code)) {
	$atts['ad_code'] = Bunyad::get('cb-vc')->textarea_decode($code);
}

$type = 'ContentBerg_Widgets_Ads';
$args = array();

?>

<div class="block">
	<?php the_widget($type, $atts, $args); ?>
</div>

