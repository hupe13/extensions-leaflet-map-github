/**
 * Javascript function for targetmarker
 *
 * @package Extensions for Leaflet Map
 */

// jump to leaflet-marker, leaflet-extramarker OR leaflet-geojson with lat and lng in query_string
function leafext_target_get_lanlng_js(lat,lng,target,mapid,zoom,debug) {
	console.log( "leafext_target_get_lanlng_js",lat,lng,target,mapid,zoom,debug );
	var map = leafext_get_map( mapid );
	var markerClusterGroup;
	thismapbounds = [];

	if ( WPLeafletMapPlugin.markers.length > 0 ) {
		map.whenReady(
			function () {
				// console.log("ready target");
				let mapbounds = map.getBounds();
				if (debug) {
					L.circleMarker( L.latLng( lat,lng ), {radius: 3,color: "red"} ).bindPopup( "latlng" ).addTo( map );
					L.rectangle( mapbounds, {color: "yellow", weight: 1} ).addTo( map );
				}
				// with bounds because of abuse, POST is better
				if (mapbounds.contains( L.latLng( lat,lng ) )) {
					leafext_target_latlng_marker_do( map,lat,lng,target,zoom,debug );
				} else {
					console.log( "lat, lng not in marker bounds or no marker" );
				}
			}
		);
	} else {
		var geojsons = window.WPLeafletMapPlugin.geojsons;
		if (geojsons.length > 0) {
			// console.log("geojsons "+geojsons.length);
			var geocount = geojsons.length;
			for (var j = 0, len = geocount; j < len; j++) {
				var geojson = geojsons[j];
				if (map._leaflet_id == geojson._map._leaflet_id) {
					geojson.on(
						"ready",
						function () {
							// console.log("ready");
							let mapbounds = map.getBounds();
							// with bounds because of abuse, POST is better
							if (mapbounds.contains( L.latLng( lat,lng ) )) {
								leafext_target_latlng_geojson_do( map,lat,lng,this.layer,target,zoom,debug );
							}
						}
					); // geojson ready
				}
			}
		}
	}
}

/**
 * Jump to leaflet-marker, leaflet-extramarker OR leaflet-geojson
 * with lat and lng in a map on same site
 */
function leafext_target_same_lanlng_js(lat,lng,target,mapid,zoom,debug) {
	console.log( "leafext_target_same_lanlng_js",lat,lng,target,mapid,zoom,debug );
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(
		function () {
			var map = leafext_get_map( mapid );
			var markerClusterGroup;
			thismapbounds = [];
			if ( WPLeafletMapPlugin.markers.length > 0 ) {
				if (debug) {
					L.circleMarker( L.latLng( lat,lng ), {radius: 3,color: "red"} ).bindPopup( "latlng" ).addTo( map );
				}
				leafext_target_latlng_marker_do( map,lat,lng,target,zoom,debug );
			} else {
				var geojsons = window.WPLeafletMapPlugin.geojsons;
				if (geojsons.length > 0) {
					// console.log( "lat lng geojson" );
					var geocount = geojsons.length;
					for (var j = 0, len = geocount; j < len; j++) {
						var geojson = geojsons[j];
						leafext_target_latlng_geojson_do( map,lat,lng,geojson,target,zoom,debug )
					}
				}
			}
		}
	);
}

function leafext_target_latlng_marker_do(map,lat,lng,target,zoom,debug){
	console.log( 'leafext_target_latlng_marker_do',lat,lng,target,zoom,debug );
	var closest = Number.MAX_VALUE;
	var closestMarker;
	let latlng = L.latLng( lat,lng );
	let radius;

	var markergroups = window.WPLeafletMapPlugin.markergroups;
	Object.entries( markergroups ).forEach(
		([key, value]) =>
		{
			if ( markergroups[key]._map !== null ) {
				if (map._leaflet_id == markergroups[key]._map._leaflet_id) {
					// console.log("markergroups loop");
					markergroups[key].eachLayer(
						function (layer) {
							// console.log(layer);
							if (layer instanceof L.Marker) {
								if (layer._preSpiderfyLatlng) {
									radius = layer._preSpiderfyLatlng.distanceTo( latlng );
								} else {
									radius = layer.getLatLng().distanceTo( latlng );
								}
								if (radius < closest) {
									closest       = radius;
									closestMarker = layer;
								}
							}
						}
					);
				}
			}
		}
	);

	if (debug) {
		// console.log(closestMarker);
		L.circle( latlng, {radius: closest,color: "red"} ).bindPopup( "radius" ).addTo( map );
		L.circle( closestMarker.getLatLng(), {radius: closest,color: "blue"} ).bindPopup( "closestMarker" ).addTo( map );
	}
	leafext_zoom_to_closest( "latlng", closest, closestMarker, target, zoom, map, debug );
}

