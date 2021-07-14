<?php

// For checking to load awesome (Home character)
function leafext_plugin_stylesheet_installed($array_css) {
    global $wp_styles;
    foreach( $wp_styles->queue as $style ) {
        foreach ($array_css as $css) {
            if (false !== strpos( $style, $css ))
                return 1;
        }
    }
    return 0;
}

function leafext_enqueue_zoomhome () {
  wp_enqueue_script('zoomhome',
    plugins_url('leaflet-plugins/leaflet.zoomhome/leaflet.zoomhome.min.js',LEAFEXT_PLUGIN_FILE),
      array('wp_leaflet_map'), null);
  wp_enqueue_style('zoomhome',
    plugins_url('leaflet-plugins/leaflet.zoomhome/leaflet.zoomhome.css',LEAFEXT_PLUGIN_FILE),
      array('leaflet_stylesheet'), null);
  // Font awesome
  $font_awesome = array('font-awesome', 'fontawesome');
  if (leafext_plugin_stylesheet_installed($font_awesome) === 0) {
      wp_enqueue_style('font-awesome',
        plugins_url('css/font-awesome.min.css',LEAFEXT_PLUGIN_FILE),
          array('zoomhome'), null);
  }
}

function leafext_enqueue_markercluster () {
  wp_enqueue_style( 'markercluster.default',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.0/css/MarkerCluster.Default.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_style( 'markercluster',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.0/css/MarkerCluster.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_script('markercluster',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.0/js/leaflet.markercluster.js',LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'),null );
}

function leafext_enqueue_elevation () {
  wp_enqueue_script( 'elevation_js',
		plugins_url('leaflet-plugins/leaflet-elevation-1.6.9/js/leaflet-elevation.min.js',
		LEAFEXT_PLUGIN_FILE),
	array('wp_leaflet_map'),null);
	wp_enqueue_style( 'elevation_css',
		plugins_url('leaflet-plugins/leaflet-elevation-1.6.9/css/leaflet-elevation.min.css',
		LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
}

function leafext_elevation_theme() {
	$ownoptions = get_option('leafext_values');
	if (is_array($ownoptions)) {
		if ( $ownoptions['theme'] == 'other' ) {
			$theme = $ownoptions['othertheme'];
		} else {
			$theme=$ownoptions['theme'].'-theme';
		}
	} else {
		$newoptions=leafext_elevation_settings();
		$theme = $newoptions['theme'];
	}
	return($theme);
}

function leafext_clear_params($atts) {
	if (is_array($atts)) {
		for ($i = 0; $i < count($atts); $i++) {
			if (isset($atts[$i])) {
				if ( strpos($atts[$i],"!") === false ) {
					$atts[$atts[$i]] = 1;
				} else {
					$atts[substr($atts[$i],1)] = 0;
				}
			}
		}
	}
	return($atts);
}

?>
