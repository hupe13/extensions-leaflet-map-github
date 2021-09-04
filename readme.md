<a href="https://wordpress.org/plugins/extensions-leaflet-map/">Official WordPress Plugin</a>

# Extensions for Leaflet Map

Contributors: hupe13  
Tags: leaflet, elevation, markercluster, zoomhome, hover, fullscreen, gpx,  
Requires at least: 5.5.3  
Tested up to: 5.8  
Stable tag: 2.1.1  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

## Description

Extends the WordPress Plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a> with Leaflet Plugins and other functions.

### Used Leaflet Plugins and Elements

*   [leaflet-elevation](https://github.com/Raruto/leaflet-elevation): GPX-Track with Elevation Profile
*   [leaflet-gpx](https://github.com/mpetazzoni/leaflet-gpx) and [leaflet-gpxgroup](https://github.com/Raruto/leaflet-elevation/blob/master/libs/leaflet-gpxgroup.js): Multiple tracks with elevation profiles on one map
*   [L.control.layers](https://leafletjs.com/examples/layers-control/): Switching Tilelayers
*   [Leaflet.markercluster](https://github.com/Leaflet/Leaflet.markercluster): Marker Cluster
*   [Leaflet.FeatureGroup.SubGroup](https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup):  add/remove groups of markers from Marker Cluster.
*	[Leaflet.MarkerCluster.PlacementStrategies](https://github.com/adammertel/Leaflet.MarkerCluster.PlacementStrategies):  implements new possibilities how to place clustered children markers
*   [leaflet.zoomhome](https://github.com/torfsen/leaflet.zoomhome): Reset the view
*   [leaflet.fullscreen](https://github.com/brunob/leaflet.fullscreen): Fullscreen mode
*   [Leaflet.GestureHandling](https://github.com/Raruto/leaflet-gesture-handling): Gesture Handling

### Other functions

*   hover: Use it to highlight a geojson element or get a tooltip on mouse over.
*   Hide Markers: Use it when a track in a GPX file contains some markers and you don't want to display them on the map.

## Screenshots

1. Track with elevation profile and Switching Tile Layers <br>![Track with elevation profile](.wordpress-org/screenshot-1.png)
2. Multiple Tracks with elevation profile<br>![Multiple Tracks with elevation profile](.wordpress-org/screenshot-2.png)
3. Hover a Geojson area <br>![Hover a Geojson area](.wordpress-org/screenshot-3.png)
4. Markercluster and Groups <br>![Markercluster](.wordpress-org/screenshot-4.png)
5. Markercluster PlacementStrategies <br>![PlacementStrategies](.wordpress-org/screenshot-5.png)
6. GestureHandling <br>![GestureHandling](.wordpress-org/screenshot-6.png)

## Documentation

Detailed documentation and examples in <a href="https://leafext.de/">German</a> and <a href="https://leafext.de/en/">English</a>.

## Installation

* First you need to install the plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a>.
* Then install this plugin.
* Go to Settings - Leaflet Map - Extensions for Leaflet Map and get documentation and settings options.

## Changelog

### 2.1.2 / This version is not stable yet!
* taking care of parameter max-zoom from leaflet-map
* Leaflet.markercluster V 1.5.1
* leaflet-gpx V 1.6.0
* changing the parameter names for cluster to those of the Leaflet.markercluster (old names are still valid)
* cluster parameters are valid for placementstrategies also
* bug in gestures corrected
* and more

### 2.1.1 / 210824
* Bugs in placementstrategies and cluster

### 2.1 / 210823
* Leaflet.MarkerCluster.PlacementStrategies

### 2.0.2 - 2.0.3 / 210816
* Bugfix https://wordpress.org/support/topic/leafext_enqueue_elevation-unknown/
* New elevation parameter: marker

### 2.0.1 / 210722
* Works with Elementor now

### 2.0 / 210715
* Tested on WordPress 5.8
* enhancement of elevation and introduction of many parameters
* leaflet-gesture-handling 1.3.5
* leaflet-elevation-1.6.9
* bug fix hover

### 1.5 / 210620
* leaflet-elevation 1.6.8
* https://github.com/Raruto/leaflet-gesture-handling (is newer)
* fixed some mistakes in gestures and others
* deal with safari bug on markers popup
* hovering on all geojsons and markers
* multielevation (gpx-groups)

### 1.4 / 210521
* zoomhomemap works on multiple maps on a page now.
* fixing markercluster and markerClusterGroup for this.

### 1.3 / 210513
* Shortcodes can be used on more than one map per page, except zoomhomemap.  
* Parameter for GestureHandling  
* fixed markerClusterGroup  
* prepare for translation
* documentation

### 1.2 / 210502
* documentation on a help page
* prepare for translation
* new setting options for <code>[markercluster]</code>
* new setting options for <code>[zoomhomemap]</code>

### 1.1 / 210417
* New functions:
* markerClusterGroup
* Switching Tile Layers

### 1.0.1 / 210409
* leaflet-elevation v1.6.7

### 1.0 / 210323
* First Release

## Upgrade Notice

### 1.4
Please check the pages where you use zoomhomemap, if it still works as it should.
Check the help at /wp-admin/admin.php?page=extensions-leaflet-map&tab=zoomhome

### 1.0.1
Elevation profile on smartphone works now.
