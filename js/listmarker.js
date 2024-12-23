/**
 * Javascript function for Shortcode listmarker.
 *
 * @package Extensions for Leaflet Map
 */

/**
 * Create Javascript code for listmarker.
 */

function leafext_listmarker_js(propertyName,overiconurl,collapse,update,hover,highlight,maxheight,maxwidth) {
	var map = window.WPLeafletMapPlugin.getCurrentMap();

	map.on(
		"update-end",
		function (e) {
			//console.log("update-end", map.options._collapse);
			// console.log(markersLayer);
			if (leafext_map_popups( map ) ) {
				markersLayer.eachLayer(
					function (layer) {
						if (layer.getPopup()) {
							if (layer.getPopup().isOpen()) {
								let thistitle = layer.options.listtitle + " ";
								// console.log("map update",thistitle);
								leafext_set_list_background( map, thistitle, highlight, false );
							} else {
								leafext_set_origicon( layer );
							}
						}
					}
				);
			}
		}
	);

	// marker with popup open don't get into cluster sometimes
	map.on(
		"zoomstart",
		function (e) {
			//console.log("zoomstart");
			map.eachLayer(
				(l) =>
				{
					if ( l instanceof L.Marker ) {
						if (l.__parent) {
							// console.log( "cluster" );
							leafext_close_popups( map );
							// } else {
							// 	console.log("no cluster");
						}
					}
				}
			);
		}
	);

	markersLayer = new L.LayerGroup();	//global layer contain searched elements
	map.addLayer( markersLayer );

	leafext_list_menu( map,markersLayer,collapse,update,highlight,maxheight,maxwidth );

	let markerlength = WPLeafletMapPlugin.markers.length;
	if ( markerlength > 0 ) {
		for (var i = 0; i < markerlength; i++) {
			let thismarker = WPLeafletMapPlugin.markers[i];
			//console.log("thismarker",thismarker);
			thismarker.options.riseOnHover = true;
			leafext_define_overicon( thismarker,overiconurl );
			thismarker.options.listtitle = thismarker.options.title;

			// hide default tooltip
			thismarker.unbindTooltip();
			thismarker.bindTooltip( "", {visibility: 'hidden', opacity: 0} ).closeTooltip();
			thismarker.options.title = "";

			markersLayer.addLayer( thismarker );
			map.removeLayer( thismarker );
		}
		map.removeLayer( markersLayer );
		map.addLayer( markersLayer );
		leafext_events_markerLayer( map, markersLayer, hover, highlight );
	}

	var geojsons      = window.WPLeafletMapPlugin.geojsons;
	let geojsonlength = window.WPLeafletMapPlugin.geojsons.length;

	if ( geojsonlength > 0 ) {
		for (var j = 0, len = geojsonlength; j < len; j++) {
			// console.log("geojson");
			// console.log(geojsons[j]);
			var geojson = geojsons[j];
			geojson.on(
				"ready",
				function () {
					this.layer.eachLayer(
						function (layer) {
							if (layer.feature.geometry.type == "Point" ) {
								// console.log(layer);
								if (layer.feature.properties[propertyName]) {
									// console.log("found");
									length++;
									layer.riseOnHover = true;
									leafext_define_overicon( layer,overiconurl );
									layer.options.listtitle = layer.feature.properties[propertyName];
									markersLayer.addLayer( layer );
									map.removeLayer( layer );
								} else {
									console.log( "not in list" );
									console.log( layer.feature.properties );
								}
								// } else {
								// console.log(layer);
							}
						}
					);
					map.removeLayer( markersLayer );
					map.addLayer( markersLayer );
					// console.log(markersLayer);
					leafext_events_markerLayer( map, markersLayer, hover, highlight );
					map.fire( "moveend" );
				}
			);
		}
	}
}

function leafext_close_popups(map) {
	map.eachLayer(
		function (layer) {
			if (layer.options.pane === "popupPane") {
				layer.removeFrom( map );
				// console.log("leafext_close_popups");
			}
		}
	);
}

function leafext_set_list_background( map, thistitle, farbe, scroll ) {
	let lis       = document.querySelectorAll( "a" );
	let lislength = lis.length;
	for (let i = 0; i < lislength; i++) {
		let a = lis[i];
		if (a.text.includes( thistitle )) {
			// console.log( '*' + a.text.substr( 0, a.text.length - 2 ).trim() + '*', '**' + thistitle.trim() + '**' );
			if (a.text.substr( 0, a.text.length - 2 ).trim() === thistitle.trim() ) {
				// console.log(a);
				if ( scroll ) {
					//a.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
					a.scrollIntoView( { behavior: 'smooth', block: 'nearest', inline: 'end' } );
				}
				a.style.backgroundColor = farbe;
				// } else {
				// 	console.log( "not equal" );
			}
		}
	}
}

function leafext_set_origicon( layer ) {
	if (layer.options._origicon != "") {
		layer.setIcon( layer.options._origicon );
	}
	layer.closeTooltip();
}

function leafext_set_overicon( layer ) {
	if (layer.options._overicon != "") {
		layer.setIcon( layer.options._overicon );
	}
}

