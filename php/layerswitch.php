<?php
/**
 * Functions for layerswitch shortcode
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//[leaflet-map mapid=" "]
//Shortcode: [layerswitch tiles= providers= ]

function leafext_layerswitch_begin_script() {
	$text = '<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var attributions = Object.keys(map.attributionControl._attributions);
		//console.log(attributions);
		//console.log(String(attributions));
		var defaultAttr = String(attributions);
		map.attributionControl._attributions = {};
		var baselayers = {};
		var overlays = {};
		var opacity = {};

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
		});';
		return $text;
}

function leafext_layerswitch_tiles_script($mylayers,$myfulllayers){
	$text = '
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
	';
	return $text;
}

function leafext_providers_fkt_script() {
	//https://github.com/leaflet-extras/leaflet-providers/blob/57d69ea6e75834235c607eab72cbb4da862ddc96/preview/preview.js#L56';
	$text = "
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
	return $text;
}

function leafext_providers_script($maps) {
	$regtiles = get_option('leafext_providers',array());
	$text = "";
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
	return $text;
}

function leafext_opacity_script($opacities) {
	$text = '
		var opacities = '.json_encode(explode(',',$opacities)).';
		//console.log(opacities);
		opacities.forEach(function(opid) {
    	//console.log(opacity);
			if (typeof baselayers[opid] !== "undefined") {
				opacity[opid] = baselayers[opid];
			}
			if (typeof overlays[opid] !== "undefined") {
				opacity[opid] = overlays[opid];
			}
		});
	';
	return $text;
}

function leafext_layerswitch_end_script() {
	$text = '
		//console.log(baselayers);
		//console.log(overlays);

		let controlwitdh = document.getElementsByClassName( "leaflet-right");
		let maxcontrolwidth = 0;
		for(var i=0, len=controlwitdh.length; i<len; i++)	{
    	var computed = getComputedStyle( controlwitdh[i], null );
			var width = parseInt(computed.getPropertyValue( "width" ));
			if (width > maxcontrolwidth) {
		    maxcontrolwidth = width;
		  }
		}
		//console.log( maxcontrolwidth );
		//console.log(window.innerWidth);
		var collapse = false;
		if (window.innerWidth/5 < maxcontrolwidth) {
			collapse = true;
		}
		L.control.layers(baselayers,overlays,{collapsed: collapse} ).addTo(map);
		if ( Object.entries(opacity).length !==  0) {
			L.control.opacity(opacity, {collapsed: collapse}).addTo(map);
		}
	});
	</script>';
	return $text;
}

function leafext_layerswitch_function($atts){
	$providers = "";
	$tiles = "";
	$tiles_alloptions = "";
	if (is_array($atts)){
		if ( array_key_exists('providers',$atts) ) {
			leafext_enqueue_providers();
			$providers = explode ( ',', $atts['providers'] );
		}
	}
	$options = get_option('leafext_maps');
	if ( is_array($options )) {
		//
		$tiles = array();
		$tiles_alloptions = array();
		//
		if (is_array($atts)) {
			if ( array_key_exists('tiles',$atts) ) {
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
			} else {
				if ( is_array($providers) ) {
					$options = array();
				}
			}
		}
		foreach ($options as $option) {
			if (array_key_exists('options', $option) && $option['options'] != "" ) {
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
	}
	if ( !is_array($tiles) && !is_array($tiles_alloptions) && !is_array($providers) ) return;
	leafext_enqueue_opacity ();

	$text = leafext_layerswitch_begin_script();
	if ( is_array($tiles) || is_array($tiles_alloptions) ) {
		$text = $text.leafext_layerswitch_tiles_script($tiles,$tiles_alloptions);
	}
	if (is_array($providers) ) {
		$text = $text.leafext_providers_fkt_script();
		$text = $text.leafext_providers_script($providers);
	}
	if (is_array($atts)){
		if ( array_key_exists('opacity',$atts) ) {
			$text = $text.leafext_opacity_script($atts['opacity']);
		}
	}
	$text = $text.leafext_layerswitch_end_script();
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}
add_shortcode('layerswitch', 'leafext_layerswitch_function');
?>
