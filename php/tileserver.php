<?php
/**
* Functions for layerswitch shortcode
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
			'name' => 'MapTiles API',
			'keys' => array(
				'apikey' =>  '<insert key here>',
			),
		),
		array(
			'name' => 'Thunderforest',
			'keys' => array(
				'apikey' =>  '<insert api_key here>',
			),
		),
		//Esri/ArcGIS - register
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
		//Stadia Maps - register
	);
	return $tiles;
}

function leafext_providers_regnames () {
	$tiles = leafext_providers_registration ();
	return array_column($tiles, 'name');
}

//[leaflet-map mapid=" "]
//Shortcodes:
//[layerswitch mapids= providers= tiles= opacity=]

function leafext_layerswitch_begin_script() {
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
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
				if( layer.options.id ) {
					var defaultname = layer.options.id;
				} else {
					var defaultname = "Default";
				}
				baselayers[defaultname] = layer;
				map.removeLayer(layer);
				map.addLayer(layer);
			}
		});
		map.on("baselayerchange", function (e) {
			//console.log("baselayerchange");
			var layer = e.layer;
			if (!map.hasLayer(layer)) {
				return;
			}
			if (layer.options.minZoom > 1 && map.getZoom() > layer.options.minZoom) {
				map.setZoom(layer.options.minZoom);
			}
			// console.log("map min zoom "+map.options.minZoom);
			// console.log("map max zoom "+map.options.maxZoom);
			// console.log("layer min zoom "+layer.options.minZoom);
			// console.log("layer max zoom "+layer.options.maxZoom);
			map.options.minZoom=layer.options.minZoom;
			map.options.maxZoom=layer.options.maxZoom;
		});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n";
	return $text;
}

//defined tile server in tab=tileswitch
function leafext_layerswitch_tiles_script($tiles){
	$text = '<!--';
	ob_start();
	?>/*<script>*/
	var tilelayers = <?php echo json_encode($tiles); ?>;
	//console.log(tilelayers);
	tilelayers.forEach(tilelayer => {
		var layer = L.tileLayer(tilelayer.tile, tilelayer.options);
		if (tilelayer.overlay == 1) {
			overlays[tilelayer.mapid] = layer;
			if (tilelayer.opacity == 1) {
				opacity[tilelayer.mapid] = overlays[tilelayer.mapid];
			}
		} else {
			baselayers[tilelayer.mapid] = layer;
			if (tilelayer.opacity == 1) {
				opacity[tilelayer.mapid] = baselayers[tilelayer.mapid];
			}
		}
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n";
	return $text;
}

function leafext_providers_fkt_script() {
	//https://github.com/leaflet-extras/leaflet-providers/blob/b7be4769ccb5cd66cf7c5bd2f6e1042ba7a10b5c/preview/preview.js#L60-L70
	$text = '<!--';
	ob_start();
	?>/*<script>*/
	function isOverlay (providerName, layer) {
		if (layer.options.opacity && layer.options.opacity < 1) {
			return true;
		}
		var overlayPatterns = [
			'^(OpenWeatherMap|OpenSeaMap|OpenSnowMap)',
			'OpenMapSurfer.(Hybrid|AdminBounds|ContourLines|Hillshade|ElementsAtRisk)',
			'Stamen.Toner(Hybrid|Lines|Labels)',
			'^JusticeMap',
			'OpenAIP',
			'OpenRailwayMap',
			'OpenFireMap',
			'SafeCast',
			'WaymarkedTrails.(hiking|cycling|mtb|slopes|riding|skating)'
		];
		return providerName.match('(' + overlayPatterns.join('|') + ')') !== null;
	};
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n";
	return $text;
}

//providers
function leafext_providers_script($mapids,$providers) {
	$regtiles = get_option('leafext_providers',array());
	$text = "";
	if (count($providers) == count($mapids)) {
		$names = array_combine($providers,$mapids);
	} else {
		$names = array_combine($providers,$providers);
	}
	foreach ($providers as $provider) {
		$id = array_search(explode ( '.', $provider )[0], array_column($regtiles, 'name'));
		if ($id !== false) {
			$text = $text.'var layer = L.tileLayer.provider("'.$provider.'", {';
				foreach ( $regtiles[$id]['keys'] as $key => $value ) {
					$text = $text.$key.': "';
					$text = $text.$value.'",';
				}
				$text = $text.'
			} );';
		} else {
			$text = $text.'var layer = L.tileLayer.provider("'.$provider.'");';
		}
		$text = $text.'
		if (isOverlay("'.$provider.'", layer)) {
			overlays["'.$names[$provider].'"] = layer;
		} else {
			baselayers["'.$names[$provider].'"] = layer;
		}';
	}
	return $text;
}

