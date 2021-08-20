<?php

include_once LEAFEXT_PLUGIN_DIR . '/php/elevation/elevation.php';
include_once LEAFEXT_PLUGIN_DIR . '/php/elevation/multielevation.php';

//Parameter and Values
function leafext_elevation_params() {
	$params = array(

		// Default chart colors: theme lime-theme, magenta-theme, ...
		//theme: "lightblue-theme",
		array('theme', __('Theme Colors',"extensions-leaflet-map"), "lime-theme",
			array("lime-theme","steelblue-theme","purple-theme","yellow-theme","red-theme","magenta-theme","lightblue-theme")),

		// Chart container outside/inside map container
		//detached: true,
		//array('detached', 'Chart container outside/inside map container', true, 1),

		// if (detached), the elevation chart container
		//elevationDiv: "#elevation-div",

		// if (!detached) autohide chart profile on chart mouseleave
		//autohide: false,
		//array('autohide', 'if (!detached) autohide chart profile on chart mouseleave', false, 1),

		// if (!detached) initial state of chart profile control
		//collapsed: false,
		//array('collapsed', 'if (!detached) initial state of chart profile control', false, 1),

		// if (!detached) control position on one of map corners
		//position: "topright",

		//Default hupe13 polyline: { weight: 3, }, Default Raruto 5
		array('polyline', __('Polyline weight',"extensions-leaflet-map"), "{ weight: 3, }", array("{ weight: 3, }","{ weight: 4, }","{ weight: 5, }")),

		// Autoupdate map center on chart mouseover.
		//followMarker: true,
		array('followMarker', __('Autoupdate map center on chart mouseover.',"extensions-leaflet-map"), true, 1),

		// Autoupdate map bounds on chart update.
		//autofitBounds: true,
		array('autofitBounds', __('Autoupdate map bounds on chart update.',"extensions-leaflet-map"), true, 1),

		// Chart distance/elevation units.
		//imperial: false,
		array('imperial', __('Chart distance/elevation units.',"extensions-leaflet-map"), false, 1),

		// [Lat, Long] vs [Long, Lat] points. (leaflet default: [Lat, Long])
		//reverseCoords: false,
		array('reverseCoords', __('[Lat, Long] vs [Long, Lat] points. (leaflet default: [Lat, Long])',"extensions-leaflet-map"), false, 1),

		// Acceleration chart profile: true || "summary" || "disabled" || false
		//acceleration: false,
		array('acceleration', __('Acceleration chart profile',"extensions-leaflet-map"), false, array(true,"summary","disabled",false)),

		// Slope chart profile: true || "summary" || "disabled" || false
		//slope: false,
		array('slope', __('Slope chart profile',"extensions-leaflet-map"), false, array(true,"summary","disabled",false)),

		// Speed chart profile: true || "summary" || "disabled" || false
		//speed: false,
		array('speed', __('Speed chart profile',"extensions-leaflet-map"), false, array(true,"summary","disabled",false)),

		// Display time info: true || "summary" || false
		//time: false,
		array('time', __('Display time info',"extensions-leaflet-map"), false, array(true,"summary",false)),

		// Display distance info: true || "summary"
		//distance: true,
		array('distance', __('Display distance info',"extensions-leaflet-map"), true, array(true,"summary")),

		// Display altitude info: true || "summary"
		//altitude: true,
		array('altitude', __('Display altitude info',"extensions-leaflet-map"), true, array(true,"summary")),

		// Summary track info style: "line" || "multiline" || false
		//Is this an error: line/inline ?
		//summary: 'multiline',
		//hupe13: true historical
		array('summary', __('Summary track info style:',"extensions-leaflet-map"), 'multiline', array(true, "inline","multiline",false)),

		//hupe13: Download Link
		array('downloadLink', __('downloadLink',"extensions-leaflet-map"), false, 1),

		// Toggle chart ruler filter.
		//ruler: true,
		//array('ruler', __('Toggle chart ruler filter.',"extensions-leaflet-map"), true, 1),

		// Toggle chart legend filter.
		//legend: true,
		array('legend', __('Toggle chart legend filter.',"extensions-leaflet-map"), true, 1),

		// Toggle "leaflet-almostover" integration
		//almostOver: true,
		array('almostOver', __('Toggle "leaflet-almostover" integration',"extensions-leaflet-map"), true, 1),

		// Toggle "leaflet-distance-markers" integration
		//distanceMarkers: false,
		array('distanceMarkers', __('Toggle "leaflet-distance-markers" integration',"extensions-leaflet-map"), false, 1),

		// Render chart profiles as Canvas or SVG Paths
		//preferCanvas: true
		array('preferCanvas', __('Render chart profiles as Canvas or SVG Paths',"extensions-leaflet-map"), true, 1),
		
		// https://github.com/Raruto/leaflet-elevation/issues/86#issuecomment-735274347
		// marker: "elevation-line" || "position-marker" || false
		array('Marker', __('position/height indicator marker drawn onto the map',"extensions-leaflet-map"), 'elevation-line', array("elevation-line", "position-marker",false)),

	);
	return $params;
}

function leafext_elevation_settings() {
	$defaults=array();
	$params = leafext_elevation_params();
	foreach($params as $param) {
		$defaults[$param[0]] = $param[2];
	}
	$options = shortcode_atts($defaults, get_option('leafext_eleparams'));
	return $options;
}
