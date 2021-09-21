<?php
/**
 * Admin for Leaflet.elevation
 * extensions-leaflet-map
 */
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

	if ( $option[0] == "preferCanvas") echo '<p>'.sprintf ( __('Due to a bug in MacOS and iOS, see %shere%s, you should it set to false.',"extensions-leaflet-map"), '<a href="https://github.com/Raruto/leaflet-elevation/issues/123">','</a>').'</p>';

	echo __("You can change it for each map with", "extensions-leaflet-map").' <code>'.$option[0]. '</code><br>';
	if (!is_array($option[3])) {

		if ($setting != $option[2] ) {
			//var_dump($setting,$option[2]);
			echo __("Plugins Default", "extensions-leaflet-map").': ';
			echo $option[2] ? "true" : "false";
			echo '<br>';
		}

		echo '<input type="radio" name="leafext_eleparams['.$option[0].']" value="1" ';
		echo $setting ? 'checked' : '' ;
		echo '> true &nbsp;&nbsp; ';
		echo '<input type="radio" name="leafext_eleparams['.$option[0].']" value="0" ';
		echo (!$setting) ? 'checked' : '' ;
		echo '> false ';
	} else {
		$plugindefault = is_string($option[2]) ? $option[2] : ($option[2] ? "1" : "0");
		$setting = is_string($setting) ? $setting : ($setting ? "1" : "0");
		if ($setting != $plugindefault ) {
			//var_dump("Option: ",$option[2],"Plugindefault: ",$plugindefault,"Setting: ",$setting);
			echo __("Plugins Default:", "extensions-leaflet-map").' '. $plugindefault . '<br>';
		}
		echo '<select name="leafext_eleparams['.$option[0].']">';
		foreach ( $option[3] as $para) {
			echo '<option ';
			if (is_bool($para)) $para = ($para ? "1" : "0");
			if ($para === $setting) echo ' selected="selected" ';
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
	echo '<img src="'.LEAFEXT_PLUGIN_PICTS.'elevation.png">
	<h2>'.__('Note','extensions-leaflet-map').'</h2>';
	echo sprintf(
				__(
				'%s and %s are different. If you want to display a GPX track only, use %s. If you want to display a GPX track with an elevation profile use %s.',"extensions-leaflet-map"),
				"<code>[leaflet-gpx]</code>",
				"<code>[elevation]</code>",
				"<code>[leaflet-gpx]</code>",
				"<code>[elevation]</code>");
	echo '<h2>Shortcode</h2>
	<pre><code>[leaflet-map ....]
[elevation gpx="url_gpx_file" option1=value1 option2 !option3 ...]</code></pre>
'.
__('You can set a marker on Start, but this is not necessary any more.',"extensions-leaflet-map").'
<pre><code>[leaflet-map ....]
[leaflet-marker lat=... lng=... ...]Start[/leaflet-marker]
[elevation gpx="url_gpx_file" option1=value1 option2 !option3 ...]</code></pre>
	<h3>Options</h3>
	<p>';
	echo __('For boolean values applies', "extensions-leaflet-map").':<br>';
	echo '<code>false</code> = <code>!parameter</code> || <code>parameter="0"</code> || <code>parameter=0</code></br>';
	echo '<code>true</code> = <code>parameter</code> || <code>parameter="1"</code> || <code>parameter=1</code>';
	echo '</p>';

	$theme = get_option('leafext_values');
	if (is_array($theme)) {
		echo '<p>';
		if ($theme['theme'] == "other") {
			echo sprintf(__("You have installed your own theme. The settings for %s on this page are not valid for you.","extensions-leaflet-map"),"<code>theme</code>");
		} else {
			echo '<span style="color: #d63638">';
			echo __('Your theme is','extensions-leaflet-map').' '.$theme['theme'].'. ';
			echo sprintf(
				__(
				'Please delete %s these %stheme%s settings, these are valid as long as they are not deleted.',
				"extensions-leaflet-map"),
				'<a href="admin.php?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevationtheme">',
				'<code>',
				'</code></a>');
			echo '</span>';
		}
		echo '</p>';
	}
}
