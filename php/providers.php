<?php
/**
 * Functions for elevation shortcode
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_providers_registration () {
	$tiles = array(
	  array(
	    'name' => 'HEREv3',
	    'keys' => array(
	      'apiKey' => '<insert apiKey here>',
	    ),
	  ),
	  array(
	    'name' => 'HERE',
	    'keys' => array(
	      'app_id' => '<insert ID here>',
	      'app_code' =>  '<insert ID here>',
	    ),
	  ),
	  array(
	    'name' => 'Jawg',
	    'keys' => array(
	      'variant' =>  '<insert map id here or blank for default variant>',
	      'accessToken' =>  '<insert access token here>',
	    ),
	  ),
	  array(
	    'name' => 'MapBox',
	    'keys' => array(
	      'id' =>  '<insert map_ID here>',
	      'accessToken' =>  '<insert ACCESS_TOKEN here>',
	    ),
	  ),
	  array(
	    'name' => 'MapTiler',
	    'keys' => array(
	      'key' =>  '<insert key here>',
	    ),
	  ),
	  array(
	    'name' => 'Thunderforest',
	    'keys' => array(
	      'apikey' =>  '<insert api_key here>',
	    ),
	  ),
	  array(
	    'name' => 'TomTom',
	    'keys' => array(
	      'apikey' =>  '<insert your API key here>',
	    ),
	  ),
	  array(
	    'name' => 'GeoportailFrance',
	    'keys' => array(
	      'variant' =>  '<insert resource ID here>',
	      'apikey' =>  '<insert api key here>',
	    ),
	  ),
	);
	return $tiles;
}

function leafext_providers_regnames () {
	$tiles = leafext_providers_registration ();
	return array_column($tiles, 'name');
}

//Shortcode: [providers ..."]

function leafext_providers_script($maps){
	$text = '<script>
	//https://github.com/leaflet-extras/leaflet-providers/blob/57d69ea6e75834235c607eab72cbb4da862ddc96/preview/preview.js#L56';
	$text = $text."
	function isOverlay (providerName, layer) {
		if (layer.options.opacity && layer.options.opacity < 1) {
			return true;
		}
		var overlayPatterns = [
			'^(OpenWeatherMap|OpenSeaMap|OpenSnowMap)',
			'OpenMapSurfer.(Hybrid|AdminBounds|ContourLines|Hillshade|ElementsAtRisk)',
			'Stamen.Toner(Hybrid|Lines|Labels)',
			'Hydda.RoadsAndLabels',
			'^JusticeMap',
			'OpenAIP',
			'OpenRailwayMap',
			'OpenFireMap',
			'SafeCast',
			'WaymarkedTrails.(hiking|cycling|mtb|slopes|riding|skating)'
		];

		return providerName.match('(' + overlayPatterns.join('|') + ')') !== null;
	};";
	//
	$text = $text.'
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var attributions = Object.keys(map.attributionControl._attributions);
		var defaultAttr = String(attributions);
		map.attributionControl._attributions = {};

		var baselayers = {};
		var overlays = {};

		map.eachLayer(function(layer) {
			if( layer instanceof L.TileLayer ) {
				map.removeLayer(layer);
				layer.options.attribution = defaultAttr;
				map.addLayer(layer);
				if(typeof layer.options.id !== "undefined") {
					var defaultname = layer.options.id;
				} else {
					var defaultname = "Default";
				}
				baselayers[defaultname] = layer;
			}
	 	});
		';
		$regtiles = get_option('leafext_providers',array());
		foreach ($maps as $map) {
			$id = array_search(explode ( '.', $map )[0], array_column($regtiles, 'name'));
			if ($id !== false) {
				$text = $text.'var layer = L.tileLayer.provider("'.$map.'", {';
				foreach ( $regtiles[$id]['keys'] as $key => $value ) {
					$text = $text.$key.': "';
					$text = $text.$value.'",';
				}
				$text = $text.'} );';
			} else {
				$text = $text.'var layer = L.tileLayer.provider("'.$map.'");';
			}
			$text = $text.'
			if (isOverlay("'.$map.'", layer)) {
				overlays["'.$map.'"] = layer;
			} else {
				baselayers["'.$map.'"] = layer;
			}';
		}
		$text = $text.'
		//console.log(baselayers);
		//console.log(overlays);
		L.control.layers(baselayers,overlays).addTo(map);
		//var opacity = overlays;
		//L.control.opacity(opacity).addTo(map);
	});
	</script>';
	//$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

// function leafext_providers_function( $atts ) {
// 	leafext_enqueue_providers();
// 	$maps = explode ( ',', $atts['providers'] );
// 	return leafext_providers_script($maps);
// }
// add_shortcode('providers', 'leafext_providers_function' );
