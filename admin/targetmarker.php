<?php
/**
 * Help for targetmarker shortcode
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_targetmarker_help() {
	if ( is_singular() || is_archive() ) {
		$text = '';
	} else {
		$text = '<h2>' . __( 'Target Marker', 'extensions-leaflet-map' ) . '</h2>';
	}
	$text = $text . '<p>' . __( 'Jump to a position in a map with many markers and get the nearest marker', 'extensions-leaflet-map' ) . '</p>';

	$text = $text . '<h3>' . __( 'Preparation', 'extensions-leaflet-map' ) . '</h3>';

	$text = $text . '<ul><li>' . __( 'Create a source page / post with a link to the target page / post.', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '<li>' . __( 'The parameters (querystring) from this link should be:', 'extensions-leaflet-map' ) .
	'<code> ?lat=...&lng=...</code></li>';
	$text = $text . '<li>' . __( 'Create a target page / post with a map and many markers.', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '<li>' . __( 'Put the shortcode on the target page / post.', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '</ul>';

	$text = $text . '<h3>Shortcode</h3>';
	$text = $text . '<ul><li><code>fitbounds, zoomhomemap, targetmarker</code> ' . __( 'are mandandory!', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '<li>' . __( 'The order of the shortcodes is fixed!', 'extensions-leaflet-map' );
	$text = $text . ' <code>cluster - zoomhomemap - targetmarker</code>.</li></ul>';
	$text = $text . '<pre><code>&#091;leaflet-map fitbounds]
// many any
&#091;leaflet-marker ....]
&#091;leaflet-extramarker ....]
// or one
&#091;leaflet-geojson ....]
// optional
&#091;cluster ...]
// required
&#091;zoomhomemap]
&#091;targetmarker ....]</code></pre>';

	$text = $text . '<h3>' . __( 'Options', 'extensions-leaflet-map' ) . '</h3>';

		$text = $text . '<ul>
		<li>' .
		'lat, lng - ' . __( 'Latitude and Longitude taken from URL parameters, e.g.', 'extensions-leaflet-map' ) . ': <code>' . get_site_url() . '/targetpage/?lat=...&lng=...</code>'
		. '</li>
		<li>' .
		'popup - ' . __( 'popup content, if the target marker has not a popup. Default:', 'extensions-leaflet-map' ) . ' "Target"'
		. '</li>
		<li>' .
		'zoom - ' . __( 'valid if the target marker is not clustered. zoom level to zoom to the target marker. Default: zoom level if map is ready.', 'extensions-leaflet-map' )
		. '</li>
		<li>' .
		'debug - ' . __( 'if true, log some infos to the developer console and add circles about the target on the map.', 'extensions-leaflet-map' ) .
		'</li></ul>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		leafext_escape_output( $text );
	}
}
