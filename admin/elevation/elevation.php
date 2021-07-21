<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

$text = '
<img src="'.LEAFEXT_PLUGIN_PICTS.'elevation.png">
<h2>Shortcode</h2>
<pre><code>[leaflet-map ....]
// at least one marker if you use it with zoomehomemap
[leaflet-marker lat=... lng=... ...]Start[/leaflet-marker]
[elevation gpx="url_gpx_file" option1=value1 option2 !option3 ...]
</code></pre>';
$text = $text . __('For options see','extensions-leaflet-map');
$text = $text . ' <a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevationoptions"> ';
$text = $text . __('Elevation Chart Options','extensions-leaflet-map');
$text = $text . '</a>.';

echo $text;
