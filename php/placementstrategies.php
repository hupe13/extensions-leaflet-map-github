<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Parameter and Values
function leafext_placementstrategies_params() {
	$params = array(

		//elementsPlacementStrategy (default value 'clock-concentric') - defines the strategy for placing markers in cluster
		array('elementsPlacementStrategy',
			__('Strategy for placing markers in cluster: default - one-circle strategy (up to 8 elements, else spiral strategy)',
				"extensions-leaflet-map"),
			"default",
			array("default","spiral","one-circle","clock","clock-concentric","concentric","original-locations")),

		//in Example:
		array('shapes', __('Type of marker',"extensions-leaflet-map"), "marker",
			array("circle","marker")),

		//BEGIN Options that are valid for placement strategies 'concentric', 'clock' and 'clock-concentric'

		//firstCircleElements (default value 10) - the number of elements in the first circle
		array('firstCircleElements', __('Number of elements in the first circle',"extensions-leaflet-map"), "10",
			array("6","8","10","12")),

		//elementsMultiplier (default value 1.5) - the multiplicator of elements number for the next circle
		array('elementsMultiplier', __('Multiplicator of elements number for the next circle',"extensions-leaflet-map"), "1.5",
			array("1.2","1.4","1.5","1.6","1.7","1.8","1.9","2")),

		//spiderfyDistanceSurplus (default value 30) - the value to be added to each new circle distance value
		array('spiderfyDistanceSurplus', __('Value to be added to each new circle distance value',"extensions-leaflet-map"), "30",
			array("0","5","10","15","20","25","30","35")),

		//helpingCircles (default value true) - switch drawing helping circles on
		array('helpingCircles', __('Switch drawing helping circles on',"extensions-leaflet-map"), true, 1),

		//helpingCircleOptions (default value { fillOpacity: 0, color: 'grey', weight: 0.6 } ) - the style object for helpingCircle element

		//END Options that are valid for placement strategies 'concentric', 'clock' and 'clock-concentric'

		// <h4>MARKERCLUSTER</h4>
		// <p class="select-row">showCoverageOnHover:</p>

		// <p class="select-row">spiderfyOnMaxZoom:</p>
		array('spiderfyOnMaxZoom', __('When you click a cluster at the bottom zoom level we spiderfy it so you can see all of its markers.',"extensions-leaflet-map"), true, 1),

		// <p class="select-row">zoomToBoundsOnClick:</p>
		// <p class="select-row">maxClusterRadius:</p>
		array('maxClusterRadius', __('The maximum radius that a cluster will cover from the central marker (in pixels). Decreasing will make more, smaller clusters.',"extensions-leaflet-map"), "80",
			array("20","30","40","50","60","70","80","100","120","150")),

		//Sonstiges
		array('maxZoom', __('Max zoom of the map',"extensions-leaflet-map"), "10",
			array("18","17","16","15","14","13","12","11","10","9","8","7","6")),

	);

	return $params;
}

//Shortcode: [placementstrategies]

function leafext_placementstrategies_script($params){
	//var_dump($params);wp_die();
	//var_dump($params['elementsPlacementStrategy']);wp_die();
	$text = '
<script>
window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
window.WPLeafletMapPlugin.push(function () {
	var map = window.WPLeafletMapPlugin.getCurrentMap();
	//console.log(map);
	map.options.maxZoom = '.$params['maxZoom'].';
	var map_id = map._leaflet_id;

	function getRandomColor() {
		var letters = "0123456789ABCDEF";
		var color = "#";
		for (var i = 0; i < 6; i++) {
			color += letters[Math.floor(Math.random() * 16)];
		}
		return color;
	}

	if ( WPLeafletMapPlugin.markers.length > 0 ) {
		//console.log("WPLeafletMapPlugin.markers.length "+WPLeafletMapPlugin.markers.length);
		var clmarkers = L.markerClusterGroup({
			spiderLegPolylineOptions: { weight: 0 },

			elementsPlacementStrategy: "'.$params['elementsPlacementStrategy'].'",
			helpingCircles: '.$params['helpingCircles'].',

			spiderfyDistanceSurplus: '.$params['spiderfyDistanceSurplus'].',
			spiderfyDistanceMultiplier: 1,

			elementsMultiplier: '.$params['elementsMultiplier'].',
			firstCircleElements: '.$params['firstCircleElements'].',

			//
			maxClusterRadius: function(radius)
				{ return '.$params['maxClusterRadius'].'; },
			spiderfyOnMaxZoom: '.$params['spiderfyOnMaxZoom'].',
		});

		for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
			if ( WPLeafletMapPlugin.markers[i]._map !== null ) {
				if (map_id == WPLeafletMapPlugin.markers[i]._map._leaflet_id) {
					var a = WPLeafletMapPlugin.markers[i];
					';
					if ($params['shapes'] == "circle") {
						$text=$text.'
						map.createPane("locationMarker");
						map.getPane("locationMarker").style.zIndex = 610;
						var circle = L.circleMarker(a.getLatLng(), {
							fillOpacity: 0.7,
							radius: 8,
							fillColor: getRandomColor(),
							color: "grey",
							pane: "locationMarker",
						});
						//console.log(circle);
						circle.bindPopup(a.getPopup());
						clmarkers.addLayer(circle);
						//console.log(circle._popup);
					';
					} else {
						$text=$text.'
						clmarkers.addLayer(a);';
					}
					$text=$text.'
					map.removeLayer(a);
				}
			}
		}
		clmarkers.addTo( map );
		WPLeafletMapPlugin.markers.push( clmarkers );
	}
});
</script>';
//$text = \JShrink\Minifier::minify($text);
return "\n".$text."\n";
}

function leafext_placementstrategies_case ($array) {
	$params=array_keys(leafext_placementstrategies_settings());
	foreach ($params as $param) {
		if (strtolower($param) != $param) {
			if (isset($array[strtolower($param)])) {
				$array[$param] = $array[strtolower($param)];
				unset($array[strtolower($param)]);
			}
		}
	}
	return $array;
}

function leafext_placementstrategies_settings() {
	$params = leafext_placementstrategies_params();
	$defaults=array();
	foreach($params as $param) {
		$defaults[$param[0]] = $param[2];
	}
	$options = shortcode_atts($defaults, get_option('leafext_placementparams'));
	return $options;
}

function leafext_enqueue_placementstrategies () {
	wp_enqueue_script('placementstrategies',
		plugins_url('leaflet-plugins/Leaflet.MarkerCluster.PlacementStrategies/leaflet-markercluster.placementstrategies.js',LEAFEXT_PLUGIN_FILE),
		array('markercluster'),null );
}

function leafext_placementstrategies_function( $atts ){
	leafext_enqueue_markercluster ();
	leafext_enqueue_placementstrategies ();
	$atts1=leafext_placementstrategies_case($atts);
	$options = shortcode_atts( leafext_placementstrategies_settings(), $atts1);
	//var_dump($options);wp_die();
	return leafext_placementstrategies_script($options);
}
add_shortcode('placementstrategies', 'leafext_placementstrategies_function' );
?>
