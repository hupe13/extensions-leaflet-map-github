# Custom elevation

If you want use an own style, you can it do so:

Select other theme in the Options Page and give it a name.

Put in your functions.php following code:

<pre>
//Shortcode: [elevation]
function leafext_custom_elevation_function(){
	// custom
	wp_enqueue_style( 'elevation_mycss',
		'url/to/css/elevation.css', array('elevation_css'));
}

add_filter('pre_do_shortcode_tag', function ( $output, $shortcode ) {
	if ( 'elevation' == $shortcode ) {
		leafext_custom_elevation_function();
    }
	return $output;
}, 10, 2);
</pre>

In your elevation.css put the styles like the theme styles in
https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.css
