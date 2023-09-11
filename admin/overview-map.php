<?php
function leafext_overviewmap_help(){
  if (is_singular() || is_archive() ) {
    $text = '';
  } else {
    $text = '<h2>'.__('Overview Map',"extensions-leaflet-map").'</h2>';
  }

  $text=$text.'<p>'.__('Generates an overview map with geo positions provided in the pages and posts.','extensions-leaflet-map').'</p>';
  $text=$text.'<h3>'.__('Preparation','extensions-leaflet-map').'</h3>';

  $text = $text.'<ul><li>'.__('Create for every desired page or post the below mentioned customs fields.','extensions-leaflet-map').'</li>';
  $text = $text.'<li>'.__('Optional: Create a featured image for page or post.','extensions-leaflet-map').'</li>';
  $text = $text.'<li>'.__('Optional: Place the page or post in one or more categories.','extensions-leaflet-map').'</li></ul>';

  $text = $text.'<h3>'.__('Custom fields','extensions-leaflet-map').'</h3>';

  $text = $text.'<p>'.__('The custom fields names are as you like. You can these specify in overviewmap options.','extensions-leaflet-map').'</p>';

  $options=leafext_overviewmap_settings();
  $new = array();
  $new[] = array(
    'desc' => "<strong>".__('Custom field','extensions-leaflet-map').'</strong>',
    'content' => "<strong>".__('Content','extensions-leaflet-map').'</strong>',
    'default' => "<strong>".__('Default name','extensions-leaflet-map').'</strong>',
    'param' => "<strong>".__('Option to change','extensions-leaflet-map').'</strong>',
  );

  foreach ($options as $option) {
    $new[] = array(
      'desc' => $option['desc'],
      'content' => $option['content'],
      'default' => $option['default'],
      'param' => $option['param'],
    );
  }
  $text=$text.leafext_html_table($new);


  $text=$text.'<h3>Shortcode</h3>
  <pre><code>&#091;leaflet-map fitbounds]
&#091;overviewmap latlngs=... icons=... options= ...]
// optional
&#091;hover class=leafext-overview-tooltip]
</code></pre>';

  //$text = $text.'<p>'.__('You can use in shortcode <code>overviewmap</code> any of the options below.','extensions-leaflet-map').'</p>';
  $text=$text.'<h3>'.__('Options for overviewmap','extensions-leaflet-map').'</h3>';

  $options=leafext_overviewmap_params();
  $new = array();
  $new[] = array(
    'param' => "<strong>Option</strong>",
    'desc' => "<strong>".__('Description','extensions-leaflet-map').'</strong>',
    // 'content' => "<strong>".__('Content','extensions-leaflet-map').'</strong>',
    'default' => "<strong>".__('Default','extensions-leaflet-map').'</strong>',
    'values' => "<strong>".__('Possible values','extensions-leaflet-map').'</strong>',
  );

  foreach ($options as $option) {
    $new[] = array(
      'param' => $option['param'],
      'desc' => $option['desc'],
      // 'content' => $option['content'],
      'default' => is_string($option['default']) ? $option['default'] : (($option['default'] === false) ? 'false' : 'true'),
      'values' => $option['values'],
    );
  }
  $text=$text.leafext_html_table($new);

  $text=$text.'<h3>'.__('Options for marker','extensions-leaflet-map').'</h3>';
  $text = $text.'<p>'.__('You can use these in the custom field for the icon and in shortcode <code>overviewmap</code>.','extensions-leaflet-map').'</p>';

  $text=$text.'<h4>leafext_marker</h4>';
  $text=$text.'<p>'.implode(', ',leafext_marker_options()).'</p>';
  $text=$text.'<p>'.__('<b>iconUrl</b> is for icon filename required. The icons directory must be the same as from <b>iconUrl</b>.','extensions-leaflet-map').'</p>';

  $text=$text.'<h4>leafext_extramarker</h4>';
  $text=$text.'<p>'.implode(', ',leafext_extramarker_options()).'</p>';

  $text=$text.'<p>'.sprintf(__('See %sexamples%s.','extensions-leaflet-map'),'<a href="https://leafext.de/extra/category/overviewmap/">','</a>').'</p>';

  if (is_singular() || is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
