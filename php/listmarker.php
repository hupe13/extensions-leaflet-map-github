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
		let overiconurl = <?php echo wp_json_encode( $options['overiconurl'] ); ?>;
		let collapse = <?php echo wp_json_encode( $options['collapse'] ); ?>;
		let update = <?php echo wp_json_encode( $options['update'] ); ?>;
		let hover = <?php echo wp_json_encode( $options['hover'] ); ?>;
		let maxheight = <?php echo wp_json_encode( $options['maxheight'] ); ?>;
		let maxwidth = <?php echo wp_json_encode( $options['maxwidth'] ); ?>;
		leafext_listmarker_js(overiconurl,collapse,update,hover,maxheight,maxwidth);
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
		$options             = shortcode_atts( $defaults, leafext_clear_params( $atts ) );
		$options['collapse'] = filter_var( $options['collapse'], FILTER_VALIDATE_BOOLEAN );
		$options['update']   = filter_var( $options['update'], FILTER_VALIDATE_BOOLEAN );
		$options['hover']    = filter_var( $options['hover'], FILTER_VALIDATE_BOOLEAN );
		leafext_enqueue_listmarker();
		return leafext_listmarker_script( $options );
	}
}
add_shortcode( 'listmarker', 'leafext_listmarker_function' );
