// For use with only one map on a webpage
// Standard options for elevation
// Full list options at "leaflet-elevation.js"
var elevation_options = {
	//lime-theme (default), magenta-theme, steelblue-theme, purple-theme, yellow-theme, lightblue-theme
	theme: track.theme,
	// Autoupdate map center on chart mouseover.
	followMarker: false,
	legend: false,
	downloadLink:false,
	polyline: { weight: 3, },
	// Summary track info style: "line" || "multiline" || false || inline(?)
	// Slope chart profile: true || "summary" || false
	summary: track.summary,
	slope: track.slope,
};

// Instantiate elevation control.
var controlElevation = L.control.elevation(elevation_options);
var track_options= { url: track.gpx, };

window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
window.WPLeafletMapPlugin.push(function () {
	var map = window.WPLeafletMapPlugin.getCurrentMap();
	controlElevation.addTo(map);
	// Load track from url (allowed data types: "*.geojson", "*.gpx")
	controlElevation.load(track_options.url);
});
