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
	if (!current_user_can('manage_options')) {
		$disabled = " disabled ";
	} else {
		$disabled = "";
	}
	//var_dump($field);
	$options = leafext_multielevation_params();
	//var_dump($options);
	$option = leafext_array_find2($field, $options);
	//var_dump($option);echo '<br>';
	$settings = leafext_multielevation_settings();
	$setting = $settings[$field];
	if ( isset ($option['next']) ) echo '<div style="border-top: '.$option['next'].'px solid #646970"></div>';
	if ( $option['desc'] != "" ) echo '<p>'.$option['desc'].'</p>';

	echo __("You can change it with", "extensions-leaflet-map").' <code>'.$option['param']. '</code><br>';

	if (is_array($option['values'])) {
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

	} else if ($option['values'] == "color") {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script('leafext-picker',
			plugins_url('js/colorpicker.js',LEAFEXT_PLUGIN_FILE),
			array('wp-color-picker'), null);

		if ($setting != $option['default'] ) {
			//var_dump($setting,$option['default']);
			echo __("Plugins Default", "extensions-leaflet-map").': ';
			echo $option['default'];
			echo '<br>';
		}
		if (current_user_can('manage_options')) {
			echo '<input type="text" class="colorPicker" id="leafext_multieleparams['.$option['param'].']" name="leafext_multieleparams['.$option['param'].']"
	 		data-default-color = "'.$option['default'].'" value = "'.$setting.'"/>';
		} else {
			echo '<svg width="25" height="25">
			<rect width="25" height="25" style="fill:'.$option['default'].';stroke-width:1;stroke:rgb(0,0,0)" />
			</svg>';
		}
	} else {

		if ($setting != $option['default'] ) {
			//var_dump($setting,$option['default']);
			echo __("Plugins Default", "extensions-leaflet-map").': ';
			echo $option['default'] ? "true" : "false";
			echo '<br>';
		}

		echo '<input '.$disabled.' type="radio" name="leafext_multieleparams['.$option['param'].']" value="1" ';
		echo $setting ? 'checked' : '' ;
		echo '> true &nbsp;&nbsp; ';
		echo '<input '.$disabled.' type="radio" name="leafext_multieleparams['.$option['param'].']" value="0" ';
		echo (!$setting) ? 'checked' : '' ;
		echo '> false ';
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
	if (strpos($_SERVER["REQUEST_URI"], "/en/") !==  false) {
		$lang = '/en';
	} else {
		$lang = '';
	}
	$text='<img src="'.LEAFEXT_PLUGIN_PICTS.'multielevation.png" alt = "multielevation">
		<h2>Shortcode</h2>
<pre><code>[leaflet-map fitbounds ...]
[elevation-track file="http(s)://my.domain.tld/url_file_1" lat="..." lng="..." name="..." filename=0/1]
// many of this
[elevation-track file="http(s):/my.domain.tld/url_file_n" lat="..." lng="..." name="..." filename=0/1]
// At last
[multielevation filename=0/1 option1=value1 option2 !option3 ...]</code></pre><p>'.
__('See also','extensions-leaflet-map');

if (is_singular()|| is_archive() ) {
	$text = $text.' <a href="'.$lang.'/doku/filemgr/">';
} else {
	$text = $text.' <a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=filemgr-dir">';
}
$text = $text.'<code>leaflet-directory</code></a></p>
<h3>'.__('Options','extensions-leaflet-map').' elevation-track</h3>
<ul><li>'.
	sprintf ( __('%s is the url of the trackfile.','extensions-leaflet-map'),'<code>file</code>').
		'</li><li>'.
	sprintf ( __('%s, %s and %s describe the %sstart point%s and are optional.','extensions-leaflet-map'),
		'<code>lat</code>',
		'<code>lng</code>',
		'<code>name</code>',
		'<span style="color: #4f94d4">',
		'</span> <img src="'.LEAFEXT_ELEVATION_URL.'/images/elevation-poi.png" width="12" height="12" alt="elevation-poi">').
	'</li><li>'.
	sprintf ( __('If %s and %s are not specified, they are read from the file.','extensions-leaflet-map'),
		'<code>lat</code>',
		'<code>lng</code>').
	'</li><li>'.
	sprintf ( __('The name of the %sstart point%s is determined in this order:',
		'extensions-leaflet-map'),
		'<span style="color: #4f94d4">',
		'</span>').
	'<ol><li>'.
		sprintf ( __('If %s (in options below or in shortcode) is true, the filename (without extension) is used, no matter how %s is.',
			'extensions-leaflet-map'),
			'<code>filename</code>',
			'<code>name</code>').
	'</li><li>'.
		sprintf ( __('If %s is false and %s is specified, it is used.','extensions-leaflet-map'),
	 		'<code>filename</code>',
			'<code>name</code>').
	'</li><li>'.
	 	__('If not, an attempt is made to read the trackname from the file.','extensions-leaflet-map').
	'</li><li>'.
		__('If this is not available, the filename (without extension) is used.','extensions-leaflet-map').
	'</li></ol>'.
'</li></ul><p>'.

'<h3>'.__('Options','extensions-leaflet-map').' multielevation</h3>
	<ul><li>';
		if (is_singular()|| is_archive() ) {
			$link = $lang."/doku/elevation/";
		}	else {
			$link = '?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevation';
		}
	$text = $text.sprintf( __('You can use these options like in %sElevation Profile%s','extensions-leaflet-map'),
		'<a href="'.$link.'">',
		'</a>').': '.
 	leafext_eleparams_for_multi().
	'.</li><li>'.
	sprintf(__('The name of a %strack%s is determined in this order:','extensions-leaflet-map'),
		'<span style="color: #4f94d4">',
		'</span>').
	'</li><ol><li>'.
	sprintf(__('If %s (in options below or in shortcode) is true, the filename (without extension) is used.','extensions-leaflet-map'),
		'<code>filename</code>').
	'</li><li>'.
	__('If it is false, an attempt is made to read the trackname from the file.','extensions-leaflet-map').
	'</li><li>'.
	__('If this is not available, the filename (without extension) is used.','extensions-leaflet-map').
	'</li></ol>'.
	'</ul>'.

	'<h3>'.__('Options','extensions-leaflet-map').' elevation-tracks</h3>
	<ul><li>'.
	sprintf(__('If you use %s instead of %s, you get an elevation profile only with or without a summary line.','extensions-leaflet-map'),
	'<code>[elevation-<span style="color: #d63638">tracks</span>]</code>','<code>[multielevation]</code>').' '.
	sprintf (__('The name of a %strack%s is determined as described above.','extensions-leaflet-map'),
	'<span style="color: #4f94d4">',
	'</span>').
	'</li></ul>'.
	'<pre><code>[elevation-tracks filename=0/1 summary=0/1]</code></pre>'.

'<h3>Theme</h3><p>'.
	__('The theme is the same as the','extensions-leaflet-map');
	if (is_singular()|| is_archive() ) {
	$text = $text.' <a href="'.$lang.'/elevation/liste/?testing=theme">'.
	__('Elevation Theme','extensions-leaflet-map').'</a>.</p>';

} else {
	$text = $text.' <a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevationtheme">'.
	__('Elevation Theme','extensions-leaflet-map').'</a>.</p>';
}
$text = $text.
	'<h3>'.__('Options','extensions-leaflet-map').'</h3>
	<p>'.
	__('For boolean values applies', "extensions-leaflet-map").':<br>'.
	'<code>false</code> = <code>!option</code> || <code>option="0"</code> || <code>option=0</code><br>'.
	'<code>true</code> = <code>option</code> || <code>option="1"</code> || <code>option=1</code>'.
	'</p>';

	if (is_singular()|| is_archive() ) {
		return $text;
	} else {
		echo $text;
	}
}
