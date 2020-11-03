<?php
//Shortcode: [elevation-multi]
// For use with more than one map on a webpage

function elevation_function( $atts ){
	global $post;
	if ( ! is_page() ) return;
	wp_enqueue_script( 'elevation_js',
  	'https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.min.js',
			array('leaflet_js'),null);
	wp_enqueue_style( 'elevation_css',
	'https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.min.css',
	array('leaflet_stylesheet'));
	// custom js
	wp_enqueue_script('myelevation',
		plugins_url('js/markercluster.js',CODESNIPPETS__PLUGIN_FILE),array('elevation_js'), '1.0', true);
	//
	$track = shortcode_atts( array('gpx' => false, 'summary' => false), $atts);
	if ( ! $track['gpx'] ) wp_die();

	// Summary track info style: "line" || "multiline" || false || inline(?)
	// Slope chart profile: true || "summary" || false
	if ( ! $track['summary'] ) {
		$summary = false;
		$slope = false;
	} else {
		$summary = "inline";
		$slope = "summary";
	}

	$text = '
		<script>
		window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
		window.WPLeafletMapPlugin.push(function () {
			var map = window.WPLeafletMapPlugin.getCurrentMap();
			// Instantiate elevation control.
			elevation_options.summary = '.json_encode($summary).';
			elevation_options.slope = '.json_encode($slope).';
			var controlElevation = L.control.elevation(elevation_options);
			controlElevation.addTo(map);
			// Load track from url (allowed data types: "*.geojson", "*.gpx")
			var track_options= { url: "'.$track['gpx'].'" };
			controlElevation.load(track_options.url);
		});
		</script>
	';
	return $text;
}
add_shortcode('elevation-multi', 'elevation_function' );
