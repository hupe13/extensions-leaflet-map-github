<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();
// init settings fuer elevation
function leafext_elevation_init(){
	add_settings_section( 'theme_settings', 'Elevation Profile', 'leafext_elevation_help_text', 'leafext_settings_theme' );
	add_settings_field( 'leafext_values_1', 'Theme', 'leafext_form_theme', 'leafext_settings_theme', 'theme_settings' );
	add_settings_field( 'leafext_values_2', 'Other Theme', 'leafext_form_other_theme', 'leafext_settings_theme', 'theme_settings' );
	register_setting( 'leafext_settings_theme', 'leafext_values', 'leafext_validate_elevationtheme' );
}
add_action('admin_init', 'leafext_elevation_init' );

// Baue Abfrage Standardthema
function leafext_form_theme() {
	?>
	<script>
		function leafext_EnableDisableOtherTheme(leafext_elecolor) {
			var selectedValue = leafext_elecolor.options[leafext_elecolor.selectedIndex].value;
			var leafext_eleother = document.getElementById("leafext_eleother");
			leafext_eleother.disabled = selectedValue == "other" ? false : true;
			if (!leafext_eleother.disabled) {
				leafext_eleother.removeAttribute('readonly');
			}
		}
	</script>
	<?php
	$defaults = array(
		"theme" => "lime",
		"othertheme" => "" );
	$options = shortcode_atts($defaults, get_option('leafext_values') );
	echo '<select id="leafext_elecolor" name="leafext_values[theme]" onchange = "leafext_EnableDisableOtherTheme(this)">';
	$colors = array("lime","steelblue","purple","yellow","red","magenta","lightblue","other");
	foreach ($colors as $color) {
		if ($color == $options['theme']) {
			echo '<option selected ';
		} else {
			echo '<option ';
		}
		echo 'value="'.$color.'">'.$color.'</option>';
	}
	echo '</select>';
}

// Baue Abfrage eigenes Thema
function leafext_form_other_theme() {
	$defaults = array(
		"theme" => "lime",
		"othertheme" => "" );
	$options = shortcode_atts($defaults, get_option('leafext_values') );
	echo '<input id="leafext_eleother" type="text" name="leafext_values[othertheme]" placeholder="my-theme"
		pattern=".+-theme" title="'.__("must end with",'extensions-leaflet-map').' \'-theme\'"
		value="'.$options['othertheme'].'" ';
	echo ($options['theme'] == "other") ? "" : " readonly ";
	echo '/>';
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_elevationtheme($input) {
	$input['othertheme'] =  sanitize_text_field($input['othertheme']);
	return $input;
}

// Helptext
function leafext_elevation_help_text () {
	$text =  '
	<img src="'.LEAFEXT_PLUGIN_PICTS.'elevation.png">
<h2>Shortcode</h2>
<pre><code>[leaflet-map ....]
// at least one marker if you use it with zoomehomemap
[leaflet-marker lat=... lng=... ...]Start[/leaflet-marker]
[elevation gpx="url_gpx_file"]
// or
[elevation gpx="url_gpx_file" summary=1]
</code></pre>';
echo $text;
//do_settings_sections( 'leafext_settings_theme' );
//submit_button();
    $text =
	"<p>".__('If you want use an own style, select "other" theme and give it a name. Put in your functions.php following code:',"extensions-leaflet-map")."</p>
<pre>
//Shortcode: [elevation]
function leafext_custom_elevation_function() {
	// custom
	wp_enqueue_style( 'elevation_mycss',
		'url/to/css/elevation.css', array('elevation_css'));
}
add_filter('pre_do_shortcode_tag', function ( &#36;output, &#36;shortcode ) {
	if ( 'elevation' == &#36;shortcode ) {
		leafext_custom_elevation_function();
	}
	return &#36;output;
}, 10, 2);
</pre>"
	.'<p>'.
	__("In your elevation.css put the styles like the theme styles in <a href='https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.css'>https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.css</a>","extensions-leaflet-map")
	.'</p>';
	echo $text;
}
