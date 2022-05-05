<?php
/**
 * Admin for Leaflet.elevation
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_eleparams_init(){
	add_settings_section( 'eleparams_settings', leafext_elevation_tab(), 'leafext_ele_help_text', 'leafext_settings_eleparams' );
	$fields = leafext_elevation_params(array("changeable"));
	$ownoptions = get_option('leafext_values');
	if (is_array($ownoptions)) {
		foreach ($fields as $key => $value) {
			if ($value['param'] == 'theme') {
				unset($fields[$key]);
			}
		}
	}
	foreach($fields as $field) {
		$trenn = "";
		if ( isset ($field['next']) ) $trenn = '<div style="border-top: '.$field['next'].'px solid #646970"></div>';
		add_settings_field("leafext_eleparams[".$field['param']."]", $trenn.$field['shortdesc'], 'leafext_form_elevation','leafext_settings_eleparams', 'eleparams_settings', $field['param']);
	}
	register_setting( 'leafext_settings_eleparams', 'leafext_eleparams', 'leafext_validate_ele_options' );
}
add_action('admin_init', 'leafext_eleparams_init' );

// Baue Abfrage der Params
function leafext_form_elevation($field) {
	//var_dump($field);
	$options = leafext_elevation_params(array("changeable"));
	//var_dump($options);
	$option = leafext_array_find2($field, $options);
	//var_dump($option);echo '<br>';
	$settings = leafext_elevation_settings(array("changeable"));
	$setting = $settings[$field];
	if ( isset ($option['next']) ) echo '<div style="border-top: '.$option['next'].'px solid #646970"></div>';
	if ( $option['desc'] != "" ) echo '<p>'.$option['desc'].'</p>';

	echo __("You can change it for each map with", "extensions-leaflet-map").' <code>'.$option['param']. '</code><br>';
	if (!is_array($option['values'])) {

		if ($setting != $option['default'] ) {
			//var_dump($setting,$option['default']);
			echo __("Plugins Default", "extensions-leaflet-map").': ';
			echo $option['default'] ? "true" : "false";
			echo '<br>';
		}

		echo '<input type="radio" name="leafext_eleparams['.$option['param'].']" value="1" ';
		echo $setting ? 'checked' : '' ;
		echo '> true &nbsp;&nbsp; ';
		echo '<input type="radio" name="leafext_eleparams['.$option['param'].']" value="0" ';
		echo (!$setting) ? 'checked' : '' ;
		echo '> false ';
	} else {
		$plugindefault = is_string($option['default']) ? $option['default'] : ($option['default'] ? "1" : "0");
		$setting = is_string($setting) ? $setting : ($setting ? "1" : "0");
		if ($setting != $plugindefault ) {
			//var_dump("Option: ",$option['default'],"Plugindefault: ",$plugindefault,"Setting: ",$setting);
			echo __("Plugins Default:", "extensions-leaflet-map").' '. $plugindefault . '<br>';
		}
		echo '<select name="leafext_eleparams['.$option['param'].']">';
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
function leafext_validate_ele_options($options) {
	if (isset($_POST['submit'])) {
		$defaults=array();
		$params = leafext_elevation_params(array('changeable'));
		foreach($params as $param) {
			$defaults[$param['param']] = $param['default'];
		}
		$params = get_option('leafext_eleparams', $defaults);
		foreach ($options as $key => $value) {
			$params[$key] = $value;
		}
		return $params;
	}
	if (isset($_POST['delete'])) delete_option('leafext_eleparams');
	return false;
}

// Helptext
function leafext_ele_help_text () {
	leafext_enqueue_elevation_css ();
	leafext_enqueue_awesome();
	$text = "";
	$text = $text.'<p><img src="'.LEAFEXT_PLUGIN_PICTS.'elevation.png"></p>
	<h2>'.__('Note','extensions-leaflet-map').'</h2>';
	$text = $text.sprintf(
				__(
				'If you want to display a track only, use %s functions. If you want to display a track with an elevation profile use %s.',"extensions-leaflet-map"),
				"<code>[leaflet-...]</code>",
				"<code>[elevation]</code>");
	$text = $text." ";
	$text = $text.sprintf(
				__(
				'The %s parameter is called %s, but it works with gpx, kml, geojson and tcx files.',"extensions-leaflet-map"),
				"<code>[elevation]</code>",
				"<code>gpx</code>");
	$text = $text."<p>";
	$text = $text.__('The elevation shortcode has many configuration options. Some things are not trivial. If you can\'t configure something, ask in the forum.',"extensions-leaflet-map");
	$text = $text."</p>";
	$text = $text.'<h2>Shortcode</h2>
	<pre><code>[leaflet-map ....]
[elevation gpx="url_gpx_file" option1=value1 option2 !option3 ...]</code></pre>
'.
__('You can optionally set a marker on Start.',"extensions-leaflet-map").'
<pre><code>[leaflet-map ....]
[leaflet-marker lat=... lng=... ...]Start[/leaflet-marker]
[elevation gpx="url_gpx_file" option1=value1 option2 !option3 ...]</code></pre>
	<h3>Options</h3>
	<p>';
	$text = $text.__('For boolean values applies', "extensions-leaflet-map").':<br>';
	$text = $text.'<code>false</code> = <code>!parameter</code> || <code>parameter="0"</code> || <code>parameter=0</code></br>';
	$text = $text.'<code>true</code> = <code>parameter</code> || <code>parameter="1"</code> || <code>parameter=1</code>';
	$text = $text.'</p>';

	if (!(is_singular()|| is_archive())) {
		$theme = get_option('leafext_values');
		if (is_array($theme)) {
			$text = $text.'<p>';
			$text = $text.'<span style="color: #d63638">';
			if ($theme['theme'] == "other") {
				$text = $text.sprintf(__("You have installed your own %s.","extensions-leaflet-map"),
				'<a href="admin.php?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevationtheme"><code>theme</code></a>');
			} else {
				$text = $text.__('Your theme is','extensions-leaflet-map').' '.$theme['theme'].'. ';
				$text = $text.sprintf(
					__(
						'Please delete %s these %stheme%s settings, these are valid as long as they are not deleted.',
						"extensions-leaflet-map"),
						'<a href="admin.php?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevationtheme">',
						'<code>',
						'</code></a>');
			}
			$text = $text.'</span>';
			$text = $text.'</p>';
		}
	}
	if (is_singular() || is_archive() ) {
		return $text;
	} else {
		echo $text;
		echo '<div style="border-top: 3px solid #646970"></div>';
	}
}
