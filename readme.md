# Extensions for Leaflet Map Github Version

Contributors: hupe13    
Tags: leaflet, elevation, markercluster, Leaflet Plugins   
Tested up to: 6.1  
Stable tag: 3.4  
Requires at least: 5.5.3     
Requires PHP: 7.4     
License: GPLv2 or later  

[Differences](changes.md) to the <a href="https://wordpress.org/plugins/extensions-leaflet-map/">WordPress version</a>.

## Description

Extends the WordPress Plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a> with Leaflet Plugins and other functions.

### Used Leaflet Plugins and Elements

*   [leaflet-elevation](https://github.com/Raruto/leaflet-elevation), [Leaflet.i18n](https://github.com/yohanboniface/Leaflet.i18n): Track with Elevation Profile
*   [leaflet-gpxgroup](https://github.com/Raruto/leaflet-elevation/blob/master/libs/leaflet-gpxgroup.js), [Leaflet.GeometryUtil](https://github.com/makinacorpus/Leaflet.GeometryUtil): Multiple tracks with elevation profiles on one map
*   [L.control.layers](https://leafletjs.com/examples/layers-control/): Switching Tilelayers
*   [Leaflet-providers](https://github.com/leaflet-extras/leaflet-providers): An extension that contains configurations for various tile providers.
*   [Leaflet.Control.Opacity](https://github.com/dayjournal/Leaflet.Control.Opacity): makes multiple tile layers transparent.
*   [Leaflet.markercluster](https://github.com/Leaflet/Leaflet.markercluster): Provides Beautiful Animated Marker Clustering functionality
*   [Leaflet.MarkerCluster.PlacementStrategies](https://github.com/adammertel/Leaflet.MarkerCluster.PlacementStrategies): implements new possibilities how to place clustered children markers
*   [Leaflet.ExtraMarkers](https://github.com/coryasilva/Leaflet.ExtraMarkers): Shameless copy of Awesome-Markers with more shapes, colors and semantic-ui support
*   [Leaflet.FeatureGroup.SubGroup](https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup): Creates Feature Groups that add their child layers into a parent group. Typical usage is to switch them through L.Control.Layers to dynamically add/remove groups of markers from Leaflet.markercluster.
*   [Leaflet Control Search](https://github.com/stefanocudini/leaflet-search): A control for search Markers/Features location by custom property in LayerGroup/GeoJSON.
*   [leaflet-choropleth](https://github.com/timwis/leaflet-choropleth): Choropleth plugin for Leaflet (color scale based on value)
*   [leaflet.zoomhome](https://github.com/torfsen/leaflet.zoomhome): Reset the view
*   [leaflet.fullscreen](https://github.com/brunob/leaflet.fullscreen): Simple plugin for Leaflet that adds fullscreen button to your maps.
*   [Leaflet.GestureHandling](https://github.com/Raruto/leaflet-gesture-handling): A Leaflet plugin that allows to prevent default map scroll/touch behaviours.

### Other functions

*  List files for Leaflet Map
*  hover:
     * Highlight a gpx, kml or geojson element on mouse over
     * get a tooltip for marker, gpx, kml or geojson element on mouse over.
*   Hide Markers: Use it when a track in a GPX file contains some markers and you don't want to display them on the map.
*   Option to migrate from [WP GPX Maps](https://wordpress.org/plugins/wp-gpx-maps/) to elevation

## Screenshots

1. Track with elevation and other profiles and Switching Tile Layers<br>![Track with elevation profile](.wordpress-org/screenshot-1.png)
2. Hover a Geojson area <br>![Hover a Geojson area](.wordpress-org/screenshot-2.png)
3. Markercluster and Groups <br>![Markercluster](.wordpress-org/screenshot-3.png)
4. Markercluster PlacementStrategies <br>![PlacementStrategies](.wordpress-org/screenshot-4.png)
5. ExtraMarkers <br>![ExtraMarkers](.wordpress-org/screenshot-5.png)
6. Choropleth Map (data from Choropleth plugin example) <br>![Choropleth Map (data from Choropleth example)](.wordpress-org/screenshot-6.png)
7. Files for Leaflet Map <br>![Files for Leaflet Map](.wordpress-org/screenshot-7.png)

## Documentation

Detailed documentation and examples in <a href="https://leafext.de/">German</a> and <a href="https://leafext.de/en/">English</a>.

## Frequently Asked Questions

<p>
<details>
<summary>
<b>Is there a widget or other support for the editor?</b>
</summary>

* Unfortunately both plugins - Leaflet Map and Extensions for Leaflet Map - only work with shortcodes.
* If you have any questions please ask in the [forum](https://wordpress.org/support/plugin/extensions-leaflet-map/).
</details>

<details>
<summary>
<b>My gpx file is not displayed!</b>
</summary>

* Is the URL correct?
* Does the webserver return the correct mime type (application/gpx+xml)?
Put in your `.htaccess`:
```
AddType application/gpx+xml gpx
RewriteRule .*\.gpx$ - [L,T=application/gpx+xml]
```
</details>

<details>
<summary>
<b>It doesn't work!</b>
</summary>

* Are you using any caching plugin? Try to exclude the js files of both plugins from caching.
* Are you using any plugin to comply with the GDPR/DSGVO? There might be a problem with that.
* Please ask in the [forum](https://wordpress.org/support/plugin/extensions-leaflet-map/)!
</details>

<details>
<summary>
<b>Apropos GDPR/DSGVO</b>
</summary>

* If you need a plugin for this try [DSGVO/GDPR Snippet for Extensions for Leaflet Map](https://github.com/hupe13/extensions-leaflet-map-dsgvo).
* If you use [Complianz | GDPR/CCPA Cookie Consent](https://wordpress.org/plugins/complianz-gdpr/) see [here](https://complianz.io/leaflet-maps/).
</details>
</p>

## Installation

* First you need to install and configure the plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a>.
* Then install this plugin.
* Go to Settings - Leaflet Map - Extensions for Leaflet Map and get documentation and settings options.

## Changelog

### 3.4 / 23030x

* new: Leaflet Search Control
* bug if disableClusteringAtZoom = 0 fixed
* fix && in inline JavaScript
* add_filter('render_block' to prevent various WordPress filters like wpautop in leaflet-shortcode block
* disable tooltip on hover on Samsung smartphones
* pay more attention to original values on hovering
* leaflet-extramarker title
* leaflet-gesture-handling-1.4.4
* changed Gesture Handling to work with Complianz-GDPR
* there is no bug safari popups in leafletjs 1.9.3 anymore

### Previous

[Changelog](CHANGELOG.md)
