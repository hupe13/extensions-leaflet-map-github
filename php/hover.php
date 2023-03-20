<?php
/**
* Functions for hover shortcode
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

include_once LEAFEXT_PLUGIN_DIR . 'php/hover_geojsonstyle.php';
include_once LEAFEXT_PLUGIN_DIR . 'php/hover_geojsontooltip.php';
include_once LEAFEXT_PLUGIN_DIR . 'php/hover_markergroupstyle.php';
include_once LEAFEXT_PLUGIN_DIR . 'php/hover_markergrouptooltip.php';
include_once LEAFEXT_PLUGIN_DIR . 'php/hover_markertooltip.php';
include_once LEAFEXT_PLUGIN_DIR . 'php/hover_markertitle.php';

function leafext_hover_params($typ = '') {
	$params = array(
		array(
			'param' => 'marker',
			'desc' => __("show tooltip for marker on hover or not, or don't show tooltip and hide title","extensions-leaflet-map"),
			'default' => true,
			'values' => 'true, false, notitle',
			'element' => true,
			'only' => false,
		),
		array(
			'param' => 'circle',
			'desc' => __('',"extensions-leaflet-map"),
			'default' => true,
			'values' => 'true, false, tooltip, style',
			'element' => true,
			'only' => false,
		),
		array(
			'param' => 'polygon',
			'desc' => __('',"extensions-leaflet-map"),
			'default' => true,
			'values' => 'true, false, tooltip, style',
			'element' => true,
			'only' => false,
		),
		array(
			'param' => 'line',
			'desc' => __('',"extensions-leaflet-map"),
			'default' => true,
			'values' => 'true, false, tooltip, style',
			'element' => true,
			'only' => false,
		),
		array(
			'param' => 'geojson',
			'desc' => __('',"extensions-leaflet-map"),
			'default' => true,
			'values' => 'true, false, tooltip, style',
			'element' => true,
			'only' => false,
		),
		array(
			'param' => 'gpx',
			'desc' => __('',"extensions-leaflet-map"),
			'default' => true,
			'values' => 'true, false, tooltip, style',
			'element' => true,
			'only' => false,
		),
		array(
			'param' => 'kml',
			'desc' => __('',"extensions-leaflet-map"),
			'default' => true,
			'values' => 'true, false, tooltip, style',
			'element' => true,
			'only' => false,
		),
		//
		array(
			'param' => 'markertooltip',
			'desc' => __('alias for',"extensions-leaflet-map").' <code>[hover marker=true circle/polygon/line/geojson/gpx/kml=false]</code>',
			'default' => false,
			'values' => '',
			'element' => false,
			'only' => true,
		),
		array(
			'param' => 'geojsontooltip',
			'desc' => __('alias for',"extensions-leaflet-map").' <code>[hover geojson/gpx/kml=tooltip marker/circle/polygon/line=false]</code>',
			'default' => false,
			'values' => '',
			'element' => false,
			'only' => true,
		),
		array(
			'param' => 'geojsonstyle',
			'desc' => __('alias for',"extensions-leaflet-map").' <code>[hover geojson/gpx/kml=style marker/circle/polygon/line=false]</code>',
			'default' => false,
			'values' => '',
			'element' => false,
			'only' => true,
		),
		array(
			'param' => 'markergrouptooltip',
			'desc' => __('alias for',"extensions-leaflet-map").' <code>[hover circle/polygon/line=tooltip marker/geojson/gpx/kml=false]</code>',
			'default' => false,
			'values' => '',
			'element' => false,
			'only' => true,
		),
		array(
			'param' => 'markergroupstyle',
			'desc' => __('alias for',"extensions-leaflet-map").' <code>[hover circle/polygon/line=style marker/geojson/gpx/kml=false]</code>',
			'default' => false,
			'values' => '',
			'element' => false,
			'only' => true,
		),
		//
		array(
			'param' => 'exclude',
			'desc' => __('exclude any geojson, gpx, kml with a defined substring in the src url from changing its style on hovering, the tooltip is not affected','extensions-leaflet-map'),
			'default' => '',
			'values' => __('substring from url to geojson, gpx, kml file',"extensions-leaflet-map"),
			'element' => false,
			'only' => false,
		),
		array(
			'param' => 'tolerance',
			'desc' => __('determines how much to extend click tolerance round a path/object on the map','extensions-leaflet-map'),
			'default' => 0,
			'values' => 'a number',
			'element' => false,
			'only' => false,
		),
		// array(
		// 	'param' => 'tooltip',
		// 	'desc' => __('',"extensions-leaflet-map"),
		// 	'default' => '',
		// 	'values' => 'marker,circle,polygon,line,geojson,gpx,kml',
		// ),
		// array(
		// 	'param' => 'style',
		// 	'desc' => __('',"extensions-leaflet-map"),
		// 	'default' => '',
		// 	'values' => 'marker,circle,polygon,line,geojson,gpx,kml',
		// ),
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

function leafext_canvas_script($tolerance) {
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		map.options.renderer=L.canvas({ tolerance: <?php echo $tolerance;?> });
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
		$defaults=array();
		$params = leafext_hover_params();
		foreach($params as $param) {
			$defaults[$param['param']] = $param['default'];
		}
		$settings = shortcode_atts(	$defaults, get_option( 'leafext_canvas' ));
		$options  = shortcode_atts( $settings, leafext_clear_params($atts));
		//var_dump($atts,get_option( 'leafext_canvas'),$settings,$options); wp_die();
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

		if ($options['marker'] == "notitle") {
			$text = $text.leafext_markertitle_script($options);
			$options['marker'] == false;
		}

		if (in_array($options['marker'],$do_tooltip,true)
		|| $options['markertooltip'])
		$text=$text.leafext_markertooltip_script($options);
		//
		if (in_array($options['circle'],$do_tooltip,true)
		|| in_array($options['polygon'],$do_tooltip,true)
		|| in_array($options['line'],$do_tooltip,true)
		|| $options['markergrouptooltip'])
		$text = $text.leafext_markergrouptooltip_script($options);
		//
		if (in_array($options['circle'],$do_style,true)
		|| in_array($options['polygon'],$do_style,true)
		|| in_array($options['line'],$do_style,true)
		|| $options['markergroupstyle'])
		$text = $text.leafext_markergroupstyle_script($options);
		//
		if (in_array($options['geojson'],$do_tooltip,true)
		|| in_array($options['gpx'],$do_tooltip,true)
		|| in_array($options['kml'],$do_tooltip,true)
		|| $options['geojsontooltip'])
		$text = $text.leafext_geojsontooltip_script($options);
		//
		if (in_array($options['geojson'],$do_style,true)
		|| in_array($options['gpx'],$do_style,true)
		|| in_array($options['kml'],$do_style,true)
		|| $options['geojsonstyle'])
		$text = $text.leafext_geojsonstyle_script($options);

		return $text;
	}
}
add_shortcode('hover', 'leafext_hover_function');
