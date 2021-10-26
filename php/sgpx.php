<?php
/**
 * Functions for removing wp-gpx-maps plugin and using elevation instead
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

// Erstmal alles von wp-gpx-map entfernen
if ( is_plugin_active( 'wp-gpx-maps/wp-gpx-maps.php' ) ) {

	function leafext_dequeue_sgpx() {
		wp_dequeue_script('leaflet');
		wp_dequeue_style('leaflet.fullscreen');
		wp_dequeue_style('leaflet');
		wp_dequeue_style('leaflet.markercluster');
		wp_dequeue_script('leaflet.markercluster');
		wp_dequeue_style('leaflet.Photo');
		wp_dequeue_script('leaflet.Photo');
		wp_dequeue_script('leaflet.fullscreen');
		wp_dequeue_script('WP-GPX-Maps');
		wp_dequeue_script('chartjs');
		//	wp_dequeue_script('jquery');
		wp_deregister_script('leaflet');
		wp_deregister_style('leaflet.fullscreen');
		wp_deregister_style('leaflet');
		wp_deregister_style('leaflet.markercluster');
		wp_deregister_script('leaflet.markercluster');
		wp_deregister_style('leaflet.Photo');
		wp_deregister_script('leaflet.Photo');
		wp_deregister_script('leaflet.fullscreen');
		wp_deregister_script('WP-GPX-Maps');
		wp_deregister_script('chartjs');
		//	wp_deregister_script('jquery');
		remove_action('wp_print_styles', 'print_WP_GPX_Maps_styles' );
		unload_textdomain( "wp-gpx-maps" );
	}
	add_action( 'wp_enqueue_scripts', 'leafext_dequeue_sgpx' , 100);

	function leafext_remove_sgpx_shortcode() {
		remove_shortcode( 'sgpx' );
	}
	add_action( 'init', 'leafext_remove_sgpx_shortcode',20 );

} //is_plugin_active( 'wp-gpx-maps/wp-gpx-maps.php' )

// from wp-gpx-maps
function leafext_wpgpxmaps_findValue( $attr, $attributeName, $optionName, $defaultValue ) {
	$val = '';
	if ( isset( $attr[$attributeName] ) ) {
		$val = $attr[$attributeName];
	}
	if ( $val == '' ) {
		$val = get_option( $optionName );
	}
	if ( $val == '' && isset( $_GET[$attributeName] ) && $attributeName != 'download' ) {
		$val = $_GET[$attributeName];
	}
	if ( $val == '' ) {
		$val = $defaultValue;
	}
	return $val;
}

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


function leafext_sgpx_function( $atts ) {
	$options=get_option('leafext_eleparams');
	if ( $options['sgpx'] == "leaflet" && is_plugin_active( 'wp-gpx-maps/wp-gpx-maps.php' ) && ! wp_script_is( "wp_leaflet_map", 'enqueued' ) ) {
			enqueue_WP_GPX_Maps_scripts();
			wp_dequeue_style('leaflet.Photo');
			wp_dequeue_script('leaflet.Photo');
			$text = handle_WP_GPX_Maps_Shortcodes( $atts ) ;
			return $text;
	} else if ( ! $options['sgpx'] ) {
		$text = __("You are using the sgpx shortcode from plugin wp-gpx-maps. wp-gpx-maps and leaflet-map don't work at the same page or post. See admin settings page.","extensions-leaflet-map");
		$text = $text."<p>[sgpx ";
		foreach ($atts as $key=>$item){
			$text = $text. "$key = $item ";
		}
		$text = $text. "]</p>";
		return $text;
	} else {
		//
		$elemap = array();
		$elemap['width'] = leafext_wpgpxmaps_findValue( $atts, 'width', 'wpgpxmaps_width', '100%' );
		$elemap['height'] = leafext_wpgpxmaps_findValue( $atts, 'mheight', 'wpgpxmaps_height', '450px' );
		$elemap['scrollwheel'] = leafext_wpgpxmaps_findValue( $atts, 'zoomonscrollwheel', 'wpgpxmaps_zoomonscrollwheel', false );

		$maptext = "";
		foreach ($elemap as $k => $v) {
			if ( $v != "" ) $maptext = $maptext." ".$k."=".$v." ";
		}
		//
		$eleele = array();
		$eleele['waypoints'] = leafext_wpgpxmaps_findValue( $atts, 'waypoints', 'wpgpxmaps_show_waypoint', false );
		$eleele['downloadLink'] = leafext_wpgpxmaps_findValue( $atts, 'download', 'wpgpxmaps_download', '' );
		$eleele['slope'] = leafext_wpgpxmaps_findValue( $atts, 'showgrade', 'wpgpxmaps_show_grade', false );
		$eleele['speed'] = leafext_wpgpxmaps_findValue( $atts, 'showspeed', 'wpgpxmaps_show_speed', false );

		$eletext = "";
		foreach ($eleele as $k => $v) {
			$eletext = $eletext. " $k=";
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
			$eletext = $eletext.$value;
			//$eletext = $eletext.",\n";
			//if ( $v != "" ) $eletext = $eletext." ".$k."=".$v." ";
		}

		//
		$uom = leafext_wpgpxmaps_findValue( $atts, 'uom', 'wpgpxmaps_unit_of_measure', '0' );
		switch ($uom) {
			case '1': /* miles and feet */
			case '5':	/* meters / kilometers / nautical miles and feet */
				$eletext = $eletext.' imperial=1 '; break;
			//case '2': /* meters / kilometers */
			//case '3': /* meters / kilometers / nautical miles */
			//case '4':	/* meters / kilometers / nautical miles */
			default: 	/* meters / meters */
				$eletext = $eletext.' imperial=0 '; break;
		}

		//$text = '[leaflet-map][elevation gpx="'.$atts['gpx'].'"]';
		//return do_shortcode('[leaflet-map zoomcontrol][elevation gpx="'.$atts['gpx'].'"]');
		$text = '[leaflet-map zoomcontrol '.$maptext.'][elevation gpx="'.$atts['gpx'].'" marker="position-marker" '.$eletext.'][fullscreen]';
		return $text.do_shortcode($text);
	}
}
//add_shortcode('sgpx', 'leafext_sgpx_function' );

function leafext_change_sgpx_shortcode() {
	if (is_plugin_active( 'wp-gpx-maps/wp-gpx-maps.php')) {
		remove_shortcode( 'sgpx' );
	}
	add_shortcode('sgpx', 'leafext_sgpx_function' );
}
add_action( 'init', 'leafext_change_sgpx_shortcode',20 );
