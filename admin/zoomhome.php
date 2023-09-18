<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_zoomhome_help(){
	if (!(is_singular() || is_archive() )) {
	$text='<h2 id="leaflet.zoomhome">Zoomhome</h2>';
} else {
	$text='';
}
	$text=$text.'<img src="'.LEAFEXT_PLUGIN_PICTS.'home.png" alt="home"><p>
	&quot;Home&quot; '.__('button to reset the view. A must for clustering markers','extensions-leaflet-map').'.</p>
	'.__('It resets the view to all markers (leaflet-marker), lines (leaflet-line), circles (leaflet-circle), geojsons (leaflet-geojson, leaflet-gpx, leaflet-kml) and a track (elevation).','extensions-leaflet-map').'

	<h2>Shortcode</h2>

	<pre><code>[zoomhomemap fit/<span style="color: #d63638">!fit</span>]</code></pre>

	<h2>Howto</h2>';

	$text=$text.'<style>tr:nth-child(even) { background-color: #fcfcfc; }</style>';
	if (is_singular() || is_archive()){
		$text=$text.'<style>td,th { border:1px solid #195b7a !important; }</style>';
	}

	$text=$text.'<p>'.__('You can set following and the Home button works as shown in the table.','extensions-leaflet-map').'
	</p>
	<figure class="wp-block-table aligncenter is-style-stripes">
	<table class="form-table" border="1" style="text-align: center;">
	<tr>
	<th class="row-title" style="text-align:center">leaflet-map</th>
	<th style="text-align:center">leaflet-element<sup>*</sup></th>
	<th style="text-align:center">zoomhomemap</th>
	<th style="text-align:center">'.__('initial state of the map','extensions-leaflet-map').'</th>
	<th style="text-align:center">Home button</th>
	</tr>
	<tr valign="top">
	<td scope="row">fitbounds</td>
	<td>-</td>
	<td>-</td>
	<td>map</td>
	<td>map</td>
	</tr>
	<tr valign="top">
	<td scope="row"><span style="color: #d63638">!fitbounds</span></td>
	<td>fitbounds</td>
	<td>fit</td>
	<td>element</td>
	<td>map</td>
	</tr>
	<tr valign="top" class="alternate">
	<td scope="row"><span style="color: #d63638">!fitbounds</span></td>
	<td>fitbounds</td>
	<td><span style="color: #d63638">!fit</span></td>
	<td>element</td>
	<td>element</td>
	</tr>
	<tr valign="top" class="alternate">
	<td scope="row"><span style="color: #d63638">!fitbounds</span></td>
	<td><span style="color: #d63638">!fitbounds</span></td>
	<td>fit</td>
	<td>leaflet-map settings lat lng zoom</td>
	<td>map</td>
	</tr>
	<tr valign="top">
	<td scope="row"><span style="color: #d63638">!fitbounds</span></td>
	<td><span style="color: #d63638">!fitbounds</span></td>
	<td><span style="color: #d63638">!fit</span></td>
	<td>leaflet-map settings lat lng zoom</td>
	<td>leaflet-map settings lat lng zoom<sup>**</sup></td>
	</tr>
	<tr>
	<th class="row-title" style="text-align:center">leaflet-map</th>
	<th style="text-align:center">leaflet-marker</th>
	<th style="text-align:center">zoomhomemap</th>
	<th style="text-align:center">'.__('initial state of the map','extensions-leaflet-map').'</th>
	<th style="text-align:center">Home button</th>
	</tr>
	<tr valign="top">
	<td scope="row">fitbounds</td>
	<td>-</td>
	<td>-</td>
	<td>map</td>
	<td>map</td>
	</tr>
	<tr valign="top">
	<td scope="row"><span style="color: #d63638">!fitbounds</span></td>
	<td>-</td>
	<td>fit</td>
	<td>leaflet-map settings lat lng zoom</td>
	<td>map</td>
	</tr>
	<tr valign="top" class="alternate">
	<td scope="row"><span style="color: #d63638">!fitbounds</span></td>
	<td>-</td>
	<td><span style="color: #d63638">!fit</span></td>
	<td>leaflet-map settings lat lng zoom</td>
	<td>leaflet-map settings lat lng zoom</td>
	</tr>
	<tr>
	<th class="row-title" style="text-align:center">leaflet-map</th>
	<th style="text-align:center">elevation</th>
	<th style="text-align:center">zoomhomemap</th>
	<th style="text-align:center">'.__('initial state of the map','extensions-leaflet-map').'</th>
	<th style="text-align:center">Home button</th>
	</tr>
	<tr valign="top">
	<td scope="row">fitbounds</td>
	<td>-</td>
	<td>-</td>
	<td>track</td>
	<td>track</td>
	</tr>
	<tr valign="top">
	<td scope="row"><span style="color: #d63638">!fitbounds</span></td>
	<td>autofitBounds</td>
	<td>-</td>
	<td>track</td>
	<td>map</td>
	</tr>
	<tr valign="top">
	<td scope="row"><span style="color: #d63638">!fitbounds</span></td>
	<td><span style="color: #d63638">!autofitBounds</span></td>
	<td>-</td>
	<td>'.__('map like defined','extensions-leaflet-map').'</td>
	<td>'.__('map like defined','extensions-leaflet-map').'</td>
	</tr>
	</table></figure>
	* leaflet-element '.__('means','extensions-leaflet-map').' leaflet-line, leaflet-polygon, leaflet-circle, leaflet-geojson, leaflet-gpx, leaflet-kml.<br>
	** '.__('sometimes to first zoom','extensions-leaflet-map').'
	';
	if (is_singular() || is_archive() ) {
		return $text;
	} else {
		echo $text;
	}
}
