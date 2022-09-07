<?php
/**
* Functions for extramarker shortcode
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Parameter and Values
function leafext_extramarker_params() {
  $params = array(
    array(
      'param' => 'lat',
      'desc' =>  __('Latitude',"extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '',
    ),
    array(
      'param' => 'lng',
      'desc' => __("Longitude","extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '',
    ),
    // extraClasses 	Additional classes in the created <i> tag 	'' 	fa-rotate90 myclass; space delimited classes to add
    array(
      'param' => 'extraClasses',
      'desc' =>  __('Additional classes in the created &lt;i&gt; tag, Possible values: fa-rotate90 myclass; space delimited classes to add',
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '',
    ),
    // icon 	Name of the icon with prefix 	'' 	fa-coffee (see icon library's documentation)
    array(
      'param' => 'icon',
      'desc' => __("Name of the icon with prefix, Possible values: fa-coffee (see icon library's documentation)",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '',
    ),
    // iconColor 	Color of the icon 	'white' 	'white', 'black' or css code (hex, rgba etc)
    array(
      'param' => 'iconColor',
      'desc' => __("Color of the icon, Possible values: 	'white', 'black' or css code (hex, rgba etc)",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => 'white',
    ),
    // iconRotate 	Rotates the icon with css transformations 	0 	numeric degrees
    array(
      'param' => 'iconRotate',
      'desc' => __("Rotates the icon with css transformations, Possible values: numeric degrees",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '0',
    ),
    // innerHTML 	Custom HTML code 	'' 	<svg>, images, or other HTML; a truthy assignment will override the default html icon creation behavior
    array(
      'param' => 'innerHTML',
      'desc' => __("Custom HTML code, Possible values: &lt;svg&gt;, images, or other HTML; a truthy assignment will override the default html icon creation behavior",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '',
    ),
    // markerColor 	Color of the marker (css class) 	'blue' 	'red', 'orange-dark', 'orange', 'yellow', 'blue-dark', 'cyan', 'purple', 'violet', 'pink', 'green-dark', 'green', 'green-light', 'black', 'white', or color hex code if svg is true
    array(
      'param' => 'markerColor',
      'desc' => __("Color of the marker (css class), Possible values: 'red', 'orange-dark', 'orange', 'yellow', 'blue-dark', 'cyan', 'purple', 'violet', 'pink', 'green-dark', 'green', 'green-light', 'black', 'white', or color hex code if svg is true",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => 'blue',
    ),
    // number 	Instead of an icon, define a plain text 	'' 	'1' or 'A', must set icon: 'fa-number'
    array(
      'param' => 'number',
      'desc' => __("Instead of an icon, define a plain text, Possible values: '1' or 'A', must set icon: 'fa-number'",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '',
    ),
    // prefix 	The icon library's base class 	'glyphicon' 	fa (see icon library's documentation)
    array(
      'param' => 'prefix',
      'desc' => __("The icon library's base class, Possible values: fa (see icon library's documentation)",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => 'glyphicon',
    ),
    // shape 	Shape of the marker (css class) 	'circle' 	'circle', 'square', 'star', or 'penta'
    array(
      'param' => 'shape',
      'desc' => __("Shape of the marker (css class), Possible values: 'circle' 	'circle', 'square', 'star', or 'penta'",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => 'circle',
    ),
    // svg 	Use SVG version 	false 	true or false
    array(
      'param' => 'svg',
      'desc' => __("Use SVG version, Possible values: true or false",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '0',
    ),
  );
  return $params;
}

//Shortcode: [extramarker]

function leafext_extramarker_script($params,$content){
  $text = '
  <script>
  window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
  window.WPLeafletMapPlugin.push(function () {
    var map = window.WPLeafletMapPlugin.getCurrentMap();
    var group = window.WPLeafletMapPlugin.getCurrentGroup();
    var icon = L.marker(
      ['.$params['lat'].','.$params['lng'].'],
      {icon: L.ExtraMarkers.icon(
        {'.leafext_extramarkers_params ($params).'}
      )}
    );
    icon.bindPopup("'.$content.'");
    icon.addTo(group);
    window.WPLeafletMapPlugin.markers.push( icon );
  });
  </script>';
  //$text = \JShrink\Minifier::minify($text);
  return "\n".$text."\n";
}

function leafext_extramarker_settings() {
  $defaults=array();
	$params = leafext_extramarker_params();
	foreach($params as $param) {
		$defaults[$param['param']] = $param['default'];
	}
	$options = shortcode_atts($defaults, get_option('leafext_eleparams'));
	return $options;
}

function leafext_extramarker_function( $atts, $content="" ){
  leafext_enqueue_extramarker ();
  $options=leafext_case(array_keys(leafext_extramarker_settings()),leafext_clear_params($atts));
  return leafext_extramarker_script($options,$content);
}
add_shortcode('extramarker', 'leafext_extramarker_function' );

function leafext_extramarkers_params ($params) {
	///var_dump($params); wp_die();
	$text = "";
	foreach ($params as $k => $v) {
		//var_dump($v,gettype($v));
		$text = $text. "$k: ";
		switch (gettype($v)) {
			case "string":
			switch ($v) {
				// case "false":
				// case "0": $value = "false"; break;
				// case "true":
				// case "1": $value = "true"; break;
				case strpos($v,"{") !== false:
				case strpos($v,"}") !== false:
				case is_numeric($v):
				$value = $v; break;
				default:
				$value = '"'.$v.'"';
			}
			break;
			case "boolean":
			$value = $v ? "true" : "false"; break;
			case "integer":
			case "double":
			$value = $v; break;
			default: var_dump($k, $v, gettype($v)); wp_die("Type");
		}
		$text = $text.$value;
		$text = $text.",\n";
	}
	//var_dump($text); wp_die();
	return $text;
}

add_filter('pre_do_shortcode_tag', function ( $output, $shortcode, $attr) {
  // $m ist ein array:
  //0 Ganzer Shortcode: [leaflet-marker svg background="#777" iconClass="dashicons dashicons-star-filled" color="gold"]from Shortcode Helper Page[/leaflet-marker]
  //1
  //2 leaflet-marker
  //3 parameter: svg background="#777" iconClass="dashicons dashicons-star-filled" color="gold"
  //4
  //5 content: from Shortcode Helper Page
  //6
  if ( 'leaflet-marker' == $shortcode ) {
    if (isset($attr["iconclass"]) &&  str_contains($attr["iconclass"], "dashicons")) {
      wp_enqueue_style( 'dashicons' );
    }
  }
  return $output;
}, 10, 3);
