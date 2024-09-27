# Extensions for Leaflet Map

Contributors: hupe13    
Tags: leaflet, gpx, geojson, hover, marker   
Tested up to: 6.6  
Stable tag: 4.3.4     
Requires at least: 5.5.3     
Requires PHP: 7.4     
License: GPLv2 or later

Extends the WordPress Plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a> with Leaflet Plugins and other functions.

## Description

Extends the WordPress Plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a> with Leaflet Plugins and other functions.

### Functions

* Create an elevation chart profile of a track. There are also acceleration, slope, speed and tempo chart profiles. You can also place multiple tracks on one map.

* By default Leaflet Map uses tiles from openstreetmap.org or from the tile servers you configured. You can use more and switch between them.

* Many markers on a map become confusing. You can cluster and shape them.

* You can use Awesome markers.

* You can group the elements on the map by criteria and show/hide them.

* Create an overview map with geo-locations provided in the pages and posts.

* Get a tooltip when hovering over an element.

* You can design a choropleth map.

* You can display the map in fullscreen mode.

* Reset the map.

* Gesture handling

* Manage your files for Leaflet Map.

* Help to migrate from [WP GPX Maps](https://wordpress.org/plugins/wp-gpx-maps/).

* and more functions.

### Included Leaflet Plugins and fonts

#### Leaflet Plugins

* [leaflet-elevation](https://github.com/Raruto/leaflet-elevation): A Leaflet plugin that allows to add elevation profiles using d3js.
* [Leaflet.GeometryUtil](https://github.com/makinacorpus/Leaflet.GeometryUtil)
* [Leaflet.i18n](https://github.com/yohanboniface/Leaflet.i18n): Internationalisation module for Leaflet plugins.
* [leaflet-rotate](https://github.com/Raruto/leaflet-rotate): A Leaflet plugin that allows to add rotation functionality to map tiles
* [Leaflet.AlmostOver](https://github.com/makinacorpus/Leaflet.AlmostOver): This plugin allows to detect mouse click and overing events on lines, with a tolerance distance.
* [@tmcw/togeojson](https://www.npmjs.com/package/@tmcw/togeojson): Convert KML, GPX, and TCX to GeoJSON.
* [D3](https://github.com/d3/d3): Data-Driven Documents
* [Leaflet-providers](https://github.com/leaflet-extras/leaflet-providers): An extension that contains configurations for various tile providers.
* [Leaflet.Control.Opacity](https://github.com/dayjournal/Leaflet.Control.Opacity): Makes multiple tile layers transparent.
* [Leaflet.markercluster](https://github.com/Leaflet/Leaflet.markercluster): Provides Beautiful Animated Marker Clustering functionality.
* [Leaflet.MarkerCluster.PlacementStrategies](https://github.com/adammertel/Leaflet.MarkerCluster.PlacementStrategies): Styling Markerclusters.
* [Leaflet.ExtraMarkers](https://github.com/coryasilva/Leaflet.ExtraMarkers): Shameless copy of Awesome-Markers with more shapes and colors.
* [Leaflet.FeatureGroup.SubGroup](https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup): Grouping of Leaflet elements by options and features.
* [Leaflet.Control.Layers.Tree](https://github.com/jjimenezshaw/Leaflet.Control.Layers.Tree): A Tree Layers Control for Leaflet.
* [Leaflet Control Search](https://github.com/stefanocudini/leaflet-search): Search Markers/Features location by option or custom property.
* [leaflet-choropleth](https://github.com/timwis/leaflet-choropleth): Choropleth plugin for Leaflet (color scale based on value).
* [leaflet.zoomhome](https://github.com/torfsen/leaflet.zoomhome): Provides a zoom control with a "Home" button to reset the view.
* [leaflet.fullscreen](https://github.com/brunob/leaflet.fullscreen): Simple plugin for Leaflet that adds fullscreen button to your maps.
* [Leaflet.GestureHandling](https://github.com/Raruto/leaflet-gesture-handling): A Leaflet plugin that allows to prevent default map scroll/touch behaviours.
* [turf](https://github.com/Turfjs/turf): Advanced geospatial analysis for browsers and Node.js

#### Font

* [Font Awesome 6](https://fontawesome.com/download)

## Screenshots

1. Track with elevation and speed profiles <br>![Track with elevation profile](.wordpress-org/screenshot-1.png)
2. ExtraMarkers <br>![ExtraMarkers](.wordpress-org/screenshot-2.png)
3. Grouping and Tree View <br>![Grouping and Tree View](.wordpress-org/screenshot-3.png)
4. Markercluster PlacementStrategies <br>![Markercluster PlacementStrategies](.wordpress-org/screenshot-4.png)
5. Tooltip on Hover <br>![Tooltip on Hover](.wordpress-org/screenshot-5.png)
6. Manage Leaflet Map files <br>![Manage Leaflet Map files](.wordpress-org/screenshot-6.png)

## Documentation

Detailed documentation and examples in <a href="https://leafext.de/">German</a> and <a href="https://leafext.de/en/">English</a>.

## Thank you

Many thanks to everyone who found errors and provided ideas for new functions.

## Frequently Asked Questions

**Is there a widget or other support for the editor?**

* Unfortunately both plugins - Leaflet Map and Extensions for Leaflet Map - only work with shortcodes.
* If you have any questions please ask in the [forum](https://wordpress.org/support/plugin/extensions-leaflet-map/).

**My gpx file is not displayed!**

* Is the URL correct?
* Does the webserver return the correct mime type (application/gpx+xml)?
Put in your `.htaccess`:
<pre><code>AddType application/gpx+xml gpx
RewriteRule .*\\.gpx$ - [L,T=application/gpx+xml]</code></pre>

**It doesn't work!**

* Are you using any caching plugin? Try to exclude at least these js files from caching:
 - /wp-content/plugins/extensions-leaflet-map/leaflet-plugins/leaflet-elevation-*
 - /wp-content/plugins/extensions-leaflet-map/leaflet-plugins/leaflet-gesture-handling-*
* Are you using any plugin to comply with the GDPR/DSGVO? There might be a problem with that.
* If you use a caching plugin and a GDPR/DSGVO plugin you need to distinguish whether the user has accepted the cookie or not.
* Please ask in the [forum](https://wordpress.org/support/plugin/extensions-leaflet-map/)!

**Apropos GDPR/DSGVO**

* If you need a plugin for this try [DSGVO/GDPR Snippet for Extensions for Leaflet Map](https://github.com/hupe13/extensions-leaflet-map-dsgvo).
* If you use [Complianz | GDPR/CCPA Cookie Consent](https://wordpress.org/plugins/complianz-gdpr/) see [here](https://complianz.io/leaflet-maps/).

## Installation

* First you need to install and configure the plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a>.
* Then install this plugin.
* Go to Settings - Leaflet Map - Extensions for Leaflet Map and get documentation and settings options.

## Changelog

### 4.3.4 / 240828

* overviewmap: custom field for popup

### Previous

[Changelog](https://github.com/hupe13/extensions-leaflet-map-github/blob/main/CHANGELOG.md)
