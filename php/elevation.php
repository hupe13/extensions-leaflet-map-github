<?php
/**
* Functions for elevation shortcode
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Parameter and Values
function leafext_elevation_params($typ = array()) {
	$params = array(
		// Aussehen

		// Default chart colors: theme lime-theme, magenta-theme, ...
		//theme: "lightblue-theme",
		array(
			'param' => 'theme',
			'shortdesc' => __('Theme Colors',"extensions-leaflet-map"),
			'desc' => '<p>
			lime - <img src="'.LEAFEXT_PLUGIN_PICTS.'/elevation-lime.svg" alt="lime" /> /
			steelblue - <img width=26px height=26px src="'.LEAFEXT_PLUGIN_PICTS.'/elevation-steelblue.svg" alt="steelblue" /> /
			purple - <img src="'.LEAFEXT_PLUGIN_PICTS.'/elevation-purple.svg" alt="purple" /> /
			yellow - <img src="'.LEAFEXT_PLUGIN_PICTS.'/elevation-yellow.svg" alt="yellow" /> /
			red - <img src="'.LEAFEXT_PLUGIN_PICTS.'/elevation-red.svg" alt="red" /> /
			magenta - <img src="'.LEAFEXT_PLUGIN_PICTS.'/elevation-magenta.svg" alt="magenta" /> /
			lightblue - <img src="'.LEAFEXT_PLUGIN_PICTS.'/elevation-lightblue.svg" alt="lightblue" />
			</p>',
			'default' => "lime-theme",
			'values' => array("lime-theme","steelblue-theme","purple-theme","yellow-theme","red-theme","magenta-theme","lightblue-theme"),
			'typ' => array('changeable','theme','multielevation'),
		),

		//Default hupe13 polyline: 3, Default Raruto 5
		array(
			'param' => 'polyline',
			'shortdesc' => __('Polyline weight',"extensions-leaflet-map"),
			'desc' => '',
			'default' => "3",
			'values' => array("2","3","4","5","6","7","8"),
			'typ' => array('changeable','look','multielevation'),
		),

		//hotline
		array(
			'param' => 'hotline',
			'shortdesc' => __('Track colors according to the values of elevation',"extensions-leaflet-map"),
			'desc' => "",
			'default' => false,
			'values' => array( false, 'elevation'), //, 'slope', 'speed'
			'typ' => array('changeable','look'),
		),

		// linearGradient: {
		// 				attr: 'z',
		// 				path: 'altitude',
		// 				range: { 0.0: '#008800', 0.5: '#ffff00', 1.0: '#ff0000' },
		// 				min: 'elevation_min',
		// 				max: 'elevation_max',
		array(
			'param' => 'linearGradient',
			'shortdesc' => __('linearGradient',"extensions-leaflet-map"),
			'desc' => "",
			'default' => false,
			'values' => 1,
			'typ' => array('changeable','look'),
		),

		// marker: "elevation-line" || "position-marker" || false
		array(
			'param' => 'marker',
			'shortdesc' => __('position/height indicator marker drawn onto the map',"extensions-leaflet-map"),
			'desc' => '<p>elevation-line <img src="'.LEAFEXT_PLUGIN_PICTS.'vert-line.svg" alt="line" align="middle"/> / position-marker
			<img src="'.LEAFEXT_ELEVATION_URL.'/images/elevation-position.svg" alt="elevation-position" align="middle"/> / '.__('nothing',"extensions-leaflet-map").' </p>',
			'default' => 'elevation-line',
			'values' => array("elevation-line", "position-marker",false),
			'typ' => array('changeable','chartlook','multielevation'),
		),

		// https://wordpress.org/support/topic/elevation-y-axis/
		// yAxisMax
		array(
			'param' => 'yAxisMax',
			'shortdesc' => __('y Axis Max',"extensions-leaflet-map"),
			'desc' => "",
			'default' => "0",
			// form input type,  pattern, help
			'values' => 'type="text" pattern="^[0-9]+$" title="'.__('number','extensions-leaflet-map').'"',
			'typ' => array('changeable','look','multielevation'),
		),

		// yAxisMin
		array(
			'param' => 'yAxisMin',
			'shortdesc' => __('y Axis Min',"extensions-leaflet-map"),
			'desc' => "",
			'default' => "0",
			// form input type,  pattern, help
			'values' => 'type="text" pattern="^[0-9]+$" title="'.__('number','extensions-leaflet-map').'"',
			'typ' => array('changeable','look','multielevation'),
		),

		// Toggle chart legend filter.
		//legend: true,
		array(
			'param' => 'legend',
			'shortdesc' => __('Toggle chart legend filter.',"extensions-leaflet-map"),
			'desc' => '<img src="'.LEAFEXT_PLUGIN_PICTS.'on.png" alt="on"/>
			<p>'.
			sprintf(__('You can always toggle the charts individually by clicking on %s.',"extensions-leaflet-map"),
			'<img src="'.LEAFEXT_PLUGIN_PICTS.'switcher.png" alt="switch"/>')
			.' '.
			sprintf(__('If %s is disabled, you can\'t see all charts at the same time (except at the beginning).',"extensions-leaflet-map"),
			'<code>legend</code>').'</p>',
			'default' => true,
			'values' => 1,
			'typ' => array('changeable','chartlook','multielevation'),
		),

		// Toggle "leaflet-edgescale" integration
		// edgeScale: false,
		array(
			'param' => 'edgeScale',
			'shortdesc' => __('Toggle "leaflet-edgescale" integration',"extensions-leaflet-map"),
			'desc' => "",
			'default' => false,
			'values' => 1,
			'typ' => array('changeable','look','multielevation'),
		),

		// Quite uncommon and undocumented options
		// Line 73 src/options.js
		// height: (screen.height * 0.3) || 200,
		array(
			'param' => 'height',
			'shortdesc' => __('Height of the chart',"extensions-leaflet-map"),
			'desc' => "",
			'default' => "200",
			// form input type,  pattern, help
			'values' => 'type="text" pattern="^[1-4][0-9]{2}$" title="'.__('three digit number (px), minimum 100, maximum 499','extensions-leaflet-map').'"',
			'typ' => array('changeable','look','multielevation'),
		),

		// Quite uncommon and undocumented options
		// width: (screen.width * 0.6) || 600,
		array(
			'param' => 'width',
			'shortdesc' => __('If (!detached) width of the chart',"extensions-leaflet-map"),
			'desc' => "",
			'default' => "(screen.width * 0.6) || 600",
			'values' => 'type="text" placeholder="(screen.width * 0.6) || 600" pattern="^[0-9]{2,4}$|^\(screen.width \* 0.[1-9]\) \|\| [0-9]{2,4}$"
				title="'.__('a number (px) or an expression like the default value','extensions-leaflet-map').'"',
			'typ' => array('changeable','look','multielevation'),
		),

		//chart: true,
		// closeBtn
		array(
			'param' => 'chart',
			'shortdesc' => __('Toggle chart',"extensions-leaflet-map"),
			'desc' => sprintf(__('show always the chart / show the chart and toggle %s to hide / hide the chart and toggle %s to show',"extensions-leaflet-map"),
			'&#10006;',
			'<img src="'.LEAFEXT_PLUGIN_PICTS.'/elevation-lime.svg" alt="lime" />'),
			'default' => true,
			'values' => array(true, "on", "off"),
			'typ' => array('changeable','look'),
		),
		// Chart container outside/inside map container
		// detached: true,
		//array('detached', 'Chart container outside/inside map container', true, 1),
		array(
			'param' => 'detached',
			'shortdesc' => __('Chart container outside/inside map container',"extensions-leaflet-map"),
			'desc' => sprintf(__('%s outside, %s inside',"extensions-leaflet-map"),'true - ','false - '),
			'default' => true,
			'values' => 1,
			//'typ' => array('fixed'),
			'typ' => array('changeable','look'),
		),

		// if (detached), the elevation chart container
		//elevationDiv: "#elevation-div",

		// if (!detached) autohide chart profile on chart mouseleave
		//autohide: false,
		//array('autohide', 'if (!detached) autohide chart profile on chart mouseleave', false, 1),

		// if (!detached) initial state of chart profile control
		//collapsed: false,
		//array('collapsed', 'if (!detached) initial state of chart profile control', false, 1),

		// if (!detached) control position on one of map corners
		//position: "topright",
		array(
			'param' => 'position',
			'shortdesc' => __('If (!detached) control position on one of map corners.',"extensions-leaflet-map"),
			'desc' => '',
			'default' => "topright",
			'values' => array('topright','topleft','bottomleft','bottomright'),
			'typ' => array('changeable','look',),
		),

		// Switch track on/off
		array(
			'param' => 'track',
			'shortdesc' => __('Switch Track on and off',"extensions-leaflet-map"),
			'desc' => __('none - show filename in control - show trackname in control',"extensions-leaflet-map"),
			'default' => false,
			'values' => array(false,"filename","trackname"),
			'typ' => array('changeable','look',),
		),

		//Toggle chart ruler filter.
		//ruler: true,
		array(
			'param' => 'ruler',
			'shortdesc' => __('Toggle chart ruler filter.',"extensions-leaflet-map"),
			'desc' => '<img src="'.LEAFEXT_PLUGIN_PICTS.'/ruler.png" alt="lime">',
			'default' => true,
			'values' => 1,
			'typ' => array('changeable','look','multielevation'),
		),

		// downloadLink: "link" || false || "modal"
		array(
			'param' => 'downloadLink',
			'shortdesc' => __('Download Link',"extensions-leaflet-map"),
			'desc' => 'css: .download',
			'default' => false,
			'values' => array("link", false, "modal"),
			'typ' => array('changeable','look',),
		),

		// Alles mit Punkten / Markern

		// Startpunkt
		array(
			'param' => 'trkStart',
			'shortdesc' => __('Display circle marker on Start',"extensions-leaflet-map"),
			'desc' => '',
			'default' => true,
			'values' => 1,
			'typ' => array('changeable','points'),
		),

		// Endpunkt
		array(
			'param' => 'trkEnd',
			'shortdesc' => __('Display circle marker on End',"extensions-leaflet-map"),
			'desc' => '',
			'default' => true,
			'values' => 1,
			'typ' => array('changeable','points'),
		),

		// Toggle "leaflet-distance-markers" integration
		//distanceMarkers: false,
		array(
			'param' => 'distanceMarkers',
			'shortdesc' => __('Toggle "leaflet-distance-markers" integration',"extensions-leaflet-map"),
			'desc' => '',
			'default' => false,
			'values' => 1,
			'typ' => array('changeable','points'),
		),

		// Toggle direction arrows integration
		array(
			'param' => 'direction',
			'shortdesc' => __('Toggle direction arrows',"extensions-leaflet-map"),
			'desc' => '',
			'default' => false,
			'values' => 1,
			'typ' => array('changeable','points'),
		),

		// Display track waypoints: true || "markers" || "dots" || false
		//waypoints: true,
		array(
			'param' => 'waypoints',
			'shortdesc' => __('Display track waypoints',"extensions-leaflet-map"),
			'desc' => __('Display waypoints in map and in chart / only in map / only in chart / none',"extensions-leaflet-map"),
			'default' => true,
			'values' => array (true, "markers", "dots", false),
			'typ' => array('changeable','points',),
		),

		// // Toggle custom waypoint icons: true || { associative array of <sym> tags } || false
		// wptIcons: {
		// 	'': L.divIcon({
		// 		className: 'elevation-waypoint-marker',
		// 		html: '<i class="elevation-waypoint-icon"></i>',
		// 		iconSize: [30, 30],
		// 		iconAnchor: [8, 30],
		// 		}),
		// 		},
		array(
			'param' => 'wptIcons',
			'shortdesc' => __('Toggle custom waypoint icons',"extensions-leaflet-map"),
			'desc' => '<p>'.'true / "defined" / false'.'</p>
			<p>'.__('Only meaningful, if waypoints are shown in the map.',"extensions-leaflet-map").'</p>
			<p>'.sprintf (__('If %s is selected, you must define some %ssettings for the icons',"extensions-leaflet-map"),
			'"defined"',
			'<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevationwaypoints">').'</a>.'
			.'</p>',
			'default' => true,
			'values' => array (true, "defined", false),
			'typ' => array('changeable','points',),
		),

		// Toggle waypoint labels: true || "markers" || "dots" || false
		//wptLabels: true,
		array(
			'param' => 'wptLabels',
			'shortdesc' => __('Toggle waypoint labels',"extensions-leaflet-map"),
			'desc' => '<p>'.__('Show waypoint labels in map and in chart / only in map / only in chart / none',"extensions-leaflet-map").'</p>
			<p>'.sprintf(__('Only meaningful, if %swaypoints%s is not %s.',"extensions-leaflet-map"),'<code>','</code>','<code>0</code>').'</p>',
			'default' => true,
			'values' => array (true, "markers", "dots", false),
			'typ' => array('changeable','points',),
		),

		array(
			'param' => 'labelsRotation',
			'shortdesc' => __('Labels degrees',"extensions-leaflet-map"),
			'desc' => __('Waypoint labels rotation (degrees) in chart',"extensions-leaflet-map"),
			'default' => "0",
			'values' => "",
			'typ' => array('changeable','points',)
		),

		array(
			'param' => 'labelsAlign',
			'shortdesc' => __('Labels align',"extensions-leaflet-map"),
			'desc' => __('Waypoint labels alignment in chart',"extensions-leaflet-map"),
			'default' => "start",
			'values' => array('start' , 'middle' , 'end'),
			'typ' => array('changeable','points',)
		),

		// Informationen

		// Display distance info: true || "summary" || false
		//distance: true,
		array(
			'param' => 'distance',
			'shortdesc' => __('Display distance info',"extensions-leaflet-map"),
			'desc' => '<p>'.
			__("show distance data in graph and summary / show distance data in summary only / don't show distance data","extensions-leaflet-map").'</p>'.'css: .totlen',
			'default' => true,
			'values' => array(true,"summary",false),
			'typ' => array('changeable','info','multielevation'),
		),

		// Display time info: true || "summary" || false
		//time: false,
		array(
			'param' => 'time',
			'shortdesc' => __('Display time info (duration)',"extensions-leaflet-map"),
			'desc' => '<p>'.
			__('show time data in graph and summary / show time data in summary only / nothing',"extensions-leaflet-map").'</p>'.'css: .tottime',
			'default' => false,
			'values' => array(true,"summary",false),
			'typ' => array('changeable','info','multielevation'),
		),

		// Display track datetimes: true || false
		//timestamps: false,
		array(
			'param' => 'timestamps',
			'shortdesc' => __('Display date and clock time',"extensions-leaflet-map"),
			'desc' => '',
			'default' => false,
			'values' => 1,
			'typ' => array('changeable','info','multielevation'),
		),

		// Summary track info style: "inline" || "multiline" || false
		// hupe13: true historical
		array(
			'param' => 'summary',
			'shortdesc' => __('Summary track info style',"extensions-leaflet-map"),
			'desc' => '<p>'.
			__('some predefined settings / summary on one line / summary on multiple lines / without summary',"extensions-leaflet-map").
			'</p>',
			'default' => 'multiline',
			'values' => array(true, "inline","multiline",false),
			'typ' => array('changeable','chartlook','multielevation'),
		),

		// Graphen

		// Altitude chart profile: true || "summary" || "disabled" || false
		//altitude: true,
		//css: "minele" "maxele" "avgele"
		array(
			'param' => 'altitude',
			'shortdesc' => __('Altitude chart profile',"extensions-leaflet-map"),
			'desc' => 'css: .minele, .maxele, .avgele,',
			'default' => true,
			//'values' => array(true,"summary"),
			'values' => array(true,"summary","disabled",false),
			'typ' => array('changeable','chart','multielevation'),
		),

		// Acceleration chart profile: true || "summary" || "disabled" || false
		//acceleration: false,
		//css: "minacceleration"  "maxacceleration" "avgacceleration"
		array(
			'param' => 'acceleration',
			'shortdesc' => __('Acceleration chart profile',"extensions-leaflet-map"),
			'desc' => 'css: .minacceleration, .maxacceleration, .avgacceleration,',
			'default' => false,
			'values' => array(true,"summary","disabled",false),
			'typ' => array('changeable','chart','multielevation'),
		),

		// Slope chart profile: true || "summary" || "disabled" || false
		//slope: false,
		//css: "minslope" "maxslope"	"avgslope"	"ascent" "descent"
		array(
			'param' => 'slope',
			'shortdesc' => __('Slope chart profile',"extensions-leaflet-map"),
			'desc' => 'css: .minslope, .maxslope, .avgslope, .ascent, .descent,',
			'default' => false,
			'values' => array(true,"summary","disabled",false),
			'typ' => array('changeable','chart','multielevation'),
		),

		// Speed chart profile: true || "summary" || "disabled" || false
		//speed: false,
		//css: "minspeed" 	"maxspeed"  	"avgspeed"
		array(
			'param' => 'speed',
			'shortdesc' => __('Speed chart profile',"extensions-leaflet-map"),
			'desc' => 'css: .minspeed, .maxspeed, .avgspeed,',
			'default' => false,
			'values' => array(true,"summary","disabled",false),
			'typ' => array('changeable','chart','multielevation'),
		),

		//pace
		//css: "minpace" 	"maxpace" 	"avgpace"
		array(
			'param' => 'pace',
			'shortdesc' => __('Pace profile - time per distance',"extensions-leaflet-map"),
			'desc' => 'css: .minpace, .maxpace, .avgpace,',
			'default' => false,
			'values' => array(true,"summary","disabled",false),
			'typ' => array('changeable','chart','multielevation'),
		),

		// Verhalten u.a.

		// Autoupdate map center on chart mouseover.
		//followMarker: true,
		array(
			'param' => 'followMarker',
			'shortdesc' => __('Autoupdate map center on chart mouseover.',"extensions-leaflet-map"),
			'desc' => '',
			'default' => false,
			'values' => 1,
			'typ' => array('changeable','other','multielevation'),
		),

		//zFollow
		array(
			'param' => 'zFollow',
			'shortdesc' => __('zFollow zoom',"extensions-leaflet-map"),
			'desc' => '',
			'default' => false,
			'values' => array(false,"7","8","9","10","11","12","13","14","15","16","17","18","19"),
			'typ' => array('changeable','other'),
		),

		// Autoupdate map bounds on chart update.
		//autofitBounds: true,
		array(
			'param' => 'autofitBounds',
			'shortdesc' => __('Autoupdate map bounds on chart update.',"extensions-leaflet-map"),
			'desc' => '',
			'default' => true,
			'values' => 1,
			'typ' => array('changeable','other','multielevation'),
		),

		// Chart distance/elevation units.
		//imperial: false,
		array(
			'param' => 'imperial',
			'shortdesc' => __('Chart distance/elevation units imperial or metric.',"extensions-leaflet-map"),
			'desc' => __('miles or kilometers',"extensions-leaflet-map"),
			'default' => false,
			'values' => 1,
			'typ' => array('changeable','other','multielevation'),
		),

		// [Lat, Long] vs [Long, Lat] points. (leaflet default: [Lat, Long])
		//reverseCoords: false,
		array(
			'param' => 'reverseCoords',
			'shortdesc' => __('[Lat, Long] vs [Long, Lat] points. (leaflet default: [Lat, Long])',"extensions-leaflet-map"),
			'desc' => '',
			'default' => false,
			'values' => 1,
			'typ' => array('changeable','other','multielevation'),
		),

		// Toggle "leaflet-almostover" integration
		// almostOver: true,
		array(
			'param' => 'almostOver',
			'shortdesc' => __('Toggle "leaflet-almostover" integration',"extensions-leaflet-map"),
			'desc' => "",
			'default' => true,
			'values' => 1,
			'typ' => array('fixed'),
		),

		// Render chart profiles as Canvas or SVG Paths
		//preferCanvas: true
		array(
			'param' => 'preferCanvas',
			'shortdesc' => __('Render chart profiles as Canvas or SVG Paths.',"extensions-leaflet-map"),
			'desc' => __('Due to a problem in MacOS and iOS, it is automatically set to false in Safari.',"extensions-leaflet-map"),
			'default' => true,
			'values' => 1,
			'typ' => array('changeable','other',), //'multielevation'
		),

		// margins: { top: 30, right: 30, bottom: 30, left: 40 },
		array(
			'param' => 'margins',
			'shortdesc' => "margins",
			'desc' => "",
			'default' => "{ top: 30, right: 30, bottom: 30, left: 40 }",
			'values' => "{ top: 30, right: 30, bottom: 30, left: 40 }",
			'typ' => array('fixed'),
		),

		//cadence
		//"minrpm" "maxrpm"	"avgrpm"
		// array(
		// 	'param' => 'cadence',
		// 	'shortdesc' => __('cadence - ??',"extensions-leaflet-map"),
		// 	'desc' => "",
		// 	'default' => false,
		// 	'values' => 1,
		// 	'typ' => array('fixed'),
		// ),

		//heart
		// "minbpm" "maxbpm" "avgbpm"
		// array(
		// 	'param' => 'heart',
		// 	'shortdesc' => __('heart - ??',"extensions-leaflet-map"),
		// 	'desc' => "",
		// 	'default' => false,
		// 	'values' => 1,
		// 	'typ' => array('fixed'),
		// ),

	);

	if (count($typ) > 0) {
		$options = array();
		foreach ($typ as $type) { //"fixed", "changeable", ""
			foreach($params as $key => $param){
				if ( in_array ($type, $params[$key]['typ'])) $options[] = $params[$key];
			}
		}
		return $options;
	}
	return $params;
}

function leafext_elevation_locale() {
	$text = 'var mylocale = {
		"Acceleration"		: "'._x("Acceleration", "In Frontend", "extensions-leaflet-map").'",
		"Altitude"				: "'._x("Altitude", "In Frontend", "extensions-leaflet-map").'",
		"Slope"						: "'._x("Slope", "In Frontend", "extensions-leaflet-map").'",
		"Speed"						: "'._x("Speed", "In Frontend", "extensions-leaflet-map").'",
		"Total Time: "		: "'._x("Total Time", "In Frontend", "extensions-leaflet-map").': ",
		"Total Length: "	: "'._x("Total Length", "In Frontend", "extensions-leaflet-map").': ",
		"Max Elevation: "	: "'._x("Max Elevation", "In Frontend", "extensions-leaflet-map").': ",
		"Min Elevation: "	: "'._x("Min Elevation", "In Frontend", "extensions-leaflet-map").': ",
		"Avg Elevation: "	: "'._x("Avg Elevation", "In Frontend", "extensions-leaflet-map").': ",
		"Total Ascent: "	: "'._x("Total Ascent", "In Frontend", "extensions-leaflet-map").': ",
		"Total Descent: "	: "'._x("Total Descent", "In Frontend", "extensions-leaflet-map").': ",
		"Min Slope: "			: "'._x("Min Slope", "In Frontend", "extensions-leaflet-map").': ",
		"Max Slope: "			: "'._x("Max Slope", "In Frontend", "extensions-leaflet-map").': ",
		"Avg Slope: "			: "'._x("Avg Slope", "In Frontend", "extensions-leaflet-map").': ",
		"Min Speed: "			: "'._x("Min Speed", "In Frontend", "extensions-leaflet-map").': ",
		"Max Speed: "			: "'._x("Max Speed", "In Frontend", "extensions-leaflet-map").': ",
		"Avg Speed: "			: "'._x("Avg Speed", "In Frontend", "extensions-leaflet-map").': ",
		"Min Acceleration: "	: "'._x("Min Acceleration", "In Frontend", "extensions-leaflet-map").': ",
		"Max Acceleration: "	: "'._x("Max Acceleration", "In Frontend", "extensions-leaflet-map").': ",
		"Avg Acceleration: "	: "'._x("Avg Acceleration", "In Frontend", "extensions-leaflet-map").': ",
		"Pace"						: "'._x("Pace", "In Frontend", "extensions-leaflet-map").'",
		"Min Pace: "			: "'._x("Min Pace", "In Frontend", "extensions-leaflet-map").': ",
		"Max Pace: "			: "'._x("Max Pace", "In Frontend", "extensions-leaflet-map").': ",
		"Avg Pace: "			: "'._x("Avg Pace", "In Frontend", "extensions-leaflet-map").': ",
		"Download" 				: "'._x("Download", "In Frontend", "extensions-leaflet-map").'",
		"Elevation" 			: "'._x("Elevation", "In Frontend", "extensions-leaflet-map").'",

		"a: " 			: "'._x("a",    "In Frontend: Abbreviation for acceleration in the chart", "extensions-leaflet-map").': ",
		"cad: " 		: "'._x("cad",  "In Frontend: Abbreviation for cadence in the chart", "extensions-leaflet-map").': ",
		"hr: " 			: "'._x("hr",   "In Frontend: Abbreviation for heart rate in the chart", "extensions-leaflet-map").': ",
		"m: " 			: "'._x("m",    "In Frontend: Abbreviation for slope in the chart", "extensions-leaflet-map").': ",
		"pace: " 		: "'._x("pace", "In Frontend: Abbreviation for pace in the chart", "extensions-leaflet-map").': ",
		"t: " 			: "'._x("t",    "In Frontend: Abbreviation for time in the chart", "extensions-leaflet-map").': ",
		"T: " 			: "'._x("T",    "In Frontend: Abbreviation for duration in the chart", "extensions-leaflet-map").': ",
		"v: " 			: "'._x("v",    "In Frontend: Abbreviation for speed in the chart", "extensions-leaflet-map").': ",
		"x: " 			: "'._x("x",    "In Frontend: Abbreviation for length in the chart", "extensions-leaflet-map").': ",
		"y: " 			: "'._x("y",    "In Frontend: Abbreviation for altitude in the chart", "extensions-leaflet-map").': ",
	};
	L.registerLocale("wp", mylocale);
	L.setLocale("wp");
	';
	return $text;
}

function leafext_ele_java_params($settings) {
	$text = "";
	foreach ($settings as $k => $v) {
		switch ($k) {
			case "polyline":
			$v = filter_var($v, FILTER_SANITIZE_NUMBER_INT);
			$value = '{className: "elevation-polyline",
				color: "#000",
				opacity: 0.75,
				lineCap: "round",
				weight: '.$v.'
			}';
			$text = $text. "$k: ". $value .','."\n";
			unset ($settings[$k]);
			break;
			// true ist automatisch, wenn man das setzt, bringt es Fehler!
			case "trkStart":
			case "trkEnd":
			if ($v) {
				unset ($settings[$k]);
			}
			break;
			case "wptIcons":
			if (strpos($v, '{') !== false){
				$text = $text. "$k: ". $v .',';
				unset ($settings[$k]);
			}
			break;
			case "distanceMarkers":
			//distanceMarkers: { lazy: true, distance: true, direction: true },
			if ($settings[$k] == true) {
				$text = $text.'distanceMarkers: {'; //}
				// if ( $settings['imperial'] == true) {
				// 	$text = $text.
				// 	'offset: 1000/0.621371,
				// 	textFunction: function(distance, i, offset) {
				// 		return Math.round(distance*0.621371/1000);
				// 	},';
				// }
				if ( $settings['direction'] == true) {
					$text = $text.'lazy: true, distance: true, direction: true,';
				} else {
					$text = $text.'lazy: true, distance: true, direction: false,';
				}
				$text = $text.'},';
			} else {
				if ($settings['direction'] == true) {
					$text = $text.'distanceMarkers: { lazy: true, distance: false, direction: true },';
				} else {
					$text = $text.'distanceMarkers: false,';
				}
			}
			unset($settings[$k]);
			unset($settings['direction']);
			break;
			case "handlers":
			case "margins":
			$text = $text. "$k: ". $v .',';
			unset($settings[$k]);
			break;
			case "linearGradient":
			if ($settings['linearGradient'] == true) {
				$text = $text. "$k: ". "{
					attr: 'z',
					path: 'altitude',
					range: { 0.0: '#008800', 0.5: '#ffff00', 1.0: '#ff0000' },
					min: 'elevation_min',
					max: 'elevation_max',
				},";
			}
			unset($settings[$k]);
			break;
			default:
		}
	}
	return array($text,$settings);
}

//Shortcode: [elevation gpx="...url..."]
function leafext_elevation_script($gpx,$settings){
	list($elevation_settings, $settings) = leafext_ele_java_params($settings);
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var elevation_options = {
			<?php echo $elevation_settings; ?>
			<?php echo leafext_java_params ($settings); ?>
		};

		<?php echo leafext_elevation_locale();?>

		//BEGIN
		const toPrecision = (x, n) => Number(parseFloat(x.toPrecision(n)).toFixed(n));

		function formatTime(t) {
			//console.log(t);
			var date = new Date(t);
			//console.log("fkt "+date);
			var days = Math.floor(t/(1000 * 60 * 60 * 24));
			var hours = date.getUTCHours();
			if (days == 0 && hours == 0) { hours = ""; } else { hours = hours + ":";}
			var minutes = "0" + date.getUTCMinutes();
			minutes = minutes.substr(-2) + "\'";
			var seconds = "0" + date.getUTCSeconds();
			if (days > 0) { seconds = ""; } else { seconds = seconds.substr(-2) + "\'\'";}
			if (days == 0) { days = ""; } else { days = days + "d ";}
			return (days + hours + minutes + seconds);
		}

		// Save a reference of default "L.Control.Elevation" (for later use)
		const elevationProto = L.extend({}, L.Control.Elevation.prototype);
		// Override default "_registerHandler" behaviour.
		L.Control.Elevation.include({
			// ref: https://github.com/Raruto/leaflet-elevation/blob/c58250e7c20d52490aa3a50b611dbb282ff00a57/src/control.js#L1063-L1128
			_registerHandler: function(props) {
				if (typeof props === "object") {
					switch(props.name) {
						// ref: https://github.com/Raruto/leaflet-elevation/blob/c58250e7c20d52490aa3a50b611dbb282ff00a57/src/handlers/acceleration.js#L41-L61
						case "acceleration":
						let accelerationLabel = this.options.accelerationLabel || L._(this.options.imperial ? "ft/s²" : "m/s²");
						props.tooltip.chart                 = (item)        => L._("a: ") + toPrecision(item.acceleration || 0, 2) + " " + accelerationLabel;
						props.tooltip.marker                = (item)        => toPrecision(item.acceleration, 2) + " " + accelerationLabel;
						props.summary.minacceleration.value = (track, unit) => toPrecision(track.acceleration_min || 0, 2) + "&nbsp;" + unit;
						props.summary.maxacceleration.value = (track, unit) => toPrecision(track.acceleration_max || 0, 2) + "&nbsp;" + unit;
						props.summary.avgacceleration.value = (track, unit) => toPrecision(track.acceleration_avg || 0, 2) + "&nbsp;" + unit;
						break;
						case "altitude":
						props.summary.minele.value = (track, unit) => (track.elevation_min || 0).toFixed(0) + "&nbsp;" + unit;
						props.summary.maxele.value = (track, unit) => (track.elevation_max || 0).toFixed(0) + "&nbsp;" + unit;
						props.summary.avgele.value = (track, unit) => (track.elevation_avg || 0).toFixed(0) + "&nbsp;" + unit;
						break;
						//cadence
						case "distance":
						if (this.options.distance) {
							let distlabel = this.options.distance.label || L._(this.options.imperial ? "mi" : this.options.xLabel);
							props.tooltip.chart = (item) => L._("x: ") + toPrecision(item.dist, (item.dist > 10) ? 3 : 2 ) + " " + distlabel;
							props.summary.totlen.value = (track) => toPrecision(track.distance || 0, 3 ) + "&nbsp;" + distlabel;
						}
						break;
						//heart
						case "pace":
						if (this.options.pace) {
							//let paceLabel = this.options.paceLabel || L._(opts.imperial ? "min/mi" : "min/km");
							let paceLabel = this.options.imperial ? "/mi" : "/km";
							props.tooltip.chart         = (item)        => L._("pace: ") +  (formatTime(item.pace * 1000 * 60) || 0) + " " + paceLabel;
							props.tooltip.marker        = (item)        =>                  (formatTime(item.pace * 1000 * 60) || 0) + " " + paceLabel;
							props.summary.minpace.value = (track, unit) =>                  (formatTime(track.pace_max * 1000 * 60) || 0) + "&nbsp;" + paceLabel;
							props.summary.maxpace.value = (track, unit) =>                  (formatTime(track.pace_min * 1000 * 60) || 0) + "&nbsp;" + paceLabel;
							props.summary.avgpace.value = (track, unit) => formatTime( Math.abs((track.time / track.distance) / this.options.paceFactor) *60) + "&nbsp;" + paceLabel;
						}
						break;
						case "slope":
						let slopeLabel = this.options.slopeLabel || "%";
						props.tooltip.chart         = (item) => L._("m: ") + Math.round(item.slope) + slopeLabel;
						break;
						case "speed":
						//console.log(this.options.speed);
						if (this.options.speed) {
							let speedLabel = this.options.speedLabel || L._(this.options.imperial ? "mph" : "km/h");
							props.tooltip.chart                 = (item) => L._("v: ") + toPrecision(item.speed,2) + " " + speedLabel;
							props.tooltip.marker                = (item) => toPrecision(item.speed,3) + " " + speedLabel;
							props.summary.minspeed.value = (track, unit) => toPrecision(track.speed_min || 0, 2) + "&nbsp;" + unit;
							props.summary.maxspeed.value = (track, unit) => toPrecision(track.speed_max || 0, 2) + "&nbsp;" + unit;
							props.summary.avgspeed.value = (track, unit) => toPrecision(track.speed_avg || 0, 2) + "&nbsp;" + unit;
							//props.summary.avgspeed.value = (track, unit) => (track.speed_avg || 0) + "&nbsp;" + unit;
						}
						break;
						case "time":
						if (this.options.time) {
							props.tooltips.find(({ name }) => name === "time").chart = (item) => L._("T: ") + formatTime(item.duration || 0);
							props.summary.tottime.value = (track) => formatTime(track.time || 0);
						}
						break;
					}
				}
				elevationProto._registerHandler.apply(this, [props]);
			}
		});

		// Proceed as usual
		//var controlElevation = L.control.elevation(opts.elevationControl.options);
		//controlElevation.load(opts.elevationControl.url);
		//END

		if ( typeof map.rotateControl !== "undefined" ) {
			map.rotateControl.remove();
			map.options.rotate = true;
		}

		<?php if ( $settings['track'] ) {
			echo '
			var layersControl_options = {
				collapsed: true,
			};
			var switchtrack = L.control.layers(null, null, layersControl_options);';
		} ?>

		// Instantiate elevation control.
		L.Control.Elevation.prototype.__btnIcon = "<?php echo LEAFEXT_ELEVATION_URL;?>/images/elevation.svg";
		var controlElevation = L.control.elevation(elevation_options);
		var track_options= { url: "<?php echo $gpx;?>" };
		controlElevation.addTo(map);
		<?php if ( $settings['track'] ) {
			echo 'switchtrack.addTo(map);';
		} ?>

		// https://github.com/Raruto/leaflet-elevation/issues/232#issuecomment-1443554554
		var is_chrome = navigator.userAgent.indexOf("Chrome") > -1;
		var is_safari = navigator.userAgent.indexOf("Safari") > -1;
		if ( !is_chrome && is_safari && controlElevation.options.preferCanvas != false ) {
			console.log("is_safari - not setting preferCanvas to false");
			//controlElevation.options.preferCanvas = false;
		}

		// Load track from url (allowed data types: "*.geojson", "*.gpx")
		controlElevation.load(track_options.url);

		<?php if ( $settings['chart'] === "off" ) {
			echo 'map.on("eledata_added", function(e) {
				//console.log(controlElevation);
				controlElevation._toggle();
			});';
		}

		if ( $settings['track'] ) {
			if ( $settings['track'] == "filename" ) {
				$path_parts = pathinfo($gpx);
				$switchname = '"'.$path_parts['filename'].'"';
			} else {
				$switchname = "e.name";
			}
			echo '
			controlElevation.on("eledata_loaded", function(e) {
				switchtrack.addOverlay(e.layer, '.$switchname.');
			});
			';
		} ?>
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
	//$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_elevation_settings($typ) {
	$defaults=array();
	$params = leafext_elevation_params($typ);
	foreach($params as $param) {
		$defaults[$param['param']] = $param['default'];
	}
	$options = shortcode_atts($defaults, get_option('leafext_eleparams'));
	if (array_key_exists('polyline', $options))
	$options['polyline'] = filter_var($options['polyline'], FILTER_SANITIZE_NUMBER_INT);
	return $options;
}

function leafext_elevation_theme() {
	$ownoptions = get_option('leafext_values');
	if (is_array($ownoptions)) {
		if ( $ownoptions['theme'] == 'other' ) {
			$theme = $ownoptions['othertheme'];
		} else {
			$theme=$ownoptions['theme'].'-theme';
		}
	} else {
		$newoptions=leafext_elevation_settings(array("theme"));
		$theme = $newoptions['theme'];
	}
	return($theme);
}

function leafext_elevation_color($options) {
	static $already_run = array();
	$owncolors = get_option('leafext_color_'.$options['theme']);
	//var_dump($options,$owncolors);
	$text = "";
	if (is_array($owncolors)) {
		$options['preferCanvas'] = false;
		foreach ($owncolors as $key => $typ) {
			if (!isset($already_run[$key] ) ) {
				switch ($key) {
					case "polyline":
					$text=$text.'<style>'.
					'.'.$options['theme'].'.elevation-polyline {stroke: '.$typ.';stroke-width: '.$options['polyline'].';}'.
					'</style>';
					break;
					case "altitude":
					$text=$text.'<style>'.
					'.'.$options['theme'].'.elevation-control .area path.'.$key.',
					.'.$options['theme'].' .legend-'.$key.' rect.area {fill: '.$typ.'; }'.
					'.'.$options['theme'].'.height-focus.circle-lower {fill: '.$typ.'; }'.
					'</style>';
					break;
					case "background":
					$text=$text.'<style>'.
					'.elevation-control .background {background-color: '.$typ.' !important;}'.
					'</style>';
					break;
					default;
					$text=$text.'<style>'.
					'.'.$options['theme'].'.elevation-control .area path.'.$key.','.
					'.'.$options['theme'].' .legend-'.$key.' rect {fill: '.$typ.';}'.
					'</style>';
				}
				$text=$text."\n";
				$already_run[$key] = true;
			}
		}
	}
	return array($options,$text);
}

function leafext_elevation_function($atts,$content,$shortcode) {
	$text = leafext_should_interpret_shortcode($shortcode,$atts);
	if ( $text != "" ) {
		return $text;
	} else {
		if ( ! $atts['gpx'] ) {
			$text = "[elevation ";
			foreach ($atts as $key=>$item){
				$text = $text. "$key=$item ";
			}
			$text = $text. "]";
			return $text;
		}

		leafext_enqueue_elevation ();

		$atts1=leafext_case(array_keys(leafext_elevation_settings(array("changeable","fixed"))),leafext_clear_params($atts));
		$options = shortcode_atts(leafext_elevation_settings(array("changeable","fixed")), $atts1);

		$track = $atts['gpx'];

		if ( $options['chart'] === "on" || $options['chart'] === "off")  {
			$options['closeBtn'] = true;
		} else {
			$options['closeBtn'] = false;
		}

		if (isset($options['wptIcons']) ) {
			$wptIcons = $options['wptIcons'];
			if ( !is_bool($wptIcons) && $wptIcons == "defined" ) {
				unset($options['wptIcons']);
				$waypoints = get_option('leafext_waypoints', "");
				if ( $waypoints != "" && ( $options['waypoints'] == "markers" || $options['waypoints'] == "1" )) {
					$wptvalue="{'': L.divIcon({
						className: 'elevation-waypoint-marker',
						html: '<i class=\"elevation-waypoint-icon default\"></i>',
						iconSize: [30, 30],
						iconAnchor: [8, 30],
					}),
					";
					foreach ( $waypoints as $wpt ) {
						$wptvalue = $wptvalue.'"'.$wpt['css'].'":  L.divIcon(
							{
								className: "elevation-waypoint-marker",
								html: '."'".'<i class="elevation-waypoint-icon '.$wpt['css'].'"></i>'."'".','.
								html_entity_decode($wpt['js']).'
							}
						),';
					}
					$wptvalue = $wptvalue.'}';
					$options['wptIcons'] =  $wptvalue;
				}
			}
		}

		// acceleration.js
		// altitude.js
		// // cadence.js
		// distance.js
		// // heart.js
		// labels.js
		// lineargradient.js
		// pace.js
		// // runner.js
		// slope.js
		// speed.js
		// time.js

		//var_dump($options);

		$handlers = array();

		if ( (bool)$options['pace'] ) {
			$handlers[] = '"Pace"';
			if ( !(bool)$options['time'] ) $options['time'] = "summary";
			if ( (bool)$options['speed'] ) $handlers[] = '"Speed"';
			if ( (bool)$options['acceleration'] ) $handlers[] = '"Acceleration"';
			if ( (bool)$options['slope'] ) $handlers[] = '"Slope"';
		}
		if ((bool)$options['labelsRotation'] || $options['labelsAlign'] != 'start')
		$handlers[] = '"Labels"';
		if ( (bool)$options['linearGradient'] ) {
			$handlers[] = '"Slope"';
			$handlers[] = '"LinearGradient"';
		}

		$handlers = array_unique($handlers);
		//var_dump($handlers);

		if (count($handlers) > 0) $options['handlers'] = '[...L.Control.Elevation.prototype.options.handlers,'.implode(',',$handlers).']';
		//if (count($handlers) > 0) $options['handlers'] = '["Distance","Time","Altitude",'.implode(',',$handlers).']';
		//if (count($handlers) > 0) $options['handlers'] = '['.implode(',',$handlers).',...L.Control.Elevation.prototype.options.handlers]';
		//if (count($handlers) > 0) $options['handlers'] = '[ "Distance", "Time", "Altitude", "Slope", "Speed", "Acceleration", "Labels"]';

		if ( isset($options['summary']) && $options['summary'] == "1" ) {
			$params = leafext_elevation_params();
			foreach($params as $param) {
				$options['param'] = $param['default'];
			}
			$options['summary'] = "inline";
			$options['preferCanvas'] = false;
			$options['legend'] = false;
		}
		//
		if ( ! array_key_exists('theme', $atts) ) {
			$options['theme'] = leafext_elevation_theme();
		}

		if ( $options['hotline'] == "elevation") unset ($options['polyline'] );
		if ( $options['direction'] == true) leafext_enqueue_rotate();
		if ( $options['distanceMarkers'] == true) leafext_enqueue_rotate();

		list($options,$style) = leafext_elevation_color($options);
		ksort($options);

		$text=$style.leafext_elevation_script($track,$options);
		//
		return $text;
	}
}
add_shortcode('elevation', 'leafext_elevation_function');
