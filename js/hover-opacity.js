/**
 * Javascript functions for Shortcode hover opacity.
 *
 * @package Extensions for Leaflet Map
 */

// mapPane 	HTMLElement 	'auto' 	Pane that contains all other map panes
// tilePane 	HTMLElement 	200 	Pane for GridLayers and TileLayers
// overlayPane 	HTMLElement 	400 	Pane for vectors (Paths, like Polylines and Polygons), ImageOverlays and VideoOverlays
// shadowPane 	HTMLElement 	500 	Pane for overlay shadows (e.g. Marker shadows)
// markerPane 	HTMLElement 	600 	Pane for Icons of Markers
// tooltipPane 	HTMLElement 	650 	Pane for Tooltips.
// popupPane 	HTMLElement 	700 	Pane for Popups.

function leafext_hovertransp_geojson_js( opacity ) {
	var map    = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;
	// console.log(map_id);

	if ( WPLeafletMapPlugin.geojsons.length > 0 ) {
		var geojsons = window.WPLeafletMapPlugin.geojsons;
		var geocount = geojsons.length;

		for (var j = 0, len = geocount; j < len; j++) {
			var geojson = geojsons[j];
			// console.log(geojson);

			if (map_id == geojson._map._leaflet_id) {
				// mouseover
				geojson.layer.on(
					"mouseover",
					function (e) {
						if (leafext_map_popups( map ) == false) {
							let i = 0;
							e.target.eachLayer(
								function () {
									i += 1; }
							);
							// console.log( "mouseover has", i, "layers." );
							if (i > 1) {
								// z.B leaflet-gpx mit Track und Marker
								leafext_make_transparent_geojson( map, e.sourceTarget, 1, opacity );
							} else {
								e.target.eachLayer(
									function (layer) {
										// console.log(layer);
										leafext_make_transparent_geojson( map, layer, 1, opacity );
									}
								);
							} //end else i
						}
					}
				);
				// mouseover end

				// mouseout
				geojson.layer.on(
					"mouseout",
					function (e) {
						if (leafext_map_popups( map ) == false) {
							let i = 0;
							e.target.eachLayer(
								function () {
									i += 1; }
							);
							// console.log("mouseout has", i, "layers.");
							if (i > 1) {
								e.target.eachLayer(
									function (layer) {
										leafext_make_transparent_geojson( map, layer, 0, opacity );
									}
								);
							} else {
								e.target.eachLayer(
									function (layer) {
										leafext_make_transparent_geojson( map, layer, 0, opacity );
									}
								);
							}
						}
					}
				);
				// mouseout end

				geojson.layer.on(
					"click",
					function (e) {
						let i = 0;
						e.target.eachLayer(
							function () {
								i += 1;
							}
						);
						// console.log( "mouseclick has", i, "layers." );
						if (i > 1) {
							// z.B leaflet-gpx mit Track und Marker
							leafext_make_transparent_geojson( map, e.sourceTarget, 1, opacity );
						} else {
							map.eachLayer(
								function (layer) {
									leafext_make_transparent_geojson( map, layer, 0, opacity );
								}
							);
							e.target.eachLayer(
								function (layer) {
									leafext_make_transparent_geojson( map, layer, 1, opacity );
								}
							);
						} //end else i
					}
				);
				// mouseclick end

				geojson.layer.on(
					"popupclose",
					function (e) {
						let i = 0;
						e.target.eachLayer(
							function () {
								i += 1;
							}
						);
						// console.log( "popupclose has", i, "layers." );
						if (i > 1) {
							// z.B leaflet-gpx mit Track und Marker
							leafext_make_transparent_geojson( map, e.sourceTarget, 0, opacity );
						} else {
							map.eachLayer(
								function (layer) {
									leafext_make_transparent_geojson( map, layer, 0, opacity );
								}
							);
							e.target.eachLayer(
								function (layer) {
									leafext_make_transparent_geojson( map, layer, 0, opacity );
								}
							);
						} //end else i
					}
				);
				// mouseclick end

			}//map_id
		}//geojson foreach
	}//geojson end
}

