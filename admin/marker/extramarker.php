<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_extramarker_help() {
  if (is_singular() || is_archive() ) {
    $text = '';
  } else {
     $text = '<h2>Leaflet.Extramarkers</h2>';
  }

  $text=$text.'<p>'.sprintf(__('The %s is included in the plugin %s. If you have already installed some yourself, yours will be used.','extensions-leaflet-map'),
  '<a href="https://fontawesome.com/download">Font Awesome 6</a>',
  'Extensions for Leaflet Map',
  '<a href="https://github.com/coryasilva/Leaflet.ExtraMarkers#icons">',
  '</a>').'</p>
  <h2>Shortcode</h2>';
  $text = $text.'<pre><code>&#091;leaflet-map fitbounds ....]'."\n";
  $text = $text.'&#091;leaflet-extramarker option=... ...]description[/leaflet-extramarker]'."\n";
  $text = $text.'&#091;hover]'."\n";
  $text = $text.'&#091;zoomhomemap]</code></pre>';

  // Setup map
  $shapes = array ('circle', 'square', 'star', 'penta');
  $colors = array('red', 'orange-dark', 'orange', 'yellow', 'blue-dark', 'cyan', 'purple', 'violet', 'pink', 'green-dark', 'green', 'green-light', 'black', 'white');

  // Define test cases
  $tests = array(
    "icon=fa-coffee prefix=fa iconColor=black iconRotate=270",
    "icon=fa-coffee prefix=fa iconColor=black",
    "icon=fa-cog prefix=fa extraClasses=fa-5x iconColor=#6b1d5c",
    "icon=fa-cog prefix=fa iconColor=#6b1d5c",
    "icon=fa-igloo prefix=fas",
    "icon=fa-number number=1",
    "icon=fa-number number=42 svg",
    "icon=fa-plus prefix=fa",
    "icon=fa prefix=fa-cart-shopping",
    "icon=fa prefix=fa-utensils",
    "icon=fa-spinner prefix=fa extraClasses=fa-spin svg",
    "icon=fa-spinner prefix=fa",
    "icon=fa prefix=fa-car-side extraClasses='fa-spin fa-spin-reverse'",
    "icon=fa prefix=fa-mountain-sun",
    "icon=fa prefix=fa-person-hiking",
    "icon=fa prefix=fa-person-biking",

    //bootstrap
    //"icon='glyphicon-cog' prefix='glyphicon'",
    //
    //"icon='plus sign' prefix='icon'",
    //"icon='sync' prefix='icon'",
    //"icon='add sign' prefix='icon' svg=true",
    //"icon='archive' prefix='icon'",
  );

  $text = $text. '<p><h3>'.__('All Colors','extensions-leaflet-map').'</h3></p>';
  $text = $text. do_shortcode('[leaflet-map fitbounds height=180 width=80%]'); //lat=0.1 lng=0.36 zoom=10
  $lat = 0.1;
  $lng = 0.1;
  for ($farbe = 0; $farbe < count($colors); $farbe++) {
    $color=$colors[$farbe];
    $code='markerColor='.$color.' shape=circle';
    $text = $text. do_shortcode('[leaflet-extramarker lat='.$lat.' lng='.$lng.' '.$code.' ]'.$code.'[/leaflet-extramarker]');
    $lng = $lng + 0.052;
  }
  $text = $text. do_shortcode('[hover markertooltip]');
  //$text = $text. do_shortcode('[zoomhomemap fit]');

  $text = $text. '<p><h3>'.__('All Shapes without (1) / with (2) SVG','extensions-leaflet-map').'</h3></p>';
  $text = $text. do_shortcode('[leaflet-map fitbounds height=180 width=80% max_zoom=11]'); // lat=0.1 lng=0.23 zoom=10
  $lat = 0.1;
  $lng = 0.1;
  for ($shape = 0; $shape < count($shapes); $shape++) {
    $code='shape='.$shapes[$shape].' icon=fa-number number=1';
    $text = $text. do_shortcode('[leaflet-extramarker lat='.$lat.' lng='.$lng.' '.$code.']'.$code.'[/leaflet-extramarker]');
    $lng = $lng + 0.052;
    $code='shape='.$shapes[$shape].' icon=fa-number number=2 svg';
    $text = $text. do_shortcode('[leaflet-extramarker lat='.$lat.' lng='.$lng.' '.$code.']'.$code.'[/leaflet-extramarker]');
    $lng = $lng + 0.052;
  }
  $text = $text. do_shortcode('[hover markertooltip]');
  //$text = $text. do_shortcode('[zoomhomemap fit]');

  $text = $text. '<p><h3>'.__('Some Icons','extensions-leaflet-map').'</h3></p>';
  $text = $text. do_shortcode('[leaflet-map fitbounds height=180 width=80% max_zoom=10]'); //lat=0.1 lng=0.3 zoom=10
  $anzahl = 0;
  $lat = 0.1;
  $lng = 0.1;
  $shape='circle';
  $farbe='orange';
  for ($i = 0; $i < count($tests); $i++) {
    $code='markerColor='.$farbe.' shape='.$shape.' '.$tests[$i];
    $text = $text. do_shortcode('[leaflet-extramarker lat='.$lat.' lng='.$lng.' '.$code.' ]'.$code.'[/leaflet-extramarker]');
    $lng = $lng + 0.052;
  }
  $text = $text. do_shortcode('[hover markertooltip]');

  $text = $text. '<p><a href="https://fontawesome.com/search?o=r&m=free">'.__('More icons','extensions-leaflet-map').' ...</a></p>';
  // $text = $text. '<p>'.sprintf(__('If you use %s, you must write %s also','extensions-leaflet-map'),
  // '<code>icon=fa-solid</code>',
  // '<code>extraClasses=fa</code>').'.</p>';

  $text = $text.'<p><h2>'.__('Options','extensions-leaflet-map').'</h2></p>';
  $options = leafext_extramarker_params();
  $new = array();
  $new[] = array(
    'param' => "<strong>Option</strong>",
    'default' => "<strong>".__('Default','extensions-leaflet-map').'</strong>',
    'desc' => "<strong>".__('Description','extensions-leaflet-map').'</strong>',
  );
  foreach ($options as $option) {
    $new[] = array(
      'param' => $option['param'],
      'default' => $option['default'],
      'desc' => $option['desc'],
    );
  }
  $text = $text.leafext_html_table($new);
  if (is_singular() || is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
