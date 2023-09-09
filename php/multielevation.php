<?php
/**
* Functions for multielevation
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Parameter and Values
function leafext_multielevation_params($typ = array('changeable')) {
	$params = array (
		array(
			'param' => 'filename',
			'shortdesc' => __('Filename as name',"extensions-leaflet-map"),
			'desc' => __('Use filename (without extension) as name of the point and track.',"extensions-leaflet-map"),
			'default' => false,
			'values' => 1,
			'typ' => array('changeable','point','multielevation','tracks'),
		),
		array(
			'param' => 'summary',
			'shortdesc' => __('Summary',"extensions-leaflet-map"),
			'desc' =>	sprintf ( __('Valid for %s: Only elevation profile with or without summary line will be displayed.',"extensions-leaflet-map"),
			'<code>[elevation-<span style="color: #d63638">tracks</span>]</code>'),
			'default' => true,
			'values' => 1,
			'typ' => array('changeable','tracks'),
		),

		//     highlight: {
		//       color: '#ff0',
		//       opacity: 1,
		//     },
		array(
			'param' => 'highlight',
			'shortdesc' => __('Highlight color',"extensions-leaflet-map"),
			'desc' =>	__('The active track is displayed in this color.',"extensions-leaflet-map"),
			'default' => '#ffff00',
			'values' => "color",
			'typ' => array('changeable','multielevation'),
		),

		// flyToBounds: true,
		// array(
		// 	'param' => 'flyToBounds',
		// 	'shortdesc' => __('flyToBounds',"extensions-leaflet-map"),
		// 	'desc' =>	'',
		// 	'default' => true,
		// 	'values' => 1,
		// 	'typ' => array('changeable','multielevation'),
		// ),

		//     distanceMarkers: true,
		//     distanceMarkers_options: {
		//       lazy: true
		//     },
		array(
			'param' => 'distanceMarkers',
			'shortdesc' => __('Toggle "leaflet-distance-markers" integration',"extensions-leaflet-map"),
			'desc' =>	'',
			'default' => true,
			'values' => 1,
			'typ' => array('changeable','multielevation'),
		),

		// Toggle direction arrows integration
		array(
			'param' => 'direction',
			'shortdesc' => __('Toggle direction arrows',"extensions-leaflet-map"),
			'desc' => '',
			'default' => false,
			'values' => 1,
			'typ' => array('changeable','multielevation'),
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

// immer true, oder?
//     points: [],
//     points_options: {
//       icon: {
//         iconUrl: '../images/elevation-poi.png',
//         iconSize: [12, 12],
//       }
//     },

// fest aber true
//     legend: false,
//     legend_options: {
//       position: "topright",
//       collapsed: false,
//     },

function leafext_eleparams_for_multi($options=array()) {
	$multi=array();
	$params = leafext_elevation_params(array("multielevation"));
	foreach($params as $param) {
		$multi[] = $param["param"];
	}
	//var_dump($multi);

	if (count($options) > 1 ) {
		$multioptions = array();
		foreach ($multi as $param) {
			$multioptions[$param] = $options[$param];
		}
		//var_dump($multioptions); wp_die();
		return $multioptions;
	} else {
		//fuer Hilfe
		$text = '';
		sort($multi);
		foreach ($multi as $param) {
			$text = $text.'<code>'.$param.'</code>, ';
		}
		$text = substr($text, 0, -2);
		return $text;
	}
}

function leafext_multielevation_settings($typ=array('changeable')) {
	$defaults=array();
	$params = leafext_multielevation_params($typ);
	foreach($params as $param) {
		$defaults[$param['param']] = $param['default'];
	}
	//var_dump($defaults);
	$options = shortcode_atts($defaults, get_option('leafext_multieleparams'));
	return $options;
}

//Shortcode:
//[elevation-track file="'.$file.'" lat="'.$startlat.'" lng="'.$startlon.'" name="'.basename($file).'" filename=true/false]
// lat lng name optional
function leafext_elevation_track($atts,$content,$shortcode) {
	$text = leafext_should_interpret_shortcode($shortcode,$atts);
	if ( $text != "" ) {
		return $text;
	} else {
		if ( $atts['file'] == "" ) {
			$text = "[elevation-track ";
			foreach ($atts as $key=>$item){
				$text = $text. "$key=$item ";
			}
			$text = $text."]";
			return $text;
		}

		global $all_files;
		if (!is_array($all_files)) $all_files = array();
		global $all_points;
		if (!is_array($all_points)) $all_points = array();

		$defaults = array (
			'lat'  => '',
			'lng'  => '',
			'name' => '',
		);
		$params = shortcode_atts($defaults, $atts);

		$multioptions = shortcode_atts(leafext_multielevation_settings(array('point')), leafext_clear_params($atts)); // filename or not?
		$path_parts = pathinfo($atts['file']);
		if ( boolval($multioptions['filename']) ) {
			$params['name'] = $path_parts['filename'];
		}

		if ( $params['lat'] == "" || $params['lng'] == "" || $params['name'] == "" ) {
			$gpx = simplexml_load_file($atts['file']);
			if ($gpx ===  FALSE) {
				$text = "[*elevation-track read error ";
				$text = $text . $atts['file'];
				foreach ($params as $key=>$item){
					$text = $text. "$key=$item ";
				}
				$text = $text."]";
				return $text;
			}
		}

		if ( $params['lat'] == "" || $params['lng'] == "" ) {
			if ($path_parts['extension'] == "gpx") {
				$latlng = array(
					(float)$gpx->trk->trkseg->trkpt[0]->attributes()->lat,
					(float)$gpx->trk->trkseg->trkpt[0]->attributes()->lon,
				);
			} else if ($path_parts['extension'] == "kml") {
				$allcoordinates=$gpx->Document->Folder->Folder->Placemark->LineString->coordinates;
				$coordsArray=explode("\n",$allcoordinates);
				for ($i=0; $i<count($coordsArray);$i++) {
			    $latlonheight=explode(",",$coordsArray[$i]);
			    if (count($latlonheight) == 3) {
			      $startlat = $latlonheight[1];
			      $startlon = $latlonheight[0];
			      break;
			    }
			  }
				$latlng = array(
					(float)$startlat,
					(float)$startlon,
				);
			}
		} else {
			$latlng = array($params['lat'],$params['lng']);
		}

		// filenames as tracknames?

		if ( $params['name'] == "" ) {
			if ($path_parts['extension'] == "gpx") {
				$params['name'] = (string) $gpx->trk->name;
			} else if ($path_parts['extension'] == "kml") {
				$params['name'] = (string) $gpx->Document->name;
			}
		}
		//Fallback
		if ( $params['name'] == "" ) {
			$path_parts = pathinfo($atts['file']);
			$params['name'] = $path_parts['filename'];
		}

		$point = array(
			'latlng' => $latlng,
			'name' 	 => $params['name'],
		);

		$all_points[] = $point;
		$all_files[] = $atts['file'];
	}
}
add_shortcode('elevation-track', 'leafext_elevation_track' );

//[elevation-tracks summary=0/1]
//{multielvation ...}
function leafext_multielevation( $atts,$content,$shortcode) {
	$text = leafext_should_interpret_shortcode($shortcode,$atts);
	if ( $text != "" ) {
		return $text;
	} else {
		leafext_enqueue_leafext_elevation();
		leafext_enqueue_multielevation();
		leafext_enqueue_zoomhome();

		global $all_files;
		global $all_points;

		$ele_options = array(
			'detachedView' => true,
			'elevationDiv' => "#elevation-div",
			'zFollow' => 15,
			'flyToBounds' => true,
			'legend' => false,
			'closeBtn' => false,
		);

		if ($shortcode == "elevation-tracks") {
			$options = array (
				'acceleration' =>  false,
				'almostOver' => true,
				'downloadLink' => false,
				'followMarker' => false,
				'preferCanvas' => false,
				'speed' =>  false,
				'time' => false,
				'height' => 200,
			);
			$multioptions = shortcode_atts(leafext_multielevation_settings(array('tracks')), leafext_clear_params($atts));
			if ($multioptions['summary']) {
				$options['summary'] = "inline";
				//$options['slope'] = "summary";
			} else {
				$options['summary'] = false;
				//$options['slope'] = false;
			}
			$multioptions['distanceMarkers'] = false;
			$multioptions['distanceMarkers_options'] = "false";
		}

		if ($shortcode == "multielevation" ) {
			$atts1 = leafext_case(array_keys(leafext_elevation_settings(array("multielevation"))), leafext_clear_params($atts));
			$options = shortcode_atts(leafext_elevation_settings(array("multielevation")), $atts1);

			if (isset($options['pace']) ) {
				//$options = leafext_elevation_pace($options);
				$handlers = array();
				if ( (bool)$options['pace'] ) {
					$handlers[] = '"Pace"';
					if ( !(bool)$options['time'] ) $options['time'] = "summary";
					if ( (bool)$options['speed'] ) $handlers[] = '"Speed"';
					if ( (bool)$options['acceleration'] ) $handlers[] = '"Acceleration"';
					if ( (bool)$options['slope'] ) $handlers[] = '"Slope"';
				}
				if (count($handlers) > 0) $options['handlers'] = '[...L.Control.Elevation.prototype.options.handlers,'.implode(',',$handlers).']';
			}

			$multioptions = shortcode_atts(leafext_multielevation_settings(array('multielevation','fixed')), leafext_clear_params($atts));
			if (isset($multioptions['highlight']) ) {
				$multioptions['highlight'] = "{color: '".$multioptions['highlight']."',opacity: 1,}";
			}

			$opts_distanceMarker = array(
				'distanceMarkers' => $multioptions['distanceMarkers'],
				'direction' => $multioptions['direction'],
				'imperial' => $options['imperial'],
			);
			list($multi_dist_settings, $not_needed) = leafext_ele_java_params($opts_distanceMarker);
			//var_dump($multi_dist_settings);
			$multioptions['distanceMarkers_options'] = str_replace('distanceMarkers: ','',trim($multi_dist_settings,','));
			$multioptions['distanceMarkers'] = true;
			leafext_enqueue_rotate();
		}

		$options = array_merge($options, $ele_options);

		if ( is_array($atts) && array_key_exists('theme', $atts) ) {
			$options['theme'] = $atts['theme'];
		} else {
			$options['theme'] = leafext_elevation_theme();
		}

		list($options,$style) = leafext_elevation_color($options);

		//var_dump($all_files, $all_points, $options, $multioptions); wp_die();
		//var_dump($options,$multioptions);
		$rand = rand(1,20);
		$text = $style.leafext_multielevation_script( $all_files, $all_points, $options, $multioptions, $rand);

		$text = $text.'<p class="chart-placeholder chart-placeholder-'.$rand.'">';
		$text = $text.__("move mouse over a track or select one in control panel ...", "extensions-leaflet-map").'</p>';
		$all_files = array();
		$all_points = array();
		return $text;
	}
}
add_shortcode('elevation-tracks', 'leafext_multielevation');
add_shortcode('multielevation', 'leafext_multielevation');

function leafext_multielevation_script( $all_files, $all_points, $settings, $multioptions, $rand ) {
	//var_dump($settings,$multioptions); wp_die();
	list($elevation_settings, $settings) = leafext_ele_java_params($settings);
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var points = <?php echo json_encode($all_points); ?>;
		var tracks = <?php echo json_encode($all_files); ?>;
		//console.log(points);
		//console.log(tracks);

		if ( typeof map.rotateControl !== "undefined" ) {
			map.rotateControl.remove();
		}
		map.options.rotate = true;

		var opts = {
			points: {
				icon: {
					iconUrl: "<?php echo LEAFEXT_ELEVATION_URL;?>images/elevation-poi.png",
					iconSize: [12, 12],
				},
			},
			elevation: {
				<?php echo $elevation_settings;?>
				<?php echo leafext_java_params ($settings);?>
			},
			distanceMarkers: <?php echo $multioptions['distanceMarkers_options'];?>
		};
		console.log(opts.elevation);
		leafext_elevation_locale_js();
		leafext_elevation_prep_js ();

		var gpxGroupProto = L.GpxGroup.prototype;
		var addTrack = gpxGroupProto.addTrack;

		L.GpxGroup.include({
			addTrack: function(track) {
				fetch(track)
				.then(response => response.ok && response.text())
				.then(text => this._elevation._parseFromString(text))
				.then(geojson => {
					if(geojson) {
						geojson.name = geojson.name || (geojson[0] && geojson[0].properties.name) || track.split('/').pop().split('#')[0].split('?')[0];
						geojson.name = this.options.filename ? track.split('/').pop().split('.').slice(0, -1).join('.') : geojson.name;
						this._loadRoute(geojson);
					}
				});
			},
		});

		let routes;
		routes = L.gpxGroup(tracks, {
			async: false,
			points: points,
			points_options: opts.points,
			elevation: true,
			elevation_options: opts.elevation,
			legend: true,
			legend_options: {
				position: "topright",
				collapsed: true,
			},
			<?php echo leafext_java_params ($multioptions);?>
		});
		//console.log(routes);
		routes.addTo(map);

		L.Control.Elevation.prototype.__btnIcon = "<?php echo LEAFEXT_ELEVATION_URL;?>/images/elevation.svg";
		map.on("eledata_added eledata_clear", function(e) {
			var p = document.querySelector(".chart-placeholder-<?php echo $rand;?>");
			if(p) {
				p.style.display = e.type=="eledata_added" ? "none" : "";
			}
		});

		var bounds = [];
		bounds = new L.latLngBounds();
		var zoomHome = [];
		zoomHome = L.Control.zoomHome();
		var zoomhomemap=false;
		map.on("zoomend", function(e) {
			//console.log("zoomend");
			//console.log( zoomhomemap );
			if ( ! zoomhomemap ) {
				//console.log(map.getBounds());
				zoomhomemap=true;
				if(typeof map.zoomControl !== "undefined") {
			    map.zoomControl._zoomOutButton.remove();
			    map.zoomControl._zoomInButton.remove();
			  }
				zoomHome.addTo(map);
				zoomHome.setHomeBounds(map.getBounds());
			}
		});
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}
