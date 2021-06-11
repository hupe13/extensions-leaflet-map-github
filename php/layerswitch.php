<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//[leaflet-map mapid=" "]
//Shortcode: [layerswitch]

function leafext_layerswitch_script($mylayers){
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
		var layers = [];
		map.eachLayer(function(layer) {
			if( layer instanceof L.TileLayer ) {
				map.removeLayer(layer);
				layer.options.attribution = defaultAttr;
				layers.push(layer);
				map.addLayer(layer);
			}
	 	});

		var mylayers = '.json_encode($mylayers).'
		mylayers.forEach(extralayer => {
			//console.log(extralayer);
			layers.push(L.tileLayer(extralayer.tile, {attribution: extralayer.attr, id: extralayer.mapid}));
		});

		var baselayers = {};
		layers.forEach(function(layer) {
			//console.log(layer);
			var id = layer.options.id;
			baselayers[id] = layer;
		});
		//console.log(baselayers);
		L.control.layers(baselayers).addTo(map);
	});
	</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_layerswitch_function(){
	$options = get_option('leafext_maps');
	//var_dump($options);
	if (!is_array($options )) return;
	//
	return leafext_layerswitch_script($options);
}
add_shortcode('layerswitch', 'leafext_layerswitch_function' );
?>
