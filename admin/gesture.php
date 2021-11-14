<?php
/**
 * Admin for Leaflet.gesture
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

// init settings fuer gesture
function leafext_gesture_init(){
	add_settings_section( 'gesture_settings', 'Gesture Handling', 'leafext_gesture_help_text', 'leafext_settings_gesture' );
//	add_settings_field( 'leafext_gesture_on', 'gesture_on', 'leafext_form_gesture', 'leafext_settings_gesture', 'gesture_settings' );
	$fields = leafext_gesture_params();
	foreach($fields as $field) {
		add_settings_field("leafext_gesture[".$field['param']."]", $field['shortdesc'], 'leafext_form_gesture','leafext_settings_gesture', 'gesture_settings', $field['param']);
	}
	register_setting( 'leafext_settings_gesture', 'leafext_gesture', 'leafext_validate_gesture' );
}
add_action('admin_init', 'leafext_gesture_init' );

function leafext_form_gesture1() {
	//boolean
	$defaults = array ('on' => true );
	$options = shortcode_atts($defaults, get_option('leafext_gesture') );
	echo '<p>'.
	__("If it is true, it is valid for any map and you can't change it. If it is false, you can change it for each map:",'extensions-leaflet-map').'</p>';
	echo '<pre><code>[gestures]</code></pre>';
	echo '<input type="radio" name="leafext_gesture[on]" value="1" ';
	echo $options['on'] ? 'checked' : '' ;
	echo '> true &nbsp;&nbsp; ';
	echo '<input type="radio" name="leafext_gesture[on]" value="0" ';
	echo (!$options['on']) ? 'checked' : '' ;
	echo '> false ';
}

// Baue Abfrage der Params
function leafext_form_gesture($field) {
	$options = leafext_gesture_params();
	$option = leafext_array_find2($field, $options);
	$settings = leafext_gesture_settings();
	$setting = $settings[$field];
	if ( $option['desc'] != "" ) echo '<p>'.$option['desc'].'</p>';
	echo __("You can change it for each map with", "extensions-leaflet-map").' <code>'.$option['param']. '</code><br>';
	
	if (!is_array($option['values'])) {

		if ($setting != $option['default'] ) {
			//var_dump($setting,$option['default']);
			echo __("Plugins Default", "extensions-leaflet-map").': ';
			echo $option['default'] ? "true" : "false";
			echo '<br>';
		}

		echo '<input type="radio" name="leafext_gesture['.$option['param'].']" value="1" ';
		echo $setting ? 'checked' : '' ;
		echo '> true &nbsp;&nbsp; ';
		echo '<input type="radio" name="leafext_gesture['.$option['param'].']" value="0" ';
		echo (!$setting) ? 'checked' : '' ;
		echo '> false ';
	} else {
		$plugindefault = is_string($option['default']) ? $option['default'] : ($option['default'] ? "1" : "0");
		$setting = is_string($setting) ? $setting : ($setting ? "1" : "0");
		if ($setting != $plugindefault ) {
			//var_dump("Option: ",$option['default'],"Plugindefault: ",$plugindefault,"Setting: ",$setting);
			echo __("Plugins Default:", "extensions-leaflet-map").' '. $plugindefault . '<br>';
		}
		echo '<select name="leafext_gesture['.$option['param'].']">';
		foreach ( $option['values'] as $para) {
			echo '<option ';
			if (is_bool($para)) $para = ($para ? "1" : "0");
			if ($para === $setting) echo ' selected="selected" ';
			echo 'value="'.$para.'" >'.$para.'</option>';
		}
		echo '</select>';
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_gesture($input) {
	//var_dump($_POST,$input);
	if (isset($_POST['submit'])) {
		$input['on'] = (bool)($input['on']);
	}
	// var_dump($input);
	return $input;
}

// Erklaerung
function leafext_gesture_help_text() {
	echo '<img src="'.LEAFEXT_PLUGIN_PICTS.'gesture.png">
	<p>'.__('Brings the basic functionality of Gesture Handling into Leaflet Map.
	Prevents users from getting trapped on the map when scrolling a long page.
	You can enable it for all maps or for particular maps. It becomes active
	only when dragging or scrollWheelZoom is enabled.','extensions-leaflet-map').'</p>';
}
