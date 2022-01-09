<?php
/**
 * Functions for enqueuing Leaflet plugins
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

// For checking to load awesome (Home character, graph character)
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

function leafext_enqueue_awesome() {
	// Font awesome
	$font_awesome = array('font-awesome', 'fontawesome');
	if (leafext_plugin_stylesheet_installed($font_awesome) === 0) {
		wp_enqueue_style('font-awesome',
			plugins_url('css/font-awesome.min.css',
			LEAFEXT_PLUGIN_FILE),
			null, null);
	}
}

function leafext_css() {
	wp_enqueue_style( 'leafext_css',
//		plugins_url('css/leafext.min.css',
  plugins_url('css/leafext.min.css',
		LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
}

function leafext_enqueue_zoomhome () {
	wp_enqueue_script('zoomhome',
		plugins_url('leaflet-plugins/leaflet.zoomhome/leaflet.zoomhome.min.js',
		LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'), null);
	wp_enqueue_style('zoomhome',
		plugins_url('leaflet-plugins/leaflet.zoomhome/leaflet.zoomhome.css',
		LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'), null);
	// wp_enqueue_style('zoomhome_css',
		// plugins_url('css/zoomhome.css',
		// LEAFEXT_PLUGIN_FILE),
		// array('zoomhome'), null);
	leafext_enqueue_awesome();
	leafext_css();
}

function leafext_enqueue_markercluster () {
	wp_enqueue_style( 'markercluster.default',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.3/css/MarkerCluster.Default.css',
		LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_style( 'markercluster',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.3/css/MarkerCluster.css',
		LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_script('markercluster',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.3/js/leaflet.markercluster.js',
		LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'),null );
}

define('LEAFEXT_ELEVATION_URL', LEAFEXT_PLUGIN_URL . '/leaflet-plugins/leaflet-elevation-1.7.3/');
function leafext_enqueue_elevation () {
	wp_enqueue_script( 'elevation_js',
		plugins_url('leaflet-plugins/leaflet-elevation-1.7.3/js/leaflet-elevation.min.js',
		LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'),null);
	wp_enqueue_style( 'elevation_css',
		plugins_url('leaflet-plugins/leaflet-elevation-1.7.3/css/leaflet-elevation.min.css',
		LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	// wp_enqueue_style( 'elevation_leafext_css',
		// plugins_url('css/elevation.css',
		// LEAFEXT_PLUGIN_FILE),
		// array('elevation_css'),null);
	leafext_css();
}

function leafext_enqueue_multielevation () {
	wp_enqueue_script('leaflet.gpxgroup',
//  	plugins_url('leaflet-plugins/leaflet-elevation-1.7.3/libs/leaflet-gpxgroup.min.js',
    	plugins_url('leaflet-plugins/leaflet-elevation-1.7.3/libs/leaflet-gpxgroup.js',
		LEAFEXT_PLUGIN_FILE),
		array('elevation_js'),null);
	//
	wp_enqueue_script('leaflet_ajax_geojson_js');
	// wp_enqueue_style( 'my_elevation_css',
	 	// plugins_url('css/multielevation.css',
		// LEAFEXT_PLUGIN_FILE),
	  // array('elevation_css'), null);
  wp_enqueue_script('Leaflet.GeometryUtil',
    plugins_url('leaflet-plugins/Leaflet.GeometryUtil/leaflet.geometryutil.js',
    LEAFEXT_PLUGIN_FILE),
	  array('elevation_js'),null);
  wp_enqueue_script('leaflet.distanceMarkers',
    plugins_url('leaflet-plugins/leaflet-elevation-1.7.3/libs/leaflet-distance-marker.min.js',
		LEAFEXT_PLUGIN_FILE),
	  array('Leaflet.GeometryUtil'),null);
  wp_enqueue_style( 'leaflet.distanceMarkers',
    plugins_url('leaflet-plugins/leaflet-elevation-1.7.3/libs/leaflet-distance-marker.min.css',
    LEAFEXT_PLUGIN_FILE),
    array('elevation_css'),null);
	leafext_css();
}

function leafext_enqueue_clustergroup () {
	wp_enqueue_script('leaflet.subgroup',
		plugins_url(
		'leaflet-plugins\Leaflet.FeatureGroup.SubGroup-1.0.2/leaflet.featuregroup.subgroup.js',
		LEAFEXT_PLUGIN_FILE),
		array('markercluster'),null);
}

function leafext_enqueue_placementstrategies () {
	wp_enqueue_script('placementstrategies',
		plugins_url('leaflet-plugins/Leaflet.MarkerCluster.PlacementStrategies/leaflet-markercluster.placementstrategies.js',
		LEAFEXT_PLUGIN_FILE),
		array('markercluster'),null );
}

function leafext_enqueue_fullscreen () {
	wp_enqueue_style( 'leaflet.fullscreen',
		plugins_url('leaflet-plugins/leaflet.fullscreen-2.2.0/Control.FullScreen.css',
		LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_script('leaflet.fullscreen',
		plugins_url('leaflet-plugins/leaflet.fullscreen-2.2.0/Control.FullScreen.js',
		LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'),null);
}

define('LEAFEXT_GESTURE_JS_FILE', LEAFEXT_PLUGIN_DIR .
		'leaflet-plugins/leaflet-gesture-handling-1.3.5/js/leaflet-gesture-handling-leafext.js');
function leafext_enqueue_gestures() {
	wp_enqueue_script('gestures_leaflet',
		plugins_url('leaflet-plugins/leaflet-gesture-handling-1.3.5/js/leaflet-gesture-handling-leafext.min.js',
		LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'), null);
	wp_enqueue_style('gestures_leaflet_styles',
		plugins_url('leaflet-plugins/leaflet-gesture-handling-1.3.5/css/leaflet-gesture-handling.min.css',
		LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
}

function leafext_enqueue_easybutton() {
	wp_enqueue_style( 'easybutton_css',
		plugins_url('leaflet-plugins/Leaflet.EasyButton/easy-button.css',
		LEAFEXT_PLUGIN_FILE),
		array('elevation_css'),null);
	wp_enqueue_script('easybutton',
		plugins_url('leaflet-plugins/Leaflet.EasyButton/easy-button.js',
		LEAFEXT_PLUGIN_FILE),
		array('elevation_js'),null );
	// wp_enqueue_style( 'easybutton_mycss',
		// plugins_url('css/easy-button.css',
		// LEAFEXT_PLUGIN_FILE),
		// array('easybutton_css'),null);
	leafext_enqueue_awesome();
	leafext_css();
}

function leafext_enqueue_opacity () {
	wp_enqueue_style( 'Leaflet.Control.Opacity',
		plugins_url('leaflet-plugins/Leaflet.Control.Opacity/L.Control.Opacity.css',
		LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_script('Leaflet.Control.Opacity',
		plugins_url('leaflet-plugins/Leaflet.Control.Opacity/L.Control.Opacity.js',
		LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'),null);
}

define('LEAFEXT_PROVIDERS_JS_FILE', LEAFEXT_PLUGIN_DIR .
		'leaflet-plugins/leaflet-providers/leaflet-providers.js');
function leafext_enqueue_providers() {
	wp_enqueue_script('providers',
		plugins_url('leaflet-plugins/leaflet-providers/leaflet-providers.js',LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'),null );
}
