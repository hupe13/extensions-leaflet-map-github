<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

	$text = '<h4>'.
	__('Found an issue?','extensions-leaflet-map').'</h4>
    <p>'.
	__('Post it to','extensions-leaflet-map').' <b>WordPress Support</b>: <a href="https://wordpress.org/support/plugin/extensions-leaflet-map/" target="_blank">Leaflet Map Extensions</a></p>';
	//		include 'help/elevation.php';
	$text=$text.'<h4 id="display-a-track-with-elevation-profile">'.__('Display a track with elevation profile','extensions-leaflet-map').'</h4>
	<img src="'.LEAFEXT_PLUGIN_PICTS.'elevation.png">
	<p>
	<a href="admin.php?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevation">'.
	__('Help and Settings','extensions-leaflet-map').'</a>
	</p>';
	//
	//include 'help/layerswitch.php';
	$text=$text.'<h4 id="switching-tile-layers">'.__('Switching Tile Layers','extensions-leaflet-map').'</h4>
	<img src="'.LEAFEXT_PLUGIN_PICTS.'layerswitch.png">
	<p>
	<a href="admin.php?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=tilelayers">'.
	__('Help and Settings','extensions-leaflet-map').'</a>
	</p>';
	//
	//include 'help/markercluster.php';
	$text=$text.'<h4 id="leaflet.markercluster">Leaflet.markercluster and Leaflet.FeatureGroup.SubGroup</h4>
	<img src="'.LEAFEXT_PLUGIN_PICTS.'cluster.png">
	<img src="'.LEAFEXT_PLUGIN_PICTS.'clustergroup.png">
	<p>
	<a href="admin.php?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=cluster">'.
	__('Help and Settings','extensions-leaflet-map').'</a>
	</p>';
	//
	// include 'help/markergroup.php';
	//
	//include 'help/zoomhome.php';
	$text=$text.'<h4 id="leaflet.zoomhome">leaflet.zoomhome</h4>
	<img src="'.LEAFEXT_PLUGIN_PICTS.'home.png">
	<p>
	<a href="admin.php?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=zoomhome">'.
	__('Help','extensions-leaflet-map').'</a>
	</p>';
	//
	include 'help/fullscreen.php';
	include 'help/hovergeojson.php';
	//
	//include 'help/gesture.php';
	$text=$text.'<h4 id="gesturehandling">GestureHandling</h4>
	<img src="'.LEAFEXT_PLUGIN_PICTS.'gesture.png">
	<p>
	<a href="admin.php?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=gesture">'.
	__('Help and Settings','extensions-leaflet-map').'</a>
	</p>';
	//
	include 'help/hidemarkers.php';
	echo $text;
