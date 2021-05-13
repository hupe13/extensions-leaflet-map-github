<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

	// init settings fuer tile switching
	function leafext_maps_init(){
		add_settings_section( 'maps_settings', 'Switching Tilelayers', 'leafext_maps_help_text', 'leafext_settings_maps' );
		add_settings_field( 'leafext_form_maps_id', 'mapid:', 'leafext_form_maps', 'leafext_settings_maps', 'maps_settings', 'mapid' );
		register_setting( 'leafext_settings_maps', 'leafext_maps', 'leafext_validate_mapswitch' );
	}
	add_action('admin_init', 'leafext_maps_init' );

// Baue Abfrage der Tiles // Wie geht das besser?
function leafext_form_maps() {
	$options = get_option('leafext_maps');
	if ( ! $options ) $options = array();
	$map=array(
		"mapid" => "",
		"attr" => "" ,
		"tile" => "");
	$options[]=$map;
	$i=0;$count=count($options);
	foreach ($options as $option) {
		if ( $i > 0 ) {
			echo '<tr><th scope="row">mapid:</th>';
			echo '<td>';
		} else {
			//echo '<td>';
		}
		echo '<input class="full-width" type="text" placeholder="name" name="leafext_maps['.$i.'][mapid]" value="'.$option['mapid'].'" /></td>';
		echo '</tr>';

		echo '<tr><th scope="row">Attribution:</th>';
		echo '<td><input type="text" size="80" placeholder="Copyright" name="leafext_maps['.$i.'][attr]" value="'.$option['attr'].'" /></td>';
		echo '</tr>';

		echo '<tr><th scope="row">Tile server:</th>';
		echo '<td><input type="url"  size="80" placeholder="https://{s}.tile.server.tld/{z}/{x}/{y}.png" name="leafext_maps['.$i.'][tile]" value="'.$option['tile'].'" />';
		$i++;
		if ($i < $count) echo '</td></tr>';
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_mapswitch($options) {
	//
	$maps = array();
	foreach ($options as $option) {
		if ( $option['mapid'] !="" && $option['attr'] !="" && $option['tile'] != "" ) {
			$map=array();
			$map['mapid'] = sanitize_text_field ( $option['mapid'] );
			$map['attr'] = sanitize_text_field ( $option['attr'] );
			$map['tile'] = sanitize_text_field ( $option['tile'] );
			$maps[]=$map;
		}
	}
	return $maps;
}

// Erklaerung / Hilfe
function leafext_maps_help_text() {
	//echo '<h4 id="switching-tile-layers">'.__('Switching Tile Layers','extensions-leaflet-map').'</h4>
	echo '<img src="'.LEAFEXT_PLUGIN_PICTS.'layerswitch.png"><p>
	<h2>Shortcode</h2>
<pre><code>[leaflet-map mapid="..." ...]
[layerswitch]
</code></pre>';
	echo '<p>'.
		__("Configure a mapid, attribution and a tile url for each tile service.","extensions-leaflet-map").
		' mapid '.__("appears in the switching control. To delete a service simply clear the values.","extensions-leaflet-map")
		.'</p>';
}
