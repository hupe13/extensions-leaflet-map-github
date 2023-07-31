<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_help_hidemarkers() {
  $text='<h3>Hide Markers</h3>
	<p>'.__('If a GPX track loaded with leaflet-gpx contains waypoints that you do not want to display','extensions-leaflet-map').'.</p>
	<pre><code>[leaflet-map ...]
[leaflet-gpx src="//url/to/file.gpx" ... ]
[hidemarkers]</code></pre>';

  if (is_singular() || is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
