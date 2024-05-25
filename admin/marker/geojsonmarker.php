<?php
/**
 * Help for geojsonmarker
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_help_geojsonmarker() {
	if ( is_singular() || is_archive() ) {
		$codestyle = '';
		$server    = map_deep( wp_unslash( $_SERVER ), 'sanitize_text_field' );
		if ( strpos( $server['REQUEST_URI'], '/en/' ) !== false ) {
			$lang = '/en';
		} else {
			$lang = '';
		}
		$featuregroup = $lang . '/doku/featuregroup/';
		$cluster      = $lang . '/doku/markercluster/';
		$extramarker  = $lang . '/doku/extramarker/';
	} else {
		leafext_enqueue_admin();
		// $codestyle    = ' class="language-coffeescript"';
		$codestyle    = '';
		$featuregroup = '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=featuregroup';
		$cluster      = '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=markercluster';
		$extramarker  = '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=extramarker';
	}

	$text = '<h3>' . __(
		'Design and group markers from geojson files according to their properties',
		'extensions-leaflet-map'
	) . '</h3>';
	$text = $text . '<p>';
	/* translators: %s is a feature. */
	$text = $text . sprintf( __( 'A %s in a geojson file is specified like this:', 'extensions-leaflet-map' ), '"Point"' );
	$text = $text . '<pre>{
  "type": "Feature",
  "geometry": {
    "type": "Point",
    "coordinates": [lat, lng]
  },
  "properties": {
    "<span style="color: #d63638">property</span>": "value"
    ....
  }
}</pre></p>';

	$text = $text . '<h3>Shortcode</h3>';
	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>&#091;geojsonmarker property=<span style="color: #d63638">property</span> values=... iconprops=... icondefault=<span style="color: #4f94d4">blue</span> groups=... visible=... <i>cluster-options</i>]</code></pre>';

	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>&#091;geojsonmarker property=<span style="color: #d63638">property</span> auto <i>cluster-options</i>]</code></pre>';

	$text = $text . '<h3>circleMarker</h3>';
	$text = $text . sprintf(
		/* translators: %s are shortcodes / options. */
		__( 'If you want to use CircleMarker you must specify %1$s and optional %2$s and %3$s in %4$s shortcode.', 'extensions-leaflet-map' ),
		'<code>circlemarker</code>',
		'<code>color</code>',
		'<code>radius</code>',
		'<code>leaflet-geojson</code>'
	);
	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-geojson src="//url/to/file.geojson" circleMarker color=<span style="color: #4f94d4">blue</span> radius=...]
&#091;geojsonmarker property=<span style="color: #d63638">property</span> icondefault=<span style="color: #4f94d4">blue</span> ...]</code></pre>';

	$text = $text . '<h3>' . __( 'Marker with icon', 'extensions-leaflet-map' ) . '</h3>';

	$text = $text . sprintf(
		/* translators: %s are options. */
		__( 'The markers should have the same %1$s, only %2$s differs.', 'extensions-leaflet-map' ),
		'<code>iconsize</code>, <code>iconanchor</code>, <code>popupanchor</code>, <code>tooltipanchor</code>, <code>shadowurl</code>, <code>shadowsize</code>, <code>shadowanchor</code>',
		'<code>iconurl</code>'
	);
	$text = $text . ' ';
	$text = $text . sprintf(
		/* translators: %s are options. */
		__( 'The substring %1$s will be replaced in the path of the %2$s with any of %3$s.', 'extensions-leaflet-map' ),
		'<code>icondefault</code>',
		'<code>iconurl</code>',
		'<code>iconprops</code>'
	)
	. '<pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-geojson src="//url/to/file.geojson" iconurl="//url/to/marker-icon-<span style="color: #4f94d4">blue</span>.png" iconsize=... iconanchor="..,.." popupanchor="..,.."  tooltipanchor="..,.." shadowurl="//url/to/marker-shadow.png" shadowsize=... shadowanchor=...]
&#091;geojsonmarker property=<span style="color: #d63638">property</span> values=... iconprops=... icondefault=<span style="color: #4f94d4">blue</span> ...]</code></pre>';

	$text = $text . '<h3>Extramarker</h3>';
	$text = $text . sprintf(
		/* translators: %s is a link. */
		__( 'If you want to use ExtraMarkers you can specify %s options.', 'extensions-leaflet-map' ),
		'<a href="' . $extramarker . '">leaflet-extramarker</a>'
	);
	$text = $text . ' ';

	$text = $text . sprintf(
		/* translators: %s are options. */
		__( 'For now, %1$s are mapped to %2$s. Maybe there are other options.', 'extensions-leaflet-map' ),
		'<code>values</code>',
		'<code>markerColor</code>'
	);

	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-map fitbounds ...]
