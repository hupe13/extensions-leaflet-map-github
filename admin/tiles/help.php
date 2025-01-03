<?php
/**
 * Help tileswitch
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_help_tiles() {
	if ( is_singular() || is_archive() ) {
		$codestyle = '';
	} else {
		leafext_enqueue_admin();
		$codestyle = ' class="language-coffeescript"';
	}
	if ( ! ( is_singular() || is_archive() ) ) { // backend
		$tilesproviders = '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=tilesproviders';
		$tileswitch     = '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=tileswitch';
		$text           = '<h2>' . __( 'About Tile servers', 'extensions-leaflet-map' ) . '</h2><p>';
		$text           = $text . sprintf(
			/* translators: %s is a href. */
			__( 'The default Map Tiles are defined in the %1$s Settings%2$s.', 'extensions-leaflet-map' ),
			'<a href="' . get_admin_url() . 'admin.php?page=leaflet-map">Leaflet Map',
			'</a>'
		);
	} else { // for my frontend leafext.de
		$server = map_deep( wp_unslash( $_SERVER ), 'sanitize_text_field' );
		if ( strpos( $server['REQUEST_URI'], '/en/' ) !== false ) {
			$lang = '/en';
		} else {
			$lang = '';
		}
		$tilesproviders = $lang . '/doku/tilesproviders/';
		$tileswitch     = $lang . '/doku/tileswitch/';
		$text           = '<p>' . __( 'The default Map Tiles are defined in the Leaflet Map Settings.', 'extensions-leaflet-map' );
	}
	$text = $text . ' ' . sprintf(
		/* translators: %2$s and %3$s is a href, %1$s is an option */
		__(
			'Pay attention to the setting for %1$s, it depends on the used Map Tiles, see %2$shere%3$s.',
			'extensions-leaflet-map'
		),
		'max_zoom',
		'<a href="https://github.com/leaflet-extras/leaflet-providers/blob/master/leaflet-providers.js">',
		'</a>'
	) . '</p>';
	$text = $text . '<h3>' . __( 'Your Leaflet Map Settings', 'extensions-leaflet-map' ) . '</h3>';
	if ( is_singular() || is_archive() ) {
		$text = $text . '<style>td,th { border:1px solid #195b7a !important; }</style>';
	}
	$text = $text . '
  <figure class="wp-block-table aligncenter is-style-stripes">
  <table class="form-table" border="1">
  <tr class="alternate"><th style="text-align:center">Name</th><th style="text-align:center">Option</th><th style="text-align:center">' . __( 'Current Settings', 'extensions-leaflet-map' ) . '</th></tr>
  <tr><td>' . __( 'Tile Id', 'extensions-leaflet-map' ) . '</td><td>mapid</td><td>' . get_option( 'leaflet_mapid' ) . '</td></tr>
  <tr class="alternate"><td>' . __( 'Map Tile URL', 'extensions-leaflet-map' ) . '</td><td>tileurl</td><td>' . get_option( 'leaflet_map_tile_url' ) . '</td></tr>
  <tr><td>' . __( 'Map Tile URL Subdomains', 'extensions-leaflet-map' ) . '</td><td>subdomains</td><td>' . get_option( 'leaflet_map_tile_url_subdomains' ) . '</td></tr>
  <tr class="alternate"><td>' . __( 'Default Attribution', 'extensions-leaflet-map' ) . '</td><td></td><td>' . get_option( 'leaflet_default_attribution' ) . '</td></tr>
  <tr><td>' . __( 'Default Min Zoom', 'extensions-leaflet-map' ) . '</td><td>min_zoom</td><td>' . get_option( 'leaflet_default_min_zoom' ) . '</td></tr>
  <tr class="alternate"><td>' . __( 'Default Max Zoom', 'extensions-leaflet-map' ) . '</td><td>max_zoom</td><td>' . get_option( 'leaflet_default_max_zoom' ) . '</td></tr>
  </table></figure>';

	$text = $text . '<p>';
	$text = $text . sprintf(
		/* translators: %2$s and %3$s is a href, %1$s is an option */
		__( 'Additionally you can use some predefined Tile Providers with %1$s or you can define %2$syour Tile Servers%3$s.', 'extensions-leaflet-map' ),
		'<a href="' . $tilesproviders . '">Leaflet-providers</a>',
		'<a href="' . $tileswitch . '">',
		'</a>'
	) . '</p>';

	$text = $text . '<h2>Shortcode</h2>';
	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-map mapid="..."]
