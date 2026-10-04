<?php
/**
 * Admin Info choropleth shortcode
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

echo '<h2>Leaflet Choropleth</h2>';
echo '<h2>XSS and why excluded</h2>';
echo wp_kses_post(
	'While fixing an XSS vulnerability in this shortcode, I discovered a bug that has been there for at least two years. For that reason, and since no one has complained, I decided to remove that part and I made an additional plugin, you can get it and the documentation <a href="https://leafext.de/extra/choropleth/" target="_blank" rel="noopener">here</a>.</p>'
);
if ( defined( 'CHOROPLETH' ) ) {
	leafext_enqueue_admin();
	echo wp_kses_post( leafext_choropleth_help() );
}
