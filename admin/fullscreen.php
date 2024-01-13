<?php
/**
 * Help for fullscreen shortcode
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_help_fullscreen() {
	$text = '<h3>' . __( 'Fullscreen', 'extensions-leaflet-map' ) . '</h3>
	<img src="' . LEAFEXT_PLUGIN_PICTS . 'fullscreenon.png" alt="fullscreen-on">
	<img src="' . LEAFEXT_PLUGIN_PICTS . 'fullscreenoff.png" alt="fullscreen-off">
	<pre><code>[fullscreen]</code></pre>
	<pre><code>[fullscreen position=topleft|topright|bottomleft|bottomright]</code></pre>
	default position: <code>topleft</code>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		leafext_escape_output( $text );
	}
}
