<?php
/**
 * Admin page for elevation theme
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_elevation_colors() {
	$params = array(
		array(
			'param' => 'pace',
			'shortdesc' => "Pace",
			'desc' => "",
			'default' => "#03ffff",
		),
	);
	return $params;
}

// init settings fuer elevation colors
function leafext_elevation_color_init(){
	add_settings_section( 'elecolors_settings', leafext_elevation_tab(), 'leafext_color_help_text', 'leafext_settings_color' );
	$fields = leafext_elevation_colors();
	foreach($fields as $field) {
		add_settings_field("leafext_color[".$field['param']."]", $field['shortdesc'], 'leafext_form_colors','leafext_settings_color', 'elecolors_settings', $field['param']);
	}
	register_setting( 'leafext_settings_color', 'leafext_color', 'leafext_validate_owncolors' );
}
add_action('admin_init', 'leafext_elevation_color_init' );

// Baue Abfrage Farben
function leafext_form_colors($field) {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
	echo '<script>jQuery(document).ready(function(){jQuery(".colorPicker").wpColorPicker();});</script>';

	$options  = leafext_elevation_colors();
	$option = leafext_array_find2($field, $options);

	$owncolors = get_option('leafext_color');
	if (is_array($owncolors)) {
		$setting = $owncolors[$option['param']];
	} else {
		$setting = $option['default'];
	}
	echo '<input type="text" class="colorPicker" id="leafext_color['.$option['param'].']" name="leafext_color['.$option['param'].']"
	data-default-color = "'.$option['default'].'" value = "'.$setting.'"/>';
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_owncolors($options) {
	if (isset($_POST['submit'])) return $options;
	if (isset($_POST['delete'])) delete_option('leafext_color');
	return false;
}

// Helptext
function leafext_color_help_text () {
$text = '
<h2>Colors</h2><p>'.
__('Set preferCanvas to false!',"extensions-leaflet-map").
"</p>";
echo $text;
}
