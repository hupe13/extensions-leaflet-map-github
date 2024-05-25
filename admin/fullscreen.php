<?php
/**
 * Help for fullscreen shortcode
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_help_fullscreen() {
	if ( is_singular() || is_archive() ) {
		$codestyle = '';
	} else {
		leafext_enqueue_admin();
		$codestyle = ' class="language-coffeescript"';
	}
	$text  = '<h3>' . __( 'Fullscreen', 'extensions-leaflet-map' ) . '</h3>
	<img src="' . LEAFEXT_PLUGIN_PICTS . 'fullscreenon.png" alt="fullscreen-on">
	<img src="' . LEAFEXT_PLUGIN_PICTS . 'fullscreenoff.png" alt="fullscreen-off">
	<pre' . $codestyle . '><code' . $codestyle . '>[fullscreen]</code></pre>
	<pre' . $codestyle . '><code' . $codestyle . '>[fullscreen position=topleft|topright|bottomleft|bottomright]</code></pre>';
	$text .= __( 'default position:', 'extensions-leaflet-map' ) . ' <code>topleft</code>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}
