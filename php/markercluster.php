<?php
/**
* Functions for cluster and placementstrategies shortcode
* extensions-leaflet-map
* clusters markers from leaflet-marker, -geojson, -gpx, -kml
*/

// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Parameter and Values
function leafext_cluster_params() {
	$params = array(

		//showCoverageOnHover: When you mouse over a cluster it shows the bounds of its markers.
		array(
			'param' => 'showCoverageOnHover',
			'desc' =>  __('When you mouse over a cluster it shows the bounds of its markers.',"extensions-leaflet-map"),
			'default' => true,
			'values' => 1,
		),

		//zoomToBoundsOnClick: When you click a cluster we zoom to its bounds.
		array(
			'param' => 'zoomToBoundsOnClick',
			'desc' => __('When you click a cluster we zoom to its bounds.',"extensions-leaflet-map"),
			'default' => true,
			'values' => 1,
		),

		//spiderfyOnMaxZoom: When you click a cluster at the bottom zoom level we spiderfy it so you can see all of its markers. (Note: the spiderfy occurs at the current zoom level if all items within the cluster are still clustered at the maximum zoom level or at zoom specified by disableClusteringAtZoom option).
		array(
			'param' => 'spiderfyOnMaxZoom',
			'desc' => __('When you click a cluster at the bottom zoom level we spiderfy it so you can see all of its markers.',"extensions-leaflet-map"),
			'default' => true,
			'values' => 1,
		),

		//removeOutsideVisibleBounds: Clusters and markers too far from the viewport are removed from the map for performance.
		array(
			'param' => 'removeOutsideVisibleBounds',
			'desc' => __('Clusters and markers too far from the viewport are removed from the map for performance.',"extensions-leaflet-map"),
			'default' => true,
			'values' => 1,
		),

		//animate: Smoothly split / merge cluster children when zooming and spiderfying. If L.DomUtil.TRANSITION is false, this option has no effect (no animation is possible).
		//array('animate', __('Smoothly split / merge cluster children when zooming and spiderfying.',"extensions-leaflet-map"), true, 1),
		//animateAddingMarkers: If set to true (and animate option is also true) then adding individual markers to the MarkerClusterGroup after it has been added to the map will add the marker and animate it into the cluster. Defaults to false as this gives better performance when bulk adding markers. addLayers does not support this, only addLayer with individual Markers.

		//disableClusteringAtZoom: If set, at this zoom level and below, markers will not be clustered. This defaults to disabled.
		array(
			'param' => 'disableClusteringAtZoom',
			'desc' => __('If set, at this zoom level and below, markers will not be clustered. If 0, it is disabled.',"extensions-leaflet-map"),
			'default' => "17",
			'values' => array("18","17","16","15","14","13","12","11","10","9","8","7","6","0"),
		),

		//maxClusterRadius: The maximum radius that a cluster will cover from the central marker (in pixels). Default 80. Decreasing will make more, smaller clusters. You can also use a function that accepts the current map zoom and returns the maximum cluster radius in pixels.
		array(
			'param' => 'maxClusterRadius',
			'desc' => __('The maximum radius that a cluster will cover from the central marker (in pixels). Decreasing will make more, smaller clusters.',
				"extensions-leaflet-map"),
			'default' => "80",
			'values' => array("20","30","40","50","60","70","80","100","120","150"),
		),

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
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var clmarkers = L.markerClusterGroup({
			<?php echo leafext_java_params ($params);?>
		});
		leafext_markercluster_js(clmarkers);
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_cluster_settings() {
	$defaults=array();
	$params = leafext_cluster_params();
	foreach($params as $param) {
		$defaults[$param['param']] = $param['default'];
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
	$atts2=leafext_case(array_keys(leafext_cluster_settings()),$atts1);
	//gleiche mit eigenen settings und Plugin defaults ab
	$options = shortcode_atts( leafext_cluster_settings(), $atts2);
	//if ($options['disableClusteringAtZoom'] == "0") unset($options['disableClusteringAtZoom'] );
	return($options);
}

function leafext_cluster_function($atts,$content,$shortcode) {
	$text = leafext_should_interpret_shortcode($shortcode,$atts);
	if ( $text != "" ) {
		return $text;
	} else {
		leafext_enqueue_markercluster ();
		leafext_enqueue_leafext("markercluster");
		//erzeuge Javascript
		return leafext_cluster_script(leafext_cluster_atts ($atts));
	}
}
add_shortcode('cluster', 'leafext_cluster_function' );
