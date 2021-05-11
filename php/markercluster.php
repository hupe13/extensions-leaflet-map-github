<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [cluster]

function leafext_cluster_script($params){
	include_once LEAFEXT_PLUGIN_DIR . '/pkg/JShrink/Minifier.php';
	$text = '
	<script>
console.log("cluster.radius "+'.$params['radius'].');
console.log("cluster.spiderfy "+'.$params['spiderfy'].');
console.log("cluster.zoom "+'.$params['zoom'].');

window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
window.WPLeafletMapPlugin.push(function () {
	var map = window.WPLeafletMapPlugin.getCurrentMap();
	if ( WPLeafletMapPlugin.markers.length > 0 ) {
		map.options.maxZoom = 19;
		var myzoom = '.$params['zoom'].';
		if ( '.$params['zoom'].' == "0" ) myzoom = false;
		clmarkers = L.markerClusterGroup({
			maxClusterRadius: function(radius)
			//	{ return 60; },
			//	{return ((radius <= 13) ? 50 : 30);},
				{ return '.$params['radius'].'; },
			spiderfyOnMaxZoom: '.$params['spiderfy'].',
			// ab welcher Zoomstufe es nicht mehr tiefer geht, dann wird gespidert.
			disableClusteringAtZoom: myzoom,
		});
		for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
			var a = WPLeafletMapPlugin.markers[i];
			clmarkers.addLayer(a);
			map.removeLayer(a);
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
	wp_enqueue_style( 'markercluster.default',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.0/css/MarkerCluster.Default.min.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_style( 'markercluster',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.0/css/MarkerCluster.min.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_script('markercluster',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.0/js/leaflet.markercluster.js',LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'),null );

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