function leafext_target_latlng_geojson_do(map,lat,lng,geolayer,target,zoom,debug) {
	console.log( "leafext_target_latlng_geojson_do",lat,lng,target,zoom,debug )
	let latlng    = L.latLng( lat,lng );
	let mapbounds = map.getBounds();
	if (debug) {
		L.circleMarker( latlng, {radius: 3,color: "red"} ).bindPopup( "latlng" ).addTo( map );
		// L.rectangle( mapbounds, {color: "yellow", weight: 1} ).addTo( map );
	}
	var closest = Number.MAX_VALUE;
	var closestMarker;
	let radius;
	geolayer.eachLayer(
		function (layer) {
			if (layer.feature.geometry.type == "Point" ) {
				// console.log(layer);
				if (layer._preSpiderfyLatlng) {
					radius = layer._preSpiderfyLatlng.distanceTo( latlng );
				} else {
					radius = layer.getLatLng().distanceTo( latlng );
				}
				if (radius < closest) {
					closest       = radius;
					closestMarker = layer;
				}
			}
		}
	);

	if (debug && closest < Number.MAX_VALUE) {
		L.circle( latlng, {radius: closest,color: "red"} ).bindPopup( "radius" ).addTo( map );
		L.circle( closestMarker.getLatLng(), {radius: closest,color: "blue"} ).bindPopup( "closestMarker" ).addTo( map );
	}
	leafext_zoom_to_closest( "geojson", closest, closestMarker, target, zoom, map, debug );
}

// targetmarker same site - search with title
function leafext_target_same_title_js(title,target,mapid,zoom,debug) {
	console.log( "leafext_target_same_title_js",title,target,mapid,zoom,debug );
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(
		function () {
			var map = leafext_get_map( mapid );
			leafext_target_marker_title_do( map,title,target,zoom,debug );
		}
	);
}

// targetmarker post remote - search with title
function leafext_target_post_title_js(title,target,mapid,zoom,debug) {
	console.log( "leafext_target_post_title_js",title,target,mapid,zoom,debug );
	var map = leafext_get_map( mapid );
	map.whenReady(
		function () {
			leafext_target_marker_title_do( map,title,target,zoom,debug );
			leafext_jump_to_map();
		}
	);
}

function leafext_target_marker_title_do(map,title,target,zoom,debug){
	console.log( 'leafext_target_marker_title_do',title,target,zoom,debug );
	thismapbounds = [];
	var markerClusterGroup;
	var closest = Number.MAX_VALUE;
	var closestMarker;

	var markergroups = window.WPLeafletMapPlugin.markergroups;
		Object.entries( markergroups ).forEach(
			([key, value]) =>
			{
				if ( markergroups[key]._map !== null ) {
					if (map._leaflet_id == markergroups[key]._map._leaflet_id) {
						// console.log("markergroups loop");
						markergroups[key].eachLayer(
							function (layer) {
								// console.log(layer);
								if (layer instanceof L.Marker) {
									if ( layer.options.title == title ) {
										if (debug) {
											console.log( title );
										}
										closestMarker = layer;
										closest       = 0;
									}
								}
							}
						);
					}
				}
			}
		);
	leafext_zoom_to_closest( "title", closest, closestMarker, target, zoom, map, debug );
}

// targetmarker geojsonproperty
function leafext_target_same_geojson_js(geojsonproperty,geojsonvalue,target,mapid,zoom,debug) {
	console.log( "leafext_target_same_geojson_js",geojsonproperty,geojsonvalue,target,mapid,zoom,debug );
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	var markerClusterGroup;

	window.WPLeafletMapPlugin.push(
		function () {
			var map       = leafext_get_map( mapid );
			thismapbounds = [];
			var map_id    = map._leaflet_id;
			var geojsons  = window.WPLeafletMapPlugin.geojsons;
			var geocount  = geojsons.length;
			if (geocount > 0) {
				// console.log("geojsons "+geojsons.length);
				for (var j = 0, len = geocount; j < len; j++) {
					var geojson = geojsons[j];
					if (map_id == geojson._map._leaflet_id) {
						geojson.eachLayer(
							function (geolayer) {
								leafext_target_geojson_do( geolayer,geojsonproperty,geojsonvalue,target,zoom,map,debug );
							}
						);
					}
				}
			}
		}
	);
}

function leafext_target_post_geojson_js(geojsonproperty,geojsonvalue,target,mapid,zoom,debug) {
	console.log( "leafext_target_post_geojson_js",geojsonproperty,geojsonvalue,target,mapid,zoom,debug );
	var map       = leafext_get_map( mapid );
	thismapbounds = [];
	var map_id    = map._leaflet_id;
	var geojsons  = window.WPLeafletMapPlugin.geojsons;
	var geocount  = geojsons.length;
	if (geocount > 0) {
		console.log( "geojsons " + geojsons.length );
		for (var j = 0, len = geocount; j < len; j++) {
			var geojson = geojsons[j];
			if (map_id == geojson._map._leaflet_id) {
				// console.log(geojson);
				geojson.on(
					"ready",
					function () {
						// console.log(this);
						this.eachLayer(
							function (geolayer) {
								leafext_target_geojson_do( geolayer,geojsonproperty,geojsonvalue,target,zoom,map,debug );
							}
						);
						leafext_jump_to_map();
					}
				); // geojson ready// console.log(geojson);
			}
		}
	}
}

function leafext_target_geojson_do(geolayer,geojsonproperty,geojsonvalue,target,zoom,map,debug) {
	// console.log(geolayer.feature);
	// console.log(geolayer.feature[geojsonproperty]);
	if (geolayer.feature[geojsonproperty] == geojsonvalue) {
		if (geolayer.__parent) {
			if (debug) {
				console.log( "closest geojson marker in cluster" );
			}
			var markerClusterGroup = geolayer.__parent._group;
			leafext_zoomto_clmarker( geolayer, target, markerClusterGroup, map, debug );
		} else {
			if (debug) {
				console.log( "closest geojson marker not in cluster" );
			}
			leafext_zoomto_marker( geolayer, target, zoom, map, debug );
		}
	}
}
