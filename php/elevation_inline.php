<?php
//Shortcode: [elevation gpx="...url..."]

function testleafext_elevation_script($gpx,$summary,$slope,$theme){
	$text = '
	<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var elevation_options = {
			//lime-theme (default), magenta-theme, steelblue-theme, purple-theme, yellow-theme, lightblue-theme
			theme: '.json_encode($theme).',
			// Autoupdate map center on chart mouseover.
			followMarker: false,
			legend: false,
			downloadLink:false,
			polyline: { weight: 3, },
			// Summary track info style: "line" || "multiline" || false || inline(?)
			// Slope chart profile: true || "summary" || false
			summary: '.json_encode($summary).',
			slope: '.json_encode($slope).',
		};
		// Instantiate elevation control.
		var controlElevation = L.control.elevation(elevation_options);
		var track_options= { url: "'.$gpx.'" };
		controlElevation.addTo(map);
		// Load track from url (allowed data types: "*.geojson", "*.gpx")
		controlElevation.load(track_options.url);
		window.addEventListener("load", main);
	});
	</script>';
	return $text;
}

function testleafext_elevation_function( $atts ){
	$track = shortcode_atts( array('gpx' => false, 'summary' => false), $atts);
	if ( ! $track['gpx'] ) wp_die("No gpx track!");

	//Parameters see the sources from https://github.com/Raruto/leaflet-elevation
	if ( ! $track['summary'] ) {
		$summary = false;
		$slope = false;
	} else {
		$summary = "inline";
		$slope = "summary";
	}

	$options = get_option('leafext_values');
	if (!is_array($options )) {
		$theme = "lime-theme";
	} else if ($options['theme'] == "other") {
		$theme=$options['othertheme'];
	} else {
		$theme=$options['theme'].'-theme';
	}
	return testleafext_elevation_script($track['gpx'],$summary,$slope,$theme);
}
add_shortcode('testelevation', 'testleafext_elevation_function' );

add_filter('pre_do_shortcode_tag',  function ( $output, $shortcode ) {
	if ( 'testelevation' == $shortcode ) {
		wp_enqueue_script( 'elevation_js',
			plugins_url('leaflet-plugins/leaflet-elevation-1.6.6/js/leaflet-elevation.min.js',LEAFEXT_PLUGIN_FILE),
			array('wp_leaflet_map'),null);
		wp_enqueue_style( 'elevation_css',
			plugins_url('leaflet-plugins/leaflet-elevation-1.6.6/css/leaflet-elevation.min.css',LEAFEXT_PLUGIN_FILE),
			array('leaflet_stylesheet'),null);
		// language
		$lang = get_locale();
		if ( strlen( $lang ) > 0 ) {
			$lang = explode( '_', $lang )[0];
		}
		if( file_exists( LEAFEXT_PLUGIN_DIR . 'locale/elevation_'.$lang.'.js') ) {
			wp_enqueue_script('elevation_lang',
				plugins_url('locale/elevation_'.$lang.'.js',LEAFEXT_PLUGIN_FILE),
				array('elevation_js'), null);
		}
    }
	return $output;
}, 10, 2);

