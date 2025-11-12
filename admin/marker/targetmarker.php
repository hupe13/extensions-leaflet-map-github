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

	$text .= '<p><ul>';
	$text .= '<li>' . __( 'Jump to a marker in a map with many markers.', 'extensions-leaflet-map' ) . '</li>' . "\n";
	$text .= '<li>' . wp_sprintf(
		/* translators: %1$s is a leaflet-marker, %2$s is leaflet-extramarker, %3$s is Point */
		__( 'Markers can be %1$s and %2$s or marker %3$s in geojson files.', 'extensions-leaflet-map' ),
		'<code>leaflet-marker</code>',
		'<code>leaflet-extramarker</code>',
		'(geometry type Point)'
	) . '</li>' . "\n";
	$text .= '<li>' . __( 'Source and target can be the same page or different pages.', 'extensions-leaflet-map' ) . '</li>' . "\n";
	$text .= '<li>' . __( 'The word "page" in this documentation means page or post.', 'extensions-leaflet-map' ) . '</li>' . "\n";
	$text .= '</ul></p>' . "\n";

	$text .= '<h2>' . __( 'Code on source page', 'extensions-leaflet-map' ) . '</h2>';

	$text .= '<p><ul>';
	$text .= '<li>' . __( 'Shortcode', 'extensions-leaflet-map' ) . ' <code>targetlink</code></li>' . "\n";
	$text .= '<li>' . __( 'query string in an URL', 'extensions-leaflet-map' ) . ': "<code>?lat=...&lng=...&zoom=...</code>"</li>' . "\n";
	// $text .= '<li>' . __( 'Shortcode', 'extensions-leaflet-map' ) . ' <code>targetmarker</code></li>' . "\n";
	$text .= '</ul></p>' . "\n";

	$text .= '<h3>' . __( 'Shortcode', 'extensions-leaflet-map' ) . ' <code>targetlink</code></h3>' . "\n";

	$text .= '<p>' . __( 'A link similar to this one is created:', 'extensions-leaflet-map' ) .
	' <code>&lt;a href="' . __( 'Link to the marker on a map', 'extensions-leaflet-map' ) . '">linktext&lt;/a></code>. ';
	$text .= __( 'You can write this shortcode anywhere in your text.', 'extensions-leaflet-map' ) . '</p>' . "\n";

	$text .= '<p><ul>';
	/* translators: %1$s is a leaflet-marker, %2$s is leaflet-extramarker. */
	$text .= '<li>' . wp_sprintf( __( 'jump to %1$s or %2$s', 'extensions-leaflet-map' ), '<code>leaflet-marker</code>', '<code>leaflet-extramarker</code>' );
	$text .= '<pre' . $codestyle . '><code' . $codestyle . '>';
	$text .= '&#091;targetlink title=... ... ]' . "\n";
	$text .= '</code></pre>' . "\n";
	$text .= '</li>';
	$text .= '<li>' . __( 'jump to a marker in a geojson file', 'extensions-leaflet-map' );
	$text .= '<pre' . $codestyle . '><code' . $codestyle . '>';
	$text .= '&#091;targetlink property=... value=... ... ]' . "\n";
	$text .= '</code></pre>' . "\n";
	$text .= '</li>';
	$text .= '<li>' . __( 'jump to any marker nearest lat and lng', 'extensions-leaflet-map' );
	$text .= '<pre' . $codestyle . '><code' . $codestyle . '>';
	$text .= '&#091;targetlink lat=... lng=... ... ]' . "\n";
	$text .= '</code></pre>' . "\n";
	$text .= '</li>';
	$text .= '</ul></p>' . "\n";

	$text .= '<h3>' . __( 'Options for', 'extensions-leaflet-map' ) . ' <code>targetlink</code></h3>' . "\n";

	$text .= '<p><ul>';
	$text .= '<li>' . __( 'required: one of these', 'extensions-leaflet-map' );
	$text .= '<p><ul>';
	$text .= '<li> title - <code>title</code> ';
	$text .= wp_sprintf(
		/* translators: %1$s is a leaflet-marker, %2$s is leaflet-extramarker. */
		__( 'of the target %1$s or %2$s', 'extensions-leaflet-map' ),
		'<code>leaflet-marker</code>',
		'<code>leaflet-extramarker</code>'
	) . '</li>' . "\n";
	$text .= '<li> property, value - ' .
	wp_sprintf(
		/* translators: %1$s, %2$s are property and value. */
		__( '%1$s and %2$s of the target geojson marker', 'extensions-leaflet-map' ),
		'<code>property</code>',
		'<code>value</code>'
	)
	. '</li>';

	$text .= '<li> lat, lng - ' . __( 'latitude and longitude of target', 'extensions-leaflet-map' ) . '</li>' . "\n";
	$text .= '</ul></p></li>' . "\n";
	$text .= '<li>optional:';
	$text .= '<p><ul>';
	$text .= '<li> popup - ';
	$text .= wp_sprintf(
		/* translators: %1$s is title, %2$s is Target. */
		__( 'popup content, if the target marker has not a popup text. Default: content of %1$s, if the marker has one or %2$s', 'extensions-leaflet-map' ),
		'<code>title</code>',
		'"Target"'
	) . '</li>' . "\n";
	$text .= '<li> linktext - ';
	$text .= __( 'text of the link. Default:', 'extensions-leaflet-map' ) . ' "Target"</li>' . "\n";
	$text .= '<li> link - ';
	$text .= __( 'URL to the target page. Default: same page', 'extensions-leaflet-map' ) . '</li>' . "\n";
	$text .= wp_sprintf(
		'<li> mapid - '
		/* translators: %1$s is "mapid", %2$s is "leaflet-map". */
		. __( '%1$s from target map (see %2$s option %1$s). Default: current map', 'extensions-leaflet-map' ),
		'mapid',
		'leaflet-map'
	)
	. '</li>';
	$text .= '<li> zoom - '
	. __( 'see below', 'extensions-leaflet-map' ) . '</li>
	<li> debug - '
	. __( 'if true, log some infos to the developer console and add circles about the target on the map', 'extensions-leaflet-map' ) . '</li>';
	$text .= '</ul></p></li></ul></p>' . "\n";

	$text .= '<h3>' . __( 'Options for query string', 'extensions-leaflet-map' ) . '</h3>' . "\n";

	$text .= '<p>' . wp_sprintf(
		/* translators: %s is styling */
		__( 'This is the only way to link to a marker on your map from an %1$sexternal%2$s site.', 'extensions-leaflet-map' ),
		'<b>',
		'</b>'
	) . '</p>' . "\n";

	$text .= '<p><ul><li>' . __( 'Create a source page with a link to the target page.', 'extensions-leaflet-map' ) . '</li>' . "\n";
	$text .= '<li>' . __( 'The parameters (querystring) from this link should be:', 'extensions-leaflet-map' ) .
	' "<code>?lat=...&lng=...&zoom=...</code>" - <code>zoom</code> ' . __( 'is optional.', 'extensions-leaflet-map' ) . '</li>' . "\n";
	$text .= '<li>' . __( 'example URL for the link', 'extensions-leaflet-map' ) . ': <code>' . get_site_url() . '/targetpage/?lat=...&lng=...</code></li>' . "\n";
	$text .= '<li>' . __( 'The URL can be link to a page on your website or to a remote one.', 'extensions-leaflet-map' ) . '</li>';
	$text .= '</ul></p>' . "\n";

	$text .= '<h2>' . __( 'Shortcodes on target map', 'extensions-leaflet-map' ) . '</h2>' . "\n";

	$targetmap  = '&#091;leaflet-map fitbounds]' . "\n";
	$targetmap .= '// many any' . "\n";
	$targetmap .= '&#091;leaflet-marker title=... ....]your popupcontent&#091;/leaflet-marker]' . "\n";
	$targetmap .= '&#091;leaflet-extramarker title=... ....]your popupcontent&#091;/leaflet-extramarker]' . "\n";
	$targetmap .= '// or one geojson file with markers' . "\n";
	$targetmap .= '&#091;leaflet-geojson ....]your popupcontent&#091;/leaflet-geojson]' . "\n";
	$targetmap .= '// optional' . "\n";
	$targetmap .= '&#091;cluster ...]' . "\n";
	$targetmap .= '// required' . "\n";
	$targetmap .= '&#091;zoomhomemap]' . "\n";

	$text .= '<p><pre' . $codestyle . '><code' . $codestyle . '>' . $targetmap . '</code></pre></p>' . "\n";

	$text .= __( 'Required, if source page and target page are not the same:', 'extensions-leaflet-map' );

	$text .= '<pre' . $codestyle . '><code' . $codestyle . '>';
	$text .= '// popup, zoom, debug are optional' . "\n";
	$text .= '&#091;targetmarker popup=... zoom=... debug=...]</code></pre>';

	$text .= '<p><ul><li><code>fitbounds</code>, <code>zoomhomemap</code> ' . __( 'are required!', 'extensions-leaflet-map' ) . '</li>' . "\n";
	$text .= '<li><code>title</code> ' .
	wp_sprintf(
		/* translators: %1$s is a targetmarker, %2$s is title. */
		__( 'is required, if you use %1$s with option %2$s.', 'extensions-leaflet-map' ),
		'<code>targetlink</code>',
		'<code>title</code>'
	) . '</li>' . "\n";

	$text .= '<li><code>targetmarker</code> ' .
	__( 'is required, if source page and target page are different.', 'extensions-leaflet-map' ) . ' ' .
	wp_sprintf(
		/* translators: %s is targetmarker. */
		__( 'If the page is the same, %s does not need to be defined.', 'extensions-leaflet-map' ),
		'<code>targetmarker</code>'
	)
	. '</li>' . "\n";

	$text .= '<li>' . __( 'The order of the shortcodes is fixed!', 'extensions-leaflet-map' );
	$text .= ' <code>cluster - zoomhomemap - targetmarker</code>.</li>' . "\n";
	$text .= '<li>' . wp_sprintf(
		/* translators: %s is targetmarker. */
		__( 'If you have more than one map on a page: %s may only appear exactly once and must be the last shortcode.', 'extensions-leaflet-map' ),
		'<code>targetmarker</code>'
	) . '</li>' . "\n";
	$text .= '</ul></p>' . "\n";

	$text .= '<h3>' . __( 'Shortcode', 'extensions-leaflet-map' ) . ' <code>targetmarker</code></h3>' . "\n";

	$text .= '<p>' . __( 'All these options are optional.', 'extensions-leaflet-map' ) . '</p>' . "\n";
	$text .= '<ul><li> popup - ';
	$text .= wp_sprintf(
		/* translators: %1$s is title, %2$s is Target. */
		__(
			'popup content, if the target marker has not a popup text. Default: content of %1$s, if the marker has one or %2$s',
			'extensions-leaflet-map'
		),
		'<code>title</code>',
		'"Target"'
	) . '.</li>
	<li> zoom - '
	. __( 'see below.', 'extensions-leaflet-map' )
	. '</li>' . "\n";
	$text .= '
	<li> debug - '
	. __( 'if true, log some infos to the developer console and add circles about the target on the map.', 'extensions-leaflet-map' ) .
	'</li></ul>';

	$text .= '<h3>' . __( 'Option', 'extensions-leaflet-map' ) . ' zoom</h3>' . "\n";
	$text .= '<p>'
	. __( 'This is the zoom level for zooming to the target marker.', 'extensions-leaflet-map' );
	$text .= ' <code>zoom</code> '
	. __( 'is considered only, if the target marker is not in a cluster.', 'extensions-leaflet-map' )
	. '</p>' . "\n";
	$text .= '<b>' . __( 'Priority:', 'extensions-leaflet-map' ) . '</b>' . "\n";
	$text .= '<p><ol>' .
	'<li>' . wp_sprintf(
		/* translators: %1$s is zoom, %2$s is targetlink. */
		__( '%1$s in querystring or in %2$s', 'extensions-leaflet-map' ),
		'<code>zoom</code>',
		'<code>targetlink</code>'
	) . '</li>' . "\n" .
	'<li>' . wp_sprintf(
		/* translators: %1$s is zoom, %2$s is targetmarker. */
		__( '%1$s in %2$s', 'extensions-leaflet-map' ),
		'<code>zoom</code>',
		'<code>targetmarker</code>'
	) . '</li>' . "\n" .
	'<li>' . __( 'Default: actual zoom level of the map', 'extensions-leaflet-map' ) . '</li>' . "\n" .
	'</ol></p>' . "\n";

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}
