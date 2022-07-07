<?php
/**
 * Admin page for elevation theme
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_elevation_colors() {
	$theme = leafext_elevation_theme();
	$themes = array(
		"lime-theme" => array(
			'polyline' => '#80904e',
			'background' => "#ebf3d3",
			'altitude' => "#accc45",
		),
		"steelblue-theme" => array(
			'polyline' => '#74a1c7',
			'background' => "#dae6f0",
			'altitude' => "#6496c0",
		),
		"purple-theme" => array(
			'polyline' => '#96619c',
			'background' => "#e3d5e5",
			'altitude' => "#8b5291",
		),
		"yellow-theme" => array(
			'polyline' => '#ffff40',
			'background' => "#dae6f0",
			'altitude' => "#f8fa30",
		),
		"red-theme" => array(
			'polyline' => '#ff4040',
			'background' => "#dae6f0",
				'altitude' => "#f82e30",
		),
		"magenta-theme" => array(
			'polyline' => '#ff4086',
			'background' => "#ffffff",
				'altitude' => "#ff337e",
		),
		"lightblue-theme" => array(
			'polyline' => '#668cd9',
			'background' => "#dae6f0",
				'altitude' => "#8face0",
		),
	);
	//
	$params = array(
		array(
			'param' => 'altitude',
			'shortdesc' => __('Altitude chart profile',"extensions-leaflet-map"),
			'desc' => "",
			'default' => isset($themes[$theme]['altitude']) ? $themes[$theme]['altitude'] : "",
		),
		array(
			'param' => 'speed',
			'shortdesc' => __('Speed chart profile',"extensions-leaflet-map"),
			'desc' => "",
			'default' => "#03ffff",
		),
		array(
			'param' => 'acceleration',
			'shortdesc' => __('Acceleration chart profile',"extensions-leaflet-map"),
			'desc' => "",
			'default' => "#050402",
		),
		array(
			'param' => 'slope',
			'shortdesc' => __('Slope chart profile',"extensions-leaflet-map"),
			'desc' => "",
			'default' => "#FF0000",
		),
		array(
			'param' => 'pace',
			'shortdesc' =>  __('Pace profile - time per distance',"extensions-leaflet-map"),
			'desc' => "",
			'default' => "#03ffff",
		),
		array(
			'param' => 'polyline',
			'shortdesc' => __('Track color',"extensions-leaflet-map"),
			'desc' => "",
			'default' => isset($themes[$theme]['polyline']) ? $themes[$theme]['polyline'] : "",
		),
		array(
			'param' => 'background',
			'shortdesc' => __('Chart background color',"extensions-leaflet-map"),
			'desc' => "",
			'default' => isset($themes[$theme]['background']) ? $themes[$theme]['background'] : "",
		),
	);
	return $params;
}

//init Wahl des theme, leafext_validate_ele_options und leafext_form_elevation ist in elevation.php
function leafext_themes_init(){
	// 	leafext_eleparams ist in der Datenbank!
	add_settings_section( 'elethemes_settings', leafext_elevation_tab().'<p><div style="border-top: 1px solid #646970"></div></p>', '', 'leafext_settings_elethemes' );
	$fields = leafext_elevation_params(array("theme"));
	foreach($fields as $field) {
		$trenn = "";
		if ( isset ($field['next']) ) $trenn = '<div style="border-top: '.$field['next'].'px solid #646970"></div>';
		add_settings_field("leafext_eleparams[".$field['param']."]", $trenn.$field['shortdesc'], 'leafext_form_elevation','leafext_settings_elethemes', 'elethemes_settings', $field['param']);
	}
	register_setting( 'leafext_settings_elethemes', 'leafext_eleparams', 'leafext_validate_ele_options' );
}
add_action('admin_init', 'leafext_themes_init' );

// init settings fuer elevation colors
function leafext_elevation_color_init(){
	$theme = get_option('leafext_values');
	if (is_array($theme)) {
		$tab = leafext_elevation_tab();
	} else {
		$tab = "";
	}
	add_settings_section( 'elecolors_settings', $tab , 'leafext_color_help_text', 'leafext_settings_color' );
	//add_settings_section( 'elecolors_settings', "", 'leafext_color_help_text', 'leafext_settings_color' );
	$fields = leafext_elevation_colors();
	$theme = leafext_elevation_theme();
	foreach($fields as $field) {
		add_settings_field("leafext_color_".$theme."[".$field['param']."]", $field['shortdesc'], 'leafext_form_colors','leafext_settings_color', 'elecolors_settings', $field['param']);
	}
	register_setting( 'leafext_settings_color', 'leafext_color_'.$theme, 'leafext_validate_owncolors' );
}
add_action('admin_init', 'leafext_elevation_color_init' );

// Baue Abfrage Farben
function leafext_form_colors($field) {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_script('leafext-picker',
		plugins_url('js/colorpicker.js',LEAFEXT_PLUGIN_FILE),
		array('wp-color-picker'), null);

	$theme = leafext_elevation_theme();
	$options  = leafext_elevation_colors();
	$option = leafext_array_find2($field, $options);

	$owncolors = get_option('leafext_color_'.$theme);
	if (is_array($owncolors) && isset($owncolors[$option['param']])) {
		$setting = $owncolors[$option['param']];
	} else {
		$setting = $option['default'];
	}

	if (current_user_can('manage_options')) {
		echo '<input type="text" class="colorPicker" id="leafext_color_'.$theme.'['.$option['param'].']" name="leafext_color_'.$theme.'['.$option['param'].']"
			data-default-color = "'.$option['default'].'" value = "'.$setting.'"/>';
	} else {
		echo '<svg width="25" height="25">
		<rect width="25" height="25" style="fill:'.$option['default'].';stroke-width:1;stroke:rgb(0,0,0)" />
		</svg>';
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_owncolors($options) {
	$theme = leafext_elevation_theme();
	if (isset($_POST['submit'])) {
		//var_dump($options);
		$defaults = leafext_elevation_colors();
		foreach ($options as $key => $value) {
			$param = leafext_array_find2($key, $defaults);
			if ($options[$key] == $param['default']) {
				unset ($options[$key]);
			}
		}
		return $options;
	}
	if (isset($_POST['delete'])) delete_option('leafext_color_'.$theme);
	return false;
}

// Helptext
function leafext_color_help_text () {
	$text = "";
	$theme = get_option('leafext_values');
	if (is_array($theme)) {
		$text = $text. '<p><div style="border-top: 1px solid #646970"></div></p>
		<h2>Theme</h2><p>';
		$text = $text. __("Your theme is the","extensions-leaflet-map");
		$text = $text. ' <strong>'.$theme["othertheme"].'</strong>. ';
		$text = $text. __("To change it see the bottom of the page.","extensions-leaflet-map");
	}
	$text = $text. '<div style="border-top: 1px solid #646970"></div>';
	$text = $text. '<h2>'.__("Colors","extensions-leaflet-map").'</h2>';
	echo $text;
}
