/**
 * Javascript functions for Shortcode hover.
 *
 * @package Extensions for Leaflet Map
 */

// .

function leafext_get_tooltip(layer, tooltip) {
	// console.log(layer.options);

	if (typeof tooltip == "string" ) {
		if ( ! layer.options.tooltip) {
			if (tooltip.indexOf( '{' ) !== -1) {

				// https://gist.github.com/forcewake/82e4e646c41bb638a3db
				var tipprops = [],          // an array to collect the strings that are found
				rxp          = /{([^}]+)}/g,
				str          = tooltip,
				curMatch;
				while ( curMatch = rxp.exec( str ) ) {
					tipprops.push( curMatch[1] );
				}
				// console.log( tipprops );

				var thistooltip = tooltip;
				for (const tipprop of tipprops) {
					if (layer.feature.properties[tipprop]) {
						thistooltip = thistooltip.replace( '{' + tipprop + '}',layer.feature.properties[tipprop] );
					}
				}
				content               = thistooltip;
				layer.options.tooltip = thistooltip;
			} else {
				content               = tooltip;
				layer.options.tooltip = tooltip;
			}
		} else {
			content = layer.options.tooltip;
		}
	} else {
		content = layer.getPopup().getContent();
	}
	return content;
}

function leafext_hover_geojsonstyle_js(all_options) {
	var map    = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;
	// console.log(map_id);
	var maps     = [];
	maps[map_id] = map;

	if ( WPLeafletMapPlugin.geojsons.length > 0 ) {
		var geojsons = window.WPLeafletMapPlugin.geojsons;
		var geocount = geojsons.length;

		for (var j = 0, len = geocount; j < len; j++) {
			var geojson = geojsons[j];
			// console.log(geojson);

			if (map_id == geojsons[j]._map._leaflet_id) {
				let exclude = -1;

				// console.log(geojson._url);
				extension = geojson._url.split( "." ).pop();
				extension = extension.toLowerCase();
				if (extension == 'json') {
					extension = 'geojson';
				}
				// console.log(extension);
				// console.log(all_options[extension]);

				if ( ! (all_options[extension] == true || all_options[extension] == 'style' || all_options['geojsonstyle'])) {
					exclude = 99;
				}

				if ( all_options['exclude'] !== "" ) {
					// console.log("set exclude "+all_options['exclude']);
					exclude = geojson._url.indexOf( all_options['exclude'] );
				}
				// console.log( exclude );
				if (exclude == -1) {
					// mouseover
					geojson.layer.on(
						"mouseover mousemove",
						function (e) {
							if (leafext_map_popups( map ) == false) {
									let i = 0;
									e.target.eachLayer(
										function () {
											i += 1;
										}
									);
									// console.log("mouseover has", i, "layers.");
								if (i > 1) {
									// z.B leaflet-gpx mit Track und Marker
									leafext_make_overstyle( e.sourceTarget );
									if (all_options['opacity']) {
										leafext_make_transparent_geojson( map, e.sourceTarget, 1, all_options['opacity'] );
									}
								} else {
									e.target.eachLayer(
										function (layer) {
											// console.log(layer);
											leafext_make_overstyle( layer );
											if (all_options['opacity']) {
												leafext_make_transparent_geojson( map, layer, 1, all_options['opacity'] );
											}
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

								e.target.eachLayer(
									function (layer) {
										leafext_make_styleback( layer );
									}
								);

								let i = 0;
								e.target.eachLayer(
									function () {
										i += 1;
									}
								);
								// console.log("mouseout has", i, "layers.");
								if (i == 1) {
									geojson.resetStyle();
								}

								if (all_options['opacity']) {
									e.target.eachLayer(
										function (layer) {
											leafext_make_transparent_geojson( map, layer, 0, all_options['opacity'] );
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
										i += 1; }
								);
								// console.log("mouseclick has", i, "layers.");
							if (i > 1) {
								// z.B leaflet-gpx mit Track und Marker
								leafext_make_overstyle( e.sourceTarget );
								if (all_options['opacity']) {
									leafext_make_transparent_geojson( map, e.sourceTarget, 1, all_options['opacity'] );
								}
							} else {
								map.eachLayer(
									function (layer) {
										leafext_make_styleback( layer );
										if (all_options['opacity']) {
											leafext_make_transparent_geojson( map, layer, 0, all_options['opacity'] );
										}
									}
								);
								e.target.eachLayer(
									function (layer) {
										leafext_make_overstyle( layer );
										if (all_options['opacity']) {
											leafext_make_transparent_geojson( map, layer, 1, all_options['opacity'] );
										}
									}
								);
							} //end else i
						}
					);
					// mouseclick end

					geojson.layer.on(
						"popupclose",
						function (e) {
							e.target.eachLayer(
								function (layer) {
									leafext_make_styleback( layer );
								}
							);

							if (all_options['opacity']) {
								let i = 0;
								e.target.eachLayer(
									function () {
										i += 1;
									}
								);
								// console.log( "popupclose has", i, "layers." );
								if (i > 1) {
									// z.B leaflet-gpx mit Track und Marker
									leafext_make_transparent_geojson( map, e.sourceTarget, 0, all_options['opacity'] );
								} else {
									map.eachLayer(
										function (layer) {
											leafext_make_transparent_geojson( map, layer, 0, all_options['opacity'] );
										}
									);
									e.target.eachLayer(
										function (layer) {
											leafext_make_transparent_geojson( map, layer, 0, all_options['opacity'] );
										}
									);
								} //end else i
							}

						}
					);

				} else { // exclude
					geojson.layer.on(
						'mouseout',
						function () {
							this.bringToBack();
						}
					);
				}
			}//map_id
		}//geojson foreach
	}//geojson end
}

function leafext_hover_markergroupstyle_js(all_options) {
	var map    = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;
	// console.log(map_id);
	var maps     = [];
	maps[map_id] = map;

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
							(layer instanceof L.Polygon && all_options['polygon'] == true || all_options['polygon'] == 'style' || all_options['markergroupstyle']) ||
							(layer instanceof L.Circle && all_options['circle'] == true || all_options['circle'] == 'style' || all_options['markergroupstyle']) ||
							(layer instanceof L.Polyline && all_options['line'] == true || all_options['line'] == 'style' || all_options['markergroupstyle'])
							) {
								// console.log("is_Polygon or circle or polyline");
								layer.on(
									"mouseover mousemove",
									function (e) {
										// console.log("mouseover");
										if (leafext_map_popups( map ) == false) {
											leafext_make_overstyle( e.sourceTarget );
										}
									}
								);
								layer.on(
									"mouseover",
									function (e) {
										if (all_options['opacity']) {
											if (leafext_map_popups( map ) == false) {
												markergroups[key].eachLayer(
													function (layer) {
														if (layer != e.sourceTarget) {
															leafext_make_transparent( map, layer, 1, all_options['opacity'] );
														}
													}
												);
											}
										}
									}
								);
								layer.on(
									"mouseout",
									function (e) {
										if (leafext_map_popups( map ) == false) {
											leafext_make_styleback( e.sourceTarget );
											if (all_options['opacity']) {
												markergroups[key].eachLayer(
													function (layer) {
														leafext_make_transparent( map, layer, 0, all_options['opacity'] );
													}
												);
											}
										}
									}
								);
								layer.on(
									"click",
									function (e) {
										map.eachLayer(
											function (layer) {
												leafext_make_styleback( layer );
											}
										);
										leafext_make_overstyle( e.sourceTarget );
										if (all_options['opacity']) {
											markergroups[key].eachLayer(
												function (layer) {
													if (layer != e.sourceTarget) {
														leafext_make_transparent( map, layer, 1, all_options['opacity'] );
													}
												}
											);
										}
									}
								);
								layer.on(
									"popupclose",
									function (e) {
										leafext_make_styleback( e.sourceTarget );
										if (all_options['opacity']) {
											markergroups[key].eachLayer(
												function (layer) {
													leafext_make_transparent( map, layer, 0, all_options['opacity'] );
												}
											);
										}
									}
								);

							} else {
								// console.log("other");
								// console.log(layer);
							}
						}
					);
				}
			}
		}
	);
}

