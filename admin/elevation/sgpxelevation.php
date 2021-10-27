<?php
/**
 * Admin for wp-gpx-maps
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_sgpxparams_init(){
	add_settings_section( 'sgpxparams_settings', leafext_elevation_tab(), 'leafext_sgpx_help_text', 'leafext_settings_sgpxparams' );
	$fields = leafext_sgpx_params();
	foreach($fields as $field) {
		$trenn = "";
		if ( isset ($field['next']) ) $trenn = '<div style="border-top: '.$field['next'].'px solid #646970"></div>';
		add_settings_field("leafext_sgpxparams[".$field['param']."]", $trenn.$field['shortdesc'], 'leafext_form_sgpx','leafext_settings_sgpxparams', 'sgpxparams_settings', $field['param']);
	}
	register_setting( 'leafext_settings_sgpxparams', 'leafext_sgpxparams', 'leafext_validate_sgpx_options' );
}
add_action('admin_init', 'leafext_sgpxparams_init' );

// Baue Abfrage der Params
function leafext_form_sgpx($field) {
	$options = leafext_sgpx_params();
	$option = leafext_array_find2($field, $options);
	$settings = leafext_sgpx_settings();
	$setting = $settings[$field];
	if ( isset ($option['next']) ) echo '<div style="border-top: '.$option['next'].'px solid #646970"></div>';
	if ( $option['desc'] != "" ) echo '<p>'.$option['desc'].'</p>';

	//echo __("You can change it for each map with", "extensions-leaflet-map").' <code>'.$option['param']. '</code><br>';
	if (!is_array($option['values'])) {

		if ($setting != $option['default'] ) {
			//var_dump($setting,$option['default']);
			echo __("Plugins Default", "extensions-leaflet-map").': ';
			echo $option['default'] ? "true" : "false";
			echo '<br>';
		}

		echo '<input type="radio" name="leafext_sgpxparams['.$option['param'].']" value="1" ';
		echo $setting ? 'checked' : '' ;
		echo '> true &nbsp;&nbsp; ';
		echo '<input type="radio" name="leafext_sgpxparams['.$option['param'].']" value="0" ';
		echo (!$setting) ? 'checked' : '' ;
		echo '> false ';
	} else {
		$plugindefault = is_string($option['default']) ? $option['default'] : ($option['default'] ? "1" : "0");
		$setting = is_string($setting) ? $setting : ($setting ? "1" : "0");
		if ($setting != $plugindefault ) {
			//var_dump("Option: ",$option['default'],"Plugindefault: ",$plugindefault,"Setting: ",$setting);
			echo __("Plugins Default:", "extensions-leaflet-map").' '. $plugindefault . '<br>';
		}
		echo '<select name="leafext_sgpxparams['.$option['param'].']">';
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
function leafext_validate_sgpx_options($options) {
	if (isset($_POST['submit'])) return $options;
	if (isset($_POST['delete'])) {
		if ( $_POST['delete'] == 'Delete all settings from wp-gpx-maps!' ) {
			global $wpdb;
			$option_names = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'wpgpxmaps_%' " );
			foreach ( $option_names as $key=>$value ) {
				delete_option($value->option_name);
			}
		} else {
			delete_option('leafext_sgpxparams');
		}
	}
	return false;
}

// Helptext
function leafext_sgpx_help_text () {
	echo '
	<h2>'.__('Help','extensions-leaflet-map').'</h2>';
	echo __("This site will help you to switch from wp-gpx-maps.","extensions-leaflet-map");
	echo '<ul><li>';
	echo __('Configure your defaults <a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevation">elevation settings</a>, especially the theme.',"extensions-leaflet-map");
	echo '</li><li>';
	echo __('You can first test it: select "leaflet", but write in your test page / post <code>[leaflet-map height="1"]</code>.',"extensions-leaflet-map");
	echo '</li><li>';
	echo __('Select "1" to interpret the parameters elevation-like.',"extensions-leaflet-map");
	echo '</li><li>';
	echo __("Deactivate and delete the plugin wp-gpx-maps.","extensions-leaflet-map");
	echo '</li><li>';
	echo __("Not all settings have been deleted, delete them here.","extensions-leaflet-map");
	echo '</li><li>';
	echo __("If wp-gpx-maps exists anymore, sgpx parameters will interpreted elevation-like always. You can delete all settings on this page.","extensions-leaflet-map");
	// echo '</li><li>';
	// echo __(".","extensions-leaflet-map");
	echo '</li></ul>';
	echo sprintf(
		__('See documentation and examples in %shere%s.',
			"extensions-leaflet-map"),
			'<a href="https://leafext.de/en/doku/elevation/sgpx/">',
			'</a>');
}
