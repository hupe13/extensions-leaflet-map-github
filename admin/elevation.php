<?php
// init settings fuer elevation
function leafext_elevation_init(){
	add_settings_section( 'theme_settings', 'Elevation Theme', 'leafext_theme_text', 'leafext_settings_theme' );
	add_settings_field( 'leafext_values_1', 'Theme', 'leafext_form_theme', 'leafext_settings_theme', 'theme_settings' );
	add_settings_field( 'leafext_values_2', 'Other Theme', 'leafext_form_other_theme', 'leafext_settings_theme', 'theme_settings' );
	register_setting( 'leafext_settings_theme', 'leafext_values', 'leafext_validate_elevationtheme' );
}
add_action('admin_init', 'leafext_elevation_init' );

// Baue Abfrage Standardthema
function leafext_form_theme() {
	$options = get_option('leafext_values');
	if ( ! $options ) {
		$options = array(
			"theme" => "lime",
			"othertheme" => "" );
		}
	echo '<select name="leafext_values[theme]">';
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
	$options = get_option('leafext_values');
	if ( ! $options ) {
		$options = array(
			"theme" => "lime",
			"othertheme" => "" );
	}
	echo '<input type="text" name="leafext_values[othertheme]" placeholder="my-theme" value="'.$options['othertheme'].'" />';
}

// Erklaerung
function leafext_theme_text() {
    echo '<p>'.__('Here you can switch colors for an elevation theme.',"extensions-leaflet-map").'</p>';
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_elevationtheme($input) {
	// Say our second option must be safe text with no HTML tags
	$input['othertheme'] =  wp_filter_nohtml_kses($input['othertheme']);
	return $input;
}

// Helptext
function leafext_elevation_help_text () {
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
	return $text;
}
