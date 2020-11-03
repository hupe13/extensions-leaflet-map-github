// For use with any map on a webpage
//GestureHandling disables the following map attributes.
//dragging
//tap
//scrollWheelZoom

(function() {
	function main() {
		if (!window.WPLeafletMapPlugin) {
			console.log("no plugin found!");
			return;
		}

		var maps = window.WPLeafletMapPlugin.maps;

		//console.log("gesture");
		for (var i = 0, len = maps.length; i < len; i++) {
			var map = maps[i];
			if ( map.dragging.enabled()
					|| map.scrollWheelZoom.enabled()
				) {
				console.log("enabled");
				map.gestureHandling.enable();
			}
		}
	}
	window.addEventListener("load", main);
})();