function leafext_make_transparent_geojson( map, layer, onoff, opacity ) {
	var map_id = map._leaflet_id;
	// console.log(map_id);

	var markers        = window.WPLeafletMapPlugin.markers;
	var markers_length = WPLeafletMapPlugin.markers.length;
	if (markers_length > 0) {
		for (var i = 0; i < markers_length; i++) {
			var a = WPLeafletMapPlugin.markers[i];
			if (( a._map != null && a._map._leaflet_id == map_id) ) {
				if (onoff) {
					a.setOpacity( opacity );
				} else {
					a.setOpacity( 1 );
				}
			}
		}
	}

	var markergroups = window.WPLeafletMapPlugin.markergroups;
	Object.entries( markergroups ).forEach(
		([key, value]) =>
		{
			if ( markergroups[key]._map !== null ) {
				if (map_id == markergroups[key]._map._leaflet_id) {
					// console.log("markergroups loop");
					markergroups[key].eachLayer(
						function (layer) {
							// console.log(layer);
							if (layer instanceof L.Marker) {
								// console.log("is_marker");
							} else if (
								layer instanceof L.Polygon ||
								layer instanceof L.Circle ||
								layer instanceof L.Polyline
							) {
								// console.log( "is_Polygon or circle or polyline" );
								if (onoff) {
									if ( layer.setStyle ) {
										layer.setStyle(
											{
												'opacity' : opacity,
											}
										);
									}
								} else {
									if ( layer.setStyle ) {
										layer.setStyle(
											{
												'opacity' : 1,
											}
										);
									}
								}
							} else {
								// console.log( "other" );
								// console.log( layer );
							}
						}
					);
				}
			}
		}
	);

	if ( WPLeafletMapPlugin.geojsons.length > 0 ) {
		var thisgeojsons = window.WPLeafletMapPlugin.geojsons;
		var thisgeocount = thisgeojsons.length;

		for (var j = 0, len = thisgeocount; j < len; j++) {
			var geojson = thisgeojsons[j];
			// console.log(geojson);

			if (map_id == geojson._map._leaflet_id) {
				// console.log(j, layer._leaflet_id, geojson);

				let i = 0;
				geojson.eachLayer(
					function () {
						i += 1;
					}
				);

				if (i > 1) {
					// z.B leaflet-gpx mit Track und Marker
					// console.log( geojson._leaflet_id, layer._leaflet_id );
					geojson.eachLayer(
						function (geolayer) {
							// console.log( "make geojson layer > 1 transparent " + onoff );
							if ( geolayer.setStyle ) {
								if (onoff) {
									if (geolayer._leaflet_id != layer._leaflet_id) {
										// console.log( "geolayer.leaflet_id style unterschiedlich" );
										geolayer.setStyle(
											{
												'opacity' : opacity,
												}
										);
									} else {
										// console.log( "leaflet_id gleich - bleibt" );
									}
								} else {
									geolayer.setStyle(
										{
											'opacity' : 1,
											}
									);
								}

							} else {
								// console.log( "no style" );
								// console.log(geolayer);
								// console.log( geolayer._leaflet_id, layer._leaflet_id );
								if (onoff) {
									if (geolayer._leaflet_id != layer._leaflet_id) {
										// console.log( "geolayer.leaflet_id unterschiedlich" );
										geolayer.setOpacity( opacity );
									} else {
										// console.log( "geolayer.leaflet_id gleich" );
										geolayer.setOpacity( 1 );
									}
								} else {
									geolayer.setOpacity( 1 );
								}
							}

						}
					);
				} else {
					geojson.eachLayer(
						function (alllayers) {
							// console.log(layer);
							// console.log( alllayers._leaflet_id, layer._leaflet_id );
							if (alllayers._leaflet_id != layer._leaflet_id) {
								// console.log( "make geojson layer transparent " + onoff );
								if ( alllayers.setStyle ) {
									if (onoff) {
										alllayers.setStyle(
											{
												'opacity' : opacity,
											}
										);
									} else {
										alllayers.setStyle(
											{
												'opacity' : 1,
											}
										);
									}
								}
							} else {
								// console.log( "make geojson layer transparent equal" );
							}
						}
					);
				} //end else i

			}//map_id
		}//geojson foreach
	}//geojson end
}

