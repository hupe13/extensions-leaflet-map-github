<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_canvas_init(){
	// Create Setting
	$section_group = 'leafext_canvas';
 	$section_name = 'leafext_canvas';
 	$validate = 'leafext_validate_canvas';
	register_setting( $section_group, $section_name, $validate );

	// Create section of Page
	$settings_section = 'leafext_canvas_main';
	$page = $section_group;
	add_settings_section(
		$settings_section,
		__('How much to extend click tolerance round a path/object on the map','extensions-leaflet-map'),
		'leafext_canvas_help',
		$page
	);

	// Add fields to that section
	add_settings_field(
		$section_name,
		__('Value:','extensions-leaflet-map'),
		'leafext_canvas_form',
		$page,
		$settings_section,
	);

}
add_action( 'admin_init', 'leafext_canvas_init' );

function leafext_canvas_form() {
  $options = get_option( 'leafext_canvas' );
  $tolerance = ( is_array ($options) && $options['tolerance'] != "" )  ? $options['tolerance'] : "0";
	echo '<input type="number"
       min="0" max="20" size=3 name="leafext_canvas[tolerance]" value="'.$tolerance.'" /> (0 - 20)';
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_canvas($options) {
	if (isset($_POST['submit'])) {
    $options['tolerance'] = (int) $options['tolerance'];
		return $options;
	}
	if (isset($_POST['delete'])) delete_option('leafext_canvas');
	return false;
}

// Erklaerung / Hilfe
function leafext_canvas_help() {
	$text = 'Klick Tolerance';
}

// Draw the menu page itself
function leafext_canvas_do_page (){
	echo '<form method="post" action="options.php">';
	settings_fields('leafext_canvas');
	do_settings_sections( 'leafext_canvas' );
	submit_button();
	submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
	echo '</form>';
}
