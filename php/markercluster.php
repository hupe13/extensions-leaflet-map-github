<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [cluster]

function leafext_cluster_script($params){
	$text = '
	<script>
//console.log("cluster.radius "+'.$params['radius'].');
//console.log("cluster.spiderfy "+'.$params['spiderfy'].');
//console.log("cluster.zoom "+'.$params['zoom'].');

window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
window.WPLeafletMapPlugin.push(function () {
	var map = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;
	if ( WPLeafletMapPlugin.markers.length > 0 ) {
		//console.log(map.options.maxZoom);
		if (typeof map.options.maxZoom == "undefined")
			map.options.maxZoom = 19;
		var myzoom = '.$params['zoom'].';
		if (myzoom > map.options.maxZoom) myzoom = map.options.maxZoom;
		//console.log(myzoom);
		if ( '.$params['zoom'].' == 0 ) myzoom = false;
		var clmarkers = L.markerClusterGroup({
			maxClusterRadius: function(radius)
			//	{ return 60; },
			//	{return ((radius <= 13) ? 50 : 30);},
				{ return '.$params['radius'].'; },
			spiderfyOnMaxZoom: '.$params['spiderfy'].',
			// ab welcher Zoomstufe es nicht mehr tiefer geht, dann wird gespidert.
			disableClusteringAtZoom: myzoom,
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

function leafext_cluster_function( $atts ){
	leafext_enqueue_markercluster ();

	$defaults = array(
		'zoom'     => "17",
		'radius'   => "80",
		'spiderfy' => 1,
	);
	$def_settings = get_option('leafext_cluster');
	$params = shortcode_atts($defaults, $def_settings);
	$params = shortcode_atts($params, $atts);
	$params['spiderfy'] = ((bool)$params['spiderfy']) ? "true" : "false";

	return leafext_cluster_script($params);
}
add_shortcode('cluster', 'leafext_cluster_function' );
?>
