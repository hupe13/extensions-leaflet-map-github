<?php
/**
 * Help for listmarker
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_help_listmarker() {
	if ( is_singular() || is_archive() ) {
		$codestyle = '';
	} else {
		leafext_enqueue_admin();
		$codestyle = ' class="language-coffeescript"';
	}

	$text = '<h2>' . __( 'Listing markers in the map', 'extensions-leaflet-map' ) . '</h2>';
	$text = $text . '<p>' . sprintf(
	/* translators: %1$s is "Leaflet Plugin", other is a href. */
		__(
			'My own %1$s based on the %2$swork%3$s of Stefano Cudini.',
			'extensions-leaflet-map'
		),
		'<a href="https://github.com/hupe13/leaflet-list-markers">Leaflet Plugin</a>',
		'<a href="https://github.com/stefanocudini/leaflet-list-markers">',
		'</a>'
	) . '</p>';
	$text = $text . '<h3>Shortcode</h3><h4>' .
	__( 'Create Map', 'extensions-leaflet-map' ) .
	'</h4><pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-map ....]</code></pre>
	<h4>';
	$text = $text . sprintf(
		/* translators: $s are shortcodes and %2$s is an option. */
		__(
			'and markers with %1$s and %2$s option and / or %3$s',
			'extensions-leaflet-map'
		),
		'<code>leaflet-marker</code>, <code>leaflet-extramarker</code>',
		'<code>title</code>',
		'<code>leaflet-geojson</code>'
	);
	$text = $text . '</h4>';
	$text = $text . '<pre' . $codestyle . '><code' . $codestyle . '>// title is mandandory for leaflet-(extra)marker!
&#091;leaflet-marker lat=... lng=... title=... ...]poi1&#091;/leaflet-marker]
... and / or ...
&#091;leaflet-extramarker lat=... lng=... title=... ...]poi2&#091;/leaflet-extramarker]
... and / or ...
&#091;leaflet-geojson src="..."]geojson {name}&#091;/leaflet-geojson]
... many any ...
</code></pre>';
	$text = $text . '<h4>' . __( 'Create control list', 'extensions-leaflet-map' ) . '</h4>
<pre' . $codestyle . '><code' . $codestyle . '>&#091;listmarker options ...]
...  or for geojson marker ...
&#091;listmarker propertyname=... options ...]
// optional - must be after listmarker shortcode!
&#091;cluster]
</code></pre>';

	$text = $text . '<h3>' . __( 'Options', 'extensions-leaflet-map' ) . '</h3>';

	$options = leafext_listmarker_params();
	$new     = array();
	$new[]   = array(
		'param'   => '<strong>' . __( 'Option', 'extensions-leaflet-map' ) . '</strong>',
		'desc'    => '<strong>' . __( 'Description', 'extensions-leaflet-map' ) . '</strong>',
		'default' => '<strong>' . __( 'Default', 'extensions-leaflet-map' ) . '</strong>',
	);

	foreach ( $options as $option ) {
		if ( is_bool( $option['default'] ) ) {
			$default = $option['default'] ? 'true' : 'false';
		} elseif ( $option['param'] === 'overiconurl' ) {
			$default = '<img src=' . $option['default'] . ' width=13 height=21 alt="marker">';
		} else {
			$default = $option['default'];
		}
		$new[] = array(
			'param'   => $option['param'],
			'desc'    => $option['desc'],
			'default' => $default,
		);
	}
	$text = $text . leafext_html_table( $new ) . "\n";

	$text = $text .
	sprintf(
		/* translators: $s is an color statement and %2$s is the word css. */
		__(
			'To change the background color (default: %1$s) for the whole website define %2$s in customizer or using another method:',
			'extensions-leaflet-map'
		),
		'<code>rgba(255, 255, 255, 0.4)</code>',
		'css'
	)
		. "\n";
	$text = $text .
	'<pre' . $codestyle . '><code' . $codestyle . '>ul.list-markers-ul {background-&#99;olor: your&#99;olor;}</code></pre>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}
