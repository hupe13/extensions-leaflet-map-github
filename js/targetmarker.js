/**
 * Javascript function for targetmarker
 *
 * @package Extensions for Leaflet Map
 */

// targetmarker
function leafext_targetmarker_js(lat,lng,target,zoom,debug) {
	var map    = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;
	if (debug) {
		console.log( "targetmarker",lat,lng,target,zoom );
		// console.log(map);
		// map.on(
		// 	"zoomend",
		// 	function (e) {
		// 		console.log( "zoomend zoom " + map.getZoom() );
		// 	}
		// );
		// map.on(
		// 	"moveend",
		// 	function (e) {
		// 		console.log( "moveend zoom " + map.getZoom() );
		// 	}
		// );
	}
	thismapbounds = [];

	const markerClusterGroupProto = L.extend( {}, L.MarkerClusterGroup );
	L.MarkerClusterGroup.include(
		{
			// original
			// https://unpkg.com/browse/leaflet.markercluster@1.5.3/dist/leaflet.markercluster-src.js
			// from line 560

			//Zoom down to show the given layer (spiderfying if necessary) then calls the callback
			leafextZoomToShowLayer: function (layer, callback) {

				// original begin ****
				var map = this._map;

				if (typeof callback !== 'function') {
					callback = function () {};
				}

				var showMarker = function () {
					// Assumes that map.hasLayer checks for direct appearance on map, not recursively calling
					// hasLayer on Layer Groups that are on map (typically not calling this MarkerClusterGroup.hasLayer, which would always return true)
					if ((map.hasLayer( layer ) || map.hasLayer( layer.__parent )) && ! this._inZoomAnimation) {
						this._map.off( 'moveend', showMarker, this );
						this.off( 'animationend', showMarker, this );

						if (map.hasLayer( layer )) {
							callback();
						} else if (layer.__parent._icon) {
							this.once( 'spiderfied', callback, this );
							layer.__parent.spiderfy();
						}
					}
				};

				if (layer._icon && this._map.getBounds().contains( layer.getLatLng() )) {
					//Layer is visible ond on screen, immediate return
					callback();
				} else if (layer.__parent._zoom < Math.round( this._map._zoom )) {
					//Layer should be visible at this zoom level. It must not be on screen so just pan over to it
					this._map.on( 'moveend', showMarker, this );
					this._map.panTo( layer.getLatLng() );
				} else {
					// this._map.on('moveend', showMarker, this);
					// this.on('animationend', showMarker, this);
					// layer.__parent.zoomToBounds();
					// original end ****
					// console.log( layer.__parent._zoom,this._map._zoom );
					if (typeof layer.__parent._childClusters !== "undefined" ) {
						this._map.on( 'moveend', showMarker, this );
						this.on( 'animationend', showMarker, this );
						if (layer.__parent._childClusters.length > 0) {
							// console.log( "layer.__parent._childClusters.length ist " + layer.__parent._childClusters.length );
							// console.log( "Childs > 0, SetView to " + layer.__parent._zoom );
							map.setView( layer.__parent.getLatLng(),layer.__parent._zoom );
						} else {
							// console.log( "layer.__parent._childClusters.length ist 0" );
							// console.log( "Childs = 0, SetView to " + layer.__parent._zoom );
							map.setView( layer.getLatLng(),layer.__parent._zoom );
						}
						// } else {
						// 		console.log( "layer.__parent._childClusters nicht vorhanden" );
					}
					// changed end ****
				}
			}
		}
	);

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
						}
					}
				);

				if (closest < Number.MAX_VALUE ) {
					if (debug) {
						// console.log(closestMarker);
						L.circle( latlng, {radius: closest,color: "red"} ).bindPopup( "radius" ).addTo( map );
						L.circle( closestMarker.getLatLng(), {radius: closest,color: "blue"} ).bindPopup( "closestMarker" ).addTo( map );
						var visibleOne = markerClusterGroup.getVisibleParent( closestMarker );
						L.circle( visibleOne.getLatLng(), {radius: closest,color: "green"} ).bindPopup( "visible" ).addTo( map );
						console.log( "closest marker in cluster" );
					}
					leafext_zoomto_clmarker( closestMarker, target, markerClusterGroup, map, debug );

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
							leafext_zoomto_marker( closestMarker, target, zoom, map, debug );
						} else {
							if (debug) {
								console.log( closest );
								console.log( "not found" );
							}
						}
					}
				}
			} else {
				console.log( "latlng not in marker bounds or no marker" );
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

								if (closestMarker.__parent) {
									if (debug) {
										L.circle( latlng, {radius: closest,color: "red"} ).bindPopup( "radius" ).addTo( map );
										L.circle( closestMarker.getLatLng(), {radius: closest,color: "blue"} ).bindPopup( "closestMarker" ).addTo( map );
										console.log( "closest geojson marker in cluster" );
									}
									markerClusterGroup = closestMarker.__parent._group;
									leafext_zoomto_clgeomarker( closestMarker, target, markerClusterGroup, map, debug );
								} else {
									if (debug) {
										console.log( "closest geojson marker not in cluster" );
									}
									leafext_zoomto_marker( closestMarker, target, zoom, map, debug );
								}
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
		"moveend",
		function (e) {
			// console.log(thismapbounds);
			console.log( "bounds on" );
			if (thismapbounds['fitBounds'] ) {
				console.log( "Reset" );
				map.fitBounds        = thismapbounds['fitBounds'];
				map._shouldFitBounds = thismapbounds['shouldFitBounds'];
			}
		}
	);
}

