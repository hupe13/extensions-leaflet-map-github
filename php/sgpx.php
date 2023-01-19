<?php
/**
 * Functions for removing wp-gpx-maps plugin and using elevation instead
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_sgpx_unclean_db() {
	global $wpdb;
	$option_names = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'wpgpxmaps_%' " );
	if (empty($option_names)) return false;
	return true;
}

function leafext_wp_gpx_maps_active() {
	if ( ! ( strpos(implode(" ",                get_option('active_plugins',         array()) ), "/wp-gpx-maps.php") === false &&
	         strpos(implode(" ",array_keys(get_site_option('active_sitewide_plugins',array()))), "/wp-gpx-maps.php") === false ) ) {
		return true;
	}
	return false;
}

define('LEAFEXT_SGPX_ACTIVE', leafext_wp_gpx_maps_active());
define('LEAFEXT_SGPX_UNCLEAN_DB', leafext_sgpx_unclean_db());
$sgpx_opts = is_bool(get_option('leafext_sgpxparams',false)) === true ? false : true;
define('LEAFEXT_SGPX_SGPX', $sgpx_opts);

// Erstmal alles von wp-gpx-map entfernen
if ( LEAFEXT_SGPX_ACTIVE ) {
	function leafext_dequeue_sgpx() {
		//
		wp_dequeue_script( 'chartjs' );
		wp_dequeue_script( 'jquery' );
		wp_dequeue_script( 'leaflet.fullscreen' );
		wp_dequeue_script( 'leaflet.markercluster' );
		wp_dequeue_script( 'leaflet.Photo' );
		wp_dequeue_script( 'leaflet' );
		wp_dequeue_script( 'output-style' );
		wp_dequeue_script( 'wp-gpx-maps' );
		wp_dequeue_style( 'leaflet.fullscreen' );
		wp_dequeue_style( 'leaflet.markercluster' );
		wp_dequeue_style( 'leaflet.Photo' );
		wp_dequeue_style( 'leaflet' );
		wp_dequeue_style( 'output-style' );
		wp_deregister_script( 'chartjs' );
		wp_deregister_script( 'leaflet.fullscreen' );
		wp_deregister_script( 'leaflet.markercluster' );
		wp_deregister_script( 'leaflet.Photo' );
		wp_deregister_script( 'leaflet');
		wp_deregister_script( 'wp-gpx-maps' );
		wp_deregister_style( 'leaflet.fullscreen');
		wp_deregister_style( 'leaflet.markercluster' );
		wp_deregister_style( 'leaflet.Photo' );
		wp_deregister_style( 'leaflet' );
		wp_deregister_style( 'output-style');
		//
		unload_textdomain( "wp-gpx-maps" );
	}
	add_action( 'wp_enqueue_scripts', 'leafext_dequeue_sgpx' , 100);

	function leafext_remove_sgpx_shortcode() {
		remove_shortcode( 'sgpx' );
	}
	add_action( 'init', 'leafext_remove_sgpx_shortcode',20 );

} //if ( LEAFEXT_SGPX_ACTIVE )

	// $folder         = wpgpxmaps_findValue( $attr, 'folder', '', '' );
	// $pointsoffset   = wpgpxmaps_findValue( $attr, 'pointsoffset', 'wpgpxmaps_pointsoffset', 10 );
	// $distanceType   = wpgpxmaps_findValue( $attr, 'distanceType', 'wpgpxmaps_distance_type', 0 );
	// $donotreducegpx = wpgpxmaps_findValue( $attr, 'donotreducegpx', 'wpgpxmaps_donotreducegpx', false );
	// ok $uom            = wpgpxmaps_findValue( $attr, 'uom', 'wpgpxmaps_unit_of_measure', '0' );

	// /* General */
	// ok $gpx            = wpgpxmaps_findValue( $attr, 'gpx', '', '' );
	// ok $w              = wpgpxmaps_findValue( $attr, 'width', 'wpgpxmaps_width', '100%' );
	// ok $mh             = wpgpxmaps_findValue( $attr, 'mheight', 'wpgpxmaps_height', '450px' );
	// $gh             = wpgpxmaps_findValue( $attr, 'gheight', 'wpgpxmaps_graph_height', '200px' );
	// $distanceType   = wpgpxmaps_findValue( $attr, 'distanceType', 'wpgpxmaps_distance_type', 0 );
	// $skipcache      = wpgpxmaps_findValue( $attr, 'skipcache', 'wpgpxmaps_skipcache', '' );
	// ok $download       = wpgpxmaps_findValue( $attr, 'download', 'wpgpxmaps_download', '' );
	// $usegpsposition = wpgpxmaps_findValue( $attr, 'usegpsposition', 'wpgpxmaps_usegpsposition', false );
	// /* Print Summary Table */
	// $summary          = wpgpxmaps_findValue( $attr, 'summary', 'wpgpxmaps_summary', false );
	// $p_tot_len        = wpgpxmaps_findValue( $attr, 'summarytotlen', 'wpgpxmaps_summary_tot_len', false );
	// $p_max_ele        = wpgpxmaps_findValue( $attr, 'summarymaxele', 'wpgpxmaps_summary_max_ele', false );
	// $p_min_ele        = wpgpxmaps_findValue( $attr, 'summaryminele', 'wpgpxmaps_summary_min_ele', false );
	// $p_total_ele_up   = wpgpxmaps_findValue( $attr, 'summaryeleup', 'wpgpxmaps_summary_total_ele_up', false );
	// $p_total_ele_down = wpgpxmaps_findValue( $attr, 'summaryeledown', 'wpgpxmaps_summary_total_ele_down', false );
	// $p_avg_speed      = wpgpxmaps_findValue( $attr, 'summaryavgspeed', 'wpgpxmaps_summary_avg_speed', false );
	// $p_avg_cad        = wpgpxmaps_findValue( $attr, 'summaryavgcad', 'wpgpxmaps_summary_avg_cad', false );
	// $p_avg_hr         = wpgpxmaps_findValue( $attr, 'summaryavghr', 'wpgpxmaps_summary_avg_hr', false );
	// $p_avg_temp       = wpgpxmaps_findValue( $attr, 'summaryavgtemp', 'wpgpxmaps_summary_avg_temp', false );
	// $p_total_time     = wpgpxmaps_findValue( $attr, 'summarytotaltime', 'wpgpxmaps_summary_total_time', false );
	// /* Map */
	// $mt                 = wpgpxmaps_findValue( $attr, 'mtype', 'wpgpxmaps_map_type', 'HYBRID' );
	// $color_map          = wpgpxmaps_findValue( $attr, 'mlinecolor', 'wpgpxmaps_map_line_color', '#3366cc' );
	// $zoomOnScrollWheel  = wpgpxmaps_findValue( $attr, 'zoomonscrollwheel', 'wpgpxmaps_zoomonscrollwheel', false );
	// $showW              = wpgpxmaps_findValue( $attr, 'waypoints', 'wpgpxmaps_show_waypoint', false );
	// $startIcon          = wpgpxmaps_findValue( $attr, 'starticon', 'wpgpxmaps_map_start_icon', '' );
	// $endIcon            = wpgpxmaps_findValue( $attr, 'endicon', 'wpgpxmaps_map_end_icon', '' );
	// $currentpositioncon = wpgpxmaps_findValue( $attr, 'currentpositioncon', 'wpgpxmaps_currentpositioncon', '' );
	// $currentIcon        = wpgpxmaps_findValue( $attr, 'currenticon', 'wpgpxmaps_map_current_icon', '' );
	// $waypointIcon       = wpgpxmaps_findValue( $attr, 'waypointicon', 'wpgpxmaps_map_waypoint_icon', '' );
	// /* Diagram - Elevation */
	// $showEle     = wpgpxmaps_findValue( $attr, 'showele', 'wpgpxmaps_show_elevation', true );
	// $color_graph = wpgpxmaps_findValue( $attr, 'glinecolor', 'wpgpxmaps_graph_line_color', '#3366cc' );
	// $uom         = wpgpxmaps_findValue( $attr, 'uom', 'wpgpxmaps_unit_of_measure', '0' );
	// $chartFrom1  = wpgpxmaps_findValue( $attr, 'chartfrom1', 'wpgpxmaps_graph_offset_from1', '' );
	// $chartTo1    = wpgpxmaps_findValue( $attr, 'chartto1', 'wpgpxmaps_graph_offset_to1', '' );
	// /* Diagram - Speed */
	// $showSpeed         = wpgpxmaps_findValue( $attr, 'showspeed', 'wpgpxmaps_show_speed', false );
	// $color_graph_speed = wpgpxmaps_findValue( $attr, 'glinecolorspeed', 'wpgpxmaps_graph_line_color_speed', '#ff0000' );
	// $uomspeed          = wpgpxmaps_findValue( $attr, 'uomspeed', 'wpgpxmaps_unit_of_measure_speed', '0' );
	// $chartFrom2        = wpgpxmaps_findValue( $attr, 'chartfrom2', 'wpgpxmaps_graph_offset_from2', '' );
	// $chartTo2          = wpgpxmaps_findValue( $attr, 'chartto2', 'wpgpxmaps_graph_offset_to2', '' );
	// /* Diagram - Heart rate */
	// $showHr         = wpgpxmaps_findValue( $attr, 'showhr', 'wpgpxmaps_show_hr', false );
	// $color_graph_hr = wpgpxmaps_findValue( $attr, 'glinecolorhr', 'wpgpxmaps_graph_line_color_hr', '#ff77bd' );
	// /* Diagram - Temperature */
	// $showAtemp         = wpgpxmaps_findValue( $attr, 'showatemp', 'wpgpxmaps_show_atemp', false );
	// $color_graph_atemp = wpgpxmaps_findValue( $attr, 'glinecoloratemp', 'wpgpxmaps_graph_line_color_atemp', '#ff77bd' );
	// /* Diagram - Cadence */
	// $showCad         = wpgpxmaps_findValue( $attr, 'showcad', 'wpgpxmaps_show_cadence', false );
	// $color_graph_cad = wpgpxmaps_findValue( $attr, 'glinecolorcad', 'wpgpxmaps_graph_line_color_cad', '#beecff' );
	// /* Diagram - Grade */
	// $showGrade         = wpgpxmaps_findValue( $attr, 'showgrade', 'wpgpxmaps_show_grade', false );
	// $color_graph_grade = wpgpxmaps_findValue( $attr, 'glinecolorgrade', 'wpgpxmaps_graph_line_color_grade', '#beecff' );
	// /* Pictures */
	// $ngGalleries = wpgpxmaps_findValue( $attr, 'nggalleries', 'wpgpxmaps_map_ngGalleries', '' );
	// $ngImages    = wpgpxmaps_findValue( $attr, 'ngimages', 'wpgpxmaps_map_ngImages', '' );
	// $attachments = wpgpxmaps_findValue( $attr, 'attachments', 'wpgpxmaps_map_attachments', false );
	// $dtoffset    = wpgpxmaps_findValue( $attr, 'dtoffset', 'wpgpxmaps_dtoffset', 0 );
	// /* Advanced */
	// $pointsoffset   = wpgpxmaps_findValue( $attr, 'pointsoffset', 'wpgpxmaps_pointsoffset', 10 );
	// $donotreducegpx = wpgpxmaps_findValue( $attr, 'donotreducegpx', 'wpgpxmaps_donotreducegpx', false );

