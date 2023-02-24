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
			sprintf(__("If it is true, it is valid for any map (depending on %s respectively %s) and you can't change it. If it is false, you can enable it for a map:",'extensions-leaflet-map')
			,'<code>scrollwheel</code>','<code>dragging</code>').
			'</p><pre><code>[gestures]</code></pre>',
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
		$lang = get_bloginfo( 'language' );
	} else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$lang = preg_split("/[\s,;]+/", $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];
	} else {
		$lang = "en";
	}
	if ( ! glob(LEAFEXT_GESTURE_LOCALE_DIR.$lang.'.js')) {
		if ( ! glob(LEAFEXT_GESTURE_LOCALE_DIR.substr($lang, 0, 2).'.js')) {
			$lang = "en";
		} else {
			$lang = substr($lang, 0, 2);
		}
	}
	return $lang;
}

// For use with any map on a webpage
function leafext_gestures_script($lang){
	ob_start();
	?>
	/*<script>*/
	// For use with any map on a webpage
	//GestureHandling disables the following map attributes.
	//dragging
	//tap
	//scrollWheelZoom
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
			var maps = window.WPLeafletMapPlugin.maps;
			//console.log("gesture");
			for (var i = 0, len = maps.length; i < len; i++) {
				var map = maps[i];
			map.whenReady ( function() {
				console.log("dragging, scroll, mobile ",map.dragging.enabled(),map.scrollWheelZoom.enabled(),L.Browser.mobile);
				if ( map.scrollWheelZoom.enabled() || ( map.dragging.enabled() && L.Browser.mobile ) ) {
					console.log(i,"enabled");
					<?php if ( $lang != "" ) { ?>
						map.options.gestureHandlingOptions = {
							locale: "<?php echo $lang;?>", // set language of the warning message.
						}
						<?php
					} ?>
					map.gestureHandling.enable();
				}
			});
			}
	});
	<?php
	$text = ob_get_clean();
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

// For use with one map on a webpage
function leafext_gesture_script($lang){
	$text = '<script><!--';
	ob_start();
	?>
	/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		console.log("dragging, scroll, mobile ",map.dragging.enabled(),map.scrollWheelZoom.enabled(),L.Browser.mobile);
		if ( map.scrollWheelZoom.enabled() || ( map.dragging.enabled() && L.Browser.mobile ) ) {
			//console.log("enabled");
			<?php if ( $lang != "" ) { ?>
				map.options.gestureHandlingOptions = {
					locale: "<?php echo $lang; ?>", // set language of the warning message.
				}
				<?php
			} ?>
			map.gestureHandling.enable();
		}
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
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
// darf nicht per default geladen werden!! auskommentiert lassen!
//add_action( 'wp_enqueue_scripts', 'leafext_gestures_function' );

add_filter('pre_do_shortcode_tag', function ( $output, $shortcode ) {
	global $leafext_gesture_loaded;
	if (!isset($leafext_gesture_loaded)) $leafext_gesture_loaded = true;
	if ( 'leaflet-map' == $shortcode && $leafext_gesture_loaded) {
		leafext_gestures_function();
		$leafext_gesture_loaded = false;
	}
	return $output;
}, 10, 2);

function leafext_gestures_shortcode() {
	$text = leafext_should_interpret_shortcode('gestures',0);
	if ( $text != "" ) {
		return $text;
	} else {
		$options = leafext_gesture_settings();
		if ( ! (bool) $options['leafext_gesture_on'] ) {
			leafext_enqueue_gestures();
			//var_dump($options);
			$lang = leafext_gestures_lang($options);
			return leafext_gesture_script($lang);
		}
	}
}
add_shortcode('gestures', 'leafext_gestures_shortcode' );
