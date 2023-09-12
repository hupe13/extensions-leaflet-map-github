<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_help_tiles() {
  if (!(is_singular() || is_archive() )) { //backend
    $tilesproviders = '?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=tilesproviders';
    $tileswitch = '?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=tileswitch';
    $text='<h2>'.__('About Tile servers','extensions-leaflet-map').'</h2><p>';
    $text=$text.sprintf(__('The default Map Tiles are defined in the %s Settings%s.','extensions-leaflet-map'),
    '<a href="'.get_admin_url().'admin.php?page=leaflet-map">Leaflet Map',
    '</a>');
  } else { // for my frontend leafext.de
    if (strpos($_SERVER["REQUEST_URI"], "/en/") !==  false) {
      $lang = '/en';
    } else {
      $lang = '';
    }
    $tilesproviders = $lang.'/doku/tilesproviders/';
    $tileswitch = $lang.'/doku/tileswitch/';
    $text='<p>'.__('The default Map Tiles are defined in the Leaflet Map Settings.','extensions-leaflet-map');
  }
  $text=$text.' '.sprintf(__('Pay attention to the setting for %s, it depends on the used Map Tiles, see %shere%s.',
  'extensions-leaflet-map'),
  "max_zoom",
  '<a href="https://github.com/leaflet-extras/leaflet-providers/blob/master/leaflet-providers.js">',
  '</a>').'</p>';
  $text = $text.'<h3>'.__('Your Leaflet Map Settings','extensions-leaflet-map').'</h3>';
  if (is_singular() || is_archive()){
		$text=$text.'<style>td,th { border:1px solid #195b7a !important; }</style>';
	}
  $text=$text.'
  <figure class="wp-block-table aligncenter is-style-stripes">
  <table class="form-table" border="1">
  <tr><th style="text-align:center">Name</th><th style="text-align:center">Option</th><th style="text-align:center">'.__('Current Settings','extensions-leaflet-map').'</th></tr>
  <tr><td>'.__('Tile Id', 'leaflet-map').'</td><td>mapid</td><td>'.get_option('leaflet_mapid').'</td></tr>
  <tr><td>'.__('Map Tile URL', 'leaflet-map').'</td><td>tileurl</td><td>'.get_option('leaflet_map_tile_url').'</td></tr>
  <tr><td>'.__('Map Tile URL Subdomains', 'leaflet-map').'</td><td>subdomains</td><td>'.get_option('leaflet_map_tile_url_subdomains').'</td></tr>
  <tr><td>'.__('Default Attribution', 'leaflet-map').'</td><td></td><td>'.get_option('leaflet_default_attribution').'</td></tr>
  <tr><td>'.__('Default Min Zoom', 'leaflet-map').'</td><td>min_zoom</td><td>'.get_option('leaflet_default_min_zoom').'</td></tr>
  <tr><td>'.__('Default Max Zoom', 'leaflet-map').'</td><td>max_zoom</td><td>'.get_option('leaflet_default_max_zoom').'</td></tr>
  </table></figure>';

  $text=$text.'<p>'.sprintf(__('Additionally you can use some predefined Tile Providers with %s or you can define %syour Tile Servers%s.','extensions-leaflet-map'),
  '<a href="'.$tilesproviders.'">Leaflet-providers</a>',
  '<a href="'.$tileswitch.'">',
  '</a>').'</p>';

  $text = $text.'<h2>Shortcode</h2>';
	$text = $text.'<pre><code>&#091;leaflet-map mapid="..."]
&#091;layerswitch tiles="mapid1,mapid2,..." mapids="mapid3,mapid4,..." providers="provider1,provider2,..." opacity="mapid1,provider1,..."]
</code></pre>';
$text = $text.'
  <h3>Parameter</h3>
  <ul>
  <li> <code>&#091;leaflet-map]</code>
  <ul>
  <li> '.__('see Leaflet Map documentation','extensions-leaflet-map').'</li>
  <li> '.__('optional: <code>mapid</code> - This appears in the switching control.','extensions-leaflet-map').'</li>
  </ul></li>
   <li> <code>&#091;layerswitch]</code>
   <ul>
   <li> '.sprintf(__('without any parameter: All your defined %stile servers%s are used.','extensions-leaflet-map'),
   '<a href="'.$tileswitch.'">',
   '</a>').'</li>'.
   '<li> <code>tiles</code>: '.sprintf(__('a comma separated list of any your defined %stile servers%s','extensions-leaflet-map'),
   '<a href="'.$tileswitch.'">',
   '</a>.').
   '</li>
   <li> <code>providers</code>: '.sprintf(__('a comma separated list of any %sproviders%s','extensions-leaflet-map'),
   '<a href="'.$tilesproviders.'">',
   '</a>.').
   '</li><li>'.
   __('optional: <code>mapids</code> - a comma separated list with shortnames for providers. These appear in the switching control.',
   'extensions-leaflet-map').' '.sprintf(
     __('The number of %s and %s must match.','extensions-leaflet-map'),"<code>mapids</code>","<code>providers</code>").'
   </li><li> '.sprintf(__('with %s you can specify the mapids and/or providers for which opacity should be regulated.','extensions-leaflet-map'),'<code>opacity</code>').
   '</li></ul></li>
   </ul>
  ';

  if (is_singular() || is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
//leafext_help_tiles();
