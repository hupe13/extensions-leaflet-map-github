# Differences to the Plugins WordPress version

* XSS fixes (not yet confirmed)
* Please let me know if something isn't working!!
* leaflet-elevation-2.9.2 - fixes problems: detached, canvas and Safari, and more. Thank you to Raruto.
* elevation option legend: black track and chart off fixed
* leaflet-extramarker: removed option innerHTML due to the risk of a potential XSS attack
* targetmarker/targetlink: debugging changed. New shortcode targetdebug, see documentation
* leaflet-search: CHANGED! Please see documentation how to specify the new options.
* sgpx: Updated the code for the new version of wp-gpx-maps, see documentation.
* removed '<!--' and  '//-->' for <script>

Please note the [install instructions](https://leafext.de/en/doku/about/versions/).

Releases are the same as the WordPress versions. If you want test the Github version, download the [main tree](https://github.com/hupe13/extensions-leaflet-map-github/archive/refs/heads/main.zip) or from the [tags](https://github.com/hupe13/extensions-leaflet-map-github/tags) or clone it.

Please install [ghu-update-puc](https://github.com/hupe13/ghu-update-puc) to get updates and keep an eye on this repository in case I've made any mistakes.
