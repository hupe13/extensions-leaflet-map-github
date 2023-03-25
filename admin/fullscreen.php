<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_help_fullscreen() {
	$text='<h3>'.__('Fullscreen','extensions-leaflet-map').'</h3>
	<img src="'.LEAFEXT_PLUGIN_PICTS.'fullscreenon.png" alt="fullscreen-on">
	<img src="'.LEAFEXT_PLUGIN_PICTS.'fullscreenoff.png" alt="fullscreen-off">
	<pre><code>[fullscreen]</code></pre>';

  if (is_singular() || is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