&#091;layerswitch tiles="mapid1,mapid2,..." mapids="mapid3,mapid4,..." providers="provider1,provider2,..." opacity="mapid1,provider1,..."]
</code></pre>';
	$text = $text . '
  <h3>Parameter</h3>
  <ul>
  <li> <code>&#091;leaflet-map]</code>
  <ul>';
	$text = $text . '<li> ' . __( 'see Leaflet Map documentation', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '<li> ' . sprintf(
		/* translators: %s is an option. */
		__( 'optional: %s - This appears in the switching control.', 'extensions-leaflet-map' ),
		'<code>mapid</code>'
	) . '</li>';
	$text = $text . '</ul></li>';
	$text = $text . '
   <li> <code>&#091;layerswitch]</code><ul>';
	$text = $text . '<li> ' . sprintf(
		/* translators: %s is a href. */
		__( 'without any parameter: All your defined %1$stile servers%2$s are used.', 'extensions-leaflet-map' ),
		'<a href="' . $tileswitch . '">',
		'</a>'
	) . '</li>';
	$text = $text . '<li> <code>tiles</code>: ' . sprintf(
		/* translators: %s is a href. */
		__( 'a comma separated list of any your defined %1$stile servers%2$s', 'extensions-leaflet-map' ),
		'<a href="' . $tileswitch . '">',
		'</a>.'
	) .
	'</li>';
	$text = $text . '<li> <code>providers</code>: ' . sprintf(
		/* translators: %s is a href. */
		__( 'a comma separated list of any %1$sproviders%2$s', 'extensions-leaflet-map' ),
		'<a href="' . $tilesproviders . '">',
		'</a>.'
	) .
	'</li>';
	$text = $text . '<li>' . sprintf(
		/* translators: %s is an option. */
		__(
			'optional: %s - a comma separated list with shortnames for providers. These appear in the switching control.',
			'extensions-leaflet-map'
		),
		'<code>mapids</code>'
	) . ' ' . sprintf(
		/* translators: %s are options */
		__( 'The number of %1$s and %2$s must match.', 'extensions-leaflet-map' ),
		'<code>mapids</code>',
		'<code>providers</code>'
	) . '
   </li><li> ' . sprintf(
		/* translators: %s is an option. */
		__( 'with %s you can specify the mapids and/or providers for which opacity should be regulated.', 'extensions-leaflet-map' ),
		'<code>opacity</code>'
	) .
	'</li>';
	$text = $text . '<li>' . sprintf(
		/* translators: %s is an option */
		__( 'optional: %s - position of tiles control', 'extensions-leaflet-map' ),
		'<code>position</code>'
	) . ': topleft, topright, bottomleft, bottomright. ' .
	__( 'Default', 'extensions-leaflet-map' ) . ': topright.
   </li>';
	$text = $text . '<li>' . sprintf(
		/* translators: %s are options */
		__( 'optional: %s - tiles control collapsed or not:', 'extensions-leaflet-map' ),
		'<code>collapsed</code>'
	) . ' true, false. ' .
	__( 'Default', 'extensions-leaflet-map' ) . ': true.
   </li>';
	$text = $text . '</ul></li></ul>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		$allowed_html          = wp_kses_allowed_html( 'post' );
		$allowed_html['style'] = true;
		echo wp_kses( $text, $allowed_html );
	}
}