function leafext_hovertransp_markergroup_js( opacity ) {
	var map    = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;
	// console.log(map_id);

	var markergroups = window.WPLeafletMapPlugin.markergroups;
	Object.entries( markergroups ).forEach(
		([key, value]) =>
		{
			if ( markergroups[key]._map !== null ) {
				if (map_id == markergroups[key]._map._leaflet_id) {
					// console.log("markergroups loop");
					markergroups[key].eachLayer(
						function (layer) {
							// console.log(layer);
							if (layer instanceof L.Marker) {
								// console.log("is_marker");
							} else if (
								layer instanceof L.Polygon ||
								layer instanceof L.Circle ||
								layer instanceof L.Polyline
							) {
								// console.log("is_Polygon or circle or polyline");
								layer.on(
									"mouseover",
									function (e) {
										// console.log("mouseover");
										if (leafext_map_popups( map ) == false) {
											markergroups[key].eachLayer(
												function (layer) {
													if (layer != e.sourceTarget) {
														leafext_make_transparent( map, layer, opacity );
													}
												}
											);
										}
									}
								);
								layer.on(
									"click",
									function (e) {
										markergroups[key].eachLayer(
											function (layer) {
												if (layer != e.sourceTarget) {
													leafext_make_transparent( map, layer, opacity );
												}
											}
										);
									}
								);
								layer.on(
									"mouseout",
									function (e) {
										if (leafext_map_popups( map ) == false) {
											markergroups[key].eachLayer(
												function (layer) {
													leafext_make_full( map,layer );
												}
											);
										}
									}
								);
								layer.on(
									"popupclose",
									function (e) {
										markergroups[key].eachLayer(
											function (layer) {
												leafext_make_full( map,layer );
											}
										);
									}
								);
							} else {
								// console.log( "other" );
								// console.log( layer );
							}
						}
					);
				}
			}
		}
	);
}

function leafext_hovertransp_marker_js( opacity ) {
	// console.log(all_options);
	var map    = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;
	// console.log(map_id);

	var markers        = window.WPLeafletMapPlugin.markers;
	var markers_length = WPLeafletMapPlugin.markers.length;
	if (markers_length > 0) {
		for (var i = 0; i < markers_length; i++) {
			var a = WPLeafletMapPlugin.markers[i];
			if (( a._map != null && a._map._leaflet_id == map_id) || a._map == null ) {

				// console.log(a);
				a.on(
					"mouseover",
					function (e) {
						if (leafext_map_popups( map ) == false) {
							// console.log(e);
							// console.log( "make marker transparent" )
							map.getPane( 'overlayPane' ).style.opacity = opacity;
							//map.getPane('shadowPane').style.opacity = 1;
							//map.getPane('markerPane').style.opacity = 1;

							for (var j = 0; j < markers_length; j++) {
								if (WPLeafletMapPlugin.markers[j] != e.sourceTarget) {
									WPLeafletMapPlugin.markers[j].setOpacity( opacity );
								}
							}
						}
					}
				);
				// mousemove
				a.on(
					"mouseout",
					function (e) {
						if (leafext_map_popups( map ) == false) {
							// console.log( "make marker back" )
							// console.log(e);
							map.getPane( 'overlayPane' ).style.opacity = 1;
							//map.getPane('shadowPane').style.opacity = 1;
							//map.getPane('markerPane').style.opacity = 1;

							for (var j = 0; j < markers_length; j++) {
								WPLeafletMapPlugin.markers[j].setOpacity( 1 );
							}
						}
					}
				);
				a.on(
					"popupclose",
					function (e) {
						map.getPane( 'overlayPane' ).style.opacity = 1;
						//map.getPane('shadowPane').style.opacity = 1;
						//map.getPane('markerPane').style.opacity = 1;

						for (var j = 0; j < markers_length; j++) {
							WPLeafletMapPlugin.markers[j].setOpacity( 1 );
						}
					}
				);
			}
		}
	}
}

function leafext_make_full( map, element ) {
	// map.getPane('overlayPane').style.opacity = opacity;
	map.getPane( 'shadowPane' ).style.opacity = 1;
	map.getPane( 'markerPane' ).style.opacity = 1;
	if ( element.setStyle ) {
		if (element.options.mouseover) {
			element.options.mouseover = false;
			element.setStyle(
				{
					'opacity' : 1,
				}
			);
		}
	}
}

function leafext_make_transparent( map, element, opacity ) {

	// map.getPane('overlayPane').style.opacity = opacity;
	map.getPane( 'shadowPane' ).style.opacity = opacity;
	map.getPane( 'markerPane' ).style.opacity = opacity;

	if ( element.setStyle ) {
		// console.log(element.options);
		if ( ! element.options.mouseover) {
			element.options.mouseover = true;
			element.setStyle(
				{
					'opacity' : opacity,
				}
			);
			element.bringToFront();
		}
	}
}
