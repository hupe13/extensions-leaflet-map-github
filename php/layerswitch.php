<?php
/**
 * Functions for layerswitch shortcode
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//[leaflet-map mapid=" "]
//Shortcode: [layerswitch]

function leafext_layerswitch_script($mylayers,$myfulllayers){
	$text = '
	<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var attributions = Object.keys(map.attributionControl._attributions);
		//console.log(attributions);
		//console.log(String(attributions));
		var defaultAttr = String(attributions);
		map.attributionControl._attributions = {};
		var baselayers = {};

		map.eachLayer(function(layer) {
			if( layer instanceof L.TileLayer ) {
				layer.options.attribution = defaultAttr;
				if(typeof layer.options.id !== "undefined") {
					var defaultname = layer.options.id;
				} else {
					var defaultname = "Default";
				}
				baselayers[defaultname] = layer;
				map.removeLayer(layer);
				map.addLayer(layer);
			}
		});

		var overlays = {};
		var opacity = {};
		var mylayers = '.json_encode($mylayers).';
		';
		foreach ($myfulllayers as $myfulllayer) {
			if ( $myfulllayer['overlay'] != "" ) {
				$text=$text.
				'overlays['.$myfulllayer['mapid'].'] = L.tileLayer('.$myfulllayer['options'].');';
				if ( $myfulllayer['opacity'] != "" ) {
					$text=$text.
					'opacity['.$myfulllayer['mapid'].'] = overlays['.$myfulllayer['mapid'].'];';
				}
			} else {
				$text=$text.
				'baselayers['.$myfulllayer['mapid'].'] = L.tileLayer('.$myfulllayer['options'].');';
				if ( $myfulllayer['opacity'] != "" ) {
					$text=$text.
					'opacity['.$myfulllayer['mapid'].'] = baselayers['.$myfulllayer['mapid'].'];';
				}
			}
		}
		$text = $text.'
		mylayers.forEach(extralayer => {
			//console.log(extralayer);
			if (extralayer.overlay == 1) {
				overlays[extralayer.mapid] = L.tileLayer(extralayer.tile, {attribution: extralayer.attr, id: extralayer.mapid});
				if (extralayer.opacity == 1) {
					opacity[extralayer.mapid] = overlays[extralayer.mapid];
				}
			} else {
				baselayers[extralayer.mapid] = L.tileLayer(extralayer.tile, {attribution: extralayer.attr, id: extralayer.mapid});
				if (extralayer.opacity == 1) {
					opacity[extralayer.mapid] = baselayers[extralayer.mapid];
				}
			}
		});

		//console.log(baselayers);
		//console.log(overlays);

		//L.control.layers(baselayers,overlays).addTo(map);
		L.control.layers(baselayers,overlays,{collapsed: false} ).addTo(map);
		//L.control.opacity(overlays, {label: "Layers Opacity",}).addTo(map);
		if ( Object.entries(opacity).length !==  0) {
			L.control.opacity(opacity).addTo(map);
			//L.control.opacity(opacity, {collapsed: true}).addTo(map);
		}
	});
	</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_layerswitch_function($atts){
	//
	if ( $atts['providers'] ) {
		leafext_enqueue_providers();
		$providers = explode ( ',', $atts['providers'] );
		return leafext_providers_script($providers);
		//
	} else {
		$options = get_option('leafext_maps');
		if (!is_array($options )) return;
		//
		$tiles = array();
		$tiles_alloptions = array();
		//
		if ( $atts['tiles'] != "") {
			$only = array();
			$atts_maps = explode(',',$atts['tiles']);
			foreach ( $atts_maps as $atts_map ) {
				foreach ($options as $option) {
					if ($option['mapid'] == $atts_map) {
						$only[]=$option;
					}
				}
				$options = $only;
			}
		}

		foreach ($options as $option) {
			if (! is_null($option['options']) && !$option['options'] == "" ) {
				// L.tileLayer(extralayer.tile, {attribution: extralayer.attr, id: extralayer.mapid});
				$entry = 'attribution: "'.str_replace('"','\"',$option['attr']).'", id: "'.$option['mapid'].'", ';
				$entry = '"'.$option['tile'].'", {'.$entry.$option['options'].'}';
				if (! is_null($option['overlay']) && !$option['overlay'] == "" ) {
					$overlay = $option['overlay'];
				} else {
					$overlay = "";
				}
				if (! is_null($option['opacity']) && !$option['opacity'] == "" ) {
					$opacity = $option['opacity'];
				} else {
					$opacity = "";
				}
				$tiles_alloptions[] = array(
					'mapid' => '"'.$option['mapid'].'"',
					'overlay' => $overlay,
					'opacity' => $opacity,
					'options' => $entry,
				);
			} else {
				$tiles[] = $option;
			}
		}
		leafext_enqueue_opacity ();
		return leafext_layerswitch_script($tiles,$tiles_alloptions);
	}
}
add_shortcode('layerswitch', 'leafext_layerswitch_function' );
?>
