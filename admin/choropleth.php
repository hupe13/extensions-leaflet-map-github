<?php
/**
 * Admin Info choropleth shortcode
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

echo '<h2>Leaflet Choropleth</h2>';
echo '<h2>XSS</h2>';
echo '<p>' . wp_kses_post(
	__( 'While fixing an XSS vulnerability in this shortcode, I discovered a bug that has been there for at least two years. For that reason, and since no one has complained, I decided to remove this shortcode and I made an additional plugin.', 'extensions-leaflet-map' )
) . '</p>';
echo '<p>' . wp_kses_post(
	wp_sprintf(
		/* translators: %s is a link */
		__( 'You can get it %1$shere%2$s.', 'extensions-leaflet-map' ),
		'<a href="https://leafext.de/extra/choropleth/" target="_blank" rel="noopener">',
		'</a>'
	)
) . '</p>';
if ( defined( 'CHOROPLETH' ) ) {
	leafext_enqueue_admin();
	echo wp_kses_post( leafext_choropleth_help() );
}
