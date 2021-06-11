<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [markerClusterGroup]

function leafext_clustergroup_script($featuregroups){
	$text = '
	<script>
		//console.log(featuregroups);
		var feat  = '.json_encode($featuregroups['feat']).';
		var groups= '.json_encode($featuregroups['groups']).';

		window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
		window.WPLeafletMapPlugin.push(function () {
			var map = window.WPLeafletMapPlugin.getCurrentMap();
			var map_id = map._leaflet_id;
			if ( WPLeafletMapPlugin.markers.length > 0 ) {
				var alle = new L.markerClusterGroup();
				var featGroups = [];
				let key;
				for (key in groups) {
					featGroups[key] = new L.featureGroup.subGroup(alle);
				}
				var control = new L.control.layers(null, null, { collapsed: false });
				for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
					if ( WPLeafletMapPlugin.markers[i]._map !== null ) {
						if (map_id == WPLeafletMapPlugin.markers[i]._map._leaflet_id) {
							//console.log("valid");
							var a = WPLeafletMapPlugin.markers[i];
							if (feat == "iconUrl") {
								for (key in groups) {
									//console.log("iconUrl");
									if (a.getIcon().options[feat].match (key)) {
										a.addTo(featGroups[key]);
										map.removeLayer(a);
									}
								}
							} else if (a.options.title !== undefined) {
								for (key in groups) {
									//console.log("title");
									if (a.options.title.match(key)) {
										a.addTo(featGroups[key]);
										map.removeLayer(a);
									}
								}
							}
						}
					}
				}
				for (key in groups) {
					control.addOverlay(featGroups[key], groups[key]);
				}
				control.addTo(map);
				alle.addTo(map);
				for (key in groups) {
					featGroups[key].addTo(map);
				}
			}
		});
		// title *
		// alt * (no)
		// iconUrl *
		</script>';
		$text = \JShrink\Minifier::minify($text);
		return "\n".$text."\n";
}

function leafext_clustergroup_function( $atts ){
	leafext_enqueue_markercluster ();
	wp_enqueue_script('leaflet.subgroup',
		plugins_url(
		'leaflet-plugins\Leaflet.FeatureGroup.SubGroup-1.0.2/leaflet.featuregroup.subgroup.js',
		LEAFEXT_PLUGIN_FILE),
		array('markercluster'),null);

	$featuregroups = shortcode_atts( array('feat' => false, 'strings' => false, 'groups' => false), $atts);
	//feat="iconUrl" strings="red green" groups="rot gruen"

	$cl_strings= array_map('trim', explode( ',', $featuregroups['strings'] ));
	$cl_groups = array_map('trim', explode( ',', $featuregroups['groups'] ));
	if ( count( $cl_strings ) != count( $cl_groups ) ) wp_die("strings and groups do not match.");

	$featuregroups = array(
		'feat' => sanitize_text_field($featuregroups['feat']),
		'groups' => array_combine($cl_strings, $cl_groups),
	);
	return leafext_clustergroup_script($featuregroups);
}
add_shortcode('markerClusterGroup', 'leafext_clustergroup_function' );
?>
