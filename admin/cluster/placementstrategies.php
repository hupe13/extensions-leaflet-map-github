<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//
include_once LEAFEXT_PLUGIN_DIR . '/php/placementstrategies.php';

function leafext_admin_placementstrategies() {
		echo '<form method="post" action="options.php">';
		settings_fields('leafext_settings_placementparams');
		do_settings_sections( 'leafext_settings_placementparams' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		echo '</form>';
}

function leafext_placementparams_init(){
	add_settings_section( 'placementparams_settings', leafext_cluster_tab(), 'leafext_placement_help_text', 'leafext_settings_placementparams' );
	$fields = leafext_placementstrategies_params();
	foreach($fields as $field) {
		add_settings_field("leafext_placementparams[".$field[0]."]", $field[1], 'leafext_form_placement','leafext_settings_placementparams', 'placementparams_settings', $field[0]);
	}
	register_setting( 'leafext_settings_placementparams', 'leafext_placementparams', 'leafext_validate_placement_options' );
}
add_action('admin_init', 'leafext_placementparams_init' );

// Baue Abfrage der Params
function leafext_form_placement($field) {
	//var_dump($field);
	$options = leafext_placementstrategies_params();
	//var_dump($options);
	$option = leafext_array_find($field, $options);
	//var_dump($option);echo '<br>';
	$settings = leafext_placementstrategies_settings();
	$setting = $settings[$field];

	echo __("You can change it for each map with", "extensions-leaflet-map").' <code>'.$option[0]. '</code><br>';
	if (!is_array($option[3])) {

		if ($setting != $option[2] ) {
			//var_dump($setting,$option[2]);
			echo __("Plugins Default", "extensions-leaflet-map").': ';
			echo $option[2] ? "1" : "0";
			echo '<br>';
		}

		echo '<input type="radio" name="leafext_placementparams['.$option[0].']" value="1" ';
		echo $setting ? 'checked' : '' ;
		echo '> true &nbsp;&nbsp; ';
		echo '<input type="radio" name="leafext_placementparams['.$option[0].']" value="0" ';
		echo (!$setting) ? 'checked' : '' ;
		echo '> false ';
	} else {
		$plugindefault = is_string($option[2]) ? $option[2] : ($option[2] ? "1" : "0");
		$setting = is_string($setting) ? $setting : ($setting ? "1" : "0");
		if ($setting != $plugindefault ) {
			//var_dump("Option: ",$option[2],"Plugindefault: ",$plugindefault,"Setting: ",$setting);
			echo __("Plugins Default:", "extensions-leaflet-map").' '. $plugindefault . '<br>';
		}
		echo '<select name="leafext_placementparams['.$option[0].']">';
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
function leafext_validate_placement_options($options) {
	if (isset($_POST['submit'])) return $options;
	if (isset($_POST['delete'])) delete_option('leafext_placementparams');
	return false;
}

// Helptext
function leafext_placement_help_text () {
	echo '<h2>Leaflet.MarkerCluster.PlacementStrategies</h2>';
	echo '<p>';
	echo '<a href=https://github.com/adammertel/Leaflet.MarkerCluster.PlacementStrategies">'.__('Demo and Documentation','extensions-leaflet-map').'</a>';
  echo ' - '.__('Not all parameters are implemented in the plugin.','extensions-leaflet-map').'';
	echo '</p>';
	echo '<h3>Shortcode</h3>
	<pre><code>[leaflet-map ....]
// many markers
[leaflet-marker lat=... lng=... ...]poi1[/leaflet-marker]
[leaflet-marker lat=... lng=... ...]poi2[/leaflet-marker]
...
[leaflet-marker lat=... lng=... ...]poixx[/leaflet-marker]
[placementstrategies ...]
//optional
[hover]
[zoomhomemap]
</code></pre>
<h3>Options</h3>';
}
