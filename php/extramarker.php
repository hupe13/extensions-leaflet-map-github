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
      'filter' => '',
    ),
    array(
      'param' => 'lng',
      'desc' => __("Longitude","extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '',
      'filter' => '',
    ),
    // extraClasses 	Additional classes in the created <i> tag 	'' 	fa-rotate90 myclass; space delimited classes to add
    array(
      'param' => 'extraClasses',
      'desc' =>  __('Additional classes in the created &lt;i&gt; tag, Possible values: fa-rotate90 myclass; space delimited classes to add',
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '',
      'filter' => '',
    ),
    // icon 	Name of the icon with prefix 	'' 	fa-coffee (see icon library's documentation)
    array(
      'param' => 'icon',
      'desc' => __("Name of the icon with prefix, Possible values: fa-coffee (see icon library's documentation)",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '',
      'filter' => '',
    ),
    // iconColor 	Color of the icon 	'white' 	'white', 'black' or css code (hex, rgba etc)
    array(
      'param' => 'iconColor',
      'desc' => __("Color of the icon, Possible values: 'white', 'black' or css code (hex, rgba etc)",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => 'white',
      'filter' => '',
    ),
    // iconRotate 	Rotates the icon with css transformations 	0 	numeric degrees
    array(
      'param' => 'iconRotate',
      'desc' => __("Rotates the icon with css transformations, Possible values: numeric degrees",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '0',
      'filter' => 'FILTER_SANITIZE_NUMBER_INT',
    ),
    // innerHTML 	Custom HTML code 	'' 	<svg>, images, or other HTML; a truthy assignment will override the default html icon creation behavior
    array(
      'param' => 'innerHTML',
      'desc' => __("Custom HTML code, Possible values: &lt;svg&gt;, images, or other HTML; a truthy assignment will override the default html icon creation behavior",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '',
      'filter' => '',
    ),
    // markerColor 	Color of the marker (css class) 	'blue' 	'red', 'orange-dark', 'orange', 'yellow', 'blue-dark', 'cyan', 'purple', 'violet', 'pink', 'green-dark', 'green', 'green-light', 'black', 'white', or color hex code if svg is true
    array(
      'param' => 'markerColor',
      'desc' => __("Color of the marker (css class), Possible values: 'red', 'orange-dark', 'orange', 'yellow', 'blue-dark', 'cyan', 'purple', 'violet', 'pink', 'green-dark', 'green', 'green-light', 'black', 'white', or color hex code if svg is true",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => 'red',
      'filter' => '',
    ),
    // number 	Instead of an icon, define a plain text 	'' 	'1' or 'A', must set icon: 'fa-number'
    array(
      'param' => 'number',
      'desc' => __("Instead of an icon, define a plain text, Possible values: '1' or 'A', must set icon: 'fa-number'",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '',
      'filter' => '',
    ),
    // prefix 	The icon library's base class 	'glyphicon' 	fa (see icon library's documentation)
    array(
      'param' => 'prefix',
      'desc' => __("The icon library's base class, Possible values: fa (see icon library's documentation)",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => 'glyphicon',
      'filter' => '',
    ),
    // shape 	Shape of the marker (css class) 	'circle' 	'circle', 'square', 'star', or 'penta'
    array(
      'param' => 'shape',
      'desc' => __("Shape of the marker (css class), Possible values: 'circle', 'square', 'star', or 'penta'",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => 'circle',
      'filter' => '',
    ),
    // svg 	Use SVG version 	false 	true or false
    array(
      'param' => 'svg',
      'desc' => __("Use SVG version, Possible values: true or false",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => 'false',
      'filter' => 'FILTER_VALIDATE_BOOLEAN',
    ),
    // tooltipAnchor
    // array(
    //   'param' => 'tooltipAnchor',
    //   'desc' => __('The coordinates of the point from which tooltips will "open", relative to the icon anchor (<code>[17,42]</code>).',
    //   "extensions-leaflet-map"),
    //   //'shortdesc' => '',
    //   'default' => '12,-24',
    //   'filter' => 'latlon',
    // ),
    // popupAnchor
    // array(
    //   'param' => 'popupAnchor',
    //   'desc' => __('Set the anchor position of the popup: e.g. "40,60" for 40px left 60px top',
    //   "extensions-leaflet-map"),
    //   //'shortdesc' => '',
    //   'default' => '1,-32',
    //   'filter' => 'latlon',
    // ),
    array(
      'param' => 'title',
      'desc' => __("Add a hover-over message to your marker (different than popup)",
      "extensions-leaflet-map"),
      //'shortdesc' => '',
      'default' => '',
      'filter' => '',
    ),
  );
  return $params;
}

function leafext_extramarker_defaults() {
  $defaults=array();
  $params = leafext_extramarker_params();
  foreach($params as $param) {
    $defaults[$param['param']] = $param['default'];
  }
  return $defaults;
}

function leafext_extramarker_filter() {
  $filters=array();
  $params = leafext_extramarker_params();
  foreach($params as $param) {
    $filters[$param['param']] = $param['filter'];
  }
  return $filters;
}

//Shortcode: [leaflet-extramarker]
function leafext_extramarker_function( $atts,$content,$shortcode) {
  $text = leafext_should_interpret_shortcode($shortcode,$atts);
  if ( $text != "" ) {
    return $text;
  } else {
    leafext_enqueue_extramarker ();
    if (isset($atts['lat']) && isset($atts['lng'])) {
      $latlng = "lat=".$atts['lat']." lng=".$atts['lng'];
    } else {
      $latlng = "";
    }
    if (isset($atts['title'])) { $title="title='".$atts['title']."'"; } else { $title = ""; }
    $marker_shortcode = "[leaflet-marker ".$latlng." ".$title."]".$content."[/leaflet-marker]";
    $text = do_shortcode($marker_shortcode);

    $text = \JShrink\Minifier::minify($text);
    $atts1 = leafext_case(array_keys(leafext_extramarker_defaults()),leafext_clear_params($atts));
    $options = shortcode_atts(leafext_extramarker_defaults(), $atts1);

    //var_dump($options);//wp_die();
    $icon = 'var extramarker = L.ExtraMarkers.icon({'.leafext_extramarkers_params ($options).'tooltipAnchor:[12,-24]});';
    //var_dump($icon);
    //$text = str_replace(",marker_options","",$text);
    $text = str_replace("marker.addTo(group);","marker.addTo(group);".$icon."marker.setIcon(extramarker);",$text);
    $text = \JShrink\Minifier::minify($text);
    //var_dump($text);

    // // Creates a red marker with the coffee icon
    // var redMarker = L.ExtraMarkers.icon({
    //   icon: 'fa-coffee',
    //   markerColor: 'red',
    //   shape: 'square',
    //   prefix: 'fa'
    // });
    //
    // L.marker([51.941196,4.512291], {icon: redMarker}).addTo(map);
    // marker.setIcon(redMarker);

    return $text;
  }
}
add_shortcode('extramarker', 'leafext_extramarker_function' );
add_shortcode('leaflet-extramarker', 'leafext_extramarker_function' );

function leafext_extramarkers_params ($params) {
  $filters = leafext_extramarker_filter();
  //var_dump($params); //wp_die();
  $text = "";

  foreach ($params as $k => $v) {
    if ($k == "lat" || $k == "lng") continue;
    if ($v == "") continue;
    $text = $text. "$k: ";
    switch ($filters[$k]) {
      case "FILTER_VALIDATE_BOOLEAN":
      $value = filter_var($v, FILTER_VALIDATE_BOOLEAN) ? 'true':'false'; break;
      case "FILTER_SANITIZE_NUMBER_INT":
      $value = filter_var($v, FILTER_SANITIZE_NUMBER_INT); break;
      case "latlon":
      $value = "[".$v."]"; break;
      default:
      $value = '"'.$v.'"';
    }
    $text = $text.$value;
    $text = $text.",\n";
  }
  //var_dump($text); wp_die();
  return $text;
}
