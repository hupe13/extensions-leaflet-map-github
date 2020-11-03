// For use with only one map on a webpage
window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
window.WPLeafletMapPlugin.push(function () {
	var map = window.WPLeafletMapPlugin.getCurrentMap();
	// create fullscreen control
	var fsControl = new L.Control.FullScreen();
	// add fullscreen control to the map
	map.addControl(fsControl);
});
