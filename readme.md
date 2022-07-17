# Extensions for Leaflet Map Github Version

Contributors: hupe13    
Tags: leaflet, gpx, elevation, markercluster, zoomhome, hover, fullscreen  
Tested up to: 6.0  
Stable tag: 3.1  
Requires at least: 5.5.3     
Requires PHP: 7.4     
License: GPLv2 or later  

Differences to the WordPress plugin <a href="https://wordpress.org/plugins/extensions-leaflet-map/">Extensions for Leaflet Map</a>: [Changes](changes.md)

## Description

Extends the WordPress Plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a> with Leaflet Plugins and other functions.

### Used Leaflet Plugins and Elements

*   [leaflet-elevation](https://github.com/Raruto/leaflet-elevation), [Leaflet.i18n](https://github.com/yohanboniface/Leaflet.i18n): Track with Elevation Profile
*   [leaflet-gpxgroup](https://github.com/Raruto/leaflet-elevation/blob/master/libs/leaflet-gpxgroup.js), [Leaflet.GeometryUtil](https://github.com/makinacorpus/Leaflet.GeometryUtil): Multiple tracks with elevation profiles on one map
*   [L.control.layers](https://leafletjs.com/examples/layers-control/): Switching Tilelayers
*   [Leaflet-providers](https://github.com/leaflet-extras/leaflet-providers): An extension that contains configurations for various tile providers.
*   [Leaflet.Control.Opacity](https://github.com/dayjournal/Leaflet.Control.Opacity): makes multiple tile layers transparent.
*   [Leaflet.markercluster](https://github.com/Leaflet/Leaflet.markercluster): Provides Beautiful Animated Marker Clustering functionality
*   [Leaflet.FeatureGroup.SubGroup](https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup): add/remove groups of markers from Marker Cluster.
*   [Leaflet.MarkerCluster.PlacementStrategies](https://github.com/adammertel/Leaflet.MarkerCluster.PlacementStrategies): implements new possibilities how to place clustered children markers
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
2. Track with elevation profile only and Switching Tile Layers<br>![Track with elevation profile](.wordpress-org/screenshot-2.png)
3. Multiple Tracks with elevation profile<br>![Multiple Tracks with elevation profile](.wordpress-org/screenshot-3.png)
4. Hover a Geojson area <br>![Hover a Geojson area](.wordpress-org/screenshot-4.png)
5. Markercluster and Groups <br>![Markercluster](.wordpress-org/screenshot-5.png)
6. Markercluster PlacementStrategies <br>![PlacementStrategies](.wordpress-org/screenshot-6.png)
7. GestureHandling <br>![GestureHandling](.wordpress-org/screenshot-7.png)

## Documentation

Detailed documentation and examples in <a href="https://leafext.de/">German</a> and <a href="https://leafext.de/en/">English</a>.

## Installation

* First you need to install and configure the plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a>.
* Then install this plugin.
* Go to Settings - Leaflet Map - Extensions for Leaflet Map and get documentation and settings options.

## Changelog

### 3.1 / 220xxx

* leaflet-gestures-1.4.3: Please check your Leaflet Map settings for scrollwheel. See the help for Gesture Handling!
* Leaflet.Control.FullScreen 2.4.0
* Backend interface for non-admin
* File Manager
* leaflet-directory: Tracks from all files in a directory

## Upgrade Notice

### 3.1

Please check your Leaflet Map settings for scrollwheel. See the help for Gesture Handling!

### Previous

[Changelog](CHANGELOG.md)
