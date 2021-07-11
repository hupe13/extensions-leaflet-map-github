<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

$text = '
<img src="'.LEAFEXT_PLUGIN_PICTS.'elevation.png">
<h2>Shortcode</h2>
<pre><code>[leaflet-map ....]
// at least one marker if you use it with zoomehomemap
[leaflet-marker lat=... lng=... ...]Start[/leaflet-marker]
[elevation gpx="url_gpx_file"]
// or
[elevation gpx="url_gpx_file" summary=1]
</code></pre>'.
"<h2>Theme</h2><p>".__('If you want use an own style, select "other" theme and give it a name. Put in your functions.php following code:',"extensions-leaflet-map").
"</p><pre>
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
__('In your elevation.css put the styles like the theme styles in',"extensions-leaflet-map")
.' <a href="https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.css"
>https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.css</a></p>';

$theme = get_option('leafext_values');
if (!is_array($theme))
	$text = $text.__("Please change this only, if you want to use your own theme.","extensions-leaflet-map");

echo $text;
