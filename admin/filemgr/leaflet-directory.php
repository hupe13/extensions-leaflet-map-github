<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();
function leafext_directory_help_text() {
  $text='<h3>Tracks from all files in a directory</h3>'."\n";
  $text=$text.'<pre><code>[leaflet-map fitbounds]'."\n";
  $text=$text.'[leaflet-dir url="..." src="..." type="..." cmd="..." start]'."\n";
  $text=$text.'// if cmd = track'."\n";
  $text=$text.'[multielevation]</code></pre>';

  $text=$text.'<ul style="list-style: disc;">
  <li style="margin-left: 1.5em;"> url - url to directory, default: URL from upload directory.
  <li style="margin-left: 1.5em;"> src - (relative) path to directory, accessible both from path and from url
  <li style="margin-left: 1.5em;"> cmd - "track" (default) or "leaflet"
  <li style="margin-left: 1.5em;"> type - default is "gpx". For "track" it is ignored. For "leaflet" a list of gpx,kml,geojson,json.
  <li style="margin-left: 1.5em;"> start - (optional) if "leaflet" and a file is a gpx file, display start point and cluster
  </ul>
  ';
  return $text;
}
