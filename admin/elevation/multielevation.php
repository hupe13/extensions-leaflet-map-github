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

	echo __("You can change it with", "extensions-leaflet-map").' <code>'.$option['param']. '</code><br>';
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
[elevation-<span style="color: #d63638">track</span> file="http(s)://my.domain.tld/url_file" lat="..." lng="..." name="..." filename=0/1]
// many of this
[elevation-<span style="color: #d63638">track</span> file="http(s):/my.domain.tld/url_file" lat="..." lng="..." name="..." filename=0/1]
// At last either
[elevation-<span style="color: #d63638">tracks</span> filename=0/1 summary=0/1]
// or
[multielevation filename=0/1 option1=value1 option2 !option3 ...]</code></pre>
</code></pre>'.

'<h3>Options elevation-<span style="color: #d63638">track</span></h3>
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
		sprintf ( __('If %s is specified, it is used.','extensions-leaflet-map'),
	 	'<code>name</code>').
	'</li><li>'.
	 	__('If not, an attempt is made to read the trackname from the file.','extensions-leaflet-map').
	'</li><li>'.
		__('If this is not available, the filename (without extension) is used.','extensions-leaflet-map').
	'</li></ol>'.
'</li></ul><p>'.
	sprintf( __('Use either %s or %s as last instruction.','extensions-leaflet-map'),
		'<code>[elevation-<span style="color: #d63638">tracks</span>]</code>',
		'<code>[multielevation]</code>').'</p>'.

'<h3>Options elevation-<span style="color: #d63638">tracks</span></h3>
	<ul><li>'.
	sprintf(__('If you use %s, you get an elevation profile only with or without a summary line.','extensions-leaflet-map'),
		'<code>[elevation-<span style="color: #d63638">tracks</span>]</code>').
	'</li><li>'.
	sprintf(__('The name of a %s is determined in this order:','extensions-leaflet-map'),
		'<span style="color: #4f94d4">track</span>').
	'</li><li>'.
	sprintf(__('If %s (in options below or in shortcode) is true, the filename (without extension) is used.','extensions-leaflet-map'),
		'<code>filename</code>').
	'</li><li>'.
	__('If it is false, an attempt is made to read the trackname from the file.','extensions-leaflet-map').
	'</li><li>'.
	__('If this is not available, the filename (without extension) is used.','extensions-leaflet-map').
	'</li></ol>'.
	'</li></ul>'.

'<h3>Options multielevation</h3>
	<ul><li>'.
	sprintf( __('If you use %s, you can use options like in','extensions-leaflet-map'),
		'<code>[multielevation]</code>').
	' <a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevation">'.__('Elevation Profile','extensions-leaflet-map').'</a>. '.
	sprintf( __('Note, not all options can be used, e.g. %schart%s.','extensions-leaflet-map'),
		'<code>',
		'</code>').
	'</li><li>'.
	sprintf (__('The name of a %s is determined as described above.','extensions-leaflet-map'),
		'<span style="color: #4f94d4">track</span>').
	'</li></ul>'.

'<h3>Theme</h3><p>'.
	__('The theme is the same as the','extensions-leaflet-map').
	' <a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevation">'.
	__('Elevation Theme','extensions-leaflet-map').'</a>.</p>'.

	'<h3>Options</h3>
	<p>'.
	__('For boolean values applies', "extensions-leaflet-map").':<br>'.
	'<code>false</code> = <code>!option</code> || <code>option="0"</code> || <code>option=0</code></br>'.
	'<code>true</code> = <code>option</code> || <code>option="1"</code> || <code>option=1</code>'.
	'</p>';
	if (is_singular()|| is_archive() ) {
		return $text;
	} else {
		echo $text;
	}
}
