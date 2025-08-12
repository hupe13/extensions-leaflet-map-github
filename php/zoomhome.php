<?php
/**
 * Functions for zoomhomemap shortcode
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Parameter and Values
function leafext_zoomhome_params() {
	$params = array(
		array(
			'param'   => 'zoomInTitle',
			'desc'    => sprintf(
				/* translators: %s is "zoom in". */
				__( 'Tooltip text of the %s button.', 'extensions-leaflet-map' ),
				"'zoom in'"
			),
			'default' => __( 'Zoom in', 'extensions-leaflet-map' ),
		),
		array(
			'param'   => 'zoomOutTitle',
			'desc'    => sprintf(
				/* translators: %s is "zoom out". */
				__( 'Tooltip text of the %s button.', 'extensions-leaflet-map' ),
				"'zoom out'"
			),
			'default' => __( 'Zoom out', 'extensions-leaflet-map' ),
		),
		array(
			'param'   => 'zoomHomeIcon',
			'desc'    => sprintf(
				/* translators: %s is "fa-". */
				__( 'Font-Awesome icon name for the home button (without %s).', 'extensions-leaflet-map' ),
				'"fa-"'
			),
			'default' => 'home',
		),
		array(
			'param'   => 'zoomHomeTitle',
			'desc'    => __( 'Tooltip text of the home button.', 'extensions-leaflet-map' ),
			'default' => __( 'Home', 'extensions-leaflet-map' ),
		),
		array(
			'param'   => 'fit',
			'desc'    => __( 'see table', 'extensions-leaflet-map' ),
			'default' => 1,
		),
		array(
			'param'   => 'position',
			'desc'    => __( 'position on the map:', 'extensions-leaflet-map' ) . ' <code>topleft</code>, <code>topright</code>, <code>bottomleft</code> or <code>bottomright</code>',
			'default' => 'topleft',
		),
	);
	return $params;
}

function leafext_zoomhome_settings( $atts ) {
	$defaults = array();
	$params   = leafext_zoomhome_params();
	foreach ( $params as $param ) {
		$defaults[ $param['param'] ] = $param['default'];
	}

	$atts1  = leafext_case( array_keys( $defaults ), leafext_clear_params( $atts ) );
	$params = shortcode_atts( $defaults, $atts1 );

	if ( ! leafext_check_position_control( $params['position'] ) ) {
		$params['position'] = 'topleft';
	}
	return $params;
}

function leafext_zoomhome_script( $options ) {
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var map_id = map._leaflet_id;
		var maps=[];
		maps[map_id] = map;
		// parameter fit: only when map !fitbound and ele fitbounds then set zoomhome to map,
		// not in elevation
		// 0: home = ele fitbounds (default)
		// 1: home = map
		var allfit = [];
		if (<?php echo wp_json_encode( (bool) $options['fit'] ); ?> && typeof maps[map_id]._shouldFitBounds === "undefined" ) {
			allfit[map_id] = new L.latLngBounds();
		}
		var position = <?php echo wp_json_encode( $options['position'] ); ?>;
		let all_options = <?php echo wp_json_encode( $options ); ?>;
		leafext_zoomhome_js(maps,map_id,allfit,position,all_options);
	});
	<?php
	$javascript = ob_get_clean();
	$text       = $text . $javascript . '//-->' . "\n" . '</script>';
	$text       = \JShrink\Minifier::minify( $text );
	return "\n" . $text . "\n";
}

function leafext_zoomhome_function( $atts, $content, $shortcode ) {
	$text = leafext_should_interpret_shortcode( $shortcode, $atts );
	if ( $text !== '' ) {
		return $text;
	} else {
		leafext_enqueue_zoomhome();
		leafext_enqueue_leafext( 'zoomhome', 'zoomhome' );
		$params = leafext_zoomhome_settings( $atts );
		return leafext_zoomhome_script( $params );
	}
}
add_shortcode( 'zoomhomemap', 'leafext_zoomhome_function' );
