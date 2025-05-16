<?php
/**
 * Functions for elevation shortcode
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Parameter and Values
function leafext_elevation_params( $typ = array() ) {
	$params = array(
		// Aussehen

		// Default chart colors: theme lime-theme, magenta-theme, ...
		// theme: "lightblue-theme",
		array(
			'param'     => 'theme',
			'shortdesc' => __( 'Theme Colors', 'extensions-leaflet-map' ),
			'desc'      => '<p>
			lime - <img src="' . LEAFEXT_PLUGIN_PICTS . '/elevation-lime.svg" alt="lime" /> /
			steelblue - <img width=26px height=26px src="' . LEAFEXT_PLUGIN_PICTS . '/elevation-steelblue.svg" alt="steelblue" /> /
			purple - <img src="' . LEAFEXT_PLUGIN_PICTS . '/elevation-purple.svg" alt="purple" /> /
			yellow - <img src="' . LEAFEXT_PLUGIN_PICTS . '/elevation-yellow.svg" alt="yellow" /> /
			red - <img src="' . LEAFEXT_PLUGIN_PICTS . '/elevation-red.svg" alt="red" /> /
			magenta - <img src="' . LEAFEXT_PLUGIN_PICTS . '/elevation-magenta.svg" alt="magenta" /> /
			lightblue - <img src="' . LEAFEXT_PLUGIN_PICTS . '/elevation-lightblue.svg" alt="lightblue" />
			</p>',
			'default'   => 'lime-theme',
			'values'    => array( 'lime-theme', 'steelblue-theme', 'purple-theme', 'yellow-theme', 'red-theme', 'magenta-theme', 'lightblue-theme' ),
			'typ'       => array( 'changeable', 'theme', 'multielevation' ),
		),

		// Default hupe13 polyline: 3, Default Raruto 5
		array(
			'param'     => 'polyline',
			'shortdesc' => __( 'Polyline weight', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => '3',
			'values'    => array( '2', '3', '4', '5', '6', '7', '8' ),
			'typ'       => array( 'changeable', 'look', 'multielevation' ),
		),

		// hotline
		array(
			'param'     => 'hotline',
			'shortdesc' => __( 'Track colors according to the values of elevation', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => false,
			'values'    => array( false, 'elevation' ), // , 'slope', 'speed'
			'typ'       => array( 'changeable', 'look' ),
		),

		// linearGradient: {
		// attr: 'z',
		// path: 'altitude',
		// range: { 0.0: '#008800', 0.5: '#ffff00', 1.0: '#ff0000' },
		// min: 'elevation_min',
		// max: 'elevation_max',
		array(
			'param'     => 'linearGradient',
			'shortdesc' => __( 'linearGradient', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => false,
			'values'    => 1,
			'typ'       => array( 'changeable', 'look' ),
		),

		// marker: "elevation-line" || "position-marker" || false
		array(
			'param'     => 'marker',
			'shortdesc' => __( 'position/height indicator marker drawn onto the map', 'extensions-leaflet-map' ),
			'desc'      => '<p>elevation-line <img src="' . LEAFEXT_PLUGIN_PICTS . 'vert-line.svg" alt="line" align="middle"/> / position-marker
			<img src="' . LEAFEXT_ELEVATION_URL . '/images/elevation-position.svg" alt="elevation-position" align="middle"/> / ' . __( 'nothing', 'extensions-leaflet-map' ) . ' </p>',
			'default'   => 'elevation-line',
			'values'    => array( 'elevation-line', 'position-marker', false ),
			'typ'       => array( 'changeable', 'chartlook', 'multielevation' ),
		),

		// https://wordpress.org/support/topic/elevation-y-axis/
		// yAxisMax
		array(
			'param'     => 'yAxisMax',
			'shortdesc' => __( 'y Axis Max', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => '0',
			// form input type,  pattern, help
			'values'    => ' type="text" pattern="^[0-9]+$" title="' . __( 'number', 'extensions-leaflet-map' ) . '"',
			'typ'       => array( 'changeable', 'look', 'multielevation' ),
		),

		// yAxisMin
		array(
			'param'     => 'yAxisMin',
			'shortdesc' => __( 'y Axis Min', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => '0',
			// form input type,  pattern, help
			'values'    => ' type="text" pattern="^[0-9]+$" title="' . __( 'number', 'extensions-leaflet-map' ) . '"',
			'typ'       => array( 'changeable', 'look', 'multielevation' ),
		),

		// Toggle chart legend filter.
		// legend: true,
		array(
			'param'     => 'legend',
			'shortdesc' => __( 'Toggle chart legend filter.', 'extensions-leaflet-map' ),
			'desc'      => '<img src="' . LEAFEXT_PLUGIN_PICTS . 'on.png" alt="on"/>
			<p>' .
			sprintf(
				/* translators: %s is an option. */
				__( 'You can always toggle the charts individually by clicking on %s.', 'extensions-leaflet-map' ),
				'<img src="' . LEAFEXT_PLUGIN_PICTS . 'switcher.png" alt="switch"/>'
			)
			. ' ' .
			sprintf(
				/* translators: %s is an option. */
				__( 'If %s is disabled, you can\'t see all charts at the same time (except at the beginning).', 'extensions-leaflet-map' ),
				'<code>legend</code>'
			) . '</p>',
			'default'   => true,
			'values'    => 1,
			'typ'       => array( 'changeable', 'chartlook', 'multielevation' ),
		),

		// Toggle "leaflet-edgescale" integration
		// edgeScale: false,
		array(
			'param'     => 'edgeScale',
			'shortdesc' => __( 'Toggle "leaflet-edgescale" integration', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => false,
			'values'    => 1,
			'typ'       => array( 'changeable', 'look', 'multielevation' ),
		),

		// Quite uncommon and undocumented options
		// Line 73 src/options.js
		// height: (screen.height * 0.3) || 200,
		array(
			'param'     => 'height',
			'shortdesc' => __( 'Height of the chart', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => '200',
			// form input type,  pattern, help
			'values'    => ' type="text" pattern="^[1-4][0-9]{2}$" title="' . __( 'three digit number (px), minimum 100, maximum 499', 'extensions-leaflet-map' ) . '"',
			'typ'       => array( 'changeable', 'look', 'multielevation' ),
		),

		// Quite uncommon and undocumented options
		// width: (screen.width * 0.6) || 600,
		array(
			'param'     => 'width',
			'shortdesc' => __( 'If (!detached) width of the chart', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => '(screen.width * 0.6) || 600',
			'values'    => ' type="text" placeholder="(screen.width * 0.6) || 600" pattern="^[0-9]{2,4}$|^\(screen.width \* 0.[1-9]\) \|\| [0-9]{2,4}$"
				title="' . __( 'a number (px) or an expression like the default value', 'extensions-leaflet-map' ) . '"',
			'typ'       => array( 'changeable', 'look', 'multielevation' ),
		),

		// chart: true,
		// closeBtn
		array(
			'param'     => 'chart',
			'shortdesc' => __( 'Toggle chart', 'extensions-leaflet-map' ),
			'desc'      => sprintf(
				/* translators: %s are pictures. */
				__( 'show always the chart / show the chart and toggle %1$s to hide / hide the chart and toggle %2$s to show', 'extensions-leaflet-map' ),
				'&#10006;',
				'<img src="' . LEAFEXT_PLUGIN_PICTS . '/elevation-lime.svg" alt="lime" />'
			),
			'default'   => true,
			'values'    => array( true, 'on', 'off' ),
			'typ'       => array( 'changeable', 'look' ),
		),
		// Chart container outside/inside map container
		// detached: true,
		// array('detached', 'Chart container outside/inside map container', true, 1),
		array(
			'param'     => 'detached',
			'shortdesc' => __( 'Chart container outside/inside map container', 'extensions-leaflet-map' ),
			/* translators: %s are options. */
			'desc'      => sprintf( __( '%1$s outside, %2$s inside', 'extensions-leaflet-map' ), 'true - ', 'false - ' ),
			'default'   => true,
			'values'    => 1,
			// 'typ' => array('fixed'),
			'typ'       => array( 'changeable', 'look' ),
		),

		// if (detached), the elevation chart container
		// elevationDiv: "#elevation-div",

		// if (!detached) autohide chart profile on chart mouseleave
		// autohide: false,
		// array('autohide', 'if (!detached) autohide chart profile on chart mouseleave', false, 1),

		// if (!detached) initial state of chart profile control
		// collapsed: false,
		// array('collapsed', 'if (!detached) initial state of chart profile control', false, 1),

		// if (!detached) control position on one of map corners
		// position: "topright",
		array(
			'param'     => 'position',
			'shortdesc' => __( 'If (!detached) control position on one of map corners.', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => 'topright',
			'values'    => array( 'topright', 'topleft', 'bottomleft', 'bottomright' ),
			'typ'       => array( 'changeable', 'look' ),
		),

		// Switch track on/off
		array(
			'param'     => 'track',
			'shortdesc' => __( 'Switch Track on and off', 'extensions-leaflet-map' ),
			'desc'      => __( 'none - show filename in control - show trackname in control', 'extensions-leaflet-map' ),
			'default'   => false,
			'values'    => array( false, 'filename', 'trackname' ),
			'typ'       => array( 'changeable', 'look' ),
		),
		// Position of track control
		array(
			'param'     => 'trackposition',
			'shortdesc' => __( 'position of track control', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => 'topright',
			'values'    => array( 'topright', 'topleft', 'bottomleft', 'bottomright' ),
			'typ'       => array( 'changeable', 'look' ),
		),
		// Collapsed track control
		array(
			'param'     => 'trackcollapsed',
			'shortdesc' => __( 'track control collapsed', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => false,
			'values'    => 1,
			'typ'       => array( 'changeable', 'look' ),
		),

		// Toggle chart ruler filter.
		// ruler: true,
		array(
			'param'     => 'ruler',
			'shortdesc' => __( 'Toggle chart ruler filter.', 'extensions-leaflet-map' ),
			'desc'      => '<img src="' . LEAFEXT_PLUGIN_PICTS . '/ruler.png" alt="lime">',
			'default'   => true,
			'values'    => 1,
			'typ'       => array( 'changeable', 'look', 'multielevation' ),
		),

		// downloadLink: "link" || false || "modal"
		array(
			'param'     => 'downloadLink',
			'shortdesc' => __( 'Download Link', 'extensions-leaflet-map' ),
			'desc'      => 'css: .download',
			'default'   => false,
			'values'    => array( 'link', false, 'modal' ),
			'typ'       => array( 'changeable', 'look' ),
		),

		// Alles mit Punkten / Markern

		// Startpunkt
		array(
			'param'     => 'trkStart',
			'shortdesc' => __( 'Display circle marker on Start', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => true,
			'values'    => 1,
			'typ'       => array( 'changeable', 'points' ),
		),

		// Endpunkt
		array(
			'param'     => 'trkEnd',
			'shortdesc' => __( 'Display circle marker on End', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => true,
			'values'    => 1,
			'typ'       => array( 'changeable', 'points' ),
		),

		// Toggle "leaflet-distance-markers" integration
		// distanceMarkers: false,
		array(
			'param'     => 'distanceMarkers',
			'shortdesc' => __( 'Toggle "leaflet-distance-markers" integration', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => false,
			'values'    => 1,
			'typ'       => array( 'changeable', 'points' ),
		),

		// Toggle direction arrows integration
		array(
			'param'     => 'direction',
			'shortdesc' => __( 'Toggle direction arrows', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => false,
			'values'    => 1,
			'typ'       => array( 'changeable', 'points' ),
		),

		// Display track waypoints: true || "markers" || "dots" || false
		// waypoints: true,
		array(
			'param'     => 'waypoints',
			'shortdesc' => __( 'Display track waypoints', 'extensions-leaflet-map' ),
			'desc'      => __( 'Display waypoints in map and in chart / only in map / only in chart / none', 'extensions-leaflet-map' ),
			'default'   => true,
			'values'    => array( true, 'markers', 'dots', false ),
			'typ'       => array( 'changeable', 'points' ),
		),

		// // Toggle custom waypoint icons: true || { associative array of <sym> tags } || false
		// wptIcons: {
		// '': L.divIcon({
		// className: 'elevation-waypoint-marker',
		// html: '<i class="elevation-waypoint-icon"></i>',
		// iconSize: [30, 30],
		// iconAnchor: [8, 30],
		// }),
		// },
		array(
			'param'     => 'wptIcons',
			'shortdesc' => __( 'Toggle custom waypoint icons', 'extensions-leaflet-map' ),
			'desc'      => '<p>true / "defined" / false</p>
			<p>' . __( 'Only meaningful, if waypoints are shown in the map.', 'extensions-leaflet-map' ) . '</p>
			<p>' . sprintf(
				/* translators: %1$s is an option, %2$s is a link. */
				__( 'If %1$s is selected, you must define some %2$ssettings for the icons', 'extensions-leaflet-map' ),
				'"defined"',
				( ( is_singular() || is_archive() ) ? '<a href="/doku/elevationwaypoints/">' : '<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=elevationwaypoints">' )
			) . '</a>.'
			. '</p>',
			'default'   => true,
			'values'    => array( true, 'defined', false ),
			'typ'       => array( 'changeable', 'points' ),
		),

		// Toggle waypoint labels: true || "markers" || "dots" || false
		// wptLabels: true,
		array(
			'param'     => 'wptLabels',
			'shortdesc' => __( 'Toggle waypoint labels', 'extensions-leaflet-map' ),
			'desc'      => '<p>' . __( 'Show waypoint labels in map and in chart / only in map / only in chart / none', 'extensions-leaflet-map' ) . '</p>
			<p>' . sprintf(
				/* translators: %s are codes. */
				__( 'Only meaningful, if %1$swaypoints%2$s is not %3$s.', 'extensions-leaflet-map' ),
				'<code>',
				'</code>',
				'<code>0</code>'
			) . '</p>',
			'default'   => true,
			'values'    => array( true, 'markers', 'dots', false ),
			'typ'       => array( 'changeable', 'points' ),
		),

		array(
			'param'     => 'labelsRotation',
			'shortdesc' => __( 'Labels degrees', 'extensions-leaflet-map' ),
			'desc'      => __( 'Waypoint labels rotation (degrees) in chart', 'extensions-leaflet-map' ),
			'default'   => '0',
			'values'    => '',
			'typ'       => array( 'changeable', 'points' ),
		),

		array(
			'param'     => 'labelsAlign',
			'shortdesc' => __( 'Labels align', 'extensions-leaflet-map' ),
			'desc'      => __( 'Waypoint labels alignment in chart', 'extensions-leaflet-map' ),
			'default'   => 'start',
			'values'    => array( 'start', 'middle', 'end' ),
			'typ'       => array( 'changeable', 'points' ),
		),

		// Informationen

		// Display distance info: true || "summary" || false
		// distance: true,
		array(
			'param'     => 'distance',
			'shortdesc' => __( 'Display distance info', 'extensions-leaflet-map' ),
			'desc'      => '<p>' .
			__( "show distance data in graph and summary / show distance data in summary only / don't show distance data", 'extensions-leaflet-map' ) . '</p>css: .totlen',
			'default'   => true,
			'values'    => array( true, 'summary', false ),
			'typ'       => array( 'changeable', 'info', 'multielevation' ),
		),

		// Display time info: true || "summary" || false
		// time: false,
		array(
			'param'     => 'time',
			'shortdesc' => __( 'Display time info (duration)', 'extensions-leaflet-map' ),
			'desc'      => '<p>' .
			__( 'show time data in graph and summary / show time data in summary only / nothing', 'extensions-leaflet-map' ) . '</p>css: .tottime',
			'default'   => false,
			'values'    => array( true, 'summary', false ),
			'typ'       => array( 'changeable', 'info', 'multielevation' ),
		),

		// Display track datetimes: true || false
		// timestamps: false,
		array(
			'param'     => 'timestamps',
			'shortdesc' => __( 'Display date and clock time', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => false,
			'values'    => 1,
			'typ'       => array( 'changeable', 'info', 'multielevation' ),
		),

		// Summary track info style: "inline" || "multiline" || false
		// hupe13: true historical
		array(
			'param'     => 'summary',
			'shortdesc' => __( 'Summary track info style', 'extensions-leaflet-map' ),
			'desc'      => '<p>' .
			__( 'some predefined settings / summary on one line / summary on multiple lines / without summary', 'extensions-leaflet-map' ) .
			'</p>',
			'default'   => 'multiline',
			'values'    => array( true, 'inline', 'multiline', false ),
			'typ'       => array( 'changeable', 'chartlook', 'multielevation' ),
		),

		// Graphen

		// Altitude chart profile: true || "summary" || "disabled" || false
		// altitude: true,
		// css: "minele" "maxele" "avgele"
		array(
			'param'     => 'altitude',
			'shortdesc' => __( 'Altitude chart profile', 'extensions-leaflet-map' ),
			'desc'      => 'css: .minele, .maxele, .avgele,',
			'default'   => true,
			// 'values' => array(true,"summary"),
			'values'    => array( true, 'summary', 'disabled', false ),
			'typ'       => array( 'changeable', 'chart', 'multielevation' ),
		),

		// Acceleration chart profile: true || "summary" || "disabled" || false
		// acceleration: false,
		// css: "minacceleration"  "maxacceleration" "avgacceleration"
		array(
			'param'     => 'acceleration',
			'shortdesc' => __( 'Acceleration chart profile', 'extensions-leaflet-map' ),
			'desc'      => 'css: .minacceleration, .maxacceleration, .avgacceleration,',
			'default'   => false,
			'values'    => array( true, 'summary', 'disabled', false ),
			'typ'       => array( 'changeable', 'chart', 'multielevation' ),
		),

		// Slope chart profile: true || "summary" || "disabled" || false
		// slope: false,
		// css: "minslope" "maxslope"    "avgslope"  "ascent" "descent"
		array(
			'param'     => 'slope',
			'shortdesc' => __( 'Slope chart profile', 'extensions-leaflet-map' ),
			'desc'      => 'css: .minslope, .maxslope, .avgslope, .ascent, .descent,',
			'default'   => false,
			'values'    => array( true, 'summary', 'disabled', false ),
			'typ'       => array( 'changeable', 'chart', 'multielevation' ),
		),

		// Speed chart profile: true || "summary" || "disabled" || false
		// speed: false,
		// css: "minspeed"   "maxspeed"      "avgspeed"
		array(
			'param'     => 'speed',
			'shortdesc' => __( 'Speed chart profile', 'extensions-leaflet-map' ),
			'desc'      => 'css: .minspeed, .maxspeed, .avgspeed,',
			'default'   => false,
			'values'    => array( true, 'summary', 'disabled', false ),
			'typ'       => array( 'changeable', 'chart', 'multielevation' ),
		),

		// pace
		// css: "minpace"    "maxpace"   "avgpace"
		array(
			'param'     => 'pace',
			'shortdesc' => __( 'Pace profile - time per distance', 'extensions-leaflet-map' ),
			'desc'      => 'css: .minpace, .maxpace, .avgpace,',
			'default'   => false,
			'values'    => array( true, 'summary', 'disabled', false ),
			'typ'       => array( 'changeable', 'chart', 'multielevation' ),
		),

		// heart
		// "minbpm" "maxbpm" "avgbpm"
		array(
			'param'     => 'heart',
			'shortdesc' => __( 'Heart rate profile', 'extensions-leaflet-map' ),
			'desc'      => 'css: .minbpm, .maxbpm, .avgbpm,',
			'default'   => false,
			'values'    => array( true, 'summary', 'disabled', false ),
			'typ'       => array( 'changeable', 'chart', 'multielevation' ),
		),

		// Verhalten u.a.

		// Autoupdate map center on chart mouseover.
		// followMarker: true,
		array(
			'param'     => 'followMarker',
			'shortdesc' => __( 'Autoupdate map center on chart mouseover.', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => false,
			'values'    => 1,
			'typ'       => array( 'changeable', 'other', 'multielevation' ),
		),

		// zFollow
		array(
			'param'     => 'zFollow',
			'shortdesc' => __( 'zFollow zoom', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => false,
			'values'    => array( false, '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19' ),
			'typ'       => array( 'changeable', 'other' ),
		),

		// Autoupdate map bounds on chart update.
		// autofitBounds: true,
		array(
			'param'     => 'autofitBounds',
			'shortdesc' => __( 'Autoupdate map bounds on chart update.', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => true,
			'values'    => 1,
			'typ'       => array( 'changeable', 'other', 'multielevation' ),
		),

		// [Lat, Long] vs [Long, Lat] points. (leaflet default: [Lat, Long])
		// reverseCoords: false,
		array(
			'param'     => 'reverseCoords',
			'shortdesc' => __( '[Lat, Long] vs [Long, Lat] points. (leaflet default: [Lat, Long])', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => false,
			'values'    => 1,
			'typ'       => array( 'changeable', 'other', 'multielevation' ),
		),

		// Toggle "leaflet-almostover" integration
		// almostOver: true,
		array(
			'param'     => 'almostOver',
			'shortdesc' => __( 'Toggle "leaflet-almostover" integration', 'extensions-leaflet-map' ),
			'desc'      => '',
			'default'   => true,
			'values'    => 1,
			'typ'       => array( 'fixed' ),
		),

		// Render chart profiles as Canvas or SVG Paths
		// preferCanvas: true
		array(
			'param'     => 'preferCanvas',
			'shortdesc' => __( 'Render chart profiles as Canvas or SVG Paths.', 'extensions-leaflet-map' ),
			'desc'      => __( 'Due to a problem in MacOS and iOS, it is automatically set to false in Safari.', 'extensions-leaflet-map' ),
			'default'   => true,
			'values'    => 1,
			'typ'       => array( 'changeable', 'other' ), // 'multielevation'
		),

		// margins: { top: 30, right: 30, bottom: 30, left: 40 },
		array(
			'param'     => 'margins',
			'shortdesc' => 'margins',
			'desc'      => '',
			'default'   => '{ top: 30, right: 30, bottom: 30, left: 40 }',
			'values'    => '{ top: 30, right: 30, bottom: 30, left: 40 }',
			'typ'       => array( 'fixed' ),
		),

		// cadence
		// "minrpm" "maxrpm" "avgrpm"
		// array(
		// 'param' => 'cadence',
		// 'shortdesc' => __('cadence - ??',"extensions-leaflet-map"),
		// 'desc' => "",
		// 'default' => false,
		// 'values' => 1,
		// 'typ' => array('fixed'),
		// ),

		// Chart distance/elevation units.
		// imperial: false,
		array(
			'param'     => 'imperial',
			'shortdesc' => __( 'Chart distance/elevation units imperial or metric.', 'extensions-leaflet-map' ),
			'desc'      => __( 'miles or kilometers', 'extensions-leaflet-map' ),
			'default'   => false,
			'values'    => 1,
			'typ'       => array( 'changeable', 'units', 'multielevation' ),
		),

		// Factor:
		// opts.accelerationFactor = opts.accelerationFactor || 1;  // (Beschleunigung)
		// opts.paceFactor               = opts.paceFactor || 60; // 1 min = 60 sec
		// opts.timeFactor         = opts.timeFactor || 3600;

		// Labels:
		// acceleration.label  = opts.accelerationLabel  || L._(opts.imperial ? 'ft/s²' : 'm/s²');
		// pace.label            = opts.paceLabel  || L._(opts.imperial ? 'min/mi' : 'min/km');
		// slope.label         = opts.slopeLabel || '%';
		// time.label            = opts.timeLabel  || 't';

		// xLabel: "km",
		// distance.label      = opts.imperial ? "mi" : opts.xLabel;
		array(
			'param'     => 'xLabel',
			'shortdesc' => 'xLabel',
			/* translators: Label for distance in chart, will be ignored if imperial is true. */
			'desc'      => sprintf( __( 'Label for distance, will be ignored if %1$s is %2$s.', 'extensions-leaflet-map' ), '<code>imperial</code>', 'true' ) . '<br>' .
				__( 'Default', 'extensions-leaflet-map' ) . ': "km"',
			'default'   => '',
			'values'    => '',
			'typ'       => array( 'changeable', 'units', 'multielevation' ),
		),

		// opts.distanceFactor       = opts.imperial ? this.__mileFactor : (opts.distanceFactor || 1); // 1 km = (1000 m)
		// __mileFactor:     0.621371, // 1 km = (0.621371 mi)
		array(
			'param'     => 'distanceFactor',
			'shortdesc' => 'distanceFactor',
			/* translators: ... will be ignored if imperial is true */
			'desc'      => sprintf( __( 'Conversion factor to kilometers, will be ignored if %1$s is %2$s.', 'extensions-leaflet-map' ), '<code>imperial</code>', 'true' ) . '<br>' .
				__( 'Default', 'extensions-leaflet-map' ) . ': 1',
			'default'   => '',
			'values'    => '',
			'typ'       => array( 'changeable', 'units', 'multielevation' ),
		),

		// yLabel: "m",
		// altitude.label      = opts.imperial ? "ft" : opts.yLabel;
		array(
			'param'     => 'yLabel',
			'shortdesc' => 'yLabel',
			'desc'      => __( 'Label for altitude', 'extensions-leaflet-map' ) . '<br>' .
			/* translators: Default: "ft" if imperial is true, "m" otherwise. */
				sprintf( __( 'Default: %1$s if %2$s is %3$s, %4$s otherwise.', 'extensions-leaflet-map' ), '"ft"', '<code>imperial</code>', 'true', '"m"' ),
			'default'   => '',
			'values'    => '',
			'typ'       => array( 'changeable', 'units', 'multielevation' ),
		),

		// opts.altitudeFactor       = opts.imperial ? this.__footFactor : (opts.altitudeFactor || 1); // 1 m = (1 m)
		array(
			'param'     => 'altitudeFactor',
			'shortdesc' => 'altitudeFactor',
			/* translators: ... will be ignored if imperial is true */
			'desc'      => sprintf( __( 'Conversion factor to meters, will be ignored if %1$s is %2$s', 'extensions-leaflet-map' ), '<code>imperial</code>', 'true' ) . '<br>' .
				__( 'Default', 'extensions-leaflet-map' ) . ': 1',
			'default'   => '',
			'values'    => '',
			'typ'       => array( 'changeable', 'units', 'multielevation' ),
		),

		// speed.label           = opts.speedLabel || L._(opts.imperial ? 'mph' : 'km/h');
		array(
			'param'     => 'speedLabel',
			'shortdesc' => 'speedLabel',
			'desc'      => __( 'Label for speed', 'extensions-leaflet-map' ) . '<br>' .
			/* translators: Default: "mph" if imperial is true, "km/h" otherwise. */
				sprintf( __( 'Default: %1$s if %2$s is %3$s, %4$s otherwise.', 'extensions-leaflet-map' ), '"mph"', '<code>imperial</code>', 'true', '"km/h"' ),
			'default'   => '',
			'values'    => '',
			'typ'       => array( 'changeable', 'units', 'multielevation' ),
		),

		// opts.speedFactor        = opts.speedFactor || 1;
		// array(
		// 'param'     => 'speedFactor',
		// 'shortdesc' => 'speedFactor',
		// 'desc'      => __( 'Conversion factor to km/h, if you want to show m/min', 'extensions-leaflet-map' ) . ': <code>0.016666</code>',
		// 'default'   => '',
		// 'values'    => '',
		// 'typ'       => array( 'changeable', 'units', 'multielevation' ),
		// ),
	);

	if ( count( $typ ) > 0 ) {
		$options = array();
		foreach ( $typ as $type ) { // "fixed", "changeable", ""
			foreach ( $params as $key => $param ) {
				if ( in_array( $type, $params[ $key ]['typ'], true ) ) {
					$options[] = $params[ $key ];
				}
			}
		}
		return $options;
	}
	return $params;
}

function leafext_elevation_colors() {
	$theme     = leafext_elevation_theme();
	$owncolors = get_option( 'leafext_color_' . $theme );
	$themes    = array(
		'lime-theme'      => array(
			'polyline'   => '#80904e',
			'background' => '#ebf3d3',
			'altitude'   => '#accc45',
		),
		'steelblue-theme' => array(
			'polyline'   => '#74a1c7',
			'background' => '#dae6f0',
			'altitude'   => '#6496c0',
		),
		'purple-theme'    => array(
			'polyline'   => '#96619c',
			'background' => '#e3d5e5',
			'altitude'   => '#8b5291',
		),
		'yellow-theme'    => array(
			'polyline'   => '#ffff40',
			'background' => '#dae6f0',
			'altitude'   => '#f8fa30',
		),
		'red-theme'       => array(
			'polyline'   => '#ff4040',
			'background' => '#dae6f0',
			'altitude'   => '#f82e30',
		),
		'magenta-theme'   => array(
			'polyline'   => '#ff4086',
			'background' => '#ffffff',
			'altitude'   => '#ff337e',
		),
		'lightblue-theme' => array(
			'polyline'   => '#668cd9',
			'background' => '#dae6f0',
			'altitude'   => '#8face0',
		),
	);

	if ( ! isset( $themes[ $theme ] ) ) {
		$themes[ $theme ] = array(
			'polyline'   => isset( $owncolors['polyline'] ) ? $owncolors['polyline'] : '#000',
			'background' => isset( $owncolors['background'] ) ? $owncolors['background'] : '#4682b4',  // rgba(70, 130, 180, 0.2)
			'altitude'   => isset( $owncolors['altitude'] ) ? $owncolors['altitude'] : '#4682B4',
		);
	}

		$params = array(
			array(
				'param'     => 'altitude',
				'shortdesc' => __( 'Altitude chart profile', 'extensions-leaflet-map' ),
				'desc'      => '',
				'default'   => isset( $themes[ $theme ]['altitude'] ) ? $themes[ $theme ]['altitude'] : '',
			),
			array(
				'param'     => 'speed',
				'shortdesc' => __( 'Speed chart profile', 'extensions-leaflet-map' ),
				'desc'      => '',
				'default'   => isset( $themes[ $theme ]['speed'] ) ? $themes[ $theme ]['speed'] : '#03ffff',
			),
			array(
				'param'     => 'acceleration',
				'shortdesc' => __( 'Acceleration chart profile', 'extensions-leaflet-map' ),
				'desc'      => '',
				'default'   => isset( $themes[ $theme ]['acceleration'] ) ? $themes[ $theme ]['acceleration'] : '#050402',
			),
			array(
				'param'     => 'slope',
				'shortdesc' => __( 'Slope chart profile', 'extensions-leaflet-map' ),
				'desc'      => '',
				'default'   => isset( $themes[ $theme ]['slope'] ) ? $themes[ $theme ]['slope'] : '#FF0000',
			),
			array(
				'param'     => 'pace',
				'shortdesc' => __( 'Pace profile - time per distance', 'extensions-leaflet-map' ),
				'desc'      => '',
				'default'   => isset( $themes[ $theme ]['pace'] ) ? $themes[ $theme ]['pace'] : '#03ffff',
			),
			array(
				'param'     => 'heart',
				'shortdesc' => __( 'Line color of the heart rate', 'extensions-leaflet-map' ),
				'desc'      => '',
				'default'   => isset( $themes[ $theme ]['heart'] ) ? $themes[ $theme ]['heart'] : '#ff0000',
			),
			array(
				'param'     => 'polyline',
				'shortdesc' => __( 'Track color', 'extensions-leaflet-map' ),
				'desc'      => '',
				'default'   => isset( $themes[ $theme ]['polyline'] ) ? $themes[ $theme ]['polyline'] : '#000',
			),
			array(
				'param'     => 'polylineSegments',
				'shortdesc' => __( 'Track color over the ruler filter', 'extensions-leaflet-map' ),
				'desc'      => '',
				'default'   => '#F00',
			),
			array(
				'param'     => 'background',
				'shortdesc' => __( 'Chart background color', 'extensions-leaflet-map' ),
				'desc'      => '',
				'default'   => isset( $themes[ $theme ]['background'] ) ? $themes[ $theme ]['background'] : '',
			),
		);
		return $params;
}

function leafext_ele_java_params( $settings ) {
	$text = '';
	foreach ( $settings as $k => $v ) {
		switch ( $k ) {
			case 'polyline':
				$v     = filter_var( $v, FILTER_SANITIZE_NUMBER_INT );
				$value = '{className: "elevation-polyline",
				color: "#000",
				opacity: 0.75,
				lineCap: "round",
				weight: ' . $v . '
			}';
				$text  = $text . "$k: " . $value . ',' . "\n";
				unset( $settings[ $k ] );
				break;
			// true ist automatisch, wenn man das setzt, bringt es Fehler!
			case 'trkStart':
			case 'trkEnd':
				if ( $v ) {
					unset( $settings[ $k ] );
				}
				break;
			case 'wptIcons':
				if ( strpos( $v, '{' ) !== false ) {
					$text = $text . "$k: " . $v . ',';
					unset( $settings[ $k ] );
				}
				break;
			case 'distanceMarkers':
				// distanceMarkers: { lazy: true, distance: true, direction: true },
				if ( $settings[ $k ] === true ) {
					$text = $text . 'distanceMarkers: {'; // }
					// if ( $settings['imperial'] == true) {
					// $text = $text.
					// 'offset: 1000/0.621371,
					// textFunction: function(distance, i, offset) {
					// return Math.round(distance*0.621371/1000);
					// },';
					// }
					if ( $settings['direction'] === true ) {
						$text = $text . 'lazy: true, distance: true, direction: true,';
					} else {
						$text = $text . 'lazy: true, distance: true, direction: false,';
					}
					$text = $text . '},';
				} elseif ( $settings['direction'] === true ) {
						$text = $text . 'distanceMarkers: { lazy: true, distance: false, direction: true },';
				} else {
					$text = $text . 'distanceMarkers: false,';
				}
				unset( $settings[ $k ] );
				unset( $settings['direction'] );
				break;
			case 'handlers':
			case 'margins':
				$text = $text . "$k: " . $v . ',';
				unset( $settings[ $k ] );
				break;
			case 'linearGradient':
				if ( $settings['linearGradient'] === true ) {
					$text = $text . "$k: {
					attr: 'z',
					path: 'altitude',
					range: { 0.0: '#008800', 0.5: '#ffff00', 1.0: '#ff0000' },
					min: 'elevation_min',
					max: 'elevation_max',
				},";
				}
				unset( $settings[ $k ] );
				break;
			case 'yAxisMin':
				if ( $settings['yAxisMin'] === '0' ) {
					unset( $settings[ $k ] );
				}
				break;
			case 'yAxisMin':
				if ( $settings['yAxisMax'] === '0' ) {
					unset( $settings[ $k ] );
				}
				break;
			case 'distanceFactor':
			case 'altitudeFactor':
			case 'speedLabel':
			case 'xLabel':
			case 'yLabel':
			case 'speedFactor':
				if ( $settings[ $k ] === '' ) {
					unset( $settings[ $k ] );
				}
				break;
			default:
		}
	}
	return array( $text, $settings );
}

// Shortcode: [elevation gpx="...url..."]
function leafext_elevation_script( $gpx, $settings ) {
	list($elevation_settings, $settings) = leafext_ele_java_params( $settings );
	$text                                = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var elevation_options = {
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- destroys javascript
			echo $elevation_settings;
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- destroys javascript
			echo leafext_java_params( $settings );
			?>
		};

		leafext_elevation_locale_js();
		leafext_elevation_prep_js();

		if ( typeof map.rotateControl !== "undefined" ) {
			map.rotateControl.remove();
			map.options.rotate = true;
		}

		<?php
		if ( $settings['track'] ) {
			echo 'var switchtrack = L.control.layers(null, null, {';
			if ( $settings['trackcollapsed'] ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- destroys javascript
				echo 'collapsed:' . $settings['trackcollapsed'] . ',';
			}
			if ( $settings['trackposition'] ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- destroys javascript
				echo 'position:"' . $settings['trackposition'] . '"';
			}
			echo '});';
		}
		?>

		// Instantiate elevation control.
		L.Control.Elevation.prototype.__btnIcon = "<?php echo esc_url( LEAFEXT_ELEVATION_URL ); ?>/images/elevation.svg";
		var controlElevation = L.control.elevation(elevation_options);
		var track_options= { url: "<?php echo esc_url( $gpx ); ?>" };
		controlElevation.addTo(map);
		<?php
		if ( $settings['track'] ) {
			echo 'switchtrack.addTo(map);';
		}
		?>

		// https://github.com/Raruto/leaflet-elevation/issues/232#issuecomment-1443554554
		var is_chrome = navigator.userAgent.indexOf("Chrome") > -1;
		var is_safari = navigator.userAgent.indexOf("Safari") > -1;
		if ( !is_chrome && is_safari && controlElevation.options.preferCanvas != false ) {
			console.log("is_safari - setting preferCanvas to false");
			controlElevation.options.preferCanvas = false;
		}

		// Load track from url (allowed data types: "*.geojson", "*.gpx")
		controlElevation.load(track_options.url);

		<?php
		if ( $settings['track'] ) {
			if ( $settings['track'] === 'filename' ) {
				$path_parts = pathinfo( $gpx );
				$switchname = '"' . $path_parts['filename'] . '"';
			} else {
				$switchname = 'e.name';
			}
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- destroys javascript
			echo '
			controlElevation.on("eledata_loaded", function(e) {
				switchtrack.addOverlay(e.layer, ' . $switchname . ');
			});
			';
			// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped -- destroys javascript
		}
		?>
	});
	<?php
	$javascript = ob_get_clean();
	$text       = $text . $javascript . '//-->' . "\n" . '</script>';
	$text       = \JShrink\Minifier::minify( $text );
	return "\n" . $text . "\n";
}

function leafext_elevation_settings( $typ ) {
	$defaults = array();
	$params   = leafext_elevation_params( $typ );
	foreach ( $params as $param ) {
		$defaults[ $param['param'] ] = $param['default'];
	}
	$options = shortcode_atts( $defaults, get_option( 'leafext_eleparams' ) );
	if ( array_key_exists( 'polyline', $options ) ) {
		$options['polyline'] = filter_var( $options['polyline'], FILTER_SANITIZE_NUMBER_INT );
	}
	return $options;
}

function leafext_elevation_theme() {
	$ownoptions = get_option( 'leafext_values' );
	if ( is_array( $ownoptions ) ) {
		if ( $ownoptions['theme'] === 'other' ) {
			$theme = $ownoptions['othertheme'];
		} else {
			$theme = $ownoptions['theme'] . '-theme';
		}
	} else {
		$newoptions = leafext_elevation_settings( array( 'theme' ) );
		$theme      = $newoptions['theme'];
	}
	return( $theme );
}

function leafext_elevation_color( $options ) {
	static $already_run = array();
	$owncolors          = get_option( 'leafext_color_' . $options['theme'] );
	// var_dump($options,$owncolors);
	$text = '';
	if ( is_array( $owncolors ) ) {
		foreach ( $owncolors as $key => $typ ) {
			if ( ! isset( $already_run[ $key ] ) ) {
				switch ( $key ) {
					case 'polylineSegments':
						break;
					case 'polyline':
						if ( isset( $options['polyline'] ) ) {
							$text = $text . '<style>' .
							'.' . $options['theme'] . '.elevation-polyline {stroke: ' . $typ . ';stroke-width: ' . $options['polyline'] . ';}' .
							'</style>';
						}
						break;
					case 'altitude':
						$text = $text . '<style>' .
						'.' . $options['theme'] . '.elevation-control .area path.' . $key . ',
					.' . $options['theme'] . ' .legend-' . $key . ' rect.area {fill: ' . $typ . '; }' .
						'.' . $options['theme'] . '.height-focus.circle-lower {fill: ' . $typ . '; }' .
						'</style>';
						break;
					case 'background':
						$text = $text . '<style>' .
						'.elevation-control .background {background-color: ' . $typ . ' !important;}' .
						'</style>';
						break;
					case 'heart':
						$text = $text . '<style>' .
						'.heart {stroke: ' . $typ . ' !important;}' .
						'.legend-' . $key . ' rect {fill: ' . $typ . ' !important; fill-opacity: 0.7; }' .
						'</style>';
						break;
					default:
						$text = $text . '<style>' .
						'.' . $options['theme'] . '.elevation-control .area path.' . $key . ',' .
						'.' . $options['theme'] . ' .legend-' . $key . ' rect {fill: ' . $typ . ';}' .
						'</style>';
				}
				$text                = $text . "\n";
				$already_run[ $key ] = true;
			}
		}
	}
	return array( $options, $text );
}

function leafext_elevation_function( $atts, $content, $shortcode ) {
	$text = leafext_should_interpret_shortcode( $shortcode, $atts );
	if ( $text !== '' ) {
		return $text;
	} else {
		if ( ! $atts['gpx'] ) {
			$text = '[elevation ';
			foreach ( $atts as $key => $item ) {
				$text = $text . "$key=$item ";
			}
			$text = $text . ']';
			return $text;
		}

		if ( isset( $atts['summary'] ) && $atts['summary'] === '1' ) {
			$atts['slope']        = 0;
			$atts['speed']        = 0;
			$atts['acceleration'] = 0;
			$atts['time']         = 0;
			$atts['downloadLink'] = 0;
			$atts['polyline']     = '{ weight: 3, }';
			$atts['legend']       = 0;
			$atts['summary']      = 'inline';
			$atts['legend']       = false;
		}

		$atts1   = leafext_case( array_keys( leafext_elevation_settings( array( 'changeable', 'fixed' ) ) ), leafext_clear_params( $atts ) );
		$options = shortcode_atts( leafext_elevation_settings( array( 'changeable', 'fixed' ) ), $atts1 );

		leafext_enqueue_elevation();
		leafext_enqueue_tmcw_togeojson();
		leafext_enqueue_leafext_elevation();

		$track = $atts['gpx'];

		if ( $options['chart'] === 'on' || $options['chart'] === 'off' ) {
			$options['closeBtn'] = true;
		} else {
			$options['closeBtn'] = false;
		}
		if ( $options['chart'] === 'off' ) {
			$options['collapsed'] = true;
		}

		if ( isset( $options['wptIcons'] ) ) {
			$wpt_icons = $options['wptIcons'];
			if ( ! is_bool( $wpt_icons ) && $wpt_icons === 'defined' ) {
				unset( $options['wptIcons'] );
				$waypoints = get_option( 'leafext_waypoints', '' );
				if ( $waypoints !== '' && ( $options['waypoints'] === 'markers' || $options['waypoints'] === '1' ) ) {
					$wptvalue = "{'': L.divIcon({
						className: 'elevation-waypoint-marker',
						html: '<i class=\"elevation-waypoint-icon default\"></i>',
						iconSize: [30, 30],
						iconAnchor: [8, 30],
					}),
					";
					foreach ( $waypoints as $wpt ) {
						$wptvalue = $wptvalue . '"' . $wpt['css'] . '":  L.divIcon(
							{
								className: "elevation-waypoint-marker",
								html: ' . "'" . '<i class="elevation-waypoint-icon ' . $wpt['css'] . '"></i>' . "'" . ',' .
								html_entity_decode( $wpt['js'] ) . '
							}
						),';
					}
					$wptvalue            = $wptvalue . '}';
					$options['wptIcons'] = $wptvalue;
				}
			}
		}

		// acceleration.js
		// altitude.js
		// // cadence.js
		// distance.js
		// heart.js
		// labels.js
		// lineargradient.js
		// pace.js
		// // runner.js
		// slope.js
		// speed.js
		// time.js

		// var_dump($options);

		$handlers = array();

		if ( (bool) $options['pace'] ) {
			$handlers[] = '"Pace"';
			if ( ! (bool) $options['time'] ) {
				$options['time'] = 'summary';
			}
			if ( (bool) $options['speed'] ) {
				$handlers[] = '"Speed"';
			}
			if ( (bool) $options['acceleration'] ) {
				$handlers[] = '"Acceleration"';
			}
			if ( (bool) $options['slope'] ) {
				$handlers[] = '"Slope"';
			}
		}
		if ( (bool) $options['heart'] ) {
			$handlers[] = '"Heart"';
			if ( ! (bool) $options['time'] ) {
				$options['time'] = 'summary';
			}
		}
		if ( (bool) $options['labelsRotation'] || $options['labelsAlign'] !== 'start' ) {
			$handlers[] = '"Labels"';
		}
		if ( (bool) $options['linearGradient'] ) {
			$handlers[] = '"Slope"';
			$handlers[] = '"LinearGradient"';
		}

		$handlers = array_unique( $handlers );
		// var_dump($handlers);

		if ( count( $handlers ) > 0 ) {
			$options['handlers'] = '[...L.Control.Elevation.prototype.options.handlers,' . implode( ',', $handlers ) . ']';
		}
		// if (count($handlers) > 0) $options['handlers'] = '["Distance","Time","Altitude",'.implode(',',$handlers).']';
		// if (count($handlers) > 0) $options['handlers'] = '['.implode(',',$handlers).',...L.Control.Elevation.prototype.options.handlers]';
		// if (count($handlers) > 0) $options['handlers'] = '[ "Distance", "Time", "Altitude", "Slope", "Speed", "Acceleration", "Labels"]';

		if ( ! array_key_exists( 'theme', $atts ) ) {
			$options['theme'] = leafext_elevation_theme();
		}

		$owncolors = get_option( 'leafext_color_' . $options['theme'] );
		if ( is_array( $owncolors ) ) {
			if ( isset( $owncolors['polylineSegments'] ) ) {
				$options['polylineSegments'] = '{className: "elevation-polyline-segments",
					color: "' . $owncolors['polylineSegments'] . '",
					interactive: false,
				}';
			}
			$options['preferCanvas'] = 'false';
		}

		if ( $options['hotline'] === 'elevation' ) {
			unset( $options['polyline'] );
		}
		if ( $options['direction'] === true ) {
			leafext_enqueue_rotate();
		}
		if ( $options['distanceMarkers'] === true ) {
			leafext_enqueue_rotate();
		}

		list($options, $style) = leafext_elevation_color( $options );
		ksort( $options );

		$text = $style . leafext_elevation_script( $track, $options );
		return $text;
	}
}
add_shortcode( 'elevation', 'leafext_elevation_function' );
