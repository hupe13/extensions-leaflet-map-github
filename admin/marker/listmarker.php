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
	$text = $text . '<h3>Shortcode</h3>
<h4>' . __( 'Create Map', 'extensions-leaflet-map' ) . '</h4>
<pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-map ....]
</code></pre>
<h4>' . __( 'and markers with leaflet-marker and title option', 'extensions-leaflet-map' ) . '</h4>
<pre' . $codestyle . '><code' . $codestyle . '>// title is mandandory!
&#091;leaflet-marker lat=... lng=... title=... ...]poi1&#091;/leaflet-marker]
&#091;leaflet-marker lat=... lng=... title=... ...]poi2&#091;/leaflet-marker]
... many any ...
&#091;leaflet-marker lat=... lng=... title=... ...]poixx&#091;/leaflet-marker]
</code></pre>

<h4>' . __( 'Create control list', 'extensions-leaflet-map' ) . '</h4>
<pre' . $codestyle . '><code' . $codestyle . '>&#091;listmarker options ...]
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
	$text = $text . leafext_html_table( $new );

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}
