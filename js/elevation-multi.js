// For use with more than one map on a webpage
// Standard options for elevation
// Full list options see  https://github.com/Raruto/leaflet-elevation

var elevation_options = {
	//lime-theme (default), magenta-theme, steelblue-theme, purple-theme, yellow-theme, lightblue-theme
	theme: "steelblue-theme",
	// Autoupdate map center on chart mouseover.
	followMarker: false,
	legend: false,
	downloadLink:false,
	polyline: { weight: 3, },
};

var mylocale = {
	"Altitude": "Höhe",
	"Total Length: ": "Gesamtstrecke: ",
	"Max Elevation: ": "Maximale Höhe: ",
	"Min Elevation: ": "Minimale Höhe: ",
	"Total Ascent: ": "Gesamtanstieg: ",
	"Total Descent: ": "Gesamtabstieg: ",
	"Min Slope: ": "max. Gefälle: ",
	"Max Slope: ": "max. Steigung: "
};
L.registerLocale("de", mylocale);
L.setLocale("de");
