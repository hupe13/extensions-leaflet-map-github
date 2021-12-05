<?php
/**
 * Admin for multielevation
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_multieleparams_init(){
	add_settings_section( 'multieleparams_settings', leafext_elevation_tab(), 'leafext_multiele_help_text', 'leafext_settings_multieleparams' );
	$fields = leafext_multielevation_params();
	foreach($fields as $field) {
		$trenn = "";
		if ( isset ($field['next']) ) $trenn = '<div style="border-top: '.$field['next'].'px solid #646970"></div>';
		add_settings_field("leafext_multieleparams[".$field['param']."]", $trenn.$field['shortdesc'], 'leafext_form_multielevation','leafext_settings_multieleparams', 'multieleparams_settings', $field['param']);
	}
	register_setting( 'leafext_settings_multieleparams', 'leafext_multieleparams', 'leafext_validate_multiele_options' );
}
add_action('admin_init', 'leafext_multieleparams_init' );

// Baue Abfrage der Params
function leafext_form_multielevation($field) {
	//var_dump($field);
	$options = leafext_multielevation_params();
	//var_dump($options);
	$option = leafext_array_find2($field, $options);
	//var_dump($option);echo '<br>';
	$settings = leafext_multielevation_settings();
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

		echo '<input type="radio" name="leafext_multieleparams['.$option['param'].']" value="1" ';
		echo $setting ? 'checked' : '' ;
		echo '> true &nbsp;&nbsp; ';
		echo '<input type="radio" name="leafext_multieleparams['.$option['param'].']" value="0" ';
		echo (!$setting) ? 'checked' : '' ;
		echo '> false ';
	} else {
		$plugindefault = is_string($option['default']) ? $option['default'] : ($option['default'] ? "1" : "0");
		$setting = is_string($setting) ? $setting : ($setting ? "1" : "0");
		if ($setting != $plugindefault ) {
			//var_dump("Option: ",$option['default'],"Plugindefault: ",$plugindefault,"Setting: ",$setting);
			echo __("Plugins Default:", "extensions-leaflet-map").' '. $plugindefault . '<br>';
		}
		echo '<select name="leafext_multieleparams['.$option['param'].']">';
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
function leafext_validate_multiele_options($options) {
	if (isset($_POST['submit'])) return $options;
	if (isset($_POST['delete'])) delete_option('leafext_multieleparams');
	return false;
}

// Helptext
function leafext_multiele_help_text () {
	$text='
	<img src="'.LEAFEXT_PLUGIN_PICTS.'multielevation.png">
	<h2>Shortcode</h2>
	<pre>
<code>[leaflet-map fitbounds ...]
[elevation-<span style="color: #d63638">track</span> file="..." lat="..." lng="..." name="..."]
//many of this
[elevation-<span style="color: #d63638">track</span> file="..." lat="..." lng="..." name="..."]
[elevation-<span style="color: #d63638">tracks</span> summary=... filename=...]
</code></pre>
	'.__('Parameter <code>lat</code>, <code>lng</code> and <code>name</code> are optional.','extensions-leaflet-map').' '.
	__('If <code>summary</code> is false, you can use any option from <code>elevation</code>.','extensions-leaflet-map').
	'<h2>Theme</h2>'.
	__('The theme is the same as the','extensions-leaflet-map').' <a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevation">'.__('Elevation Theme','extensions-leaflet-map').'</a>.
	<h3>Options</h3>
	<p>'.
	__('For boolean values applies', "extensions-leaflet-map").':<br>'.
	'<code>false</code> = <code>!parameter</code> || <code>parameter="0"</code> || <code>parameter=0</code></br>'.
	'<code>true</code> = <code>parameter</code> || <code>parameter="1"</code> || <code>parameter=1</code>'.
	'</p>';
	echo $text;
}
