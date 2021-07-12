<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_eleparams_init(){
	add_settings_section( 'eleparams_settings', leafext_elevation_tab(), 'leafext_ele_help_text', 'leafext_settings_eleparams' );
	$fields = leafext_elevation_params();
	foreach($fields as $field) {
		add_settings_field("leafext_eleparams[".$field[0]."]", $field[1], 'leafext_form_elevation','leafext_settings_eleparams', 'eleparams_settings', $field[0]);
	}
	register_setting( 'leafext_settings_eleparams', 'leafext_eleparams', 'leafext_validate_ele_options' );
}
add_action('admin_init', 'leafext_eleparams_init' );

// Baue Abfrage der Params
function leafext_form_elevation($field) {
	//var_dump($field);
	$options = leafext_elevation_params();
	//var_dump($options);
	$option = leafext_array_find($field, $options);
	//var_dump($option);echo '<br>';
	$settings = leafext_elevation_settings();
	$setting = $settings[$field];

	if ($setting != $option[2] ) {
		echo __("Plugins Default:", "extensions-leaflet-map").' '.$option[2]. '<br>';
	}
	echo __("You can change it for every map with", "extensions-leaflet-map").' <code>'.$option[0]. '</code></br>';
	if (!is_array($option[3])) {
		echo '<input type="radio" name="leafext_eleparams['.$option[0].']" value="1" ';
		echo $setting ? 'checked' : '' ;
		echo '> true &nbsp;&nbsp; ';
		echo '<input type="radio" name="leafext_eleparams['.$option[0].']" value="0" ';
		echo (!$setting) ? 'checked' : '' ;
		echo '> false ';
	} else {
		echo '<select name="leafext_eleparams['.$option[0].']">';
		foreach ( $option[3] as $para) {
			echo '<option ';
			if ($para === $setting) echo ' selected="selected" ';
			if (is_bool($para)) $para = ($para ? "1" : "0");
			echo 'value="'.$para.'" >'.$para.'</option>';
		}
		echo '</select>';
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_ele_options($options) {
	if (isset($_POST['submit'])) return $options;
	if (isset($_POST['delete'])) delete_option('leafext_eleparams');
	return false;
}

// Helptext
function leafext_ele_help_text () {
	echo '<p>';
	echo __('For boolean values', "extensions-leaflet-map").':<br>';
	echo '<code>false</code> = <code>!parameter</code> || <code>parameter="0"</code> || <code>parameter=0</code></br>';
	echo '<code>true</code> = <code>parameter</code> || <code>parameter="1"</code> || <code>parameter=1</code>';
	echo '</p>';
	//var_dump(get_option('leafext_values'));
	$theme = get_option('leafext_values');
	if (is_array($theme)) {
		echo '<p>';
		if ($theme['theme'] == "other") {
			echo __("You have installed your own theme. The settings for <code>theme</code> on this page are not valid for you.","extensions-leaflet-map");
		} else {
			echo __('Your theme is','extensions-leaflet-map').' '.$theme['theme'].'. ';
			echo __('Please delete <a href="admin.php?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevationtheme">these</a> <code>theme</code> settings, these are valid as long as they are not deleted.',"extensions-leaflet-map");
		}
		echo '</p>';
	}
}
