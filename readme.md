<a href="https://wordpress.org/plugins/extensions-leaflet-map/">Official WordPress Plugin</a>

# Extensions for Leaflet Map

Stable tag: 1.2  
Requires at least: 5.5.3  
Tested up to: 5.7  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  
Tags: leaflet-map, elevation, markercluster, zoomhome, hover, fullscreen  
Contributors: hupe13

## Description

Plugin to extend the WordPress Plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a>, see Bozdoz <a href="https://github.com/bozdoz/wp-plugin-leaflet-map#how-can-i-add-another-leaflet-plugin">FAQ</a>.

### WordPress Plugin

You need to install the plugin "Leaflet Map".

### Involved Leaflet Plugins

*   [leaflet-elevation](https://github.com/Raruto/leaflet-elevation)
*   [leaflet.fullscreen](https://github.com/brunob/leaflet.fullscreen)
*   [Leaflet.GestureHandling](https://github.com/elmarquis/Leaflet.GestureHandling)
*   [Leaflet.markercluster](https://github.com/Leaflet/Leaflet.markercluster)
*   [leaflet.zoomhome](https://github.com/torfsen/leaflet.zoomhome)
*   [Leaflet.FeatureGroup.SubGroup](https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup)

### Other functions

*   hovergeojson: Use it to highlight a geojson area or line on mouse over.
*   Hide Markers: Use it when a track in a GPX file contains some markers and you don't want to display them on the map.
*   Switch tile layers with L.control.layers.

## Screenshots

1. Track with elevation profile and Switching Tile Layers <br>![Track with elevation profile](.wordpress-org/screenshot-1.png)
2. Hover a Geojson area <br>![Hover a Geojson area](.wordpress-org/screenshot-2.png)
3. Markercluster and Groups <br>![Markercluster](.wordpress-org/screenshot-3.png)
4. GestureHandling <br>![GestureHandling](.wordpress-org/screenshot-4.png)

## Frequently Asked Questions

### Display a track with elevation profile

<p>You may go to Settings -> Leaflet Map -> Leaflet Map Extensions and select a color theme.</p>
<pre><code>[leaflet-map ....]
// at least one marker if you use it with zoomehomemap
[leaflet-marker lat=... lng=... ...]Start[/leaflet-marker]
[elevation gpx="url_gpx_file"]
// or
[elevation gpx="url_gpx_file" summary=1]
</code></pre>

### Switching Tile Layers

<p>First go to Settings -> Leaflet Map -> Leaflet Map Extensions and configure tile layers.</p>
<pre><code>[leaflet-map mapid="..." ...]
[layerswitch]
</code></pre>

### Leaflet.markercluster

<p>Many markers on a map become confusing. That is why they are clustered.</p>
You can define some parameters in Settings -> Leaflet Map -> Leaflet Map Extensions or per map.
<pre><code>[leaflet-map ....]
// many markers
[leaflet-marker lat=... lng=... ...]poi1[/leaflet-marker]
[leaflet-marker lat=... lng=... ...]poi2[/leaflet-marker]
 ...
[leaflet-marker lat=... lng=... ...]poixx[/leaflet-marker]
[cluster]
// or
[cluster radius="..." zoom="..." spiderfy=0]
[zoomhomemap]
</code></pre>

### Leaflet.FeatureGroup.SubGroup

<p>dynamically add/remove groups of markers from Marker Cluster.
Parameter:</p>
<ul>
<li>feat - possible values: iconUrl, title</li>
<li>strings - comma separated strings to distinguish the markers, e.g. an unique string in iconUrl or title</li>
<li>groups - comma separated labels appear in the selection menu</li>
<li>The number of strings and groups must match.</li>
</ul>
<pre><code>[leaflet-marker title="..." iconUrl="...red..." ... ] ... [/leaflet-marker]
[leaflet-marker title="..." iconUrl="...green..." ... ] ... [/leaflet-marker]
//many markers
[markerClusterGroup feat="iconUrl" strings="red,green" groups="rot,gruen"]
</code></pre>
<p>Here the groups are differentiated according to the color of the markers.</p>

### leaflet.zoomhome

<p>
&quot;Home&quot; button to reset the view. A must have for clustering markers.</p>
<p>You can define wether zoomhomemap should zoom to all objects when calling the map. But this is valid for synchron loaded objects like markers only.
For asynchron loaded object, like geojsons, use the leaflet-map attribute fitbounds. If you use the elevation shortcode,
please use at least one marker (e.g. starting point).</p>
<pre>
<code>[leaflet-map lat=... lng=... zoom=... !fitbounds !zoomcontrol]
[leaflet-marker ....]
[zoomhomemap !fit]</code>
</pre>or
<pre><code>[leaflet-map !zoomcontrol ....]
  ...
[zoomhomemap]
</code></pre>

### Fullscreen

<pre><code>[fullscreen]</code></pre>

### hovergeojson

<p>Use it to highlight a geojson area or line on mouse over.</p>
<pre><code>[leaflet-map ...]
[leaflet-geojson src="//url/to/file.geojson" color="..."]...[/leaflet-geojson]
//or / and
[leaflet-gpx src="//url/to/file.gpx" color="..."]...[/leaflet-gpx]
//or / and
[leaflet-kml src="//url/to/file.kml" color="..."]...[/leaflet-kml]
[hover]
</code></pre>

### GestureHandling

Brings the basic functionality of Gesture Handling into Leaflet Map.
Prevents users from getting trapped on the map when scrolling a long page.
You can enable it for all maps or for particular maps. It becomes active 
only when dragging or scrollWheelZoom is enabled.
You can define the options in Settings -> Leaflet Map -> Leaflet Map Extensions.</p>';

### Hide Markers

<p>If a GPX track contains waypoints that you do not want to display.</p>
<pre><code>[leaflet-map ...]
[leaflet-gpx src="//url/to/file.gpx" ... ]
[hidemarkers]
</code></pre>

### More

Maybe new functions are <a href="https://github.com/hupe13/extensions-leaflet-map-testing">here</a>.

## Installation

You can install the plugin through the WordPress installer under Plugins → Add New by searching for "extensions-leaflet-map".

Alternatively you can download the file from here, unzip it and move the unzipped contents to the wp-content/plugins folder of your WordPress installation. You will then be able to activate the plugin.

(Optionally) Go to Settings - Leaflet Map - Extensions for Leaflet Map and get some documentation and settings options.

## Changelog

### 1.3 (geplant)

* Shortcode can now be used on more than one map per page. 
  Except zoomhomemap, this is valid for all maps on a page. 
* Parameter for GestureHandling
* ready for Polyglots translation (I hope)

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

### 1.0.1
Elevation profile on smartphone works now.