function leafext_hover_geojsontooltip_js(tooltip,all_options) {
	var snap   = parseInt( all_options['popupclose'] );
	var map    = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;
	// console.log(map_id);
	var maps     = [];
	maps[map_id] = map;

	if ( WPLeafletMapPlugin.geojsons.length > 0 ) {
		var geojsons = window.WPLeafletMapPlugin.geojsons;
		var geocount = geojsons.length;
		for (var j = 0, len = geocount; j < len; j++) {
			var geojson = geojsons[j];
			// console.log(geojson);
			if (map_id == geojsons[j]._map._leaflet_id) {

				geojson.layer.on(
					"click",
					function (e) {
						// console.log("click");
						e.target.eachLayer(
							function (layer) {
								layer.unbindTooltip();
								layer.bindTooltip( "", {visibility: 'hidden', opacity: 0} ).closeTooltip();
							}
						);
					}
				);
				// mouse click end

				// mousemove
				geojson.layer.on(
					"mousemove",
					function (e) {
						// console.log("geojson mousemove");
						let i = 0;
						e.target.eachLayer(
							function () {
								i += 1;
							}
						);
						// console.log("mousemove has", i, "layers.");

						e.target.eachLayer(
							function (layer) {
								if (i == 1) {
									leafext_hover_geojsonlayer( e,map,layer,tooltip,all_options );
								} else {
									leafext_hover_geojsonlayer( e,map,e.sourceTarget,tooltip,all_options );
								}
							}
						);
					}
				); // mousemove end
			} //geojson foreach
		}
	} //geojson end
}