function leafext_events_markerLayer(map, markersLayer, hover, highlight) {
	markersLayer.eachLayer(
		function (layer) {
			layer.on(
				"mouseover",
				function (e) {
					// console.log( "layer mouseover" );
					if (leafext_map_popups( map ) == false) {
						let thistitle = e.sourceTarget.options.listtitle + " ";
						// console.log( "mouseover: " + thistitle );
						if (hover == true) {
							leafext_set_list_background( map, thistitle, highlight, true );
						}
						leafext_set_overicon( e.sourceTarget );
						e.sourceTarget.bindTooltip( thistitle ,{className: 'leafext-tooltip'} );
						e.sourceTarget.openTooltip();
					} else {
						// console.log("popup open");
						// console.log(e.sourceTarget.getTooltip());
						e.sourceTarget.closeTooltip();
						e.sourceTarget.bindTooltip( "", {visibility: 'hidden', opacity: 0} ).closeTooltip();
					}
				}
			);
			layer.on(
				"mouseout",
				function (e) {
					// console.log( "layer mouseout" );
					if (leafext_map_popups( map ) == false) {
						let thistitle = e.sourceTarget.options.listtitle + " ";
						// console.log("marker mouseout: "+thistitle);
						if (hover == true) {
							leafext_set_list_background( map, thistitle, "", false );
						}
						leafext_set_origicon( e.sourceTarget );
						// e.sourceTarget.closeTooltip();
						//e.sourceTarget.bindTooltip( "", {visibility: 'hidden', opacity: 0} ).closeTooltip();
						// } else {
						// 	console.log("popup open");
					}
				}
			);
			layer.on(
				"click",
				function (e) {
					// console.log( "layer click" );
					let thistitle = e.sourceTarget.options.listtitle + " ";
					//console.log("marker click",thistitle);
					leafext_set_overicon( e.sourceTarget );
					leafext_set_list_background( map, thistitle, highlight, true, map );
				}
			);
			layer.on(
				"popupopen",
				function (e) {
					// let thistitle = e.sourceTarget.options.listtitle;
					// console.log("popupopen",thistitle);
					layer.unbindTooltip();
					layer.bindTooltip( "", {visibility: 'hidden', opacity: 0} ).closeTooltip();
				}
			);
			layer.on(
				"popupclose",
				function (e) {
					let thistitle = e.sourceTarget.options.listtitle + " ";
					//console.log("popupclose",thistitle);
					leafext_set_list_background( map, thistitle, "", false );
					leafext_set_origicon( e.sourceTarget );
				}
			);
		}
	);
}

function leafext_list_menu(map,markersLayer,collapse,update,highlight,maxheight,maxwidth) {
	// console.log("leafext_list_menu",markersLayer);

	//inizialize Leaflet List Markers
	list = new L.Control.ListMarkers(
		{
			layer: markersLayer,
			itemIcon: null,
			collapsed: collapse,
			label: 'listtitle',
			update: update,
			maxheight: maxheight,
			maxwidth: maxwidth,
			maxItems: Number.MAX_VALUE
		}
	);
	list.on(
		"item-mouseover",
		function (e) {
			if (leafext_map_popups( map ) == false) {
				e.layer.fire( "mouseover" );
			}
		}
	);
	list.on(
		"item-mouseout",
		function (e) {
			if (leafext_map_popups( map ) == false) {
				e.layer.fire( "mouseout" );
			}
		}
	);
	list.on(
		"item-click",
		function (e) {
			// console.log("item-click");
			leafext_close_popups( map );
			let thistitle = e.layer.options.listtitle + " ";
			//console.log("item-click",thistitle);
			thismapbounds = [];
			leafext_jumpto_marker( map,e.layer.getLatLng().lat,e.layer.getLatLng().lng,e.layer.getPopup(),map.getZoom(),false );
			leafext_set_overicon( e.layer );
			leafext_set_list_background( highlight, thistitle, false, map );
		}
	);
	map.addControl( list );
}

function leafext_define_overicon(marker,overiconurl) {
	marker.options._origicon = marker.getIcon();
	marker.options._overicon = "";
	if (overiconurl != "") {
		// console.log(marker.getIcon());
		marker.options._overicon = "";
		if (marker.getIcon().options.iconUrl) {
			if (marker.getIcon().options.iconUrl.includes( '/' )) {
				let markeroptions        = marker.getIcon().options;
				var markericon           = L.Icon.extend(
					{
						options: markeroptions,
					}
				);
				overicon                 = new markericon(
					{
						iconUrl: overiconurl,
					}
				);
				marker.options._overicon = overicon;
			}
		}
	}
}

function leafext_jumpto_marker(map,lat,lng,target,zoom,debug){
	if (debug) {
		console.log( 'leafext_jumpto_marker',lat,lng,target,zoom,debug );
	}
	var closest = Number.MAX_VALUE;
	var closestMarker;
	let latlng = L.latLng( lat,lng );
	let radius;

	markersLayer.eachLayer(
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

	if (debug) {
		// console.log(closestMarker);
		L.circle( latlng, {radius: closest,color: "red"} ).bindPopup( "radius" ).addTo( map );
		L.circle( closestMarker.getLatLng(), {radius: closest,color: "blue"} ).bindPopup( "closestMarker" ).addTo( map );
	}
	leafext_zoom_to_closest( "latlng", closest, closestMarker, target, zoom, map, debug );
}
