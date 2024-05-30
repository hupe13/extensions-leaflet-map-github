<?php
/**
 * Functions for fullscreen shortcode
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Shortcode: [fullscreen]
function leafext_fullscreen_script( $position ) {
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		// create fullscreen control
		var fsControl = new L.Control.FullScreen({
			position: <?php echo wp_json_encode( $position ); ?>
		});
		// add fullscreen control to the map
		map.addControl(fsControl);
	});
	<?php
	$javascript = ob_get_clean();
	$text       = $text . $javascript . '//-->' . "\n" . '</script>';
	$text       = \JShrink\Minifier::minify( $text );
	return "\n" . $text . "\n";
}

function leafext_fullscreen_function( $atts, $content, $shortcode ) {
	$text = leafext_should_interpret_shortcode( $shortcode, $atts );
	if ( $text !== '' ) {
		return $text;
	} else {
		leafext_enqueue_fullscreen();
		$options = shortcode_atts( array( 'position' => 'topleft' ), $atts );
		if ( ! leafext_check_position_control( $options['position'] ) ) {
			$options['position'] = 'topleft';
		}
		return leafext_fullscreen_script( $options['position'] );
	}
}
add_shortcode( 'fullscreen', 'leafext_fullscreen_function' );
