<?php
/**
 * Help for leaflet-search shortcode
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_leafletsearch_help() {
	if ( is_singular() || is_archive() ) {
		$text = '';
	} else {
		$text = '<h2>Leaflet Control Search</h2>';
	}

	$text = $text . '<p>' . esc_html__( 'A control for search Markers/Features location by custom property.', 'extensions-leaflet-map' ) . '</p>';

	$text = $text . '<h3>Shortcode</h3><p>
  <pre><code>&#091;leaflet-map fitbounds]
//any many
&#091;leaflet-marker      ...] ... [/leaflet-marker]
&#091;leaflet-extramarker ...] ... [/leaflet-extramarker]
&#091;leaflet-polygon     ...] ... [/leaflet-polygon]
&#091;leaflet-circle      ...] ... [/leaflet-circle]
&#091;leaflet-line        ...] ... [/leaflet-line]
&#091;leaflet-geojson     ...] ... [/leaflet-geojson]
//one or more
&#091;leaflet-search propertyName="..." ...]</code></pre>';

	$text = $text . '</p><h3>' . esc_html__( 'Note', 'extensions-leaflet-map' ) . '</h3>';
	$text = $text . '<p>' . sprintf(
		esc_html__( '%1$s must be before %2$s.', 'extensions-leaflet-map' ),
		'<code>leaflet-search</code>',
		'<code>leaflet-optiongroup, cluster</code>'
	) . '</p>';

	$text = $text . '<h2>' . esc_html__( 'Options', 'extensions-leaflet-map' ) . '</h2>';

	$options = leafext_search_params();
	$new     = array();
	$new[]   = array(
		'param'   => '<strong>Option</strong>',
		'desc'    => '<strong>' . esc_html__( 'Description', 'extensions-leaflet-map' ) . '</strong>',
		'default' => '<strong>' . esc_html__( 'Default', 'extensions-leaflet-map' ) . '</strong>',
		'values'  => '<strong>' . esc_html__( 'Values', 'extensions-leaflet-map' ) . '</strong>',
	);
	foreach ( $options as $option ) {
		if ( $option['default'] == '' && $option['param'] == 'marker' ) {
			$option['default'] = '(' . esc_html__( 'red circle', 'extensions-leaflet-map' ) . ')';
		}
		$new[] = array(
			'param'   => $option['param'],
			'desc'    => $option['desc'],
			'default' => ( gettype( $option['default'] ) == 'boolean' ) ? ( $option['default'] ? 'true' : 'false' ) : $option['default'],
			'values'  => $option['values'],
		);
	}
	$text = $text . leafext_html_table( $new );

	$text = $text . '<p>' . sprintf(
		esc_html__( 'See %1$sLeaflet Map Github page%2$s for more or less useful and possible options for %3$s.', 'extensions-leaflet-map' ),
		'<a href="https://github.com/bozdoz/wp-plugin-leaflet-map#leaflet-marker">',
		'</a>',
		'propertyName'
	) . '</p>';

	$text = $text . '<h3>' . esc_html__( 'Option', 'extensions-leaflet-map' ) . ' container</h3>';
	$text = $text . '<p>' . sprintf( esc_html__( 'If you want the search field to be outside the map, define a div element with a custom html block on the post / page and give it an id. This id you then specify in option %s.', 'extensions-leaflet-map' ), 'container' ) . '</p>';
	$text = $text . '<code>&lt;div id="myId" style="height:3em; border:2px solid gray; width:200px;">&lt;/div></code>';
	$text = $text . '<p>' . esc_html__( 'Define some css:', 'extensions-leaflet-map' ) . '</p>';
	$text = $text . '<pre><code>&lt;style>
.leaflet-control-search.search-exp { border: none !important;}
.search-input {width: 80%;}
&lt;/style></code></pre>';
	$text = $text . '<style>.leaflet-control-search.search-exp { border: none !important;}.search-input {width: 80%;}</style>';
	$text = $text . '<p>' . esc_html__( 'Define a leaflet-search command with the option container:', 'extensions-leaflet-map' ) . '</p>';
	$text = $text . '<p><code>&#091;leaflet-search propertyname=... ... container=myId ...]</code></p>';
	$text = $text . '<div id="myId" style="height:3em; border:2px solid gray; width:200px;"></div>';
	$text = $text . do_shortcode( '[leaflet-map !boxZoom !doubleClickZoom !dragging !keyboard !scrollwheel !attribution !touchZoom !show_scale height=200 width=200 fitbounds min_zoom=12 max_zoom=16]' );
	$text = $text . do_shortcode( '[leaflet-marker lat=0.0 lng=0.0]Marker[/leaflet-marker]' );
	$text = $text . do_shortcode( '[leaflet-search container=myId propertyName=popupContent textPlaceholder="M ..."]' );
	$text = $text . '<p>' .
	sprintf(
		esc_html__( 'The specific style and css depends on your %1$stheme and taste%2$s', 'extensions-leaflet-map' ),
		'<a href="https://leafext.de/leafletsearch/searchcontainer/">',
		'</a>.</p>'
	);
	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		// leafext_escape_output( $text );
		//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- all escaped, see above
		echo $text;
	}
}
