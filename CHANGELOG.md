### 4.7 / 2510xx

* evelation on mobile: solved: Clamp cursor position to prevent dragging out of bounds on mobile (https://github.com/Raruto/leaflet-elevation/issues/270)

### 4.6 / 250816

* zoomhomemap has some options now
* fixed: leafext_plugin_active did not work with network activated plugins
* new Jshrink
* new leaflet.fullscreen

### 4.5.1 / 250604

* new togeojson.js library fixes heart rate profile of track segments (elevation).

### 4.5 / 250520

* Bug zoomhomemap with elevation fixed
* elevation: heart rate profile

### 4.4.5 / 250407

* layerswitch - bug if min_zoom > 0 fixed
* new translation: dutch nl_NL
* WordPress 6.8

### 4.4.4 / 250316

* new version L.Control.Layers.Tree.js, leaflet-providers.js, Control.FullScreen, togeojson.umd.js
* fontawesome-free-6.7.2
* Plugin Check issues
* targetmarker: different zoom, documentation
* Plugin Update Checker v5p5
* new organization of Github updates

### 4.4.3 / 250105

* filemgr display issue fixed
* listmarker svg icon fixed

### 4.4.2 / 241224

* listmarker: fixed empty overiconurl

### 4.4.1 / 241218

* elevation setting bug
* overview-map: new option newtab: Open page, post or category links in a new tab
* listmarker: works with leaflet-extramarker and geojson markers now
* listmarker: new option highlight: color to highlight the list entry
* listmarker: new option background: define the background color of the list entry
* tmcw/togeojson version 6.0.0

### 4.4 / 241121

* new shortcode: listmarker
* error handling multielevation
* WordPress 6.7

### 4.3.5 / 241012

* more WordPress Coding Standards
* Bugs in Backend: filemgr, parentgroup
* remove < br/> if using Classic editor
* new version leaflet-providers
* Tests on WordPress 6.7 (beta)

### 4.3.4 / 240828

* overviewmap: custom field for popup

### 4.3.3 / 240811

* overviewmap: bug in ALL categories/tag

### 4.3.2 / 240807

* leaflet-parentgroup: you can set more options now.
* leaflet-extramarker: title with spaces fixed.
* overviewmap: marker title, categories option AND
* targetmarker, targetlink: new option - mapid
* elevation: new languages: portuguese, polish
* fontawesome-free-6.6.0-web

### 4.3.1 / 240701

* elevation: units such as knots, nautical miles
* parentgroup: realized with Leaflet.Control.Layers.Tree, works with markercluster and geojson also now.
* leaflet-parentgroup = parentgroup

### 4.3 / 240613

* many new possibilities for targetmarker
* overviewmap: you can use tags like categories
* overviewmap: works for custom post types

### 4.2.5 / 240512

* extramarker: admin interface
* fontawesome-free-6.5.2-web
* geojsonmarkers: groups others will be deleted if empty
* new version Control.FullScreen.js
* overviewmap: same icon options for all markers
* overviewmap: target=_blank deleted
* overviewmap: disable transients in backend

### 4.2.4 / 240423

* Bug in overviewmap (negative lat, lng)

### 4.2.3 / 240420

* new option for hover: opacity
* hover: new default for popupclose: 0
* Bug in overviewmap
* WPCS, translations

### 4.2.2 / 240401

* catalan
* bug markerclustergroup

### 4.2.1 /240325

* fixed bugs in markerclustergroup
* fixed bug in uploader

### 4.2 / 240322

* new version leaflet-rotate, d3
* grouping: html tags in menu
* some changes for WPCS

### 4.1 / 240216

* fixed small bugs in leaflet-extramarker and geojsonmarker
* fixed bug in cluster
* documentation
* update turf, JShrink
* elevation: italian translation

### 4.0 / 240126

* WordPress Coding Standards (see [readme-wpcs.md](https://github.com/hupe13/extensions-leaflet-map-github/blob/main/readme-wpcs.md))
* new shortcode parentgroup for nested groups from leaflet-optiongroup and -featuregroup
* new shortcode targetmarker - Jump to a position in a map with many markers and get the nearest marker
* try to fix problems with Elementor
* new version leaflet.fullscreen

### 3.5.8 / 240102

* Bug in leaflet-search fixed.
* WordPress Coding Standards (css and some php files)
* overviewmap: using transients
* fontawesome-free-6.5.1-web

### 3.5.7 / 231222

* markerclustergroup control has a position and collapse option now.

### 3.5.6 / 231216

* every control has a position and collapse option now.
* translation: sprintf argnum %s

### 3.5.5 / 231125

* fixing bug elevation colors
* new option: Delete all plugin settings when deleting the plugin?

### 3.5.4 / 231119

* new version leaflet-search
* new version leaflet.fullscreen
* new version leaflet-providers.js
* solved: Polyline layer turns black after turning off all legend filters
* you can change the track color over the ruler filter now
* some corrections that [Plugin Check](https://github.com/WordPress/plugin-check) has found

### 3.5.3 / 230920

* new shortcode overviewmap: generates an overview map with geo positions provided in the pages and posts
* new options for hover: class (style the tooltip) and popupclose (keep the popup open or not)
* reduce inline Javascript for geojsonmarker
* multielevation accepts now also kml files

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
