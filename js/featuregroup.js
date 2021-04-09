//console.log(featuregroups);
feat  =featuregroups.feat;
groups=featuregroups.groups;

window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
window.WPLeafletMapPlugin.push(function () {
	var map = window.WPLeafletMapPlugin.getCurrentMap();
	if ( WPLeafletMapPlugin.markers.length > 0 ) {

		var alle = new L.markerClusterGroup();
		
		var featGroups = [];
		let key;
		for (key in groups) {
			featGroups[key] = new L.featureGroup.subGroup(alle);
		}
		
		var control = new L.control.layers(null, null, { collapsed: false });

		for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
			var a = WPLeafletMapPlugin.markers[i];
			//console.log(a.options);
			for (key in groups) {
				if (a.getIcon().options[feat].match (key))
					a.addTo(featGroups[key]);
			}
			map.removeLayer(a);
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
// 'title' *
// 'alt' *
// 'iconUrl' *