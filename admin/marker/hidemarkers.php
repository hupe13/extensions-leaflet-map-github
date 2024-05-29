<?php
/**
 * Help for hidemarkers
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_help_hidemarkers() {
	if ( is_singular() || is_archive() ) {
		$codestyle = '';
	} else {
		leafext_enqueue_admin();
		$codestyle = ' class="language-coffeescript"';
	}
	$text = '<h3>' . __( 'Hide Markers', 'extensions-leaflet-map' ) . '</h3>
	<p>' . __( 'If a GPX track loaded with leaflet-gpx contains waypoints that you do not want to display', 'extensions-leaflet-map' ) . '.</p>
	<pre' . $codestyle . '><code' . $codestyle . '>[leaflet-map ...]
[leaflet-gpx src="//url/to/file.gpx" ... ]
[hidemarkers]</code></pre>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}
