<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_extramarker_help() {
  if (is_singular() || is_archive() ) {
    $text = '';
  } else {
    $text = '<h2>Leaflet.Extramarkers</h2>';
  }

  $text=$text.'<p>Only the <a href="https://fontawesome.com/download">Font Awesome 6</a> is included in the plugin Extensions for Leaflet Map.
  You can install the <a href="https://github.com/coryasilva/Leaflet.ExtraMarkers#icons">other fonts</a> and use these also.</p>
  <h2>Shortcode</h2>';
  $text = $text.'<pre><code>&#91;leaflet-map ....]'."\n";
  $text = $text.'&#91;extramarker option=... ...]description[/extramarker]'."\n";
  $text = $text.'&#91;zoomhomemap fit]</code></pre>';

  // Setup map
  $shapes = array ('circle', 'square', 'star', 'penta');
  $colors = array('red', 'orange-dark', 'orange', 'yellow', 'blue-dark', 'cyan', 'purple', 'violet', 'pink', 'green-dark', 'green', 'white', 'black');

  // Define test cases
  $tests = array(
    "icon='fa-coffee' prefix='fa' iconColor='black' iconRotate=270",
    "icon='fa-coffee' prefix='fa' iconColor='black'",
    "icon='fa-cog' prefix='fa' extraClasses='fa-5x' iconColor='#6b1d5c'",
    "icon='fa-cog' prefix='fa' iconColor='#6b1d5c'",
    "icon='fa-igloo' prefix='fas'",
    "icon='fa-number' number='1'",
    "icon='fa-number' number='42' svg=true",
    "icon='fa-spinner' prefix='fa' extraClasses='fa-spin' svg=true",
    "icon='fa-spinner' prefix='fa' extraClasses='fa-spin'",
    "icon='fa-spinner' prefix='fa'",
    "icon='fa-plus' prefix='fa'",
    //bootstrap
    //"icon='glyphicon-cog' prefix='glyphicon'",
    //
    //"icon='plus sign' prefix='icon'",
    //"icon='sync' prefix='icon'",
    //"icon='add sign' prefix='icon' svg=true",
    //"icon='archive' prefix='icon'",
  );

  $text = $text. '<p><h3>All Colors</h3></p>';
  $text = $text. do_shortcode('[leaflet-map height=100 width=70% lat=0.1 lng=0.36 zoom=10]');
  $lat = 0.1;
  $lng = 0.1;
  for ($farbe = 0; $farbe < count($colors); $farbe++) {
    $color=$colors[$farbe];
    if ($color == "white") {
      $code='markerColor='.$color.' iconColor=black shape=circle icon=fa-number number=1';
    } else {
      $code='markerColor='.$color.' shape=circle icon=fa-number number=1';
    }
    $text = $text. do_shortcode('[extramarker lat='.$lat.' lng='.$lng.' '.$code.' ]'.$code.'[/extramarker]');
    $lng = $lng + 0.052;
  }
  //$text = $text. do_shortcode('[zoomhomemap fit]');

  $text = $text. '<p><h3>All Shapes without (1) / with (2) SVG</h3></p>';
  $text = $text. do_shortcode('[leaflet-map height=100 width=70%  lat=0.1 lng=0.23 zoom=10]');
  $lat = 0.1;
  $lng = 0.1;
  for ($shape = 0; $shape < count($shapes); $shape++) {
    $code='shape='.$shapes[$shape].' icon=fa-number number=1';
    $text = $text. do_shortcode('[extramarker lat='.$lat.' lng='.$lng.' '.$code.']'.$code.'[/extramarker]');
    $lng = $lng + 0.052;
    $code='shape='.$shapes[$shape].' icon=fa-number number=2 svg';
    $text = $text. do_shortcode('[extramarker lat='.$lat.' lng='.$lng.' '.$code.']'.$code.'[/extramarker]');
    $lng = $lng + 0.052;
  }
  //$text = $text. do_shortcode('[zoomhomemap fit]');

  $text = $text. '<p><h3>Some Icons</h3></p>';
  $text = $text. do_shortcode('[leaflet-map height=100 width=70%  lat=0.1 lng=0.3 zoom=10]');
  $anzahl = 0;
  $lat = 0.1;
  $lng = 0.1;
  $shape='circle';
  $farbe='orange';
  for ($i = 0; $i < count($tests); $i++) {
    $code='markerColor='.$farbe.' shape='.$shape.' '.$tests[$i];
    $text = $text. do_shortcode('[extramarker lat='.$lat.' lng='.$lng.' '.$code.' ]'.$code.'[/extramarker]');
    $lng = $lng + 0.052;
  }
  //$text = $text. do_shortcode('[zoomhomemap fit]');

  $text = $text.'<h2>Options</h2>';
  $options = leafext_extramarker_params();
  $new = array();
  $new[] = array(
    'param' => "<strong>Option</strong>",
    'default' => "<strong>Default</strong>",
    'desc' => "<strong>Description</strong>",
  );
  foreach ($options as $option) {
    $new[] = array(
      'param' => $option['param'],
      'default' => $option['default'],
      'desc' => $option['desc'],
    );
  }
  $text = $text.'<div style="width:70%;">'.leafext_html_table($new).'</div>';
  if (is_singular() || is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
