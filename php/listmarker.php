<?php
/**
 * Functions for listmarker
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Parameter and Values
function leafext_listmarker_params() {
	$params = array(
		array(
			'param'   => 'propertyName',
			'desc'    => sprintf(
				/* translators: %1$s is "feature.property", %2$s is a shortcode, %3$s is an option. */
				__(
					'a %1$s for geojson marker. For %2$s it is always %3$s and is ignored.',
					'extensions-leaflet-map'
				),
				'feature.property',
				'<code>leaflet-(extra)marker</code>',
				'<code>title</code>'
			),
			'default' => '',
		),
		array(
			'param'   => 'overiconurl',
			'desc'    => __( 'url to the icon when it hover or clicked. If it is an empty string, the icon does not change. Default: red icon', 'extensions-leaflet-map' ),
			'default' => LEAFEXT_PLUGIN_URL . '/pict/marker-icon-red.png',
		),
		array(
			'param'   => 'collapse',
			'desc'    => __( 'true or false - the list is collapsed or not (default)', 'extensions-leaflet-map' ),
			'default' => false,
		),
		array(
			'param'   => 'update',
			'desc'    => __( 'true or false - show only visible marker in list (default) / show all marker in list', 'extensions-leaflet-map' ),
			'default' => true,
		),
		array(
			'param'   => 'hover',
			'desc'    => __( 'true or false - show marker in list when hover', 'extensions-leaflet-map' ),
			'default' => false,
		),
		array(
			'param'   => 'background',
			'desc'    => __( 'background color for the list', 'extensions-leaflet-map' ),
			'default' => 'rgba(255, 255, 255, 0.4)',
		),
		array(
			'param'   => 'highlight',
			'desc'    => __( 'color to highlight the list entry', 'extensions-leaflet-map' ),
			'default' => 'rgba(255, 255, 255, 0.8)',
		),
		array(
			'param'   => 'maxheight',
			'desc'    => __( 'maximum height of list in relation to the height of the map', 'extensions-leaflet-map' ),
			'default' => 0.7,
		),
		array(
			'param'   => 'maxwidth',
			'desc'    => __( 'maximum width of list in relation to the width of the map', 'extensions-leaflet-map' ),
			'default' => 0.5,
		),
	);
	return $params;
}

function leafext_listmarker_script( $options ) {
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		let propertyName = <?php echo wp_json_encode( $options['propertyName'] ); ?>;
		let overiconurl = <?php echo wp_json_encode( $options['overiconurl'] ); ?>;
		let collapse = <?php echo wp_json_encode( $options['collapse'] ); ?>;
		let update = <?php echo wp_json_encode( $options['update'] ); ?>;
		let hover = <?php echo wp_json_encode( $options['hover'] ); ?>;
		let highlight = <?php echo wp_json_encode( $options['highlight'] ); ?>;
		let maxheight = <?php echo wp_json_encode( $options['maxheight'] ); ?>;
		let maxwidth = <?php echo wp_json_encode( $options['maxwidth'] ); ?>;
		leafext_listmarker_js(propertyName,overiconurl,collapse,update,hover,highlight,maxheight,maxwidth);
	});
	<?php
	$javascript = ob_get_clean();
	$text       = $text . $javascript . '//-->' . "\n" . '</script>';
	$text       = \JShrink\Minifier::minify( $text );
	return "\n" . $text . "\n";
}

function leafext_listmarker_function( $atts, $content, $shortcode ) {
	$text = leafext_should_interpret_shortcode( $shortcode, $atts );
	if ( $text !== '' ) {
		return $text;
	} else {
		if ( ! is_array( $atts ) ) {
			$atts = array();
		}
		$defaults = array();
		$params   = leafext_listmarker_params();
		foreach ( $params as $param ) {
			$defaults[ $param['param'] ] = $param['default'];
		}
		$atts1               = leafext_case( array_keys( $defaults ), leafext_clear_params( $atts ) );
		$options             = shortcode_atts( $defaults, $atts1 );
		$options['collapse'] = filter_var( $options['collapse'], FILTER_VALIDATE_BOOLEAN );
		$options['update']   = filter_var( $options['update'], FILTER_VALIDATE_BOOLEAN );
		$options['hover']    = filter_var( $options['hover'], FILTER_VALIDATE_BOOLEAN );
		if ( $options['background'] !== $defaults['background'] ) {
			$text = $text . '<style>ul.list-markers-ul {background-color: ' . $options['background'] . '}</style>';
		}

		leafext_enqueue_listmarker();
		return $text . leafext_listmarker_script( $options );
	}
}
add_shortcode( 'listmarker', 'leafext_listmarker_function' );
