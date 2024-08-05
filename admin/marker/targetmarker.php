<?php
/**
 * Help for targetmarker / targetlink shortcode
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_targetmarker_help() {
	if ( is_singular() || is_archive() ) {
		$text      = '';
		$codestyle = '';
	} else {
		leafext_enqueue_admin();
		$codestyle = ' class="language-coffeescript"';
		$text      = '<h2>' . __( 'Target Marker', 'extensions-leaflet-map' ) . '</h2>';
	}
	$targetmap = '&#091;leaflet-map fitbounds]' . "\n";
	$targetmap = $targetmap . '// many any' . "\n";
	$targetmap = $targetmap . '&#091;leaflet-marker title=... ....]your popupcontent&#091;/leaflet-marker]' . "\n";
	$targetmap = $targetmap . '&#091;leaflet-extramarker title=... ....]your popupcontent&#091;/leaflet-extramarker]' . "\n";
	$targetmap = $targetmap . '// or one geojson file with markers' . "\n";
	$targetmap = $targetmap . '&#091;leaflet-geojson ....]your popupcontent&#091;/leaflet-geojson]' . "\n";
	$targetmap = $targetmap . '// optional' . "\n";
	$targetmap = $targetmap . '&#091;cluster ...]' . "\n";
	$targetmap = $targetmap . '// required' . "\n";
	$targetmap = $targetmap . '&#091;zoomhomemap]' . "\n";

	$text = $text . '<ul><li>' . __( 'Jump to marker in a map with many markers.', 'extensions-leaflet-map' ) . '</li><li>';
	$text = $text . sprintf(
		/* translators: %1$s is a leaflet-marker, %2$s is leaflet-extramarker, %3$s is Point */
		__( 'Markers can be %1$s and %2$s or marker %3$s in geojson files.', 'extensions-leaflet-map' ),
		'<code>leaflet-marker</code>',
		'<code>leaflet-extramarker</code>',
		'(geometry type Point)'
	) . '</li>';
	$text = $text . '<li>' . __( 'Source and target can be the same page / post or different pages / posts.', 'extensions-leaflet-map' ) . '</li></ul>';

	$text = $text . '<h3>' . __( 'Same source and target', 'extensions-leaflet-map' ) . '</h3>';

	$text = $text . '<h4>' . __( 'Source - shortcode for', 'extensions-leaflet-map' ) . ' <code>targetlink</code></h4>';
	$text = $text . '<p>' . __( 'A link similar to this one is created:', 'extensions-leaflet-map' ) .
	' <code>&lt;a href="' . __( 'Link to the marker on the map on the same page / post', 'extensions-leaflet-map' ) . '">linktext&lt;/a></code>. ';
	$text = $text . __( 'You can write this shortcode anywhere in your text.', 'extensions-leaflet-map' ) . '</p>';

	$text = $text . '<ul><li>';
	/* translators: %1$s is a leaflet-marker, %2$s is leaflet-extramarker. */
	$text = $text . sprintf( __( 'jump to %1$s or %2$s', 'extensions-leaflet-map' ), '<code>leaflet-marker</code>', '<code>leaflet-extramarker</code>' );
	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>';
	$text = $text . '&#091;targetlink title=... linktext=... ]' . "\n";
	$text = $text . '</code></pre>' . "\n";
	$text = $text . '</li><li>';
	$text = $text . __( 'jump to a marker in a geojson file', 'extensions-leaflet-map' );
	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>';
	$text = $text . '&#091;targetlink property=... value=... linktext=... ]' . "\n";
	$text = $text . '</code></pre>' . "\n";
	$text = $text . '</li>';

	$text = $text . '<li>';
	$text = $text . __( 'jump to any marker nearest lat and lng.', 'extensions-leaflet-map' );
	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>';
	$text = $text . '&#091;targetlink lat=... lng=... linktext=... ]' . "\n";
	$text = $text . '</code></pre>' . "\n";
	$text = $text . '</li>';

	$text = $text . '</ul>';

	$text = $text . '<h4>' . __( 'Source - options for', 'extensions-leaflet-map' ) . ' <code>targetlink</code></h4>';
	$text = $text . '<ul>';
	$text = $text . '<li> title - <code>title</code> ';
	/* translators: %1$s is a leaflet-marker, %2$s is leaflet-extramarker. */
	$text = $text . sprintf( __( 'of the target %1$s or %2$s', 'extensions-leaflet-map' ), '<code>leaflet-marker</code>', '<code>leaflet-extramarker</code>' ) . '</li>';
	$text = $text . '<li> property - <code>property</code> '
	. __( 'of the target geojson marker', 'extensions-leaflet-map' ) . '</li>
	<li> value - <code>value</code> '
	. __( 'of the target geojson marker', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '<li> lat, lng - ' . __( 'latitude and longitude of target', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '<li> linktext - ';
	$text = $text . __( 'text of the link. Default:', 'extensions-leaflet-map' ) . ' "Target"</li>';
	$text = $text . '<li> popup - ';
	/* translators: %1$s is a title, %2$s is "target". */
	$text = $text . sprintf( __( 'popup content, if the target marker has not a popup. Default: %1$s, if the marker has one or %2$s.', 'extensions-leaflet-map' ), '<code>title</code>', '"Target"' ) . '</li>';
	$text = $text . sprintf(
		'<li> mapid - '
		/* translators: %1$s is "mapid", %2$s is "leaflet-map". */
		. __( '%1$s from target map (see %2$s option %1$s). Default: current map.', 'extensions-leaflet-map' ),
		'mapid',
		'leaflet-map'
	)
	. '</li>';
	$text = $text . '<li> zoom - '
	. __( 'valid if the target marker is not clustered. Zoom level for move to location for target marker. Default: actual zoom level.', 'extensions-leaflet-map' )
	. '</li>
	<li> debug - '
	. __( 'if true, log some infos to the developer console and add circles about the target on the map.', 'extensions-leaflet-map' ) .
	'</li></ul>';

	$text = $text . '<h4>' . __( 'Target - shortcode for the map', 'extensions-leaflet-map' ) . '</h4>';
	$text = $text . '<ul><li><code>fitbounds</code>, <code>zoomhomemap</code> ' . __( 'are mandatory!', 'extensions-leaflet-map' ) . '</li>';
	/* translators: %1$s is a targetmarker, %2$s is title. */
	$text = $text . '<li><code>title</code> ' . sprintf( __( 'is mandatory, if you use %1$s with option %2$s.', 'extensions-leaflet-map' ), '<code>targetlink</code>', '<code>title</code>' ) . '</li></ul>';

	// $text = $text . '<li><code>title</code> ' . __( 'is mandatory.', 'extensions-leaflet-map' ) . '</li></ul>';

	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>' . $targetmap . '</code></pre>' . "\n";

	$text = $text . '<h3>' . __( 'Different source and target', 'extensions-leaflet-map' ) . '</h3>';
	/* translators: %1$s is GET, %2$s is POST. */
	$text = $text . '<p>' . sprintf( __( 'There are two ways to the target: %1$s or %2$s.', 'extensions-leaflet-map' ), 'GET', 'POST' ) . '</p>';

	$text = $text . '<h4>' . __( 'Source', 'extensions-leaflet-map' ) . ' - GET</h4>';

	$text = $text . '<p>' . sprintf(
		/* translators: %s is styling */
		__( 'This is the only way to link to a marker on your map from an %1$sexternal%2$s site.', 'extensions-leaflet-map' ),
		'<b>',
		'</b>'
	) . '</p>';

	$text = $text . '<ul><li>' . __( 'Create a source page / post with a link to the target page / post.', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '<li>' . __( 'The parameters (querystring) from this link should be:', 'extensions-leaflet-map' ) .
	'<code> ?lat=...&lng=...</code></li>';
	$text = $text . '<li>' . __( 'example URL for the link', 'extensions-leaflet-map' ) . ': <code>' . get_site_url() . '/targetpage/?lat=...&lng=...</code></li>';
	$text = $text . '</ul>';

	$text = $text . '<h4>' . __( 'Source', 'extensions-leaflet-map' ) . ' - POST</h4>';

	$text = $text . '<p>' . __( 'A link similar to this one is created:', 'extensions-leaflet-map' ) .
	' <code>&lt;a href="' . get_site_url() . '/targetpage/">linktext&lt;/a></code>. ';
	$text = $text . __( 'You can write this shortcode anywhere in your text.', 'extensions-leaflet-map' ) . '</p>';

	$text = $text . '<ul><li>';
	/* translators: %1$s is leaflet-marker, %2$s is leaflet-extramarker. */
	$text = $text . sprintf( __( 'jump to %1$s or %2$s on a target page', 'extensions-leaflet-map' ), '<code>leaflet-marker</code>', '<code>leaflet-extramarker</code>' );
	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>';
	$text = $text . '&#091;targetlink link=' . get_site_url() . '/targetpage/ title=... linktext=...]' . "\n";
	$text = $text . '</code></pre>' . "\n";
	$text = $text . '</li><li>';
	$text = $text . __( 'jump to a marker in a geojson file on a target page', 'extensions-leaflet-map' );
	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>';
	$text = $text . '&#091;targetlink link=' . get_site_url() . '/targetpage/ property=... value=... linktext=...]' . "\n";
	$text = $text . '</code></pre>' . "\n";
	$text = $text . '</li></ul>';

	$text = $text . '<h4>' . sprintf( __( 'Target - shortcode for the map', 'extensions-leaflet-map' ), '<code>targetmarker</code>' ) . '</h4>';

	$text = $text . '<ul><li><code>fitbounds, zoomhomemap, targetmarker</code> ' . __( 'are mandatory!', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '<li>' . __( 'The order of the shortcodes is fixed!', 'extensions-leaflet-map' );
	$text = $text . ' <code>cluster - zoomhomemap - targetmarker</code>.</li>';
	/* translators: %1$s is a targetmarker, %2$s is title. */
	$text = $text . '<li><code>title</code> ' . sprintf( __( 'is mandatory, if you use %1$s with option %2$s.', 'extensions-leaflet-map' ), '<code>targetlink</code>', '<code>title</code>' ) . '</li>';
	$text = $text . '</ul>';

	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>' . $targetmap;
	$text = $text . '// popup, zoom, debug are optional' . "\n";
	$text = $text . '&#091;targetmarker popup=... zoom=... debug=...]</code></pre>';
	$text = $text . '<h4>' . __( 'Target - options for', 'extensions-leaflet-map' ) . ' <code>targetmarker</code></h4>';

	// $text = $text . '<ul><li>' .
	// 'lat, lng - (GET) ' . __( 'Latitude and Longitude are taken from URL parameters, e.g.', 'extensions-leaflet-map' ) . ': <code>' . get_site_url() . '/targetpage/?lat=...&lng=...</code>'
	// . '</li>';
	// $text = $text .
	// '<li>' .
	// 'title - (POST) <code>title</code> ' . __( 'is taken from POST parameters', 'extensions-leaflet-map' )
	// . '</li>';
	$text = $text . '<ul><li> popup - ';
	/* translators: %1$s is title, %2$s is Target. */
	$text = $text . sprintf( __( 'popup content, if the target marker has not a popup. Default: %1$s, if the marker has one or %2$s.', 'extensions-leaflet-map' ), '<code>title</code>', '"Target"' ) . '</li>
	<li> zoom - '
	. __( 'valid only, if the target marker is not clustered. Zoom level if target marker was found. Default: actual zoom level.', 'extensions-leaflet-map' )
	. '</li>';
	$text = $text . sprintf(
		'<li> mapid - '
		/* translators: %1$s is "mapid", %2$s is "leaflet-map". */
		. __( ' %1$s from target map (see %2$s option %1$s). Default: current map.', 'extensions-leaflet-map' ),
		'mapid',
		'leaflet-map'
	)
	. '</li>';
	$text = $text . '
	<li> debug - '
	. __( 'if true, log some infos to the developer console and add circles about the target on the map.', 'extensions-leaflet-map' ) .
	'</li></ul>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}
