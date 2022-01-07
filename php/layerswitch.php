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
		var mylayers = '.json_encode($mylayers).';
		';
		foreach ($myfulllayers as $myfulllayer) {
			if ( $myfulllayer['overlay'] != "" ) {
				$text=$text.
				'overlays['.$myfulllayer['mapid'].'] = L.tileLayer('.$myfulllayer['options'].');';
			} else {
				$text=$text.
				'baselayers['.$myfulllayer['mapid'].'] = L.tileLayer('.$myfulllayer['options'].');';
			}
		}
		$text = $text.'
		mylayers.forEach(extralayer => {
			//console.log(extralayer);
			if (extralayer.overlay == 1) {
				overlays[extralayer.mapid] = L.tileLayer(extralayer.tile, {attribution: extralayer.attr, id: extralayer.mapid});
			} else {
				baselayers[extralayer.mapid] = L.tileLayer(extralayer.tile, {attribution: extralayer.attr, id: extralayer.mapid});
			}
		});

		//console.log(baselayers);
		//console.log(overlays);

		//L.control.layers(baselayers,overlays).addTo(map);
		L.control.layers(baselayers,overlays,{collapsed: false} ).addTo(map);
		//L.control.opacity(overlays, {label: "Layers Opacity",}).addTo(map);
		//L.control.opacity(overlays).addTo(map);
		//L.control.opacity(overlays, {collapsed: true}).addTo(map);
	});
	</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_layerswitch_function(){
	$options = get_option('leafext_maps');
	if (!is_array($options )) return;
	leafext_enqueue_opacity ();
	$maps = array();
	$mapsfull = array();
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
			$mapsfull[] = array(
				'mapid' => '"'.$option['mapid'].'"',
				'overlay' => $overlay,
				'options' => $entry,
			);
		} else {
			$maps[] = $option;
		}
	}
	return leafext_layerswitch_script($maps,$mapsfull);
}
add_shortcode('layerswitch', 'leafext_layerswitch_function' );
?>
