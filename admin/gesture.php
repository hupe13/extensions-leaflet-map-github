<?php
/**
* Admin for Leaflet.gesture
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

// init settings fuer gesture
function leafext_gesture_init(){
	add_settings_section( 'gesture_settings', __('Gesture Handling','extensions-leaflet-map'), 'leafext_gesture_help_text', 'leafext_settings_gesture' );
	//	add_settings_field( 'leafext_gesture_on', 'gesture_on', 'leafext_form_gesture', 'leafext_settings_gesture', 'gesture_settings' );
	$fields = leafext_gesture_params();
	foreach($fields as $field) {
		add_settings_field("leafext_gesture[".$field['param']."]", $field['shortdesc'], 'leafext_form_gesture','leafext_settings_gesture', 'gesture_settings', $field['param']);
	}
	register_setting( 'leafext_settings_gesture', 'leafext_gesture', 'leafext_validate_gesture' );
}
add_action('admin_init', 'leafext_gesture_init' );

// Baue Abfrage der Params
function leafext_form_gesture($field) {
	$options = leafext_gesture_params();
	$option = leafext_array_find2($field, $options);
	$settings = leafext_gesture_settings();
	$setting = $settings[$field];
	if ( $option['desc'] != "" ) echo '<p>'.$option['desc'].'</p>';
	//echo __("You can change it for each map with", "extensions-leaflet-map").' <code>'.$option['param']. '</code><br>';

	if (!current_user_can('manage_options')) {
		$disabled = " disabled ";
	} else {
		$disabled = "";
	}

	if (!is_array($option['values'])) {

		if ($setting != $option['default'] ) {
			//var_dump($setting,$option['default']);
			echo __("Plugins Default", "extensions-leaflet-map").': ';
			echo $option['default'] ? "true" : "false";
			echo '<br>';
		}
		echo '<input '.$disabled.' type="radio" name="leafext_gesture['.$option['param'].']" value="1" ';
		echo $setting ? 'checked' : '' ;
		echo '> true &nbsp;&nbsp; ';
		echo '<input '.$disabled.' type="radio" name="leafext_gesture['.$option['param'].']" value="0" ';
		echo (!$setting) ? 'checked' : '' ;
		echo '> false ';
	} else {
		$plugindefault = is_string($option['default']) ? $option['default'] : ($option['default'] ? "1" : "0");
		$setting = is_string($setting) ? $setting : ($setting ? "1" : "0");
		if ($setting != $plugindefault ) {
			//var_dump("Option: ",$option['default'],"Plugindefault: ",$plugindefault,"Setting: ",$setting);
			echo __("Plugins Default:", "extensions-leaflet-map").' '. $plugindefault . '<br>';
		}
		echo '<select '.$disabled.' name="leafext_gesture['.$option['param'].']">';
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
		$input['leafext_gesture_on'] = (bool)($input['leafext_gesture_on']);
	}
	// var_dump($input);
	return $input;
}

// Erklaerung
function leafext_gesture_help_text() {
	$text = "";
	if (!(is_singular() || is_archive() )) {
		$text = $text . '<img src="'.LEAFEXT_PLUGIN_PICTS.'gesture.png">';
	}
	$text = $text . '<p>'
	.__('Brings the basic functionality of Gesture Handling into Leaflet Map. Prevents users from getting trapped on the map when scrolling a long page.','extensions-leaflet-map');
	$text = $text . '</p><ul>';

	if ( current_user_can('manage_options') || (is_singular() || is_archive()) ) {
		$text = $text . '<li> '.__('You can enable it for all maps or for individual maps.','extensions-leaflet-map');
	} else {
		$text = $text . '<li> ';
		$settings = leafext_gesture_settings();
		//var_dump($settings);
		if ($settings['leafext_gesture_on']) {
			$text = $text . __('It is enabled for all maps.','extensions-leaflet-map');
		} else {
			$text = $text . __('It is disabled by default. You can enable it for a map with','extensions-leaflet-map').' <code>[gestures]</code>.';
		}
	}
	$text = $text . '</li>'.'<li> '.__('When Gesture Handling is enabled:','extensions-leaflet-map');
	$text = $text . '<ul><p>';
	$text = $text . '<li> '.
	sprintf(__('If Scroll Wheel Zoom (%s) is enabled, it becomes active on both desktop and touch devices.','extensions-leaflet-map'),
	'<code>scrollwheel</code>').'</li>';
	$text = $text . '<li> '.
	sprintf(__('If %s is enabled, it becomes active on touch devices only.','extensions-leaflet-map'),
	'<code>dragging</code>').'</li>';
	if (!(is_singular() || is_archive())) {
		$text = $text . '<li> '.
		sprintf(__('Your %s setting for','extensions-leaflet-map'),
		'<a href="'.get_admin_url().'admin.php?page=leaflet-map">Leaflet Map</a>').
		' '.__('Scroll Wheel Zoom', 'leaflet-map').' (<code>scrollwheel</code>) '.
		' '.__('is','extensions-leaflet-map').' ';
		$text = $text . (get_option('leaflet_scroll_wheel_zoom','0') == "1" ? "true" : "false");
		$text = $text . ', <code>dragging</code> '.__('is true at default','extensions-leaflet-map').'.</li>';
		$text = $text . '<li>'.
		__('This means for you:','extensions-leaflet-map').' ';
		$text = $text . (get_option('leaflet_scroll_wheel_zoom','0') == "1" ? __("It is enabled on both desktop and touch device by default.",'extensions-leaflet-map') : __("It is only enabled on touch devices by default.",'extensions-leaflet-map'));
		$text = $text . '</li>'.'<li> '.
		__('You can change it with','extensions-leaflet-map').' <code>[leaflet-map ';
		$text = $text . (get_option('leaflet_scroll_wheel_zoom','0') == "1" ? '!' : "");
		$text = $text . 'scrollwheel !dragging]</code>'.'</li>';
	}
	$text = $text . '</p></ul></li></ul>';
	if (!(is_singular() || is_archive() )) {
		$text = $text . '<p>'.sprintf(__('You can test it yourself on a %sseparate page%s.','extensions-leaflet-map'),
		'<a href="https://leafext.de/extra/gesture/">','</a>').'</p>';
	}
	if (is_singular() || is_archive() ) {
		return $text;
	} else {
		echo $text;
	}
}