function leafext_hover_markergrouptooltip_js(all_options) {
	var snap = parseInt( all_options['popupclose'] );
	// console.log("snap "+snap);
	var map    = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;
	// console.log(map_id);
	var maps     = [];
	maps[map_id] = map;

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
							(layer instanceof L.Polygon && all_options['polygon'] == true || all_options['polygon'] == 'tooltip' || all_options['markergrouptooltip']) ||
							(layer instanceof L.Circle && all_options['circle'] == true || all_options['circle'] == 'tooltip' || all_options['markergrouptooltip']) ||
							(layer instanceof L.Polyline && all_options['line'] == true || all_options['line'] == 'tooltip' || all_options['markergrouptooltip'])
							) {
								// console.log("is_Polygon or circle or polyline");
								layer.on(
									"mousemove",
									function (e) {
										// console.log("mousemove");
										if ( e.sourceTarget.getPopup() && e.sourceTarget.getPopup().isOpen()) {
										} else {
											if (leafext_map_popups( map )) {
												e.sourceTarget.unbindTooltip();
												e.sourceTarget.bindTooltip( "", {visibility: 'hidden', opacity: 0} ).closeTooltip();
												if (snap > 0) {
													leafext_tooltip_snap( e,e.sourceTarget._map,snap );
												}
											} else {
												if ( e.sourceTarget.getPopup()) {
													var content = e.sourceTarget.getPopup().getContent();
													e.sourceTarget.bindTooltip( content,{className: all_options['class']} );
													e.sourceTarget.openTooltip( e.latlng );
												}
											}
										}
									}
								);
								// mousemove

								layer.on(
									"click",
									function (e) {
										// console.log("click");
										e.sourceTarget.unbindTooltip();
										e.sourceTarget.bindTooltip( "", {visibility: 'hidden', opacity: 0} ).closeTooltip();
									}
								);
							} else {
								// console.log("other");
								// console.log(layer);
							}
						}
					);
				}
			}
		}
	);
}

function leafext_hover_markertitle_js() {
	var map    = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;
	// console.log(map_id);
	var maps           = [];
	maps[map_id]       = map;
	var markers        = window.WPLeafletMapPlugin.markers;
	var markers_length = WPLeafletMapPlugin.markers.length;
	if (markers_length > 0) {
		for (var i = 0; i < markers_length; i++) {
			var a = WPLeafletMapPlugin.markers[i];
			if (( a._map != null && a._map._leaflet_id == map_id) || a._map == null ) {
				// console.log(a.options);
				// console.log(a.options.title);
				if ( a.options.title ) {
					// console.log("has title - deleted");
					a.options.title = "";
				}
				if ( a._icon ) {
					// console.log("has _icon - title deleted");
					a._icon.title = "";
				}
				// console.log(a);
				a.unbindTooltip();
				a.bindTooltip( "", {visibility: 'hidden', opacity: 0} ).closeTooltip();
			}
		}
	}
}

