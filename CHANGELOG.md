### 3.5.2 / 230808

* leaflet-elevation 2.5.0
* local hosting of d3.min.js and tmcw/togeojson.umd.js
* bug multielevation: removed map.zoomControl (+/-)
* bug layerswitch: fixed extra options (Javascript in admin backend) in leafext_layerswitch_tiles_script and max_zoom
* CSP: prevent unsafe-eval for turf

### 3.5.1 / 230627

* fixed bug with yAxisMin

### 3.5 / 230626

* enqueue rotate for multielevation
* remove 'other' group from geojsonmarker groups if empty
* leaflet-elevation 2.4.0 with branch linear gradient
* leaflet-elevation waypoint labels rotate PR
* leaflet-elevation new options width, yAxisMin, yAxisMax
* New shortcode [hoverlap] - hover overlapping elements
* reduce inline Javascript

### 3.4.4 / 230512

* leaflet-elevation-2.3.4
* elevation: new option edgeScale
* Raruto fixed [Map automatically moves after clicking on markers when rotate: true](https://github.com/Raruto/leaflet-elevation/issues/250)
* layerswitch: providers can have a [mapid](https://wordpress.org/support/topic/change-maps-name-in-layerswitch/)
* cluster: fixed bug disableClusteringAtZoom=0
* New shortcode geojsonmarker: Design markers from geojson files according to their properties
* hover geojsontooltip: specify a short string for tooltip, if the popup is too big

### 3.4.3 / 230422

* elevation-tracks: bug fixed

### 3.4.2 / 230421

* elevation: arrows on track (https://github.com/Raruto/leaflet-elevation/issues/244)
* elevation: almostOver = true
* multielevation: arrows on track
* leaflet-directory: bugs fixed
* filemgr: vulnerable to Cross Site Scripting (XSS) fixed
* filemgr: bugs fixed

### 3.4.1 / 230402

* revision of hover: bugs fixed, many new options
* leaflet-search: code cleanup, no option type anymore, css for iPhone
* leaflet-search: leaflet-polyline, -circle, -line
* leaflet-optiongroup: leaflet-polyline, -circle, -line

### 3.4 / 230304

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

### 3.3.3 / 230128

* interpret shortcode only in frontend, consider is_home()
* some documentation
* bug in optiongroup

### 3.3.2 / 230115

* bug in grouping the default icon fixed

### 3.3 / 230114

* leaflet-elevation-2.2.8 with own presentation of results.
* new shortcode: choropleth - https://github.com/timwis/leaflet-choropleth
* new shortcodes leaflet-optiongroup and leaflet-featuregroup (Leaflet.FeatureGroup.SubGroup)
* new option in markerClusterGroup: visible
* fixed some bugs in zoomhomemap, hover, multielevation
* compability with themes and (block)editor
* sgpx (from WP GPX Maps) does not work with some themes (like TT2)

### 3.2.2 / 221202

* elevation new parameter track: switch track on/off
* filemgr: per default all types selected
* typo multielevation (distanceMarkers)
* fixed: fullscreen crashes the editor.

### 3.2.1 / 221104

* detect network activated leaflet-map on multisite
* works with the new standard theme now
* some documentation cleanup

### 3.2 / 221003

* new shortcode leaflet-extramarker: https://github.com/coryasilva/Leaflet.ExtraMarkers
* leaflet-elevation 2.2.7 (with pull request)
* elevation: followMarker, zFollow, !detached chart

### 3.1.1 / 220823

* error extensions in file listing
* pace and acceleration work together now
* bug in sgpx

### 3.1 / 220718

* leaflet-gestures-1.4.3: Please check your Leaflet Map settings for scrollwheel. See the help for Gesture Handling!
* Leaflet.Control.FullScreen 2.4.0
* Backend interface for non-admin
* File Manager
* leaflet-directory: Tracks from all files in a directory

### 3.0.1 / 220602

* Solved Bug: detect Browser language in gesture handling
* leaflet-gesture-handling-1.4.2
* leaflet-elevation-2.2.6

### 3.0 / 220524

* Solved Bugs
  * hover with placementstrategies and hidemarkers
  * own theme in multielevation
* leaflet-gesture-handling 1.4.1
* leaflet-elevation-2.2.5
   * New: Pace - time per distance
   * New: Themes and Colors
   * Please check css in your own theme.
   * Bug: `trkStart`, `trkStop` do not work on multiple maps on one page

### 2.2.7 / 220409

* hover: bug with geojson fixed
* hover: tooltip on click in circle, polygon, line removed
* elevation: some strings for translation added
* swedish translation frontend from @argentum

### 2.2.6 / 220401

* hover.php reviewed, new: hovering circles, polygons and lines
* zoomhome.php max_zoom removed. This should be set in leaflet-map settings depending on the tiles.
  See https://github.com/leaflet-extras/leaflet-providers/blob/master/leaflet-providers.js
* clustergroup.php (Leaflet.FeatureGroup.SubGroup): markers can also be defined in a geojson file,
  new parameters others and unknown

### 2.2.5 / 220226

* leaflet-elevation 1.7.6
* elevation: new/changed options: waypoints, wptLabels, wptIcons
* fixed error in collapse control top right

### 2.2.4 / 220202

* WordPress 5.9
* fixed layerswitch
* fixed gestures (sgpx did not work because of this)
* cluster now works with leaflet-marker and markers in leaflet-gpx, leaflet-geojson, leaflet-kml
* multielavation: it works with multiple maps on one page now.
* hover: new option for click tolerance

### 2.2.3 / 220118

* leaflet-elevation 1.7.5
* elevation:
    * miles marker
    * in Safari: setting preferCanvas to false as default
* layerswitch:
    * extra options
    * Leaflet-providers
    * Leaflet.Control.Opacity
* some changes in leaflet-gestures.js for L.control

### 2.2.2 / 211228
* gestures: The language can be determined by Site or by Browser
* multielevation revised:
   * bug fix start marker
   * geojson filter for waypoints
   * option for filename as trackname
   * options like in elevation
   * Leaflet.GeometryUtil
* hidemarkers: geojson filter for marker
* leaflet.fullscreen 2.2.0

### 2.2.1 / 211111
* leaflet-elevation 1.7.3
* multielevation fixed, removed leaflet-gpx
* multielevation parameter name, lat, lng now optional
* zoomhomemap reviewed

### 2.2 / 211031
* Integration shortcode sgpx from WordPress plugin wp-gpx-maps (some parameters)
* leaflet.markercluster 1.5.3
* leaflet.fullscreen 2.1.0
* leaflet-elevation 1.7.2

### 2.1.3 / 211016
* bug fix in parameters for markercluster
* new parameter for elevation: show / hide chart and summary block as whole
* revision of the elevation admin page

### 2.1.2 / 210922
* Leaflet.markercluster V 1.5.1
* leaflet-gpx V 1.7.0
* leaflet-elevation V 1.7.0; new parameter waypoints
* taking care of parameter max_zoom from leaflet-map
* changing the parameter names for [cluster] to those of the Leaflet.markercluster (old names are still valid)
* [cluster] parameters are valid for [markerClusterGroup] also
* bug in [gestures] fixed
* bug in [layerswitch] option attribution fixed
* [zoomhomemap] works for any [leaflet-*] object. [elevation] no longer needs a marker for [zoomhomemap].
* ignore waypoints in gpxgroup (multielevation)
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
* new setting options for [markercluster]
* new setting options for [zoomhomemap]

### 1.1 / 210417
* New functions:
* markerClusterGroup
* Switching Tile Layers

### 1.0.1 / 210409
* leaflet-elevation v1.6.7

### 1.0 / 210323
* First Release
