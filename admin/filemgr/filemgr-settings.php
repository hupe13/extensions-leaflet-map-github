<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Parameter and Values
function leafext_filemgr_params($typ = array()) {
	$params = array(
		array(
			'param' => 'types',
			'shortdesc' => __('Types',"extensions-leaflet-map"),
			'desc' => __('Allow upload to media library',"extensions-leaflet-map"),
			'default' => array(),
			'values' => array ("gpx","kml","geojson","json","tcx"),
		),
		array(
			'param' => 'gpxupload',
			'shortdesc' => __('Upload gpx files into the directory',"extensions-leaflet-map").' /upload_dir()/gpx/',
			'desc' => __('This may be of interest if you have used wp-gpx-maps.',"extensions-leaflet-map"),
			'default' => "0",
			'values' => 1,
		),
		array(
			'param' => 'nonadmin',
			'shortdesc' => __('Allow non admin',"extensions-leaflet-map"),
			'desc' => sprintf(__('Allow all users who have access to the backend to see the files. A permission check %s only done if the files are registered in the media library.',"extensions-leaflet-map"),
			'(<code>current_user_can("edit_post / read", this_post)</code>)'),
			'default' => "0",
			'values' => 1,
		),
	);
	return $params;
}

// init settings
function leafext_filemgr_init(){
	register_setting( 'leafext_settings_filemgr', 'leafext_filemgr', 'leafext_validate_filemgr_options' );
	//register_setting( 'leafext_settings_filemgr', 'leafext_filemgr' );
	add_settings_section( 'filemgr_settings', __('Settings','extensions-leaflet-map'), 'leafext_managefiles_help', 'leafext_settings_filemgr' );
	$fields = leafext_filemgr_params();
	foreach($fields as $field) {
		add_settings_field("leafext_filemgr[".$field['param']."]", $field['shortdesc'], 'leafext_form_filemgr','leafext_settings_filemgr', 'filemgr_settings', $field['param']);
	}
}
add_action('admin_init', 'leafext_filemgr_init');

function leafext_validate_filemgr_options($options){
	if (isset($_POST['submit'])) {
		$defaults=array();
		$params = leafext_filemgr_params();
		foreach($params as $param) {
			$defaults[$param['param']] = $param['default'];
		}
		$params = get_option('leafext_filemgr', $defaults);
		foreach ($options as $key => $value) {
			$params[$key] = $value;
		}
		return $params;
	}
	if (isset($_POST['delete'])) delete_option('leafext_filemgr');
	return false;
}

function leafext_form_filemgr($field) {
	$options = leafext_filemgr_params();
	//var_dump($options); wp_die();
	$option = leafext_array_find2($field, $options);
	$settings = leafext_filemgr_settings();
	$setting = $settings[$field];
	if ( $option['desc'] != "" ) echo '<p>'.$option['desc'].'</p>';

	if ( $field == "types" ) {
		foreach ( $option['values'] as $typ ) {
			$checked = in_array($typ, $setting) ? " checked " : "";
			echo ' <input type="checkbox" name="leafext_filemgr['.$option['param'].'][]" value="'.$typ.'" id="'.$typ.'" '.$checked.'>';
			echo ' <label for="'.$typ.'" >'.$typ.'</label> ';
		}
	} else {
		if ($setting != $option['default'] ) {
			//var_dump($setting,$option['default']);
			echo __("Plugins Default", "extensions-leaflet-map").': ';
			echo $option['default'] ? "true" : "false";
			echo '<br>';
		}
		echo '<input type="radio" name="leafext_filemgr['.$option['param'].']" value="1" ';
		echo $setting ? 'checked' : '' ;
		echo '> true &nbsp;&nbsp; ';
		echo '<input type="radio" name="leafext_filemgr['.$option['param'].']" value="0" ';
		echo (!$setting) ? 'checked' : '' ;
		echo '> false ';
	}
}

function leafext_filemgr_settings() {
	$defaults=array();
	$params = leafext_filemgr_params();
	foreach($params as $param) {
		$defaults[$param['param']] = $param['default'];
	}
	$options = shortcode_atts($defaults, get_option('leafext_filemgr'));
	//var_dump($options); wp_die();
	return $options;
}

function leafext_managefiles_help() {
	if (!current_user_can('manage_options')) {
		$text = __('You can display all gpx, kml, geojson, json and tcx files in subdirectories of uploads directory.','extensions-leaflet-map').' ';
	} else {
		$text = __('Here you can display all gpx, kml, geojson, json and tcx files in subdirectories of uploads directory.','extensions-leaflet-map').' ';
	}
	if (!current_user_can('manage_options')) {
		$text = $text .  ' '.__('You can manage these according to your permissions','extensions-leaflet-map');
	} else {
		$text = $text .  __('You can manage these','extensions-leaflet-map');
	}
	$text = $text .  '<ul>';
	$text = $text .  '<li>';
	$text = $text .  __('direct in the Media Library.','extensions-leaflet-map');
	$text = $text .  '</li>';
	$text = $text .  '<li>';
	$text = $text .  __('with any (S)FTP-Client,','extensions-leaflet-map');
	$text = $text .  '</li>';
	$text = $text .  '<li>';
	$text = $text .  __('with any File Manager plugin,','extensions-leaflet-map');
	$text = $text .  '</li>';
	$text = $text .  '<li>';
	$text = $text .  __('with any plugin for importing uploaded files to the Media Library.','extensions-leaflet-map');
	$text = $text .  '</li>';
	$text = $text .  '<li>';
	$text = $text .  __('or in your own way.','extensions-leaflet-map');
	$text = $text .  '</li>';
	$text = $text .  '</ul>';
	if (is_singular() || is_archive() ) {
		return $text;
	} else {
		echo $text;
	}
}
