<?php
/**
 * Functions for extramarker for geojson files
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Shortcode: [leaflet-geojson-extramarker]
function leafext_extramarker_geojson_function( $atts, $content, $shortcode ) {
	$text = leafext_should_interpret_shortcode( $shortcode, $atts );
	if ( $text !== '' ) {
		return $text;
	} else {
		leafext_enqueue_extramarker();
		$geojson_shortcode = '[leaflet-geojson ';
		$file              = '';
		$mapid             = '0';
		if ( is_array( $atts ) ) {
			foreach ( $atts as $key => $item ) {
				if ( is_int( $key ) ) {
					if ( $item === 'circleMarker' ) {
						continue;
					}
					$geojson_shortcode .= "$item ";
				} else {
					$geojson_shortcode .= $key . '="' . $item . '" ';
					if ( $key === 'src' ) {
						$file = $item;
					}
				}
			}
		}
		$geojson_shortcode .= ']' . $content . '[/leaflet-geojson]';

		$atts1   = leafext_case( array_keys( leafext_extramarker_defaults() ), leafext_clear_params( $atts ) );
		$options = shortcode_atts( leafext_extramarker_defaults(), $atts1 );
		$icon    = leafext_extramarkers_params( $options ) . 'tooltipAnchor:[12,-24]';

		return do_shortcode( $geojson_shortcode ) . leafext_extramarker_geojson_script( $file, $icon );
	}
}
add_shortcode( 'leaflet-geojson-extramarker', 'leafext_extramarker_geojson_function' );

function leafext_extramarker_geojson_script( $file, $icon ) {
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		let file = <?php echo wp_json_encode( $file ); ?>;
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var map_id = map._leaflet_id;
		var geojsons = window.WPLeafletMapPlugin.geojsons;
		if (geojsons.length > 0) {
			var geocount = geojsons.length;
			for (var j = 0, len = geocount; j < len; j++) {
				var geojson = geojsons[j];
				if (map_id == geojson._map._leaflet_id) {
					if (file == geojson._url) {
						geojson.on(
							"ready",
							function () {
								var a = this.layer;
								a.eachLayer(
									function (layer) {
										if ( layer.feature.geometry.type == "Point" ) {
											layer.setIcon( L.ExtraMarkers.icon({
											<?php
											// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- is Javascript
											echo $icon;
											?>
											}) );
										}
									}
								);
							}
						);
					}
				}
			}
		}
	});
	<?php
	$javascript = ob_get_clean();
	$text       = $text . $javascript . '//-->' . "\n" . '</script>';
	$text       = \JShrink\Minifier::minify( $text );
	return "\n" . $text . "\n";
}
