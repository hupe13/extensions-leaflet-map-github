<?php
/**
 * Documentation HELP
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_help_entries() {
	$table = array();

	$table[ __( 'Elevation Profiles', 'extensions-leaflet-map' ) ] = array(
		array(
			'function'  => __( 'Track with an elevation profile', 'extensions-leaflet-map' ),
			'shortcode' => 'elevation',
			'tab'       => 'elevation',
			'plugins'   => '<a href="https://github.com/Raruto/leaflet-elevation">leaflet-elevation</a>,
      <a href="https://github.com/yohanboniface/Leaflet.i18n">Leaflet.i18n</a>',
			'doku'      => '/doku/elevation/',
			'moredoku'  => array(
				array(
					'function' => __( 'Customize waypoints', 'extensions-leaflet-map' ),
					'doku'     => '/doku/elevationwaypoints/',
				),
			),
			'kategorie' => 'elevation',
			'examples'  => '',
		),
		array(
			'function'  => __( 'Multiple tracks with elevation profiles', 'extensions-leaflet-map' ),
			'shortcode' => 'elevation-track, elevation-tracks, multielevation',
			'tab'       => 'multielevation',
			'plugins'   => '<a href="https://github.com/Raruto/leaflet-elevation">leaflet-elevation</a>,
      <a href="https://github.com/makinacorpus/Leaflet.GeometryUtil">Leaflet.GeometryUtil</a>',
			'doku'      => '/doku/multielevation/',
			'kategorie' => 'multielevation',
			'examples'  => '',
		),
		array(
			'function'  => __( 'Migration from', 'extensions-leaflet-map' ) . ' <a href="https://wordpress.org/plugins/wp-gpx-maps/">WP GPX Maps</a>',
			'shortcode' => 'sgpx',
			'tab'       => 'sgpxelevation',
			'plugins'   => '',
			'doku'      => '/doku/sgpxelevation/',
			'kategorie' => '',
			'examples'  => '/extra/category/sgpx/',
		),
	);

	$table[ __( 'Functions for Markers implemented with Leaflet Plugins', 'extensions-leaflet-map' ) ] = array(
		'menu' => __( 'Marker and Icons', 'extensions-leaflet-map' ),
		array(
			'function'  => __( 'Marker Clustering', 'extensions-leaflet-map' ),
			'shortcode' => 'cluster',
			'tab'       => 'markercluster',
			'plugins'   => '<a href="https://github.com/Leaflet/Leaflet.markercluster">Leaflet.markercluster</a>',
			'doku'      => '/doku/markercluster/',
			'kategorie' => 'cluster',
			'examples'  => '',
			'strpos'    => 'marker',
		),
		array(
			'function'  => __( 'Clustering and Grouping of Markers', 'extensions-leaflet-map' ),
			'shortcode' => 'markerclustergroup',
			'tab'       => 'markerclustergroup',
			'plugins'   => '<a href="https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup">Leaflet.FeatureGroup.SubGroup</a>',
			'doku'      => '/doku/markerclustergroup/',
			'kategorie' => '',
			'examples'  => '/grouping/markerclustergroup/',
			'strpos'    => 'marker',
		),
		array(
			'function'  => __( 'Styling Markercluster', 'extensions-leaflet-map' ),
			'shortcode' => 'placementstrategies',
			'tab'       => 'markerclusterplacementstrategies',
			'plugins'   => '<a href="https://github.com/adammertel/Leaflet.MarkerCluster.PlacementStrategies">Leaflet.MarkerCluster.PlacementStrategies</a>',
			'doku'      => '/doku/placementstrategies/',
			'kategorie' => '',
			'examples'  => '/cluster/placementstrategies/',
			'strpos'    => 'marker',
		),
		array(
			'function'  => 'Awesome Icons',
			'shortcode' => 'leaflet-extramarker',
			'tab'       => 'extramarker',
			'plugins'   => '<a href="https://github.com/coryasilva/Leaflet.ExtraMarkers">Leaflet.ExtraMarkers</a>',
			'doku'      => '/doku/extramarker/',
			'kategorie' => '',
			'examples'  => '',
			'strpos'    => 'marker',
		),
		array(
			'function'  => __( 'Design and Group markers from geojson files', 'extensions-leaflet-map' ),
			'shortcode' => 'geojsonmarker',
			'tab'       => 'geojsonmarker',
			'plugins'   => '',
			'doku'      => '/doku/geojsonmarker/',
			'kategorie' => '',
			'examples'  => '/marker/geojsonmarker/',
			'strpos'    => 'marker',
		),
	);

	$table[ __( 'More Functions for Markers', 'extensions-leaflet-map' ) ] = array(
		'menu' => __( 'More for Markers', 'extensions-leaflet-map' ),
		array(
			'function'  => __( 'Overview Map', 'extensions-leaflet-map' ),
			'shortcode' => 'overviewmap',
			'tab'       => 'overviewmap',
			'plugins'   => '',
			'doku'      => '/doku/overviewmap/',
			'kategorie' => '',
			'examples'  => '/extra/category/overviewmap/',
			'strpos'    => 'marker',
		),
		array(
			'function'  => __( 'Target Marker', 'extensions-leaflet-map' ),
			'shortcode' => 'targetmarker',
			'tab'       => 'targetmarker',
			'plugins'   => '',
			'doku'      => '/doku/targetmarker/',
			'kategorie' => '',
			'examples'  => '/examples/targetmarker/',
			'strpos'    => 'marker',
		),
		array(
			'function'  => __( 'Hide Markers', 'extensions-leaflet-map' ),
			'shortcode' => 'hidemarkers',
			'tab'       => 'hidemarkers',
			'plugins'   => '',
			'doku'      => '/doku/hidemarkers/',
			'kategorie' => '',
			'examples'  => '',
			'strpos'    => 'marker',
		),
	);

	$table[ __( 'Functions implemented with Leaflet Plugins', 'extensions-leaflet-map' ) ] = array(
		'menu' => __( 'Leaflet Plugins', 'extensions-leaflet-map' ),
		array(
			'function'  => __( 'Grouping by options and features', 'extensions-leaflet-map' ),
			'shortcode' => 'leaflet-optiongroup, leaflet-featuregroup, parentgroup',
			'tab'       => 'featuregroup',
			'plugins'   => '<a href="https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup">Leaflet.FeatureGroup.SubGroup</a>',
			'doku'      => '/doku/featuregroup/',
			'kategorie' => 'grouping',
			'examples'  => '',
		),
		array(
			'function'  => __( 'Search markers/features', 'extensions-leaflet-map' ),
			'shortcode' => 'leaflet-search',
			'tab'       => 'leafletsearch',
			'plugins'   => '<a href="https://github.com/stefanocudini/leaflet-search">Leaflet Control Search</a>',
			'doku'      => '/doku/leafletsearch/',
			'kategorie' => 'leafletsearch',
			'examples'  => '',
		),
		array(
			'function'  => __( 'Switching Tile Servers', 'extensions-leaflet-map' ),
			'shortcode' => 'layerswitch',
			'tab'       => 'tiles',
			'plugins'   => '<a href="https://leafletjs.com/examples/layers-control/">L.control.layers</a>,
      <a href="https://github.com/leaflet-extras/leaflet-providers">Leaflet-providers</a>,
      <a href="https://github.com/dayjournal/Leaflet.Control.Opacity">Leaflet.Control.Opacity</a>',
			'doku'      => '/doku/tileshelp/',
			'moredoku'  => array(
				array(
					'function' => __( 'Leaflet-providers', 'extensions-leaflet-map' ),
					'doku'     => '/doku/tilesproviders/',
				),
				array(
					'function' => __( 'Extra Tile Server', 'extensions-leaflet-map' ),
					'doku'     => '/doku/tileswitch/',
				),
			),
			'kategorie' => '',
			'examples'  => '',
		),
		array(
			'function'  => __( 'Reset the map', 'extensions-leaflet-map' ),
			'shortcode' => 'zoomhomemap',
			'tab'       => 'zoomhome',
			'plugins'   => '<a href="https://github.com/torfsen/leaflet.zoomhome">leaflet.zoomhome</a>',
			'doku'      => '/doku/zoomhome/',
			'kategorie' => '',
			'examples'  => '/zoomhome/zoomhome/',
		),
		array(
			'function'  => __( 'Fullscreen', 'extensions-leaflet-map' ),
			'shortcode' => 'fullscreen',
			'tab'       => 'fullscreen',
			'plugins'   => '<a href="https://github.com/brunob/leaflet.fullscreen">leaflet.fullscreen</a>',
			'doku'      => '/doku/fullscreen/',
			'kategorie' => '',
			'examples'  => '',
		),
		array(
			'function'  => __( 'Gesture Handling', 'extensions-leaflet-map' ),
			'shortcode' => 'gestures',
			'tab'       => 'gesture',
			'plugins'   => '<a href="https://github.com/Raruto/leaflet-gesture-handling">Leaflet.GestureHandling</a>',
			'doku'      => '/doku/gesture/',
			'kategorie' => '',
			'examples'  => '',
		),
		array(
			'function'  => __( 'Leaflet Choropleth', 'extensions-leaflet-map' ),
			'shortcode' => 'choropleth',
			'tab'       => 'choropleth',
			'plugins'   => '<a href="https://github.com/timwis/leaflet-choropleth">Leaflet Choropleth</a>',
			'doku'      => '/doku/choropleth/',
			'kategorie' => '',
			'examples'  => '',
		),
	);

	$table[ __( 'Hovering', 'extensions-leaflet-map' ) ] = array(
		array(
			'function'  => __( 'Hovering and Tooltips', 'extensions-leaflet-map' ),
			'shortcode' => 'hover',
			'tab'       => 'hover',
			'plugins'   => '',
			'doku'      => '/doku/hover/',
			'kategorie' => 'hover',
			'examples'  => '',
		),
		array(
			'function'  => __( 'Hovering overlapping elements', 'extensions-leaflet-map' ),
			'shortcode' => 'hoverlap',
			'tab'       => 'hoverlap',
			'plugins'   => '<a href="https://github.com/Turfjs/turf">Turf</a>, <a href="https://github.com/makinacorpus/Leaflet.GeometryUtil">Leaflet.GeometryUtil</a>',
			'doku'      => '/doku/hoverlap/',
			'kategorie' => 'hoverlap',
			'examples'  => '',
		),
	);

	$table[ __( 'Files for Leaflet Map', 'extensions-leaflet-map' ) ] = array(
		array(
			'function'  => __( 'Files for Leaflet Map', 'extensions-leaflet-map' ),
			'shortcode' => __( 'Files for Leaflet Map', 'extensions-leaflet-map' ),
			'tab'       => 'filemgr' . ( current_user_can( 'manage_options' ) ? '' : '-list' ),
			'plugins'   => '',
			'doku'      => '/doku/filemgr/',
			'kategorie' => '',
			'examples'  => '',
		),
		array(
			'function'  => __( 'Tracks from all files in a directory', 'extensions-leaflet-map' ),
			'shortcode' => 'leaflet-directory',
			'tab'       => 'filemgr-dir',
			'plugins'   => '',
			'doku'      => '/doku/filemgr/',
			'kategorie' => 'directory',
			'examples'  => '',
		),
	);
	return $table;
}

function leafext_plugins() {
	$plugins   = array();
	$plugins[] = array(
		'name'      => 'leaflet-elevation',
		'desc'      => __( 'A Leaflet plugin that allows to add elevation profiles using d3js.', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/Raruto/leaflet-elevation',
		'shortcode' => 'elevation, multielevation, elevation-track, elevation-tracks',
	);
	$plugins[] = array(
		'name'      => 'Leaflet.GeometryUtil',
		'desc'      => '',
		'link'      => 'https://github.com/makinacorpus/Leaflet.GeometryUtil',
		'shortcode' => '',
	);
	$plugins[] = array(
		'name'      => 'Leaflet.i18n',
		'desc'      => __( 'Internationalisation module for Leaflet plugins.', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/yohanboniface/Leaflet.i18n',
		'shortcode' => '',
	);
	$plugins[] = array(
		'name'      => 'leaflet-rotate',
		'desc'      => __( 'A Leaflet plugin that allows to add rotation functionality to map tiles', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/Raruto/leaflet-rotate',
		'shortcode' => '',
	);
	$plugins[] = array(
		'name'      => 'Leaflet.AlmostOver',
		'desc'      => __( 'This plugin allows to detect mouse click and overing events on lines, with a tolerance distance.', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/makinacorpus/Leaflet.AlmostOver',
		'shortcode' => '',
	);
	$plugins[] = array(
		'name'      => '@tmcw/togeojson',
		'desc'      => __( 'Convert KML, GPX, and TCX to GeoJSON.', 'extensions-leaflet-map' ),
		'link'      => 'https://www.npmjs.com/package/@tmcw/togeojson',
		'shortcode' => '',
	);
	$plugins[] = array(
		'name'      => 'd3js',
		'desc'      => 'Data-Driven Documents',
		'link'      => 'https://github.com/d3/d3',
		'shortcode' => '',
	);
	$plugins[] = array(
		'name'      => 'Leaflet-providers',
		'desc'      => __( 'An extension that contains configurations for various tile providers.', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/leaflet-extras/leaflet-providers',
		'shortcode' => 'layerswitch',
	);
	$plugins[] = array(
		'name'      => 'Leaflet.Control.Opacity',
		'desc'      => __( 'Makes multiple tile layers transparent.', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/dayjournal/Leaflet.Control.Opacity',
		'shortcode' => 'layerswitch',
	);
	$plugins[] = array(
		'name'      => 'Leaflet.markercluster',
		'desc'      => __( 'Provides Beautiful Animated Marker Clustering functionality.', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/Leaflet/Leaflet.markercluster',
		'shortcode' => 'cluster',
	);
	$plugins[] = array(
		'name'      => 'Leaflet.MarkerCluster.PlacementStrategies',
		'desc'      => __( 'Styling Markerclusters.', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/adammertel/Leaflet.MarkerCluster.PlacementStrategies',
		'shortcode' => 'placementstrategies',
	);
	$plugins[] = array(
		'name'      => 'Leaflet.ExtraMarkers',
		'desc'      => __( 'Shameless copy of Awesome-Markers with more shapes and colors.', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/coryasilva/Leaflet.ExtraMarkers',
		'shortcode' => 'leaflet-extramarker',
	);
	$plugins[] = array(
		'name'      => 'Leaflet.FeatureGroup.SubGroup',
		'desc'      => __( 'Grouping of Leaflet elements by options and features.', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup',
		'shortcode' => 'leaflet-optiongroup, leaflet-featuregroup, parentgroup',
	);
	$plugins[] = array(
		'name'      => 'Leaflet Control Search',
		'desc'      => __( 'Search Markers/Features location by option or custom property.', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/stefanocudini/leaflet-search',
		'shortcode' => 'leaflet-search',
	);
	$plugins[] = array(
		'name'      => 'leaflet-choropleth',
		'desc'      => __( 'Choropleth plugin for Leaflet (color scale based on value).', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/timwis/leaflet-choropleth',
		'shortcode' => 'choropleth',
	);
	$plugins[] = array(
		'name'      => 'leaflet.zoomhome',
		'desc'      => sprintf( __( 'Provides a zoom control with a %1$sHome%2$s button to reset the view.', 'extensions-leaflet-map' ), '&quot;', '&quot;' ),
		'link'      => 'https://github.com/torfsen/leaflet.zoomhome',
		'shortcode' => 'zoomhomemap',
	);
	$plugins[] = array(
		'name'      => 'leaflet.fullscreen',
		'desc'      => __( 'Simple plugin for Leaflet that adds fullscreen button to your maps.', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/brunob/leaflet.fullscreen',
		'shortcode' => 'fullscreen',
	);
	$plugins[] = array(
		'name'      => 'Leaflet.GestureHandling',
		'desc'      => __( 'A Leaflet plugin that allows to prevent default map scroll/touch behaviours.', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/Raruto/leaflet-gesture-handling',
		'shortcode' => 'gestures',
	);
	$plugins[] = array(
		'name'      => 'turf',
		'desc'      => __( 'Advanced geospatial analysis for browsers and Node.js', 'extensions-leaflet-map' ),
		'link'      => 'https://github.com/Turfjs/turf',
		'shortcode' => '',
	);
	return $plugins;
}

function leafext_help_table( $leafext_plugin_name = '' ) {
	$header     = '<h3>' .
	__( 'Found an issue? Do you have a question?', 'extensions-leaflet-map' ) . '</h3>
	<p>' .
	__( 'Post it to the support forum', 'extensions-leaflet-map' ) .
	': <a href="https://wordpress.org/support/plugin/extensions-leaflet-map/" target="_blank">Extensions for Leaflet Map</a></p>';
		$header = $header . '<h3>' .
	__( 'Documentation', 'extensions-leaflet-map' ) . '</h3><p>';
	$header     = $header .
	sprintf(
		__(
			'Detailed documentation and examples in %1$sGerman%2$s and %3$sEnglish%4$s',
			'extensions-leaflet-map'
		),
		'<a href="https://leafext.de/">',
		'</a>',
		'<a href="https://leafext.de/en/">',
		'</a>'
	);
	$header     = $header . '.</p>';

	if ( is_singular() || is_archive() ) {
		$style = '<style>td,th { border:1px solid #195b7a !important; }</style>';
	} else {
		$style = '<style>tr:nth-child(even) { background-color: #fcfcfc; }</style>';
	}

	$text = '<p><figure class="wp-block-table aligncenter is-style-stripes">
	<table class="form-table" border="1">';

	$text = $text . '<thead><tr><th style="text-align:center">' . __( 'Function', 'extensions-leaflet-map' ) . '</th>' .
	'<th style="text-align:center">Shortcode(s) / ' . __( 'Function', 'extensions-leaflet-map' ) . '</th>';
	if ( is_singular() || is_archive() ) {
		$text = $text . '<th style="text-align:center">&nbsp;</th>';
	}
	$text = $text . '</tr></thead><tbody>';

	$table = leafext_help_entries();

	if ( get_locale() != 'de_DE' ) {
		$en = '/en';
	} else {
		$en = '';
	}

	foreach ( $table as $key => $entries ) {
		$text = $text . '<tr><td colspan="3" style="text-align:center"><b>' . $key . '</b></td></tr>';
		foreach ( $entries as $entry ) {
			if ( is_array( $entry ) ) {
				$text = $text . '<tr><td>' . $entry['function'] . '</td>';
				if ( is_singular() || is_archive() ) {
					$text = $text . '<td><a href="' . $entry['doku'] . '">' . $entry['shortcode'] . '</a></td>';
					if ( $entry['kategorie'] != '' ) {
						$text = $text . '<td><a href="/examples/' . $entry['kategorie'] . '/">' . __( 'Examples', 'extensions-leaflet-map' ) . '</a></td>';
					} elseif ( $entry['examples'] != '' ) {
						$text = $text . '<td><a href="' . ( str_starts_with( $entry['examples'], '/extra' ) ? '' : $en ) . $entry['examples'] . '">' . __( 'Examples', 'extensions-leaflet-map' ) . '</a></td>';
					} else {
						$text = $text . '<td>&nbsp;</td>';
					}
				} else {
					$text = $text . '<td><a href="?page=' . $leafext_plugin_name . '&tab=' . $entry['tab'] . '">' . $entry['shortcode'] . '</a></td>';
				}
				$text = $text . '</tr>';
			}
		}
	}

	$text = $text . '</tbody></table></figure></p>';

	$text = $text .
	'<h2 id="leaflet-plugins">' . __( 'Included and used Leaflet Plugins', 'extensions-leaflet-map' ) . '</h2>';
	include_once LEAFEXT_PLUGIN_DIR . '../extensions-leaflet-map-github/admin/help.php';

	$plugins = leafext_plugins();
	$text    = $text . '<h3>Shortcodes</h3>';
	$text    = $text . '<ul>';
	foreach ( $plugins as $plugin ) {
		if ( $plugin['shortcode'] != '' ) {
			$text = $text . '<li><a href="' . $plugin['link'] . '">' . $plugin['name'] . '</a> - ' . $plugin['desc'] . ' (' . $plugin['shortcode'] . ')</li>';
		}
	}
	$text = $text . '</ul>';
	$text = $text . '<h3>' . __( 'Helper Plugins', 'extensions-leaflet-map' ) . '</h3>';
	$text = $text . '<ul>';
	foreach ( $plugins as $plugin ) {
		if ( $plugin['shortcode'] == '' ) {
			$text = $text . '<li><a href="' . $plugin['link'] . '">' . $plugin['name'] . '</a> - ' . $plugin['desc'] . '</li>';
		}
	}
	$text = $text . '</ul>';

	$ende = '<p>' . __( 'You may be interested in', 'extensions-leaflet-map' ) .
	' <a href="https://github.com/hupe13/leafext-dsgvo">DSGVO/GDPR Snippet for Extensions for Leaflet Map</a>.</p>';

	if ( is_singular() || is_archive() ) {
		return $style . $text;
	} else {
		leafext_escape_output( $header );
		// phpcs:ignore
		echo $style;
		leafext_escape_output( $text . $ende );
	}
}