function leafext_sgpx_params() {
	$params = array(
		//  Switch from sgpx
		array(
			'param' => 'sgpx',
			'shortdesc' => __('Replace <code>sgpx</code> (wp-gpx-maps) with <code>elevation</code>.',"extensions-leaflet-map"),
			'desc' => sprintf(__("No / Yes / Only, when %s is used, e.g. for testing.","extensions-leaflet-map"),'<code>[leaflet-map height="1"]</code>'),
			'default' => false,
			'values' => array(false, true, "leaflet"),
			'next' => "0",
		),
	);
	return $params;
}

function leafext_sgpx_settings() {
	$defaults=array();
	$params = leafext_sgpx_params();
	foreach($params as $param) {
		$defaults[$param['param']] = $param['default'];
	}
	$options = shortcode_atts($defaults, get_option('leafext_sgpxparams'));
	return $options;
}

function leafext_sgpx_function( $atts,$content,$shortcode) {
	$text = leafext_should_interpret_shortcode($shortcode,$atts);
	if ( $text != "" ) {
		return $text;
	} else {
		$options=get_option('leafext_sgpxparams');

		if ( LEAFEXT_SGPX_ACTIVE && LEAFEXT_SGPX_SGPX && $options['sgpx'] == "leaflet" && !wp_script_is( "wp_leaflet_map", 'enqueued' )) {
			if (function_exists('enqueue_WP_GPX_Maps_scripts')) {
				enqueue_WP_GPX_Maps_scripts();
				wp_dequeue_style('leaflet.Photo');
				wp_dequeue_script('leaflet.Photo');
				$text = handle_WP_GPX_Maps_Shortcodes( $atts ) ;
			} else if (function_exists('wpgpxmaps_enqueue_scripts')) {
				wpgpxmaps_enqueue_scripts();
				wp_dequeue_style('leaflet.Photo');
				wp_dequeue_script('leaflet.Photo');
				$text = wpgpxmaps_handle_shortcodes( $atts ) ;
			} else {
				$text = __("You are using the sgpx shortcode from plugin wp-gpx-maps. But the script cannot detect how to handle it. Please ask in the forum.","extensions-leaflet-map");
				$text = $text."<p>[sgpx ";
				foreach ($atts as $key=>$item){
					$text = $text. "$key = $item ";
				}
				$text = $text. "]</p>";
			}
			return $text;

		} else if ( LEAFEXT_SGPX_ACTIVE && ( ( LEAFEXT_SGPX_SGPX && ! $options['sgpx'] ) || ! LEAFEXT_SGPX_SGPX ) ) {
			$text = __("You are using the sgpx shortcode from plugin wp-gpx-maps. wp-gpx-maps and leaflet-map don't work together.","extensions-leaflet-map").' ';
			$text = $text .sprintf('See %sadmin settings page%s.',
			'<a href="'.get_admin_url().'admin.php?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=sgpxelevation">',
			'</a>');
			$text = $text."<p>[sgpx ";
			foreach ($atts as $key=>$item){
				$text = $text. "$key = $item ";
			}
			$text = $text. "]</p>";
			return $text;
		} else {
			//
			$elemap = array();

			$elemap['width'] = isset( $atts['width'] ) ? $atts['width'] : "";
			$elemap['height'] = isset( $atts['mheight'] ) ? $atts['mheight'] : "";
			$elemap['scrollwheel'] = isset( $atts['mheight'] ) ? $atts['zoomonscrollwheel'] : "";

			$maptext = "";
			foreach ($elemap as $k => $v) {
				if ( $v != "" ) $maptext = $maptext." ".$k."=".$v." ";
			}
			//
			$eleele = array();

			$eleele['waypoints'] =  isset( $atts['waypoints'] ) ? $atts['waypoints'] : "";
			$eleele['downloadLink'] =  isset( $atts['download'] ) ? $atts['download'] : "";
			$eleele['slope'] = isset( $atts['showgrade'] ) ? $atts['showgrade'] : "";
			$eleele['speed'] = isset( $atts['showspeed'] ) ? $atts['showspeed'] : "";

			$eletext = "";
			foreach ($eleele as $k => $v) {
				if ( $v == "") continue;
				switch (gettype($v)) {
					case "string":
					switch ($v) {
						case "false":
						case "0": $value = '"0"'; break;
						case "true":
						case "1": $value = '"1"'; break;
						default:
						if (is_numeric($v)) {
							$value = $v;
						} else {
							$value = '"'.$v.'"';
						}
						break;
					}
					break;
					case "boolean":
					$value = $v ? '"1"' : '"0"'; break;
					case "integer":
					switch ($v) {
						//case 0: $value = "false"; break;
						//case 1: $value = "true"; break;
						default: $value = $v; break;
					}
					break;
					default: var_dump($k, $v, gettype($v)); wp_die("Type");
				}
				if ( $value != "" ) $eletext = $eletext." ".$k."=".$value;
			}

			//
			$uom = isset( $atts['uom'] ) ? $atts['uom'] : "";
			switch ($uom) {
				case  "": break;
				case '1': /* miles and feet */
				case '5':	/* meters / kilometers / nautical miles and feet */
				$eletext = $eletext.' imperial=1 '; break;
				//case '2': /* meters / kilometers */
				//case '3': /* meters / kilometers / nautical miles */
				//case '4':	/* meters / kilometers / nautical miles */
				default: 	/* meters / meters */
				$eletext = $eletext.' imperial=0 '; break;
			}

			$text = '[leaflet-map zoomcontrol '.$maptext.'][elevation gpx="'.$atts['gpx'].'" marker="position-marker"'.$eletext.'][fullscreen]';
			if (is_single() || is_page()) echo "<script>console.log(".json_encode( $text ).")</script>";
			return do_shortcode($text);
		}
	}
}
//add_shortcode('sgpx', 'leafext_sgpx_function' );

function leafext_change_sgpx_shortcode() {
	if ( LEAFEXT_SGPX_ACTIVE ) {
		remove_shortcode( 'sgpx' );
	}
	add_shortcode('sgpx', 'leafext_sgpx_function' );
}
add_action( 'init', 'leafext_change_sgpx_shortcode',20 );

function leafext_insert_jquery() {
	$options=get_option('leafext_sgpxparams');
	if ( LEAFEXT_SGPX_ACTIVE && LEAFEXT_SGPX_SGPX && $options['sgpx'] == "leaflet" && !wp_script_is( "wp_leaflet_map", 'enqueued' )) {
		wp_enqueue_script('jquery-core', false, array(), false, false);
	}
}
add_filter('wp_enqueue_scripts','leafext_insert_jquery',1);
