/**
 * Javascript function for Shortcodes parentgroup
 *
 * @package Extensions for Leaflet Map
 */

/**
 * Create Javascript code for parentgroup
 */

function leafext_parentgroup_js(parent, childs, grouptext, visible) {
	var map    = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;

	console.log( "parent " + parent + " on map " + map_id + "; childs:",childs,"; visible:",visible );

	if ( WPLeafletMapPlugin.markers.length > 0 ) {
		console.log( "leaflet markers" );
		var parentGroup = L.featureGroup();
		parentGroup.addTo( map );
		control[map_id].addOverlay( parentGroup, '<b>' + parent + '</b>' );
		for (key in childs) {
			featGroups[map_id][childs[key]].remove();
			control[map_id].removeLayer( featGroups[map_id][childs[key]] );
			featGroups[map_id][childs[key]].setParentGroupSafe( parentGroup );
			control[map_id].addOverlay( featGroups[map_id][childs[key]], '__' + leafext_unescapeHTML( [grouptext[childs[key]]] ) );
		}

		for (key in childs) {
			if (visible[childs[key]] == "1") {
				featGroups[map_id][childs[key]].addTo( map );
			}
		}
	}
	var geojsons = window.WPLeafletMapPlugin.geojsons;
	if (geojsons.length > 0) {
		console.log( "geojsons " + geojsons.length );
		var geocount    = geojsons.length;
		var parentGroup = L.featureGroup();
		parentGroup.addTo( map );
		control[map_id].addOverlay( parentGroup, '<b>' + parent + '</b>' );
		for (var j = 0, len = geocount; j < len; j++) {
			var geojson = geojsons[j];
			if (map._leaflet_id == geojson._map._leaflet_id) {
				geojson.on(
					"ready",
					function () {
						for (key in childs) {
							console.log( childs[key] );
							console.log( featGroups[map_id][childs[key]] );
							featGroups[map_id][childs[key]].remove();
							control[map_id].removeLayer( featGroups[map_id][childs[key]] );
							featGroups[map_id][childs[key]].setParentGroupSafe( parentGroup );
							control[map_id].addOverlay( featGroups[map_id][childs[key]], '__' + leafext_unescapeHTML( [grouptext[childs[key]]] ) );
						}

						for (key in childs) {
							if (visible[childs[key]] == "1") {
								featGroups[map_id][childs[key]].addTo( map );
							}
						}
					}
				); // geojson ready
			}
		}
	}
}