function leafext_opacity_script($opacities) {
	$text = '<!--';
	ob_start();
	?>/*<script>*/
	var opacities = <?php echo json_encode($opacities);?>;
	//console.log(opacities);
	opacities.forEach(function(opid) {
		if ( baselayers[opid] ) {
			opacity[opid] = baselayers[opid];
		}
		if ( overlays[opid] ) {
			opacity[opid] = overlays[opid];
		}
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n";
	return $text;
}

function leafext_layerswitch_end_script() {
	$text = '<!--';
	ob_start();
	?>/*<script>*/
	//console.log(baselayers);
	//console.log(overlays);

	L.control.layers(baselayers,overlays).addTo(map);
	if ( Object.entries(opacity).length !==  0) {
		L.control.opacity(opacity,{collapsed:true,}).addTo(map);
	}
});
<?php
$javascript = ob_get_clean();
$text = $text . $javascript . '//-->'."\n".'</script>';
return "\n".$text."\n";
}

function leafext_layerswitch_function($atts,$content,$shortcode) {
	//var_dump($atts,$content,$shortcode);
	$text = leafext_should_interpret_shortcode($shortcode,$atts);
	if ( $text != "" ) {
		return $text;
	} else {
		$tiles = array();
		$mapids = array();
		$providers = array();
		$opacities = array();
		//
		$defined_tileservers = get_option('leafext_maps',array());
		//
		if (is_array($atts)){
			if ( array_key_exists('tiles',$atts) && count($defined_tileservers) > 0 ) {
				$only = array();
				$atts_maps = explode(',',$atts['tiles']);
				foreach ( $atts_maps as $atts_map ) {
					foreach ($defined_tileservers as $defined_tileserver) {
						if ($defined_tileserver['mapid'] == $atts_map) {
							$only[]=$defined_tileserver;
						}
					}
				}
				$defined_tileservers = $only;
			}
			//
			if ( array_key_exists('providers',$atts) ) {
				if ( ! array_key_exists('tiles',$atts )) {
					$defined_tileservers = array();
				}
				leafext_enqueue_providers();
				$providers = explode ( ',', $atts['providers'] );
				if (array_key_exists('mapids',$atts) ) {
					$mapids = explode ( ',', $atts['mapids'] );
				}
				if (count($providers) != count($mapids)) {
					$mapids = $providers;
				}

				if ( array_key_exists('opacity',$atts) ) {
					$atts_opacity = explode ( ',', $atts['opacity'] );
					for ($i = 0; $i < count($providers); $i++) {
						if (in_array($providers[$i],$atts_opacity)) {
							$opacities[] = $mapids[$i];
						}
						if (in_array($mapids[$i],$atts_opacity)) {
							$opacities[] = $mapids[$i];
						}
					}
				}
			}
		}

		foreach ($defined_tileservers as $defined_tileserver) {
			$overlay = $defined_tileserver['overlay'] == "1" ?  "1" : "";
			$opacity = $defined_tileserver['opacity'] == "1" ?  "1" : "";

			$tileoptions = array();
			$tileoptions['attribution'] = $defined_tileserver['attr'];

			$javas=explode(',',str_replace(' ','',$defined_tileserver['options']));

			$key="";
			$value="";
			foreach ($javas as $java) {
			  $parts = [];
			  $tok = strtok($java, ":");
			  while ($tok !== false) {
			    $parts[] = $tok;
			    $tok = strtok(":");
			  }
			  if (count($parts) > 1 ) {
			    if ($key != "") {
			      $tileoptions[$key] = trim($value,',');
			    }
			    $key = $parts[0];
			    $value="";
			    for ($i = 1; $i < count($parts); $i++) {
			      $value = $value.':'.$parts[$i];
			      $value = trim($value,':');
			    }
			  } else {
			    if (count($parts) == 1) {
			      $value = $value.','.$parts[0];
			    }
			  }
			}
			if ($key != "") {
			  $tileoptions[$key] = trim($value,',');
			}

			$tiles[] = array(
				'mapid' => $defined_tileserver['mapid'],
				'tile' => $defined_tileserver['tile'],
				'overlay' => $overlay,
				'opacity' => $opacity,
				'options' => $tileoptions,
			);
		}

		if ( count($tiles) == 0 && count($providers) == 0 ) return;
		leafext_enqueue_opacity ();

		$text = leafext_layerswitch_begin_script();
		if ( count($tiles) > 0 ) {
			$text = $text.leafext_layerswitch_tiles_script($tiles);
		}
		if (count($providers) > 0 ) {
			$text = $text.leafext_providers_fkt_script();
			$text = $text.leafext_providers_script($mapids,$providers);
		}
		if (is_array($atts)){
			if ( array_key_exists('opacity',$atts) ) {
				$opacities = array_unique(array_merge($opacities,explode(',',$atts['opacity'])));
				$text = $text.leafext_opacity_script($opacities);
			}
		}
		$text = $text.leafext_layerswitch_end_script();
		$text = \JShrink\Minifier::minify($text);
		return "\n".$text."\n";
	}
}
add_shortcode('layerswitch', 'leafext_layerswitch_function');
