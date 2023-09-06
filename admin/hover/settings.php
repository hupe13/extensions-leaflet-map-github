<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_hover_init(){
	add_settings_section( 'hover_settings', '', '', 'leafext_settings_hover' );
	$fields = leafext_hover_params();
	foreach($fields as $field) {
		if ($field['changeable']) {
			add_settings_field(
				"leafext_hover[".$field['param']."]",
				$field['desc'],
				'leafext_form_hover',
				'leafext_settings_hover',
				'hover_settings',
				$field['param']
			);
		}
	}
	register_setting( 'leafext_settings_hover', 'leafext_hover', 'leafext_validate_hover' );
}
add_action('admin_init', 'leafext_hover_init' );

function leafext_form_hover($field) {
	$params = leafext_hover_params();
	$defaults = array();
	foreach($params as $param) {
		if ($param['changeable']) {
			$defaults[$param['param']] = $param['default'];
		}
	}

	$options = leafext_hover_settings();
	//var_dump($options);
	if (!current_user_can('manage_options')) {
		$disabled = " disabled ";
	} else {
		$disabled = "";
	}

	foreach ($options as $key=>$value) {
		if ($key == $field) {
			echo __("You can change it for each map with", "extensions-leaflet-map").' <code>'.$key. '</code><br>';
			if ($value != $defaults[$key] ) {
				echo __("Plugins Default", "extensions-leaflet-map").': '.$defaults[$key].'<br>';
			}
			if ($key == 'class') {
				echo '<input '.$disabled.' type="text" size=15 name="leafext_hover['.$key.']" value="'.$value.'" />';
			} else {
				echo '<input '.$disabled.' type="number" min="0" size=3 name="leafext_hover['.$key.']" value="'.$value.'" />';
			}
		}
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_hover($options) {
	if (isset($_POST['submit'])) {
		$options['class'] = sanitize_text_field( $options['class'] );
		$options['tolerance'] = (int) $options['tolerance'];
		$options['snap'] = (int) $options['snap'];
		delete_option('leafext_canvas'); //old option
		return $options;
	}
	if (isset($_POST['delete'])) {
		delete_option('leafext_hover');
		delete_option('leafext_canvas');
	}
	return false;
}

// Draw the menu page itself
function leafext_hover_admin_page (){
	if (current_user_can('manage_options')) {
		echo '<form method="post" action="options.php">';
	} else {
		echo '<form>';
	}
	settings_fields('leafext_settings_hover');
	do_settings_sections( 'leafext_settings_hover' );
	if (current_user_can('manage_options')) {
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
	}
	echo '</form>';
}
