<?php
/**
 * Functions for cluster and placementstrategies shortcode
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Parameter and Values
function leafext_cluster_params() {
	$params = array(

		//showCoverageOnHover: When you mouse over a cluster it shows the bounds of its markers.
		array('showCoverageOnHover', __('When you mouse over a cluster it shows the bounds of its markers.',"extensions-leaflet-map"), true, 1),

		//zoomToBoundsOnClick: When you click a cluster we zoom to its bounds.
		array('zoomToBoundsOnClick', __('When you click a cluster we zoom to its bounds.',"extensions-leaflet-map"), true, 1),

		//spiderfyOnMaxZoom: When you click a cluster at the bottom zoom level we spiderfy it so you can see all of its markers. (Note: the spiderfy occurs at the current zoom level if all items within the cluster are still clustered at the maximum zoom level or at zoom specified by disableClusteringAtZoom option).
		array('spiderfyOnMaxZoom', __('When you click a cluster at the bottom zoom level we spiderfy it so you can see all of its markers.',"extensions-leaflet-map"), true, 1),

		//removeOutsideVisibleBounds: Clusters and markers too far from the viewport are removed from the map for performance.
		array('removeOutsideVisibleBounds', __('Clusters and markers too far from the viewport are removed from the map for performance.',"extensions-leaflet-map"), true, 1),

		//animate: Smoothly split / merge cluster children when zooming and spiderfying. If L.DomUtil.TRANSITION is false, this option has no effect (no animation is possible).
		//array('animate', __('Smoothly split / merge cluster children when zooming and spiderfying.',"extensions-leaflet-map"), true, 1),

		//

		//animateAddingMarkers: If set to true (and animate option is also true) then adding individual markers to the MarkerClusterGroup after it has been added to the map will add the marker and animate it into the cluster. Defaults to false as this gives better performance when bulk adding markers. addLayers does not support this, only addLayer with individual Markers.


		//disableClusteringAtZoom: If set, at this zoom level and below, markers will not be clustered. This defaults to disabled.
		array('disableClusteringAtZoom', __('If set, at this zoom level and below, markers will not be clustered. If 0, it is disabled.',"extensions-leaflet-map"), "17",
			//array("18","17","16","15","14","13","12","11","10","9","8","7","6","0")),
			array(18,17,16,15,14,13,12,11,10,9,8,7,6,0)),

		//maxClusterRadius: The maximum radius that a cluster will cover from the central marker (in pixels). Default 80. Decreasing will make more, smaller clusters. You can also use a function that accepts the current map zoom and returns the maximum cluster radius in pixels.
		array('maxClusterRadius', __('The maximum radius that a cluster will cover from the central marker (in pixels). Decreasing will make more, smaller clusters.',"extensions-leaflet-map"), "80",
			//array("20","30","40","50","60","70","80","100","120","150")),
			array(20,30,40,50,60,70,80,100,120,150)),

		//polygonOptions: Options to pass when creating the L.Polygon(points, options) to show the bounds of a cluster. Defaults to empty, which lets Leaflet use the default Path options.

		//singleMarkerMode: If set to true, overrides the icon for all added markers to make them appear as a 1 size cluster. Note: the markers are not replaced by cluster objects, only their icon is replaced. Hence they still react to normal events, and option disableClusteringAtZoom does not restore their previous icon (see #391).

		//spiderLegPolylineOptions: Allows you to specify PolylineOptions to style spider legs. By default, they are { weight: 1.5, color: '#222', opacity: 0.5 }.

		//spiderfyDistanceMultiplier: Increase from 1 to increase the distance away from the center that spiderfied markers are placed. Use if you are using big marker icons (Default: 1).

		//iconCreateFunction: Function used to create the cluster icon.

		//spiderfyShapePositions: Function used to override spiderfy default shape positions.

		//clusterPane: Map pane where the cluster icons will be added. Defaults to L.Marker's default (currently 'markerPane'). See the pane example.

		);

	return $params;
}

//Shortcode: [cluster]
function leafext_cluster_script($params){
	$text = '
	<script>
	//console.log("cluster.zoom "+'.$params['disableClusteringAtZoom'].');
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
	var map = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;
	if ( WPLeafletMapPlugin.markers.length > 0 ) {
		//console.log("map.options.maxZoom "+map.options.maxZoom);
		if (typeof map.options.maxZoom == "undefined")
			map.options.maxZoom = 19;
		var clmarkers = L.markerClusterGroup({';
			$text = $text.leafext_java_params ($params);
			$text = $text.'
		});

		//console.log("WPLeafletMapPlugin.markers.length "+WPLeafletMapPlugin.markers.length);
		for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
			if ( WPLeafletMapPlugin.markers[i]._map !== null ) {
				if (map_id == WPLeafletMapPlugin.markers[i]._map._leaflet_id) {
					var a = WPLeafletMapPlugin.markers[i];
					clmarkers.addLayer(a);
					map.removeLayer(a);
				}
			}
		}
		clmarkers.addTo( map );
		WPLeafletMapPlugin.markers.push( clmarkers );
	}
});
</script>';
$text = \JShrink\Minifier::minify($text);
return "\n".$text."\n";
}

function leafext_cluster_settings() {
	$params = leafext_cluster_params();
	$defaults=array();
	foreach($params as $param) {
		$defaults[$param[0]] = $param[2];
	}
	$options = shortcode_atts($defaults,
		leafext_array_replace_keys(get_option('leafext_cluster'),
			['zoom' => 'disableClusteringAtZoom',
			'radius' => 'maxClusterRadius',
			'spiderfy' => 'spiderfyOnMaxZoom']));
	return $options;
}

function leafext_cluster_atts ($atts) {
	//Ersetze alt - neu, vorher interpretiere "parameter" als true und "!parameter" als false
	$atts1 = leafext_array_replace_keys(leafext_clear_params($atts),
		['zoom' => 'disableClusteringAtZoom',
		'radius' => 'maxClusterRadius',
		'spiderfy' => 'spiderfyOnMaxZoom']);
	//bereinige die nur Kleinbuchstaben vom Shortcode zu gross und klein wie der Java-Parameter ist
	$atts2=leafext_case(array_keys(leafext_cluster_settings()),leafext_clear_params($atts1));
	//gleiche mit eigenen settings und Plugin defaults ab
	$options = shortcode_atts( leafext_cluster_settings(), $atts2);
	return($options);
}

function leafext_cluster_function( $atts ){
	leafext_enqueue_markercluster ();
	//erzeuge Javascript
	return leafext_cluster_script(leafext_cluster_atts ($atts));
}
add_shortcode('cluster', 'leafext_cluster_function' );
?>
