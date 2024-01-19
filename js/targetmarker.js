/**
 * Javascript function for targetmarker (marker in geojson files)
 *
 * @package Extensions for Leaflet Map
 */

// targetmarker
function leafext_targetmarker_js(lat,lng,target,zoom,debug) {
	var map    = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;
	if (debug) {
		console.log( "targetmarker",lat,lng,target,zoom );
	}
	thismapbounds = [];
	var markerClusterGroup;
	map.whenReady(
		function () {
			// console.log("ready target");
			let latlng    = L.latLng( lat,lng );
			let mapbounds = map.getBounds();
			if (debug) {
				L.circleMarker( latlng, {radius: 3,color: "red"} ).bindPopup( "latlng" ).addTo( map );
				L.rectangle( mapbounds, {color: "yellow", weight: 1} ).addTo( map );
			}
			if (mapbounds.contains( latlng )) {
				var closest = Number.MAX_VALUE;
				var closestMarker;
				let j = 0;
				map.eachLayer(
					function (layer) {
						if ( layer instanceof L.FeatureGroup ) {
							// console.log("L.FeatureGroup");
							if (layer._markerCluster) {
								// console.log("L.markerClusterGroup");
								markerClusterGroup = layer;
								layer.eachLayer(
									function (a) {
										if (a instanceof L.Marker) {
											j++;
											let radius = a.getLatLng().distanceTo( latlng );
											if (radius < closest) {
												// console.log(a);
												closest       = radius;
												closestMarker = a;
												// console.log(radius);
												// console.log("closest in cluster");
											}
										}
									}
								);
							}
							// console.log("j: "+j);
						}
					}
				);

				if (closest < Number.MAX_VALUE ) {
					if (debug) {
						// console.log(closestMarker);
						L.circle( latlng, {radius: closest,color: "red"} ).bindPopup( "radius" ).addTo( map );
						L.circle( closestMarker.getLatLng(), {radius: closest,color: "blue"} ).bindPopup( "closestMarker" ).addTo( map );
						console.log( "closest marker in cluster" );
					}

					leafext_fitbounds_off( map );
					leafext_zoomto_clmarker( closestMarker, target, markerClusterGroup, map, debug );
					leafext_fitbounds_on( map );
				} else {
					if ( WPLeafletMapPlugin.markers.length > 0 ) {
						var length = WPLeafletMapPlugin.markers.length;
						// console.log("length "+length);
						for (var i = 0; i < length; i++) {
							let a = WPLeafletMapPlugin.markers[i];
							// console.log(i, a);
							if ( a._map !== null ) {
								if (map_id == a._map._leaflet_id) {
									j++;
									let radius = a.getLatLng().distanceTo( latlng );
									// console.log(i,radius);
									if (radius < closest) {
										// console.log(a);
										closest       = radius;
										closestMarker = a;
										// console.log(radius);
										// console.log("closest");
									}
								}
							}
						}

						if (closest < Number.MAX_VALUE ) {
							if (debug) {
								L.circle( latlng, {radius: closest,color: "red"} ).bindPopup( "radius" ).addTo( map );
								L.circle( closestMarker.getLatLng(), {radius: closest,color: "blue"} ).bindPopup( "closestMarker" ).addTo( map );
								console.log( "closest marker not in cluster" );
							}

							leafext_fitbounds_off( map );
							leafext_zoomto_marker( closestMarker, target, zoom, map, debug );
							leafext_fitbounds_on( map );
						} else {
							if (debug) {
								console.log( closest );
								console.log( "not found" );
							}
						}
					}
				}
			} else {
				console.log( "latlng not in marker bounds" );
			}
		}
	);

	var geojsons = window.WPLeafletMapPlugin.geojsons;
	if (geojsons.length > 0) {
		// console.log("geojsons "+geojsons.length);
		var geocount = geojsons.length;
		for (var j = 0, len = geocount; j < len; j++) {
			var geojson = geojsons[j];
			// console.log(geojson);
			if (map_id == geojson._map._leaflet_id) {
				// console.log("geojson");
				geojson.on(
					"ready",
					function () {
						// console.log("ready");
						let latlng    = L.latLng( lat,lng );
						let mapbounds = map.getBounds();
						if (debug) {
							L.circleMarker( latlng, {radius: 3,color: "red"} ).bindPopup( "latlng" ).addTo( map );
							L.rectangle( mapbounds, {color: "yellow", weight: 1} ).addTo( map );
						}
						if (mapbounds.contains( latlng )) {
							var a       = this.layer;
							var closest = Number.MAX_VALUE;
							var closestMarker;
							// console.log(a);
							a.eachLayer(
								function (layer) {
									if (layer.feature.geometry.type == "Point" ) {
										let radius = layer.getLatLng().distanceTo( latlng );
										if (radius < closest) {
											// console.log(layer);
											// console.log(radius);
											closest       = radius;
											closestMarker = layer;
										}
									}
								}
							);

							if (closest < Number.MAX_VALUE ) {
								if (debug) {
									L.circle( latlng, {radius: closest,color: "red"} ).bindPopup( "radius" ).addTo( map );
									L.circle( closestMarker.getLatLng(), {radius: closest,color: "blue"} ).bindPopup( "closestMarker" ).addTo( map );
								}

								leafext_fitbounds_off( map );

								if (closestMarker.__parent) {
									if (debug) {
										console.log( "closest geojson marker in cluster" );
									}
									markerClusterGroup = closestMarker.__parent._group;
									leafext_zoomto_clmarker( closestMarker, target, markerClusterGroup, map, debug );
								} else {
									if (debug) {
										console.log( "closest geojson marker not in cluster" );
									}
									leafext_zoomto_marker( closestMarker, target, zoom, map, debug );
								}
								leafext_fitbounds_on( map );
							} else {
								if (debug) {
									console.log( closest );
									console.log( "not found" );
								}
							}
						} else {
							console.log( "latlng not in geojson bounds" );
						}
					}
				); // geojson ready
			}
		}
	}
}

