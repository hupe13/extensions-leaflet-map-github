<a href="https://wordpress.org/plugins/extensions-leaflet-map/">Official WordPress Plugin</a>

# Extensions for Leaflet Map

Contributors: hupe13  
Tags: leaflet-map, elevation, markercluster, zoomhome, hover, fullscreen  
Requires at least: 5.5.3  
Tested up to: 5.7  
Stable tag: 1.4  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

## Description

Plugin to extend the WordPress Plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a>.

### WordPress Plugin

You need to install the plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a>.

### Used Leaflet Plugins and Elements

*   [leaflet-elevation](https://github.com/Raruto/leaflet-elevation): GPX-Track with Elevation Profile
*   [leaflet-gpx](https://github.com/mpetazzoni/leaflet-gpx) and [leaflet-gpxgroup](https://github.com/Raruto/leaflet-elevation/blob/master/libs/leaflet-gpxgroup.js): Multiple tracks with elevation profiles on one map
*   [L.control.layers](https://leafletjs.com/examples/layers-control/): Switching Tilelayers
*   [Leaflet.markercluster](https://github.com/Leaflet/Leaflet.markercluster): Marker Cluster
*   [Leaflet.FeatureGroup.SubGroup](https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup):  add/remove groups of markers from Marker Cluster.
*   [leaflet.zoomhome](https://github.com/torfsen/leaflet.zoomhome): Reset the view
*   [leaflet.fullscreen](https://github.com/brunob/leaflet.fullscreen): Fullscreen mode
*   [Leaflet.GestureHandling](https://github.com/Raruto/leaflet-gesture-handling): Gesture Handling

### Other functions

*   hover: Use it to highlight a geojson element or marker on mouse over.
*   Hide Markers: Use it when a track in a GPX file contains some markers and you don't want to display them on the map.

## Screenshots

1. Track with elevation profile and Switching Tile Layers <br>![Track with elevation profile](.wordpress-org/screenshot-1.png)
2. Multiple Tracks with elevation profile<br>![Multiple Tracks with elevation profile](.wordpress-org/screenshot-2.png)
3. Hover a Geojson area <br>![Hover a Geojson area](.wordpress-org/screenshot-3.png)
4. Markercluster and Groups <br>![Markercluster](.wordpress-org/screenshot-4.png)
5. GestureHandling <br>![GestureHandling](.wordpress-org/screenshot-5.png)

## Frequently Asked Questions

### Display a track with elevation profile

<p>You may go to Settings -> Leaflet Map -> Leaflet Map Extensions and select a color theme.</p>

```php
[leaflet-map ....]
// at least one marker if you use it with zoomehomemap
[leaflet-marker lat=... lng=... ...]Start[/leaflet-marker]
[elevation gpx="url_gpx_file"]
// or
[elevation gpx="url_gpx_file" summary=1]
```

It is possible to display multiple tracks with their elevation profiles also, see help.

### Switching Tile Layers

<p>First go to Settings -> Leaflet Map -> Leaflet Map Extensions and configure tile layers.</p>

```php
[leaflet-map mapid="..." ...]
[layerswitch]
```

### Leaflet.markercluster

<p>Many markers on a map become confusing. That is why they are clustered.</p>
You can define some parameters in Settings -> Leaflet Map -> Leaflet Map Extensions or per map.

```php
[leaflet-map ....]
// many markers
[leaflet-marker lat=... lng=... ...]poi1[/leaflet-marker]
[leaflet-marker lat=... lng=... ...]poi2[/leaflet-marker]
 ...
[leaflet-marker lat=... lng=... ...]poixx[/leaflet-marker]
[cluster]
// or
[cluster radius="..." zoom="..." spiderfy=0]
[zoomhomemap]
```

### Leaflet.FeatureGroup.SubGroup

<p>dynamically add/remove groups of markers from Marker Cluster.   
Parameter:</p>
<ul>
<li>feat - possible values: iconUrl, title</li>
<li>strings - comma separated strings to distinguish the markers, e.g. an unique string in iconUrl or title</li>
<li>groups - comma separated labels appear in the selection menu</li>
<li>The number of strings and groups must match.</li>
</ul>

```php
[leaflet-marker iconUrl="...red..." ... ] ... [/leaflet-marker]
[leaflet-marker iconUrl="...green..." ... ] ... [/leaflet-marker]
//many markers
[markerClusterGroup feat="iconUrl" strings="red,green" groups="rot,gruen"]
```
or

```php
[leaflet-marker title="first ..."  ... ] ... [/leaflet-marker]
[leaflet-marker title="second ..." ... ] ... [/leaflet-marker]
//many markers
[markerClusterGroup feat="title" strings="first,second" groups="First Group,Second Group"]
```

### leaflet.zoomhome

<p>
&quot;Home&quot; button to reset the view. A must have for clustering markers.</p>
<p>There are several usage possibilities, see the help in Settings -> Leaflet Map -> Leaflet Map Extensions.</p>

```php
[leaflet-map lat=... lng=... zoom=... !fitbounds]
[leaflet-marker ....]
[zoomhomemap !fit]
```
or
```php
[leaflet-map ....]
  ...
[zoomhomemap]
```

### Fullscreen

```php
[fullscreen]
```

### hover

<p>Use it to highlight a geojson element or marker on mouse over.</p>

```php
[leaflet-map ...]
[leaflet-geojson src="//url/to/file.geojson" color="..."]{name}[/leaflet-geojson]
//or / and
[leaflet-gpx src="//url/to/file.gpx" color="..."]{name}[/leaflet-gpx]
//or / and
[leaflet-kml src="//url/to/file.kml" color="..."]{name}[/leaflet-kml]
//or / and
[leaflet-marker ... ]...[/leaflet-marker]
[hover]
```

### GestureHandling

Brings the basic functionality of Gesture Handling into Leaflet Map.
Prevents users from getting trapped on the map when scrolling a long page.
You can enable it for all maps or for particular maps. It becomes active
only when dragging or scrollWheelZoom is enabled.
You can define the options in Settings -> Leaflet Map -> Leaflet Map Extensions.</p>

### Hide Markers

<p>If a GPX track contains waypoints that you do not want to display.</p>

```php
[leaflet-map ...]
[leaflet-gpx src="//url/to/file.gpx" ... ]
[hidemarkers]
```

### More

Maybe new functions are <a href="https://github.com/hupe13/extensions-leaflet-map-testing">here</a>.

## Installation

You can install the plugin through the WordPress installer under Plugins. Add this by searching for "extensions-leaflet-map".
Please see https://wordpress.org/support/article/managing-plugins/#installing-plugins.

Go to Settings - Leaflet Map - Extensions for Leaflet Map and get some documentation and settings options.

## Changelog

### 1.5 (Plan)

* leaflet-elevation 1.6.8
* https://github.com/Raruto/leaflet-gesture-handling (is newer)
* fixed some mistakes in gestures and others
* deal with safari bug on markers popup
* hovering on all geojsons and markers
* multielevation (gpx-groups)

### 1.4

* zoomhomemap works on multiple maps on a page now.
* fixing markercluster and markerClusterGroup for this.

### 1.3

* Shortcodes can be used on more than one map per page, except zoomhomemap.  
* Parameter for GestureHandling  
* fixed markerClusterGroup  
* prepare for translation
* documentation

### 1.2

* documentation on a help page
* prepare for translation
* new setting options for <code>[markercluster]</code>
* new setting options for <code>[zoomhomemap]</code>


### 1.1
New functions:
* markerClusterGroup
* Switching Tile Layers

### 1.0.1
* leaflet-elevation v1.6.7

### 1.0
* First Release

## Upgrade Notice

### 1.4
Please check the pages where you use zoomhomemap, if it still works as it should.
Check the help at /wp-admin/admin.php?page=extensions-leaflet-map&tab=zoomhome

### 1.0.1
Elevation profile on smartphone works now.
