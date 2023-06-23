/**
* Javascript functions for Extensions for Leaflet Map
* extensions-leaflet-map
*/

function leafext_fullscreen_js() {
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		// create fullscreen control
		var fsControl = new L.Control.FullScreen();
		// add fullscreen control to the map
		map.addControl(fsControl);
	});
}
