<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();
function leafext_directory_help_text() {
  $text='<h3>'.__('Tracks from all files in a directory','extensions-leaflet-map').'</h3>'."\n";
  $text=$text.'<pre><code>[leaflet-map fitbounds]'."\n";
  $text=$text.'[leaflet-dir url="..." src="..." type="..." cmd="..." start]'."\n";
  $text=$text.'// if cmd = track'."\n";
  $text=$text.'[multielevation]</code></pre>';

  $text=$text.'<ul style="list-style: disc;">
  <li style="margin-left: 1.5em;"> url - '.__('url to directory, default: URL from upload directory.','extensions-leaflet-map').'
  <li style="margin-left: 1.5em;"> src - '.__('(relative) path to directory, accessible both from path and from url','extensions-leaflet-map').'
  <li style="margin-left: 1.5em;"> cmd - "track" '.__('(default) or','extensions-leaflet-map').' "leaflet"
  <li style="margin-left: 1.5em;"> type - '.sprintf(__('default is %s. For %s it is ignored. For %s a list of %s.','extensions-leaflet-map'),'"gpx"','"track"','"leaflet"','gpx,kml,geojson,json').'
  <li style="margin-left: 1.5em;"> start - (optional). '.sprintf(__('If %s and a file is a gpx file, display start point and cluster','extensions-leaflet-map'),'"leaflet"').'.
  </ul>
  ';
  return $text;
}
