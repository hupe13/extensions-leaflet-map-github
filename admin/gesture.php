<?php
// init settings fuer gesture
function leafext_gesture_init(){
	add_settings_section( 'gesture_settings', 'Gesture Handling', 'leafext_gesture_help_text', 'leafext_settings_gesture' );
	add_settings_field( 'leafext_gesture_on', 'gesture_on', 'leafext_form_gesture', 'leafext_settings_gesture', 'gesture_settings' );
	register_setting( 'leafext_settings_gesture', 'leafext_gesture', 'leafext_validate_gesture' );
}
add_action('admin_init', 'leafext_gesture_init' );

function leafext_form_gesture() {
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

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_gesture($input) {
	if (isset($_POST['submit'])) {
		$input['on'] = (bool)($input['on']);
	}
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
