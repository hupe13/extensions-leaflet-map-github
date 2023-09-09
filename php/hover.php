<?php
/**
* Functions for hover shortcode
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_hover_params($typ = '') {
	$params = array(
		array(
			'param' => 'marker',
			'desc' => '<ul style="list-style-type:disc;margin-left:1em;">'.
			'<li>'.'<code>true</code> - '.sprintf(__("show tooltip and hide %s if present","extensions-leaflet-map"),'<em>title</em>').'</li>'.
			'<li>'.'<code>false</code> - '.sprintf(__("do not show tooltip and hide %s","extensions-leaflet-map"),'<em>title</em>').'</li>'.
			'<li>'.'<code>title</code> - '.sprintf(__("do not show tooltip but show %s","extensions-leaflet-map"),'<em>title</em>').'</li>'.'</ul>',
			'default' => true,
			'values' => 'true, false, title',
			'element' => true,
			'only' => false,
			'changeable' => false,
		),
		array(
			'param' => 'circle',
			'desc' => __('',"extensions-leaflet-map"),
			'default' => true,
			'values' => 'true, false, tooltip, style',
			'element' => true,
			'only' => false,
			'changeable' => false,
		),
		array(
			'param' => 'polygon',
			'desc' => __('',"extensions-leaflet-map"),
			'default' => true,
			'values' => 'true, false, tooltip, style',
			'element' => true,
			'only' => false,
			'changeable' => false,
		),
		array(
			'param' => 'line',
			'desc' => __('',"extensions-leaflet-map"),
			'default' => true,
			'values' => 'true, false, tooltip, style',
			'element' => true,
			'only' => false,
			'changeable' => false,
		),
		array(
			'param' => 'geojson',
			'desc' => __('',"extensions-leaflet-map"),
			'default' => true,
			'values' => 'true, false, tooltip, style',
			'element' => true,
			'only' => false,
			'changeable' => false,
		),
		array(
			'param' => 'gpx',
			'desc' => __('',"extensions-leaflet-map"),
			'default' => true,
			'values' => 'true, false, tooltip, style',
			'element' => true,
			'only' => false,
			'changeable' => false,
		),
		array(
			'param' => 'kml',
			'desc' => __('',"extensions-leaflet-map"),
			'default' => true,
			'values' => 'true, false, tooltip, style',
			'element' => true,
			'only' => false,
			'changeable' => false,
		),
		//
		array(
			'param' => 'markertooltip',
			'desc' => __('alias for',"extensions-leaflet-map").' <code>[hover marker=true circle/polygon/line/geojson/gpx/kml=false]</code>',
			'default' => false,
			'values' => '',
			'element' => false,
			'only' => true,
			'changeable' => false,
		),
		array(
			'param' => 'geojsontooltip',
			'desc' => __('alias for',"extensions-leaflet-map").' <code>[hover geojson/gpx/kml=tooltip marker/circle/polygon/line=false]</code>, '.
					__('specify a short string as parameter, if the popup is too big.',"extensions-leaflet-map"),
			'default' => false,
			'values' => __('nothing or a string like the popup content for geojsons','extensions-leaflet-map').': <code>Field A = {field_a}</code>.',
			'element' => false,
			'only' => true,
			'changeable' => false,
		),
		array(
			'param' => 'geojsonstyle',
			'desc' => __('alias for',"extensions-leaflet-map").' <code>[hover geojson/gpx/kml=style marker/circle/polygon/line=false]</code>',
			'default' => false,
			'values' => '',
			'element' => false,
			'only' => true,
			'changeable' => false,
		),
		array(
			'param' => 'markergrouptooltip',
			'desc' => __('alias for',"extensions-leaflet-map").' <code>[hover circle/polygon/line=tooltip marker/geojson/gpx/kml=false]</code>',
			'default' => false,
			'values' => '',
			'element' => false,
			'only' => true,
			'changeable' => false,
		),
		array(
			'param' => 'markergroupstyle',
			'desc' => __('alias for',"extensions-leaflet-map").' <code>[hover circle/polygon/line=style marker/geojson/gpx/kml=false]</code>',
			'default' => false,
			'values' => '',
			'element' => false,
			'only' => true,
			'changeable' => false,
		),
		//
		array(
			'param' => 'exclude',
			'desc' => __('exclude any geojson, gpx, kml with a defined substring in the src url from changing its style on hovering, the tooltip is not affected','extensions-leaflet-map'),
			'default' => '',
			'values' => __('substring from url to geojson, gpx, kml file',"extensions-leaflet-map"),
			'element' => false,
			'only' => false,
			'changeable' => false,
		),
		array(
			'param' => 'class',
			'desc' => __('className for the tooltip','extensions-leaflet-map'),
			'default' => 'leafext-tooltip',
			'values' => __('a className',"extensions-leaflet-map"),
			'element' => false,
			'only' => false,
			'changeable' => true,
		),
		array(
			'param' => 'tolerance',
			'desc' => sprintf(__('How much to extend click tolerance round an object on the map, only valid for %s','extensions-leaflet-map'),'leaflet-geojson, gpx, kml'),
			'default' => 0,
			'values' => __('a number',"extensions-leaflet-map"),
			'element' => false,
			'only' => false,
			'changeable' => true,
		),
		array(
			'param' => 'popupclose',
			'desc' => __('At which distance of the mouse from a popup the popup closes. If it is 0, this is disabled, i.e. keep the popup open.','extensions-leaflet-map'),
			'default' => 50,
			'values' => __('a number',"extensions-leaflet-map"),
			'element' => false,
			'only' => false,
			'changeable' => true,
		),

		// array(
		// 	'param' => '',
		// 	'desc' => __('',"extensions-leaflet-map"),
		// 	'default' => '',
		// 	'values' => __('',"extensions-leaflet-map"),
		// ),
	);

	if ($typ != '') {
		foreach ($params as $key => $value) {
			if (! $params[$key][$typ]) {
				unset($params[$key]);
			}
		}
		$selection = array();
		foreach ($params as $value) {
			array_push($selection,$value['param']);
		}
		return $selection;
	}
	return $params;
}

function leafext_hover_settings() {
	$params = leafext_hover_params();
	$defaults = array();
	foreach($params as $param) {
		$defaults[$param['param']] = $param['default'];
	}
	$options = shortcode_atts($defaults,get_option('leafext_hover'));
	$options = shortcode_atts($options,get_option('leafext_canvas'));
	//var_dump($options); wp_die();
	return $options;
}

function leafext_canvas_script($tolerance) {
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		map.options.renderer=L.canvas({ tolerance: <?php echo $tolerance;?> });
		console.log("tolerance "+<?php echo $tolerance;?>);
		console.log(map.options.renderer);
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_hover_function($atts,$content,$shortcode) {
	$text = leafext_should_interpret_shortcode($shortcode,$atts);
	if ( $text != "" ) {
		return $text;
	} else {
		leafext_enqueue_geometry();
		leafext_enqueue_js();
		leafext_enqueue_leafext("hover");
		$defaults=array();
		$params = leafext_hover_params();
		foreach($params as $param) {
			$defaults[$param['param']] = $param['default'];
		}

		$settings = leafext_hover_settings();
		//var_dump($atts);
		$options  = shortcode_atts( $settings, leafext_clear_params($atts));
		//var_dump($atts,$settings,$options); wp_die();
		$text = "";
		if ($options['tolerance'] != 0) {
			$text = $text.leafext_canvas_script( $options['tolerance'] );
		}

		$do_tooltip = array(true,'tooltip');
		$do_style = array(true,'style');
		$do_only = leafext_hover_params('only');
		$do_element = leafext_hover_params('element');

		foreach ($do_only as $only) {
			if ($options[$only]) {
				foreach ($do_element as $element) {
					$options[$element] = false;
				}
				break;
			}
		}
		//var_dump($options);

		if (is_string($options['geojsontooltip'])) {
			$options['geojsontooltip'] = filter_var($options['geojsontooltip'], FILTER_SANITIZE_SPECIAL_CHARS);
		}

		$text = $text.'<script><!--';
		ob_start();
		?>/*<script>*/
		window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
		window.WPLeafletMapPlugin.push(function () {
			let all_options = <?php echo json_encode($options);?>;
			console.log("leafext_hover_function");
			console.log(all_options);
			<?php
			if (in_array($options['marker'],$do_tooltip,true)
			|| $options['markertooltip']) {
				?>
				leafext_hover_markertooltip_js(all_options);
				<?php
				$options['marker'] = true;
			}
			//
			if (in_array($options['circle'],$do_tooltip,true)
			|| in_array($options['polygon'],$do_tooltip,true)
			|| in_array($options['line'],$do_tooltip,true)
			|| $options['markergrouptooltip'])
			?>
			leafext_hover_markergrouptooltip_js(all_options);
			<?php
			//
			if (in_array($options['circle'],$do_style,true)
			|| in_array($options['polygon'],$do_style,true)
			|| in_array($options['line'],$do_style,true)
			|| $options['markergroupstyle'])
			?>
			leafext_hover_markergroupstyle_js(all_options);
			<?php
			//
			if (in_array($options['geojson'],$do_tooltip,true)
			|| in_array($options['gpx'],$do_tooltip,true)
			|| in_array($options['kml'],$do_tooltip,true)
			|| $options['geojsontooltip'])
			?>
			let tooltip = <?php echo json_encode($options['geojsontooltip']);?>;
			leafext_hover_geojsontooltip_js(tooltip,all_options);
			<?php
			//
			if (in_array($options['geojson'],$do_style,true)
			|| in_array($options['gpx'],$do_style,true)
			|| in_array($options['kml'],$do_style,true)
			|| $options['geojsonstyle'])
			?>
			leafext_hover_geojsonstyle_js(all_options);
			<?php
			//
			if ($options['marker'] == false ) {
				//$text = $text.leafext_markertitle_script($options);
				?>
				leafext_hover_markertitle_js();
				<?php
			}
			?>
		});
		<?php
		$javascript = ob_get_clean();
		$text = $text . $javascript . '//-->'."\n".'</script>';
		return $text;
	}
}
add_shortcode('hover', 'leafext_hover_function');
