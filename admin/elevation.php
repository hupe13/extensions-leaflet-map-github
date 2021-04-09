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
	echo '<input type="text" name="leafext_values[othertheme]" value="'.$options['othertheme'].'" />  (see help)';
}

// Erklaerung
function leafext_theme_text() {
    echo '<p>Here you can switch colors for an elevation theme.</p>';
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_elevationtheme($input) {
	// Say our second option must be safe text with no HTML tags
	$input['othertheme'] =  wp_filter_nohtml_kses($input['othertheme']);
	return $input;
}

// Helptext
function leafext_elevation_help () {
    $screen = get_current_screen();
    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'    => 'elevation',
        'title' => __('Elevation Theme'),
        'content'   => "<p>".__('If you want use an own style, you can it do so:</p>
				<p>Select other theme in the Options Page and give it a name.</p>
				<p>Put in your functions.php following code:',"extensions-leaflet-map")."</p>
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
</pre>".
				__("In your elevation.css put the styles like the theme styles in
				<a href='https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.css'>https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.css</a>","extensions-leaflet-map")
    ) );
}