function leafext_hover_markertooltip_js(all_options) {
	// console.log(all_options);
	var snap   = parseInt( all_options['popupclose'] );
	var map    = window.WPLeafletMapPlugin.getCurrentMap();
	var map_id = map._leaflet_id;
	// console.log(map_id);
	var maps     = [];
	maps[map_id] = map;

	var markers        = window.WPLeafletMapPlugin.markers;
	var markers_length = WPLeafletMapPlugin.markers.length;
	if (markers_length > 0) {
		for (var i = 0; i < markers_length; i++) {
			var a = WPLeafletMapPlugin.markers[i];
			if (( a._map != null && a._map._leaflet_id == map_id) || a._map == null ) {
				// console.log(a.options);
				// console.log(a.options.title);
				if ( a.options.title ) {
					// console.log("has title - deleted");
					a.options.title = "";
				}
				if ( a._icon ) {
					// console.log("has _icon - title deleted");
					a._icon.title = "";
				}
				// console.log(a);
				a.on(
					"mouseover mousemove",
					function (e) {
						// console.log("marker mouseover");
						// console.log(e);
						if ( e.sourceTarget.getPopup() && e.sourceTarget.getPopup().isOpen()) {
						} else {
							if (leafext_map_popups( map )) {
								e.sourceTarget.unbindTooltip();
								e.sourceTarget.bindTooltip( "", {visibility: 'hidden', opacity: 0} ).closeTooltip();
								if (snap > 0) {
									leafext_tooltip_snap( e,e.sourceTarget._map,snap );
								}
							} else {
								if ( e.sourceTarget.getPopup()) {
									var content = e.sourceTarget.getPopup().getContent();
									e.sourceTarget.bindTooltip( content,{className: all_options['class']} );
									e.sourceTarget.openTooltip( e.latlng );
								}
							}
						}
					}
				);
				// mousemove

				a.on(
					"click",
					function (e) {
						// console.log( "click marker" );
						map.eachLayer(
							function (layer) {
								leafext_make_styleback( layer );
							}
						);
						e.sourceTarget.unbindTooltip();
						e.sourceTarget.bindTooltip( "", {visibility: 'hidden', opacity: 0} ).closeTooltip();
					}
				);

				if (all_options['opacity']) {
				a.on(
					"mouseover",
					function (e) {
						if (leafext_map_popups( map ) == false) {
							// console.log(e);
							// console.log( "make marker transparent" )
							map.getPane( 'overlayPane' ).style.opacity = all_options['opacity'];
							//map.getPane('shadowPane').style.opacity = 1;
							//map.getPane('markerPane').style.opacity = 1;

							for (var j = 0; j < markers_length; j++) {
								if (WPLeafletMapPlugin.markers[j]._map._leaflet_id == map_id) {
									if (WPLeafletMapPlugin.markers[j] != e.sourceTarget) {
										WPLeafletMapPlugin.markers[j].setOpacity( all_options['opacity'] );
									}
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
}

function leafext_hover_geojsonlayer(e,map,layer,tooltip,all_options) {
	if ( layer.getPopup() && layer.getPopup().isOpen()) {
		// console.log("geojson is open");
	} else {
		if (leafext_map_popups( map )) {
			layer.unbindTooltip();
			layer.bindTooltip( "", {visibility: 'hidden', opacity: 0} ).closeTooltip();
			if (all_options['popupclose'] > 0) {
				leafext_tooltip_snap( e,layer._map,all_options['popupclose'] );
			}
		} else {
			if ( layer.getPopup()) {
				// var content = layer.getPopup().getContent();
				var content = leafext_get_tooltip( layer, tooltip );
				layer.bindTooltip( content,{className: all_options['class']} );
				layer.openTooltip( e.latlng );
			}
		}
	}
}

function leafext_tooltip_snap(e,map,snap) {
	// console.log(snap);
	var elements = [];
	map.eachLayer(
		function (layer) {
			if ( layer.getPopup() ) {
				if ( layer.getPopup().isOpen()) {
					// console.log("is open");
					// console.log(layer.getPopup().getLatLng());
					elements.push( new L.Marker( layer.getPopup().getLatLng() ) );
				}
			}
		}
	);
	// console.log(elements);
	var result = L.GeometryUtil.closestLayer(
		map,
		elements, // alle Marker
		e.latlng // mouse position.
	);
	// console.log(result.distance,snap);
	if (result.distance > snap) {
		map.closePopup();
	}
}

// mapPane 	HTMLElement 	'auto' 	Pane that contains all other map panes
// tilePane 	HTMLElement 	200 	Pane for GridLayers and TileLayers
// overlayPane 	HTMLElement 	400 	Pane for vectors (Paths, like Polylines and Polygons), ImageOverlays and VideoOverlays
// shadowPane 	HTMLElement 	500 	Pane for overlay shadows (e.g. Marker shadows)
// markerPane 	HTMLElement 	600 	Pane for Icons of Markers
// tooltipPane 	HTMLElement 	650 	Pane for Tooltips.
// popupPane 	HTMLElement 	700 	Pane for Popups.

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

function leafext_make_transparent( map, element, onoff, opacity ) {
	if (onoff) {
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
	} else {
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
}
