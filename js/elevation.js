// For use with only one map on a webpage
// Standard options for elevation
// Full list options at "leaflet-elevation.js"

var __ = wp.i18n.__;
__( '__', 'extensions-leafet-map' );

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

var mylocale = {
	"Altitude": __('Altitude', 'extensions-leaflet-map')+': ',
	"Total Length: ": __('Total Length', 'extensions-leaflet-map')+': ',
	"Max Elevation: ": __('Max Elevation', 'extensions-leaflet-map')+': ',
	"Min Elevation: ": __('Min Elevation', 'extensions-leaflet-map')+': ',
	"Total Ascent: ": __('Total Ascent', 'extensions-leaflet-map')+': ',
	"Total Descent: ": __('Total Descent', 'extensions-leaflet-map')+': ',
	"Min Slope: ": __('Min Slope', 'extensions-leaflet-map')+': ',
	"Max Slope: ": __('Max Slope', 'extensions-leaflet-map')+': ',
};
L.registerLocale("wp", mylocale);
L.setLocale("wp");

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
