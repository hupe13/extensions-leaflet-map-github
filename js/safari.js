(function() {
  function main() {
    var maps = window.WPLeafletMapPlugin.maps;
    //console.log("safaritest");
    for (var i = 0, len = maps.length; i < len; i++) {
      var map = maps[i];
      if (typeof map.tap !== 'undefined') {
        if (map.options.tap) {
          map.tap.enable();
        } else {
          map.tap.disable();
        }
      }
      var is_chrome = navigator.userAgent.indexOf("Chrome") > -1;
      var is_safari = navigator.userAgent.indexOf("Safari") > -1;
      if ( !is_chrome && is_safari ) {
		  console.log("is_safari");
        map.tap.disable();
      }
    }
  }
  window.addEventListener("load", main);
})();