function leafext_fitbounds_on(map) {
	map.once(
		"zoomend",
		function (e) {
			// console.log(thismapbounds);
			if (thismapbounds['fitBounds'] ) {
				// console.log("Reset");
				map.fitBounds        = thismapbounds['fitBounds'];
				map._shouldFitBounds = thismapbounds['shouldFitBounds'];
			}
		}
	);
}

function leafext_fitbounds_off(map) {
	if (map.fitBounds) {
		thismapbounds['fitBounds'] = map.fitBounds;
		// console.log("map has fitbounds");
		map.fitBounds = false;
		if (map._shouldFitBounds) {
			thismapbounds['shouldFitBounds'] = map._shouldFitBounds;
			// console.log("map has shouldFitBounds");
			delete map._shouldFitBounds;
		}
	} else {
		console.log( "map has no fitbounds" );
	}
}

function leafext_zoomto_clmarker(closestMarker, target, markerClusterGroup, map, debug) {
	markerClusterGroup.zoomToShowLayer(
		closestMarker,
		function () {
			// console.log(this);
			// console.log(this._zoom);
			// flyTo funktioniert nicht zuverlaessig bzw. das Vorhandensein von this._zoom
			// if (this._zoom) {
			// 	map.flyTo( closestMarker.getLatLng(), this._zoom, {animate: true} );
			// } else {
			map.setView( closestMarker.getLatLng() );
			// }
			if ( closestMarker.getPopup() ) {
				if (debug) {
					console.log( "has popup" );
				}
				closestMarker.openPopup();
			} else {
				if (debug) {
					console.log( "no popup" );
				}
				closestMarker.bindPopup( target ).openPopup();
			}
		}
	);
}

function leafext_zoomto_marker(closestMarker, target, zoom, map, debug) {
	if (zoom === false) {
		zoom = map.getZoom();
	}
	map.flyTo( closestMarker.getLatLng(), zoom, {animate: true} );

	if ( closestMarker.getPopup() ) {
		closestMarker.openPopup();
	} else {
		if (debug) {
			console.log( "no popup" );
		}
		closestMarker.bindPopup( target ).openPopup();
	}
}
