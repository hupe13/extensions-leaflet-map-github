# Extensions for Leaflet Map

## Description

Plugin to extend the Wordpress Plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a>, see Bozdoz <a href="https://github.com/bozdoz/wp-plugin-leaflet-map#how-can-i-add-another-leaflet-plugin">FAQ</a>.

### Wordpress Plugin

You need to install the plugin "Leaflet Map".

### Involved Leaflet Plugins

*   [leaflet-elevation](https://github.com/Raruto/leaflet-elevation)
*   [leaflet.fullscreen](https://github.com/brunob/leaflet.fullscreen)
*   [Leaflet.GestureHandling](https://github.com/elmarquis/Leaflet.GestureHandling)
*   [Leaflet.markercluster](https://github.com/Leaflet/Leaflet.markercluster)
*   [leaflet.zoomhome](https://github.com/torfsen/leaflet.zoomhome)

### Other functions

*   hovergeojson: Use it to highlight a geojson area or line on mouse over.
*   Hide Markers: Use it when a track in a GPX file contains some markers and you don't want to display them on the map.

### Shortcodes

* Display a track with elevation profile

<pre>
[leaflet-map ....]
// at least one marker if you use it with zoomehomemap
[leaflet-marker lat=... lng=... ...]Start[/leaflet-marker]
[elevation gpx="url_gpx_file"]
// or
[elevation gpx="url_gpx_file" summary=1]
</pre>

* Fullscreen

<pre>
[fullscreen]
</pre>

* GestureHandling

<pre>
[leaflet-map dragging ... ]
// or
[leaflet-map scrollwheel ... ]
// or
[leaflet-map dragging scrollwheel ... ]
</pre>

* Leaflet.markercluster

<pre>
[leaflet-map ....]
// many markers
[leaflet-marker lat=... lng=... ...]poi1[/leaflet-marker]
[leaflet-marker lat=... lng=... ...]poi2[/leaflet-marker]
 ...
[leaflet-marker lat=... lng=... ...]poixx[/leaflet-marker]
[cluster]
</pre>

* leaflet.zoomhome

<pre>
[leaflet-map ....]
  ...
[zoomhomemap]
</pre>

* Hide Markers

<pre>
[leaflet-map ...]
[leaflet-gpx src="//url/to/file.gpx" ... ]
[hidemarkers]
</pre>

* hovergeojson

<pre>
[leaflet-map ...]
[leaflet-geojson src="//url/to/file.geojson" color="..."]...[/leaflet-geojson]
//or / and
[leaflet-gpx src="//url/to/file.gpx" color="..."]...[/leaflet-gpx]
//or / and
[leaflet-kml src="//url/to/file.kml" color="..."]...[/leaflet-kml]
[hover]
</pre>

Mehr <a href="https://phw-web.de/doku/leaflet/">Dokumentation</a> auf deutsch.

## Installation

1. Upload the plugin files to the `/wp-content/plugins/wp-leaflet-extensions` directory or use https://github.com/afragen/github-updater
2. Activate the plugin through the 'Plugins' screen in WordPress
3. (Optionally) Go to Settings - Extensions for Leaflet Map and select a theme for elevation.

## Changelog

### 0.0.4

* More hover functions

### 0.0.3

* Bump version of fullscreen plugin to v2.0.0. Thanks to davidkopp

### 0.0.2

* Zoomhome gefixt.

### 0.0.1

* First Release is running on my website

## Upgrade Notice

### 0.0.4

* More hover functions
