<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_help_tiles() {
  $text='
  <h2>'.__('About Tile servers','extensions-leaflet-map').'</h2><p>';
  if (!(is_singular() || is_archive() )) { //backend
    $text=$text.sprintf(__('The default Map Tiles are defined in the %s Settings%s.','extensions-leaflet-map'),
    '<a href="'.get_admin_url().'admin.php?page=leaflet-map">Leaflet Map ',
    '</a>');
  } else { //frontend
    $text=$text.__('The default Map Tiles are defined in the Leaflet Map Settings.','extensions-leaflet-map');
  }
  $text=$text.' ';
  if (!(is_singular() || is_archive() )) { //backend
    $tilesproviders = '?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=tilesproviders';
    $tileswitch = '?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=tileswitch';
  } else { // for my frontend leafext.de
    $tilesproviders = site_url().'/tiles/providers/';
    $tileswitch = site_url().'/tiles/switch/';
  }
  $text=$text.sprintf(__('Additionally you can use some predefined Tile Providers with %s or you can define %syour Tile Servers%s.','extensions-leaflet-map'),
  '<a href="'.$tilesproviders.'">Leaflet-providers</a>',
  '<a href="'.$tileswitch.'">',
  '</a>');
  $text=$text.'</p>';

  $text=$text.'
  <figure class="wp-block-table aligncenter is-style-stripes">
  <table class="form-table" border=1">
  <tr><th style="text-align:center">Name</th><th style="text-align:center">Option</th><th style="text-align:center">'.__('Current Settings','extensions-leaflet-map').'</th></tr>
  <tr><td>'.__('Tile Id', 'leaflet-map').'</td><td>mapid</td><td>'.get_option('leaflet_mapid').'</td></tr>
  <tr><td>'.__('Map Tile URL', 'leaflet-map').'</td><td>tileurl</td><td>'.get_option('leaflet_map_tile_url').'</td></tr>
  <tr><td>'.__('Map Tile URL Subdomains', 'leaflet-map').'</td><td>subdomains</td><td>'.get_option('leaflet_map_tile_url_subdomains').'</td></tr>
  <tr><td>'.__('Default Attribution', 'leaflet-map').'</td><td></td><td>'.get_option('leaflet_default_attribution').'</td></tr>
  <tr><td>'.__('Default Min Zoom', 'leaflet-map').'</td><td>min_zoom</td><td>'.get_option('leaflet_default_min_zoom').'</td></tr>
  <tr><td>'.__('Default Max Zoom', 'leaflet-map').'</td><td>max_zoom</td><td>'.get_option('leaflet_default_max_zoom').'</td></tr>
  </table></figure>';

  $text = $text.'<h2>Shortcode</h2>';
	$text = $text.'<pre><code>[leaflet-map mapid="..."]
[layerswitch tiles="mapid1,mapid2,..." providers="provider1,provider2,..." opacity="mapid1,provider1,..."]
</code></pre>';
	//$text = $text.__('','extensions-leaflet-map');
$text = $text.'
  <h3>Parameter</h3>
  <ul style="list-style: disc;">
  <li style="margin-left: 1.5em;"> <code>[leaflet-map]</code>
  <ul style="list-style: disc;">
  <li style="margin-left: 1.5em;"> '.__('see Leaflet Map documentation','extensions-leaflet-map').'
  <li style="margin-left: 1.5em;"> '.__('optional: mapid - This appears in the switching control.','extensions-leaflet-map').'
  </ul>
   <li style="margin-left: 1.5em;"> <code>[layerswitch]</code>
   <ul style="list-style: disc;">
   <li style="margin-left: 1.5em;"> '.sprintf(__('without any parameter: All defined %stile servers%s are used.','extensions-leaflet-map'),
   '<a href="'.$tileswitch.'">',
   '</a>').
   '<li style="margin-left: 1.5em;"> '.sprintf(__('with %s and/or %s you can specify which tiles should be used.','extensions-leaflet-map'),
   '<a href="'.$tileswitch.'"><code>tiles</code></a>',
   '<a href="'.$tilesproviders.'"><code>providers</code></a>').
   '<li style="margin-left: 1.5em;"> '.sprintf(__('with %s you can specify the mapids and/or providers for which opacity should be regulated.','extensions-leaflet-map'),'<code>opacity</code>').
   '</ul>
   </ul>
  ';

  if (is_singular() || is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}

leafext_help_tiles();