&#091;leaflet-geojson src="//url/to/file.geojson"]
&#091;geojsonmarker property=<span style="color: #d63638">property</span> icondefault=<span style="color: #4f94d4">blue</span> markerColor=<span style="color: #4f94d4">blue</span> <i>extramarker-options</i>] ...</code></pre>';

	$text = $text . '<h3>Options</h3>';
	$text = $text . '<ul><li><code>property</code> - ' . __( 'required', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '<li><code>values</code><ul>';
	$text = $text . '<li> ' . __( 'comma separated strings of property values', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '<li> ' . __( 'if not specified collect values from property', 'extensions-leaflet-map' ) . '</li>';
	/* translators: %s is an option. */
	$text = $text . '<li> ' . sprintf( __( 'required for markers with %s', 'extensions-leaflet-map' ), '<code>iconurl</code>' ) . '</li>';
	$text = $text . '<li> ' . sprintf(
		/* translators: %s is a shortcode. */
		__( 'required if you want to group like with %s', 'extensions-leaflet-map' ),
		'<a href="' . $featuregroup . '"><code>leaflet-featuregroup</code></a>'
	);
	$text = $text . '</li></ul></li>';

	$text = $text . '<li><code>iconprops</code>';
	/* translators: %s is an option. */
	$text = $text . '<ul><li> ' . sprintf( __( 'comma separated colors for marker or substrings in %s for marker to distinguish the values', 'extensions-leaflet-map' ), '<code>iconurl</code>' ) . '</li>';
	/* translators: %s is an option. */
	$text = $text . '<li> ' . sprintf( __( 'required for markers with %s', 'extensions-leaflet-map' ), '<code>iconurl</code>' ) . '</li>';
	$text = $text . '<li> ' . __( 'if not specified colors are generated', 'extensions-leaflet-map' );
	$text = $text . ' ';
	/* translators: %s are options / values. */
	$text = $text . sprintf( __( '(max 16 %1$s for %2$s, 14 for %3$s)', 'extensions-leaflet-map' ), '<code>values</code>', 'circleMarker', 'ExtraMarker' ) . '</li>';
	/* translators: %s is an option. */
	$text = $text . '<li> ' . sprintf( __( 'if specified the count must match the count of %s', 'extensions-leaflet-map' ), '<code>values</code>' ) . '</li>';
	$text = $text . '</ul></li>';
	$text = $text . '<li><code>icondefault</code> - ';
	$text = $text . __( 'default color', 'extensions-leaflet-map' ) .
	' (<span style="color: #4f94d4">blue</span>), '
	. __( 'resp. substring of ', 'extensions-leaflet-map' ) . '<code>iconurl</code></li>';
	$text = $text . '<li><code>groups</code> - ' . sprintf(
		/* translators: %s is a shortcode. */
		__( 'required if you want to group like with %s', 'extensions-leaflet-map' ),
		'<a href="' . $featuregroup . '"><code>leaflet-featuregroup</code></a>'
	) . '</li>';
	$text = $text . '<li> <code>visible</code> - ' . __( 'for grouping', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '<li> <code>auto</code> <ul>';
	$text = $text . '<li> ' . sprintf(
		/* translators: %s are options. */
		__( 'automatic generation of %1$s, %2$s (color) and %3$s', 'extensions-leaflet-map' ),
		'<code>values</code>',
		'<code>iconprops</code>',
		'<code>groups</code>'
	) . '</li>';
	$text = $text . '<li>' . __( 'not for markers with iconUrl', 'extensions-leaflet-map' ) . '</li>';
	/* translators: %s are options / values. */
	$text = $text . '<li>' . sprintf( __( 'no more than 16 different %1$s for %2$s', 'extensions-leaflet-map' ), '<code>values</code>', 'circleMarker' ) . '</li>';
	/* translators: %s are options / values. */
	$text = $text . '<li>' . sprintf( __( 'no more than 14 different %1$s for %2$s', 'extensions-leaflet-map' ), '<code>values</code>', 'ExtraMarker' ) . '</li>';
	$text = $text . '</ul></li>';
	$text = $text . '<li>' . sprintf(
		/* translators: %s is a shortcode. */
		__( 'The markers are clustered. Optional you can specify options from %s.', 'extensions-leaflet-map' ),
		'<a href="' . $cluster . '"><code>cluster</code></a>'
	) . '</li>';
	$text = $text . '<li>' . sprintf(
		/* translators: %s is an option. */
		__( 'optional: %s - position of group control', 'extensions-leaflet-map' ),
		'<code>position</code>'
	) . ': topleft, topright, bottomleft or bottomright.
</li>';
	$text = $text . '<li>' . sprintf(
	/* translators: %s is an option. */
		__( 'optional: %s - group control collapsed or not:', 'extensions-leaflet-map' ),
		'<code>collapsed</code>'
	) . ' true or false.
</li>';
	$text = $text . '</ul>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}
