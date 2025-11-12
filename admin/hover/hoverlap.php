<?php
/**
 * Help for hoverlap functions
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_help_hoverlap() {
	if ( is_singular() || is_archive() ) {
		$codestyle = '';
		$text      = '';
	} else {
		leafext_enqueue_admin();
		$codestyle = ' class="language-coffeescript"';
		$text      = '<h2 id="hover">' . __( 'Popups and highlighting overlapping elements', 'extensions-leaflet-map' ) . '</h2>';
	}

	$text = $text . '<h2>Shortcode</h2>';
	$text = $text . '<h4>' . __( 'Create Map', 'extensions-leaflet-map' ) . '</h4>';
	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-map ...]</code></pre>';
	$text = $text . '<h4>' . wp_sprintf(
		/* translators: %s is a shortcode. */
		__( 'Load any elements with any %s shortcode.', 'extensions-leaflet-map' ),
		'leaflet-*'
	) . '</h4>';

	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>// some, but not ovarlapping with other markers
&#091;leaflet-marker ...]...&#091;/leaflet-marker]
&#091;leaflet-extramarker ...]...&#091;/leaflet-extramarker]
// any many
&#091;leaflet-geojson ...]{name}&#091;/leaflet-geojson]
&#091;leaflet-gpx ...]{name}&#091;/leaflet-gpx]
&#091;leaflet-kml ...]{name}&#091;/leaflet-kml]
&#091;leaflet-polygon ...]Polygon ...&#091;/leaflet-polygon]
&#091;leaflet-circle ...]Circle ...&#091;/leaflet-circle]
&#091;leaflet-line ...]Line ...&#091;/leaflet-line]</code></pre>';

	$text = $text . '<h4>' . __( 'And hoverlap', 'extensions-leaflet-map' ) . '</h4>';
	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>&#091;hoverlap]</code></pre>';
	$text = $text . '<h3>' . __( 'Options', 'extensions-leaflet-map' ) . '</h3>';

	$text = $text . '<p>' . wp_sprintf(
		/* translators: %s are options / shortcode. */
		__( 'The options %1$s and %2$s are the same as for the %3$s shortcode.', 'extensions-leaflet-map' ),
		'<code>exclude</code>, <code>tolerance</code>',
		'<code>class</code>',
		'<code>hover</code>'
	);
	$text = $text . '<br>' . wp_sprintf(
		/* translators: %1$s is a shortcode and %2$s is a link. */
		__( 'For overlapping markers see %1$s or %2$s.', 'extensions-leaflet-map' ),
		'<code>&#091;cluster]</code>',
		'<a href="https://leafext.de/extra/spiderfier/">Overlapping Marker Spiderfier for Leaflet</a>'
	)
	. '</p>';

	$text = $text .
	'<h3>Development</h3><p>' .
	__( 'All this is not ready yet.', 'extensions-leaflet-map' ) .
	'<ul><li>' .
	__( 'How to deal with the sometimes large contents of popups?', 'extensions-leaflet-map' ) .
	'<li>' .
	__( 'Geojson GeometryCollections do not (yet) work in all senses.', 'extensions-leaflet-map' ) .
	'<li>' .
	__( 'and maybe other things.', 'extensions-leaflet-map' ) .
	'</ul>' .
	__( 'I am glad about (code-)suggestions.', 'extensions-leaflet-map' ) .
	'</p>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}
// leafext_help_hoverlap();
