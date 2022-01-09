<?php
echo '
<h2>'.__('About Tile servers','extensions-leaflet-map').'</h2>
<p>'.sprintf(__('The default Map Tiles are defined in the %s Settings%s.
Additionally you can use some predefined Tile Providers with %s
or you can define %syour Tile Services%s.','extensions-leaflet-map'),
'<a href="'.get_admin_url().'admin.php?page=leaflet-map">Leaflet Map ',
'</a>',
'<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=tilesproviders">Leaflet-providers</a>',
'<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=tileswitch">',
'</a>').'</p>
';

echo '
<table border=1>
<tr><th>Name</th><th>Option</th><th>'.__('Your Setting','extensions-leaflet-map').'</th></tr>
<tr><td>'.__('Tile Id', 'leaflet-map').'</td><td>mapid</td><td>'.get_option('leaflet_mapid').'</td></tr>
<tr><td>'.__('Map Tile URL', 'leaflet-map').'</td><td>tileurl</td><td>'.get_option('leaflet_map_tile_url').'</td></tr>
<tr><td>'.__('Map Tile URL Subdomains', 'leaflet-map').'</td><td>subdomains</td><td>'.get_option('leaflet_map_tile_url_subdomains').'</td></tr>
<tr><td>'.__('Default Attribution', 'leaflet-map').'</td><td></td><td>'.get_option('leaflet_default_attribution').'</td></tr>
<tr><td>'.__('Default Min Zoom', 'leaflet-map').'</td><td>min_zoom</td><td>'.get_option('leaflet_default_min_zoom').'</td></tr>
<tr><td>'.__('Default Max Zoom', 'leaflet-map').'</td><td>max_zoom</td><td>'.get_option('leaflet_default_max_zoom').'</td></tr>
</table>
<h3>'.__('A map with your settings','extensions-leaflet-map').'</h3>';
echo do_shortcode('[leaflet-map height=300 width=300]');
