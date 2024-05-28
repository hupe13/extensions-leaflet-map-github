/**
 * Javascript function for targetmarker
 *
 * @package Extensions for Leaflet Map
 */

// extra definition in markercluster orig (bug or Sonderfall?)
L.MarkerClusterGroup.include(
	{
		// original
		// https://unpkg.com/browse/leaflet.markercluster@1.5.3/dist/leaflet.markercluster-src.js
		// from line 560

		// Zoom down to show the given layer (spiderfying if necessary) then calls the callback
		leafextZoomToShowLayer: function (layer, callback) {
			// console.log("ZoomToShowLayer changed");
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
				// Layer is visible ond on screen, immediate return
				callback();
			} else if (layer.__parent._zoom < Math.round( this._map._zoom )) {
				// Layer should be visible at this zoom level. It must not be on screen so just pan over to it
				this._map.on( 'moveend', showMarker, this );
				this._map.panTo( layer.getLatLng() );
			} else {
				// this._map.on('moveend', showMarker, this);
				// this.on('animationend', showMarker, this);
				// layer.__parent.zoomToBounds();
				// original end ****
				// console.log( layer.__parent._zoom,this._map._zoom );
				if (typeof layer.__parent._childClusters !== "undefined" ) {
					console.log( "extra definition in markercluster orig (bug or Sonderfall?)" );
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
					// console.log( "layer.__parent._childClusters nicht vorhanden" );
				}
				// changed end ****
			}
		}
	}
);

function leafext_fitbounds_on(map) {
	map.once(
		"moveend",
		function (e) {
			// console.log(thismapbounds);
			if (thismapbounds['fitBounds'] ) {
				// console.log( "bounds on" );
				map.fitBounds        = thismapbounds['fitBounds'];
				map._shouldFitBounds = thismapbounds['shouldFitBounds'];
			}
		}
	);
}

function leafext_fitbounds_off(map) {
	if (map.fitBounds) {
		// console.log("map has fitbounds");
		thismapbounds['fitBounds'] = map.fitBounds;
		map.fitBounds              = false;
		if (map._shouldFitBounds) {
			// console.log("map has shouldFitBounds");
			thismapbounds['shouldFitBounds'] = map._shouldFitBounds;
			delete map._shouldFitBounds;
		}
		// } else {
		// console.log( "map has no fitbounds" );
	}
}

function leafext_zoomto_marker(closestMarker, target, zoom, map, debug) {
	// console.log("leafext_zoomto_marker",target, zoom, debug);
	if ( zoom === false) {
		zoom = map.getZoom();
	}
	leafext_fitbounds_off( map );
	if ( ! closestMarker.getPopup() ) {
		if ( closestMarker.options.title ) {
			// console.log( "no popup" );
			closestMarker.bindPopup( closestMarker.options.title );
		} else {
			// console.log( "no popup" );
			closestMarker.bindPopup( target );
		}
		// console.log(closestMarker);
		closestMarker.once(
			'popupopen',
			function () {
				closestMarker.unbindPopup();
			}
		);
	}
	closestMarker.once(
		'popupopen',
		function () {
			// console.log("popup open");
			// leafext_fitbounds_off( map );
			map.panTo( this.getLatLng() );
			leafext_fitbounds_on( map );
		}
	);
	map.setView( closestMarker.getLatLng(), zoom );
	closestMarker.openPopup();
}

function leafext_zoomto_clmarker(closestMarker, target, markerClusterGroup, map, debug) {
	console.log( "leafext_zoomto_clmarker" );
	// leafext_fitbounds_off( map );
	closestMarker.once(
		'popupopen',
		function () {
			leafext_fitbounds_off( map );
			map.panTo( this.getLatLng() );
			leafext_fitbounds_on( map );
		}
	);
	markerClusterGroup.leafextZoomToShowLayer(
		closestMarker,
		function () {
			if ( closestMarker.getPopup() ) {
				// console.log( "has popup" );
				closestMarker.openPopup();
			} else {
				// console.log( "no popup" );
				closestMarker.bindPopup( target ).openPopup();
			}
		}
	);
}

function leafext_zoom_to_closest(type, closest, closestMarker, target, zoom, map, debug) {
	if (closest < Number.MAX_VALUE ) {
		if (closestMarker.__parent) {
			if (debug) {
				console.log( "closest " + type + " marker in cluster" );
			}
			markerClusterGroup = closestMarker.__parent._group;
			leafext_zoomto_clmarker( closestMarker, target, markerClusterGroup, map, debug );
		} else {
			if (debug) {
				console.log( "closest " + type + " marker not in cluster" );
			}
			leafext_zoomto_marker( closestMarker, target, zoom, map, debug );
		}
	} else {
		if (debug) {
			console.log( type + " marker not found" );
		}
	}
}

function leafext_jump_to_map() {
	const element = document.getElementsByClassName( "leaflet-map" )[0];
	element.scrollIntoView( { block: "center" } );
}
