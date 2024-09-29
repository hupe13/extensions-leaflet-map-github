<?php
/**
 * Functions for enqueuing Leaflet plugins
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// define( 'LEAFEXT_MINI', '.min' );
define( 'LEAFEXT_MINI', '' );

/**
 * Enqueue js and css from Leaflet plugins
 */

// elevation, multielevation
define( 'LEAFEXT_ELEVATION_VERSION', '2.5.1' );
define( 'LEAFEXT_ELEVATION_URL', LEAFEXT_PLUGIN_URL . '/leaflet-plugins/leaflet-elevation-' . LEAFEXT_ELEVATION_VERSION . '/' );
define( 'LEAFEXT_ELEVATION_DIR', LEAFEXT_PLUGIN_DIR . '/leaflet-plugins/leaflet-elevation-' . LEAFEXT_ELEVATION_VERSION . '/' );
function leafext_enqueue_elevation() {
	wp_enqueue_script(
		'elevation_js',
		plugins_url(
			'leaflet-plugins/leaflet-elevation-' . LEAFEXT_ELEVATION_VERSION . '/dist/leaflet-elevation.min.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map' ),
		LEAFEXT_ELEVATION_VERSION,
		true
	);
	wp_enqueue_script(
		'Leaflet_i18n',
		plugins_url(
			'leaflet-plugins/Leaflet.i18n/Leaflet.i18n.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'elevation_js' ),
		LEAFEXT_ELEVATION_VERSION,
		true
	);
	leafext_enqueue_geometry();
	wp_enqueue_script(
		'Leaflet_AlmostOver',
		plugins_url(
			'leaflet-plugins/Leaflet.AlmostOver/leaflet.almostover.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'Leaflet_GeometryUtil', 'wp_leaflet_map' ),
		LEAFEXT_VERSION,
		true
	);
	wp_enqueue_script(
		'd3',
		plugins_url(
			'leaflet-plugins/d3/d3.min.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'elevation_js' ),
		LEAFEXT_VERSION,
		true
	);
	wp_enqueue_script(
		'tmcw_geojson',
		plugins_url(
			'leaflet-plugins/tmcw/togeojson.umd.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'elevation_js' ),
		LEAFEXT_VERSION,
		true
	);
	wp_enqueue_style(
		'elevation_css',
		plugins_url(
			'leaflet-plugins/leaflet-elevation-' . LEAFEXT_ELEVATION_VERSION . '/dist/leaflet-elevation.min.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_VERSION
	);
	leafext_enqueue_css();
	if ( file_exists( LEAFEXT_PLUGIN_DIR . '/lang/extensions-leaflet-map-' . get_locale() . '-elevation_js.json' ) ) {
		wp_set_script_translations( 'elevation_js', 'extensions-leaflet-map', LEAFEXT_PLUGIN_DIR . '/lang/' );
	}
}

function leafext_enqueue_rotate() {
	wp_enqueue_script(
		'Leaflet_rotate',
		plugins_url(
			'leaflet-plugins/leaflet-rotate/leaflet-rotate.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'elevation_js' ),
		LEAFEXT_VERSION,
		true
	);
}

function leafext_enqueue_multielevation() {
	leafext_enqueue_elevation();
	leafext_enqueue_zoomhome();
	wp_enqueue_script(
		'leaflet_gpxgroup',
		plugins_url(
			'leaflet-plugins/leaflet-elevation-' . LEAFEXT_ELEVATION_VERSION . '/libs/leaflet-gpxgroup.min.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'elevation_js' ),
		LEAFEXT_ELEVATION_VERSION,
		true
	);
	wp_enqueue_script( 'leaflet_ajax_geojson_js' );
	wp_enqueue_script(
		'leaflet_distanceMarkers',
		plugins_url(
			'leaflet-plugins/leaflet-elevation-' . LEAFEXT_ELEVATION_VERSION . '/libs/leaflet-distance-marker.min.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'Leaflet_GeometryUtil' ),
		LEAFEXT_ELEVATION_VERSION,
		true
	);
	wp_enqueue_style(
		'leaflet_distanceMarkers',
		plugins_url(
			'leaflet-plugins/leaflet-elevation-' . LEAFEXT_ELEVATION_VERSION . '/libs/leaflet-distance-marker.min.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'elevation_css' ),
		LEAFEXT_ELEVATION_VERSION
	);
	leafext_enqueue_css();
}

// All for clustering
function leafext_enqueue_markercluster() {
	wp_enqueue_style(
		'markercluster_default',
		plugins_url(
			'leaflet-plugins/leaflet.markercluster-1.5.3/css/MarkerCluster.Default.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_VERSION
	);
	wp_enqueue_style(
		'markercluster',
		plugins_url(
			'leaflet-plugins/leaflet.markercluster-1.5.3/css/MarkerCluster.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_VERSION
	);
	wp_enqueue_script(
		'markercluster',
		plugins_url(
			'leaflet-plugins/leaflet.markercluster-1.5.3/js/leaflet.markercluster.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map' ),
		LEAFEXT_VERSION,
		true
	);
}

function leafext_enqueue_placementstrategies() {
	wp_enqueue_script(
		'placementstrategies',
		plugins_url(
			'leaflet-plugins/Leaflet.MarkerCluster.PlacementStrategies/leaflet-markercluster.placementstrategies.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'markercluster' ),
		LEAFEXT_VERSION,
		true
	);
}

function leafext_enqueue_clustergroup() {
	wp_enqueue_script(
		'leaflet_subgroup',
		plugins_url(
			'leaflet-plugins/Leaflet.FeatureGroup.SubGroup-1.0.2/leaflet.featuregroup.subgroup.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map', 'markercluster' ),
		LEAFEXT_VERSION,
		true
	);
}

// fullscreen
function leafext_enqueue_fullscreen() {
	wp_enqueue_style(
		'leaflet_fullscreen_plugin',
		plugins_url(
			'leaflet-plugins/leaflet.fullscreen/Control.FullScreen.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_VERSION
	);
	wp_enqueue_script(
		'leaflet_fullscreen_plugin',
		plugins_url(
			'leaflet-plugins/leaflet.fullscreen/Control.FullScreen.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map' ),
		LEAFEXT_VERSION,
		true
	);
}

// zoomhomemap
function leafext_enqueue_zoomhome() {
	wp_enqueue_script(
		'zoomhome',
		plugins_url(
			'leaflet-plugins/leaflet.zoomhome/leaflet.zoomhome.min.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map' ),
		LEAFEXT_VERSION,
		true
	);
	wp_enqueue_style(
		'zoomhome',
		plugins_url(
			'leaflet-plugins/leaflet.zoomhome/leaflet.zoomhome.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_VERSION
	);
	leafext_enqueue_awesome();
	leafext_enqueue_css();
}

// gesture handling
define( 'LEAFEXT_GESTURE_VERSION', '1.4.4' );
define(
	'LEAFEXT_GESTURE_LOCALE_DIR',
	LEAFEXT_PLUGIN_DIR .
	'leaflet-plugins/leaflet-gesture-handling-' . LEAFEXT_GESTURE_VERSION . '/dist/locales/'
);
function leafext_enqueue_gestures() {
	wp_enqueue_script(
		'gestures_leaflet',
		plugins_url(
			'leaflet-plugins/leaflet-gesture-handling-' . LEAFEXT_GESTURE_VERSION . '/dist/leaflet-gesture-handling.min.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map' ),
		LEAFEXT_GESTURE_VERSION,
		true
	);
	wp_enqueue_style(
		'gestures_leaflet_styles',
		plugins_url(
			'leaflet-plugins/leaflet-gesture-handling-' . LEAFEXT_GESTURE_VERSION . '/dist/leaflet-gesture-handling.min.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_GESTURE_VERSION
	);
}

// Tile server
define(
	'LEAFEXT_PROVIDERS_JS_FILE',
	LEAFEXT_PLUGIN_DIR .
	'leaflet-plugins/leaflet-providers/leaflet-providers.js'
);
function leafext_enqueue_providers() {
	wp_enqueue_script(
		'providers',
		plugins_url( 'leaflet-plugins/leaflet-providers/leaflet-providers.js', LEAFEXT_PLUGIN_FILE ),
		array( 'wp_leaflet_map' ),
		LEAFEXT_VERSION,
		true
	);
}

function leafext_enqueue_opacity() {
	wp_enqueue_style(
		'Leaflet_Control_Opacity',
		plugins_url(
			'leaflet-plugins/Leaflet.Control.Opacity/L.Control.Opacity.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_VERSION
	);
	wp_enqueue_script(
		'Leaflet_Control_Opacity',
		plugins_url(
			'leaflet-plugins/Leaflet.Control.Opacity/L.Control.Opacity.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map' ),
		LEAFEXT_VERSION,
		true
	);
}

// GeometryUtil (e.g. for hovering)
function leafext_enqueue_geometry() {
	wp_enqueue_script(
		'Leaflet_GeometryUtil',
		plugins_url(
			'leaflet-plugins/Leaflet.GeometryUtil/leaflet.geometryutil.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map' ),
		LEAFEXT_VERSION,
		true
	);
}

// turf for hovering
function leafext_enqueue_turf() {
	wp_enqueue_script(
		'leafextturf',
		plugins_url(
			'leaflet-plugins/turf/leafext-turf.min.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map' ),
		LEAFEXT_VERSION,
		true
	);
}

// Extramarker
function leafext_enqueue_extramarker() {
	wp_enqueue_script(
		'extramarker',
		plugins_url(
			'leaflet-plugins/Leaflet.ExtraMarkers/dist/js/leaflet.extra-markers.min.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map' ),
		LEAFEXT_VERSION,
		true
	);
	wp_enqueue_style(
		'extramarker',
		plugins_url(
			'leaflet-plugins/Leaflet.ExtraMarkers/dist/css/leaflet.extra-markers.min.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_VERSION
	);
	leafext_enqueue_awesome();
}

// Choropleth
function leafext_enqueue_choropleth() {
	wp_enqueue_script(
		'choropleth',
		plugins_url(
			'leaflet-plugins/leaflet-choropleth/choropleth.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_ajax_geojson_js' ),
		LEAFEXT_VERSION,
		true
	);
	wp_enqueue_style(
		'leafext_css',
		plugins_url(
			'css/choropleth.min.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_VERSION
	);
}

// leafletsearch
function leafext_enqueue_leafletsearch() {
	wp_enqueue_script(
		'leafletsearch',
		plugins_url(
			'leaflet-plugins/leaflet-search/dist/leaflet-search.min.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map' ),
		LEAFEXT_VERSION,
		true
	);
	wp_enqueue_style(
		'leafletsearch',
		plugins_url(
			'leaflet-plugins/leaflet-search/dist/leaflet-search.min.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_VERSION
	);
	$server = map_deep( wp_unslash( $_SERVER ), 'sanitize_text_field' );
	if ( strpos( $server['HTTP_USER_AGENT'], 'iPhone' ) !== false ) {
		wp_enqueue_style(
			'leafletsearchiphone',
			plugins_url(
				'css/iphone.min.css',
				LEAFEXT_PLUGIN_FILE
			),
			array( 'leafletsearch' ),
			LEAFEXT_VERSION
		);
	}
}

function leafext_enqueue_targetmarker() {
	leafext_enqueue_markercluster();
	wp_enqueue_script(
		'targetmarker-fkt',
		plugins_url(
			'js/targetmarker-fkt' . LEAFEXT_MINI . '.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map', 'markercluster' ),
		LEAFEXT_VERSION,
		true
	);
	wp_enqueue_script(
		'targetmarker',
		plugins_url(
			'js/targetmarker' . LEAFEXT_MINI . '.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map', 'targetmarker-fkt' ),
		LEAFEXT_VERSION,
		true
	);
	leafext_enqueue_css();
}

function leafext_enqueue_controltree() {
	wp_enqueue_script(
		'controltree',
		plugins_url(
			'leaflet-plugins/Control.Layers.Tree/L.Control.Layers.Tree.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map' ),
		LEAFEXT_VERSION,
		true
	);
	wp_enqueue_style(
		'controltree',
		plugins_url(
			'leaflet-plugins/Control.Layers.Tree/L.Control.Layers.Tree.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_VERSION
	);
	wp_enqueue_script(
		'leafext_parentgroup',
		plugins_url(
			'js/parentgroup' . LEAFEXT_MINI . '.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map', 'controltree' ),
		LEAFEXT_VERSION,
		true
	);
}

/**
 * Awesome font
 */

// For checking to load awesome (Home character, extra-marker)
function leafext_enqueue_awesome() {
	// Font awesome
	$font_awesome = array( 'font-awesome', 'fontawesome' );
	global $wp_styles;
	foreach ( $wp_styles->queue as $style ) {
		foreach ( $font_awesome as $css ) {
			if ( false !== strpos( $style, $css ) ) {
				return;
			}
		}
	}
	wp_enqueue_style(
		'font-awesome',
		plugins_url(
			'fonts/fontawesome-free-6.6.0-web/css/all.min.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_VERSION
	);
}

/**
 * Filters
 */

// Dashicon in frond end
add_filter(
	'pre_do_shortcode_tag',
	function ( $output, $shortcode, $attr ) {
		if ( 'leaflet-marker' === $shortcode ) {
			if ( isset( $attr['iconclass'] ) && str_contains( $attr['iconclass'], 'dashicons' ) ) {
				wp_enqueue_style( 'dashicons' );
			}
		}
		return $output;
	},
	10,
	3
);

// Disable handling of html chars in shortcode block
add_filter(
	'render_block',
	function ( $block_content, $block ) {
		if ( $block['blockName'] === 'core/shortcode' ) {
			$shortcodes = array( 'leaflet-map', 'leaflet-marker', 'leaflet-extramarker' );
			$match      = ( str_replace( $shortcodes, '', $block['innerHTML'] ) !== $block['innerHTML'] );
			if ( $match ) {
				return htmlspecialchars_decode( $block['innerHTML'] );
			}
		}
		return $block_content;
	},
	10,
	2
);

/**
 * Enqueue css for Extensions Leaflet Map
 */
function leafext_enqueue_css() {
	wp_enqueue_style(
		'leafext_css',
		plugins_url(
			'css/leafext.min.css',
			// plugins_url('css/leafext.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_VERSION
	);
}

function leafext_enqueue_overview() {
	wp_enqueue_style(
		'overview',
		plugins_url(
			'css/overview.min.css',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'leaflet_stylesheet' ),
		LEAFEXT_VERSION
	);
}

/**
 * Enqueue js for Extensions Leaflet Map
 */
function leafext_enqueue_js() {
		wp_enqueue_script(
			'leafext_js',
			plugins_url(
				'js/leafext' . LEAFEXT_MINI . '.js',
				LEAFEXT_PLUGIN_FILE
			),
			array( 'wp_leaflet_map' ),
			LEAFEXT_VERSION,
			true
		);
}

function leafext_enqueue_leafext( $file, $dep = '' ) {
	if ( $dep === '' ) {
		$deps = array( 'wp_leaflet_map' );
	} else {
		$deps = array( 'wp_leaflet_map', $dep );
	}
	wp_enqueue_script(
		'leafext_' . $file,
		plugins_url(
			'js/' . $file . LEAFEXT_MINI . '.js',
			LEAFEXT_PLUGIN_FILE
		),
		$deps,
		LEAFEXT_VERSION,
		true
	);
	leafext_enqueue_awesome();
	leafext_enqueue_css();
}

function leafext_enqueue_leafext_elevation() {
	wp_enqueue_script(
		'leafext_elevation',
		plugins_url(
			'js/elevation' . LEAFEXT_MINI . '.js',
			LEAFEXT_PLUGIN_FILE
		),
		array( 'wp_leaflet_map', 'elevation_js' ),
		LEAFEXT_VERSION,
		true
	);
	wp_set_script_translations( 'leafext_elevation', 'extensions-leaflet-map' );
}

function leafext_enqueue_admin() {
	wp_enqueue_style(
		'prism-css',
		plugins_url( 'pkg/prism/prism.css', LEAFEXT_PLUGIN_FILE ),
		array(),
		LEAFEXT_VERSION
	);
	wp_enqueue_script(
		'prism-js',
		plugins_url( 'pkg/prism/prism.js', LEAFEXT_PLUGIN_FILE ),
		array(),
		LEAFEXT_VERSION,
		true
	);
}