function leafext_fitbounds_off(map) {
	if (map.fitBounds) {
		// console.log("map has fitbounds");
		// console.log(map);
		thismapbounds['fitBounds'] = map.fitBounds;
		map.fitBounds              = false;
		if (map._shouldFitBounds) {
			// console.log("map has shouldFitBounds");
			thismapbounds['shouldFitBounds'] = map._shouldFitBounds;
			delete map._shouldFitBounds;
		}
	} else {
		console.log( "map has no fitbounds" );
	}
}

function leafext_zoomto_clgeomarker(closestMarker, target, markerClusterGroup, map, debug) {
	markerClusterGroup.zoomToShowLayer(
		closestMarker,
		function () {
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
	// map.flyTo( closestMarker.getLatLng(), zoom, {animate: true} );
	leafext_fitbounds_off( map );
	map.setView( closestMarker.getLatLng(), zoom );
	leafext_fitbounds_on( map );
	if ( closestMarker.getPopup() ) {
		closestMarker.openPopup();
	} else {
		if (debug) {
			console.log( "no popup" );
		}
		closestMarker.bindPopup( target ).openPopup();
	}
}

function leafext_zoomto_clmarker(closestMarker, target, markerClusterGroup, map, debug) {
	markerClusterGroup.leafextZoomToShowLayer(
		closestMarker,
		function () {
			var visibleOne = markerClusterGroup.getVisibleParent( closestMarker );
			if (typeof visibleOne._childClusters !== "undefined" ) {
				if (visibleOne._childClusters.length > 0) {
					// console.log( "leafext_zoomto_clmarker visibleOne._childClusters.length ist " + visibleOne._childClusters.length + ", nochmal" );
					leafext_zoomto_clmarker( closestMarker, target, visibleOne._group, map, debug );
				} else {
					// console.log( "leafext_zoomto_clmarker visibleOne._childClusters.length ist 0, fitbounds off/on" );
					leafext_zoomto_clmarker( closestMarker, target, markerClusterGroup, map, debug );
				}
				// } else {
				// 	// console.log( "leafext_zoomto_clmarker visibleOne._childClusters nicht vorhanden" );
				// 	console.log( visibleOne.__parent._zoom,map._zoom );
			}

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
