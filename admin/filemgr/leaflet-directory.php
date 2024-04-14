<?php
/**
 * Help page for leafext-directory
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_directory_help_text() {
	$text = '<h3>' . __( 'Tracks from all files in a directory', 'extensions-leaflet-map' ) . '</h3>' . "\n";
	$text = $text . '<pre><code>&#091;leaflet-map fitbounds]' . "\n";
	$text = $text . '&#091;leaflet-directory url="..." src="..." elevation]' . "\n";
	$text = $text . '&#091;multielevation]</code></pre>';

	$text = $text . '<pre><code>&#091;leaflet-map fitbounds]' . "\n";
	$text = $text . '&#091;leaflet-directory url="..." src="..." leaflet type="..." start]</code></pre>';

	$text = $text . '<ul>
  <li> url - ' . __( 'url to directory, default: URL from upload directory.', 'extensions-leaflet-map' ) . '</li>
  <li> src - ' .
	__( '(relative) path to directory, accessible both from path and from url', 'extensions-leaflet-map' ) . '</li>
  <li> elevation - ' .
	sprintf(
		/* translators: %s is a shortcode. */
		__( '(default) prepare the tracks for %s', 'extensions-leaflet-map' ),
		' <code>multielevation</code>'
	) . '</li>
  <li> leaflet - ' .
	sprintf(
		/* translators: %s is a shortcode. */
		__( 'draw the content with %scommands ', 'extensions-leaflet-map' ),
		'<code>leaflet-</code>'
	) . '</li>
  <li> type - ' .
	sprintf(
		/* translators: %s are options / values. */
		__( 'For %1$s it is ignored. For %2$s a list of %3$s. Default is %4$s.', 'extensions-leaflet-map' ),
		'"elevation"',
		'"leaflet"',
		'gpx,kml,geojson,json',
		'"gpx"'
	) . '</li>
  <li> start - (optional). ' .
	sprintf(
		/* translators: %s is an option. */
		__( 'If %s and a file is a gpx file, display start point and cluster', 'extensions-leaflet-map' ),
		'"leaflet"'
	) . '.</li>
  </ul>';
	if ( is_singular() || is_archive() ) {
		$noop = true; // phpcs
	} else {
		$text = $text . '<p>' . sprintf(
			/* translators: %s is a href. */
			__( 'Use the %1$sdirectory listing%2$s for simplicity.', 'extensions-leaflet-map' ),
			'<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=filemgr-list">',
			'</a>'
		) . '</p>';
	}
	return $text;
}
