<?php
/**
 * Functions for marker target
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Shortcode: [targetmarker]
function leafext_targetmarker_script( $options ) {
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		let lat = <?php echo wp_json_encode( $options['lat'] ); ?>;
		let lng = <?php echo wp_json_encode( $options['lng'] ); ?>;
		let target = <?php echo wp_json_encode( $options['popup'] ); ?>;
		let zoom = <?php echo wp_json_encode( $options['zoom'] ); ?>;
		let debug = <?php echo wp_json_encode( $options['debug'] ); ?>;
		leafext_targetmarker_js(lat,lng,target,zoom,debug);
	});
	<?php
	$javascript = ob_get_clean();
	$text       = $text . $javascript . '//-->' . "\n" . '</script>';
	$text       = \JShrink\Minifier::minify( $text );
	return "\n" . $text . "\n";
}

function leafext_targetmarker_function( $atts, $content, $shortcode ) {
	$text = leafext_should_interpret_shortcode( $shortcode, $atts );
	if ( $text != '' ) {
		return $text;
	} else {
		$options = shortcode_atts(
			array(
				'lat'   => '',
				'lng'   => '',
				'popup' => 'Target',
				'zoom'  => false,
				'debug' => false,
			),
			leafext_clear_params( $atts )
		);

		//phpcs:disable WordPress.Security.NonceVerification.Recommended -- no form
		$get            = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
		$options['lat'] = isset( $get['lat'] ) ? $get['lat'] : '';
		$options['lng'] = isset( $get['lng'] ) ? $get['lng'] : '';
		if ( $options['lat'] == '' || $options['lat'] == '' ) {
			$text = '[' . $shortcode . ' ';
			if ( is_array( $atts ) ) {
				foreach ( $atts as $key => $item ) {
					$text = $text . "$key=$item ";
				}
			}
			$text = $text . ' - lat, lng missing ';
			$text = $text . ']';
			return $text;
		}
		// var_dump( $options );
		leafext_enqueue_leafext( 'targetmarker' );
		return leafext_targetmarker_script( $options );
	}
}
add_shortcode( 'targetmarker', 'leafext_targetmarker_function' );
