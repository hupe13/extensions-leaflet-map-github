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
					geojson.on(
						"ready",
						function () {
							var a = this.layer;
							a.eachLayer(
								function (layer) {

									// mouseover
									layer.on(
										"mouseover",
										function (e) {
											// console.log( "leafext_hover_geojsonstyle_js " + e.type );
											// console.log( e.sourceTarget.setStyle );
											if (leafext_map_popups( map ) == false) {
												if (e.sourceTarget.setStyle) {
													leafext_make_overstyle( e.sourceTarget );
												}
											}
										}
									);
									// mouseover end

									// mouseout
									layer.on(
										"mouseout",
										function (e) {
											if (e.sourceTarget.setStyle) {
												if (leafext_map_popups( map ) == false) {
													leafext_make_styleback( e.sourceTarget );
												}
											}
										}
									);
									// mouseout end

									// mouseclick
									layer.on(
										"click",
										function (e) {
											// console.log( "leafext_hover_geojsonstyle_js " + e.type );
											// console.log( e.sourceTarget.setStyle );
											if (e.sourceTarget.setStyle) {
												leafext_make_overstyle( e.sourceTarget );
											}
										}
									);
									// mouseclick end

									layer.on(
										"popupclose",
										function (e) {
											// console.log( "leafext_hover_geojsonstyle_js " + e.type );
											// console.log( e.sourceTarget.setStyle );
											if (e.sourceTarget.setStyle) {
												leafext_make_styleback( e.sourceTarget );
											}
										}
									);
								}
							);
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
									"mouseover",
									function (e) {
										// console.log("mouseover");
										if (leafext_map_popups( map ) == false) {
											leafext_make_overstyle( e.sourceTarget );
										}
									}
								);

								layer.on(
									"mouseout",
									function (e) {
										if (leafext_map_popups( map ) == false) {
											leafext_make_styleback( e.sourceTarget );
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
									}
								);
								layer.on(
									"popupclose",
									function (e) {
										leafext_make_styleback( e.sourceTarget );
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
				geojson.on(
					"ready",
					function () {
						// console.log("ready");
						var a = this.layer;
						// console.log(a);

						a.eachLayer(
							function (layer) {
								layer.on(
									"click",
									function (e) {
										e.target.unbindTooltip();
										e.target.bindTooltip( "", {visibility: 'hidden', opacity: 0} ).closeTooltip();
									}
								);
								layer.on(
									"mousemove",
									function (e) {
										leafext_hover_geojsonlayer( e,map,e.target,tooltip,all_options );
									}
								);
							}
						);
					}
				);
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
					a.options.title_bak = a.options.title;
					a.options.title     = "";
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
					a.options.title_bak = a.options.title;
					a.options.title     = "";
				}
				if ( a._icon ) {
					// console.log("has _icon - title deleted");
					a._icon.title = "";
				}
				// console.log(a);
				a.on(
					"mouseover",
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
				// mouseover

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

			}
		}
	}
}

function leafext_hover_geojsonlayer(e,map,layer,tooltip,all_options) {
	if ( layer.getPopup() && layer.getPopup().isOpen()) {
		// console.log( "geojson is open" );
	} else {
		// console.log( "geojson no popup" );
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
