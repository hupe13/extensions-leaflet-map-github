<?php
/**
 * Help for leaflet-geojson-extramarker
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_help_geojsonextramarker() {
	if ( is_singular() || is_archive() ) {
		$codestyle = '';
		$server    = map_deep( wp_unslash( $_SERVER ), 'sanitize_text_field' );
		if ( strpos( $server['REQUEST_URI'], '/en/' ) !== false ) {
			$lang = '/en';
		} else {
			$lang = '';
		}
		$geojsonextramarker = $lang . '/doku/geojsonextramarker/';
	} else {
		leafext_enqueue_admin();
		// $codestyle    = ' class="language-coffeescript"';
		$codestyle          = '';
		$geojsonextramarker = '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=geojsonextramarker';
	}
	$text = '<h3>' .
	wp_sprintf(
		/* translators: %s is a shortcode. */
		__(
			'Use %s for marker in geojson files',
			'extensions-leaflet-map'
		),
		'leaflet-extramarker'
	) . '</h3>';

	$text .= '<h3>Shortcode</h3>';
	$text .= '<pre class="leafext-prismatic"><code class="leafext-prismatic-bg">&#091;leaflet-geojson-extramarker <i>leaflet-geojson-options</i> <i>leaflet-extramarker-options</i>]</code ></pre>';

	$text .= '<h3>' . __(
		'Options',
		'extensions-leaflet-map'
	) . '</h3>';

	$text .= '<p><ul><li>';
	$text .= wp_sprintf(
		/* translators: %s is a link. */
		__(
			'The same options as for %1$s: %2$s (all except the icon options)',
			'extensions-leaflet-map'
		),
		'<a href="https://github.com/bozdoz/wp-plugin-leaflet-map#leaflet-geojson-options">leaflet-geojson</a>',
		'src, popup_text, popup_property, fitbounds, table-view'
	);
	$text .= '</li>';
	$text .= '<li>' . __(
		'Options',
		'extensions-leaflet-map'
	) . ' <a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=extramarker#op">leaflet-extramarker</a> (' .
	__(
		'except',
		'extensions-leaflet-map'
	)
	. ' lat, lng)</li></ul></p>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}
