<?php
/**
 * Functions for gestures shortcode
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_gesture_params() {
	$params = array(
		array(
			'param' => 'leafext_gesture_on',
			'shortdesc' => __('valid for whole site or only for one map',"extensions-leaflet-map"),
			'desc' => '<p>'.
				__("If it is true, it is valid for any map and you can't change it. If it is false, you can change it.",'extensions-leaflet-map').'</p>',
			'default' => true,
			'values' => 1,
		),
		array(
			'param' => 'lang',
			'shortdesc' => __('Site Language or Browser Language',"extensions-leaflet-map"),
			'desc' => '<p>
				</p>',
			'default' => "Browser",
			'values' => array("Site","Browser"),
		),
	);
	return $params;
}

function leafext_gesture_settings() {
	$defaults=array();
	$params = leafext_gesture_params();
	foreach($params as $param) {
		$defaults[$param['param']] = $param['default'];
	}
	$options = shortcode_atts($defaults, get_option('leafext_gesture'));
	//var_dump($options); wp_die();
	return $options;
}

function leafext_gestures_lang($options) {
	if ( $options['lang'] == "Site" ) {
		$mylang = get_bloginfo( 'language' );
		$pattern = "/: \{$/i";
		$lngs = preg_grep($pattern, file( LEAFEXT_GESTURE_JS_FILE ));
		$lngs = preg_replace("/[^a-zA-Z-]+/", "", $lngs);
		//var_dump($lngs);
		if ( in_array($mylang , $lngs )) {
			$lang = $mylang;
		} else if ( in_array(substr($mylang,0,2), $lngs)) {
			$lang = substr($mylang,0,2);
		} else {
			$lang = "en";
		}
		//var_dump($lang);
	} else {
		$lang = "";
	}
	return $lang;
}

// For use with any map on a webpage
function leafext_gestures_script($lang){
	$text = '
	// For use with any map on a webpage
	//GestureHandling disables the following map attributes.
	//dragging
	//tap
	//scrollWheelZoom
	(function() {
		function main() {
			var maps = window.WPLeafletMapPlugin.maps;
			//console.log("gesture");
			for (var i = 0, len = maps.length; i < len; i++) {
				var map = maps[i];
				if ( map.dragging.enabled()
						|| map.scrollWheelZoom.enabled()
					) {
					//console.log("enabled");
					';
					if ( $lang != "" ) {
						$text = $text.'
						map.options.gestureHandlingOptions = {
							locale: "'.$lang.'", // set language of the warning message.
						}';
					}
					$text = $text.'
					map.gestureHandling.enable();
				}
			}
			//console.log(map);
		}
		window.addEventListener("load", main);
	})();
	';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

// For use with one map on a webpage
function leafext_gesture_script($lang){
	$text = '
	<script>
		window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
		window.WPLeafletMapPlugin.push(function () {
			var map = window.WPLeafletMapPlugin.getCurrentMap();
			if ( map.dragging.enabled() || map.scrollWheelZoom.enabled() ) {
				//console.log("enabled");
				';
				if ( $lang != "" ) {
					$text = $text.'
					map.options.gestureHandlingOptions = {
						locale: "'.$lang.'", // set language of the warning message.
					}';
				}
				$text = $text.'
				map.gestureHandling.enable();
			}
		});
	</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_gestures_function() {
	$options = leafext_gesture_settings();
	if ( (bool) $options['leafext_gesture_on'] ) {
		leafext_enqueue_gestures();
		$lang = leafext_gestures_lang($options);
		wp_add_inline_script( 'gestures_leaflet', leafext_gestures_script($lang), 'after' );
	}
}
add_action( 'wp_enqueue_scripts', 'leafext_gestures_function' );

add_filter('pre_do_shortcode_tag', function ( $output, $shortcode ) {
	if ( 'leaflet-map' == $shortcode ) {
		leafext_gestures_function();
	}
	return $output;
}, 10, 2);

function leafext_gestures_shortcode( $atts ) {
	$options = shortcode_atts(leafext_gesture_settings(), $atts);
	if ( ! (bool) $options['leafext_gesture_on'] ) {
		leafext_enqueue_gestures();
		//var_dump($options);
		$lang = leafext_gestures_lang($options);
		return leafext_gesture_script($lang);
	}
}
add_shortcode('gestures', 'leafext_gestures_shortcode' );
?>
