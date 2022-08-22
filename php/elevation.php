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
		// Testing
		array(
			'param' => 'testing',
			'shortdesc' => __('Test Pull Request',"extensions-leaflet-map"),
			'desc' => __('Activate leaflet-elevation version from',"extensions-leaflet-map").
			' <a href="https://github.com/Raruto/leaflet-elevation/pull/210">Pull request</a>.',
			'default' => false,
			'values' => 1,
			'typ' => array('changeable','test'),
		),

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
			'typ' => array('changeable','look'),
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

		// Quite uncommon and undocumented options
		// Line 73 src/options.js
		// height
		array(
			'param' => 'height',
			'shortdesc' => __('Height of the chart',"extensions-leaflet-map"),
			'desc' => "",
			'default' => "200",
			// form input type,  pattern, help
			'values' => 'type="text" pattern="^[1-4][0-9]{2}$" title="'.__('three digit number (px), minimum 100, maximum 499','extensions-leaflet-map').'"',
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
			'default' => true,
			'values' => 1,
			'typ' => array('changeable','other','multielevation'),
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
			'shortdesc' => __('Chart distance/elevation units.',"extensions-leaflet-map"),
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
		// array(
		// 	'param' => 'almostOver',
		// 	'shortdesc' => __('Toggle "leaflet-almostover" integration',"extensions-leaflet-map"),
		// 	'desc' => "",
		// 	'default' => true,
		// 	'values' => 1,
		// 	'typ' => array('fixed'),
		// ),

		// Render chart profiles as Canvas or SVG Paths
		//preferCanvas: true
		array(
			'param' => 'preferCanvas',
			'shortdesc' => __('Render chart profiles as Canvas or SVG Paths.',"extensions-leaflet-map"),
			'desc' => sprintf ( __('Due to a bug in MacOS and iOS, see %shere%s, it is automatically set to false in Safari.',"extensions-leaflet-map"), '<a href="https://github.com/Raruto/leaflet-elevation/issues/123">','</a>'),
			'default' => true,
			'values' => 1,
			'typ' => array('changeable','other',),
		),

		// Chart container outside/inside map container
		// detached: true,
		//array('detached', 'Chart container outside/inside map container', true, 1),
		array(
			'param' => 'detached',
			'shortdesc' => 'detached',
			'desc' => "",
			'default' => true,
			'values' => 1,
			'typ' => array('fixed'),
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
			$value =
			'{className: "elevation-polyline",
				color: "#000",
				opacity: 0.75,
				lineCap: "round",
				weight: '.$v.'}';
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
			if ($settings[$k] == true && $settings['imperial'] == "1") {
				$text = $text.
				'distanceMarkers: {
				offset: 1000/0.621371,
				textFunction: function(distance, i, offset) {
				return Math.round(distance*0.621371/1000);
				}},';
				unset($settings[$k]);
			}
			break;
			case "handlers":
			case "margins":
			$text = $text. "$k: ". $v .',';
			unset($settings[$k]);
			break;
			default:
		}
	}
	return array($text,$settings);
}

function leafext_elevation_pace($options) {
	if ( (bool)$options['pace'] ) {
		if ( !(bool) $options['time'] ) $options['time'] = "summary";
		$text = '[';
		$handlers = glob(LEAFEXT_ELEVATION_DIR.'/src/handlers/*');
		foreach ($handlers as $handler) {
			$handle = basename ($handler,'.js');
			if (isset($options[$handle]) && $handle != 'pace') {
				if ((bool)$options[$handle]) {
					$text = $text.'"'.ucfirst($handle).'",';
				}
			}
		}
		$text = $text.'import("'.LEAFEXT_ELEVATION_URL.'src/handlers/pace.js"),';
		$text = $text.'import("'.LEAFEXT_ELEVATION_URL.'src/handlers/speed.js"),';
		if ( (bool)$options['acceleration'] ) {
			$text = $text.'import("'.LEAFEXT_ELEVATION_URL.'src/handlers/acceleration.js"),';
		}
		$text = $text.']';
		$options['handlers'] = $text;
		//pace.label      = opts.paceLabel  || L._(opts.imperial ? 'min/mi' : 'min/km');
		//opts.paceFactor = opts.paceFactor || 60; // 1 min = 60 sec
		//$options['paceFactor'] = 3600;
		//deltaMax: this.options.paceDeltaMax,
		// Mein Standard: 1 (?)
		$options['paceDeltaMax'] = 1;
		//clampRange: this.options.paceRange,
		//$options['paceRange'] = 0.6;
	}
	return $options;
}

//Shortcode: [elevation gpx="...url..."]
function leafext_elevation_script($gpx,$settings){
	$text = '
	<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();';

	$text = $text.'
	var elevation_options = {';

	list($text1, $settings) = leafext_ele_java_params($settings);
	$text = $text.$text1;
	$text = $text.leafext_java_params ($settings);

	$text = $text.'};
	';

	$text = $text.leafext_elevation_locale();

	$text = $text.'
	// Instantiate elevation control.
	L.Control.Elevation.prototype.__btnIcon = "'.LEAFEXT_ELEVATION_URL.'/images/elevation.svg";
	var controlElevation = L.control.elevation(elevation_options);
	var track_options= { url: "'.$gpx.'" };
	controlElevation.addTo(map);';

	// not solved with leaflet 1.8 (220503)
	$text = $text.'
	var is_chrome = navigator.userAgent.indexOf("Chrome") > -1;
	var is_safari = navigator.userAgent.indexOf("Safari") > -1;
	if ( !is_chrome && is_safari && controlElevation.options.preferCanvas != false ) {
		console.log("is_safari - setting preferCanvas to false");
		controlElevation.options.preferCanvas = false;
	}';

	$text=$text.'
	// Load track from url (allowed data types: "*.geojson", "*.gpx")
	controlElevation.load(track_options.url);';

	if ( $settings['chart'] === "off") {
		$text=$text.'map.on("eledata_added", function(e) {
			//console.log(controlElevation);
			controlElevation._toggle();
		});';
	}

	$text=$text.'
	});
	</script>';
	$text = \JShrink\Minifier::minify($text);
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

function leafext_elevation_function( $atts ) {
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
					$wptvalue = $wptvalue.'"'.$wpt['css'].'":  L.divIcon({
						className: "elevation-waypoint-marker",
						html: '."'".'<i class="elevation-waypoint-icon '.$wpt['css'].'"></i>'."'".','.
						$wpt['js'].'}),';
				}
				$wptvalue = $wptvalue.'}';
				$options['wptIcons'] =  $wptvalue;
			}
		}
	}

	//if ($options['distance'] == false) {
		// $options['handlers'] = '[
		// 	import("'.LEAFEXT_ELEVATION_URL.'src/handlers/acceleration.js"),
		// 	import("'.LEAFEXT_ELEVATION_URL.'src/handlers/altitude.js"),
		// 	import("'.LEAFEXT_ELEVATION_URL.'src/handlers/distance.js"),
		// 	import("'.LEAFEXT_ELEVATION_URL.'src/handlers/speed.js"),
		// 	import("'.LEAFEXT_ELEVATION_URL.'src/handlers/time.js"),
		// 	import("'.LEAFEXT_ELEVATION_URL.'libs/leaflet-hotline.min.js"),
		// 	]';
	//}

	if (isset($options['pace']) ) {
		$options = leafext_elevation_pace($options);
	}

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
	list($options,$style) = leafext_elevation_color($options);
	ksort($options);

	$text=$style.leafext_elevation_script($track,$options);
	//
	return $text;
}
add_shortcode('elevation', 'leafext_elevation_function' );
