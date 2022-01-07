<?php
/**
 * Admin for layerswitch
 * extensions-leaflet-map
 */
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
		"tile" => "",
		"overlay" => "",
		"oacity" => "",
	);
	$options[]=$map;
	$i=0;$count=count($options);
	foreach ($options as $option) {
		if ( $i > 0 ) {
			echo '<tr><th colspan=2 style="border-top: 3px solid #646970"> </th></tr>';
			echo '<tr><th scope="row-title">mapid:</th>';
			echo '<td>';
		} else {
			//echo '<td>';
		}
		echo '<input class="full-width" type="text" placeholder="name" name="leafext_maps['.$i.'][mapid]" value="'.$option['mapid'].'" /></td>';
		echo '</tr>';

		echo '<tr><th scope="row-title">Attribution:</th>';
		echo '<td><input type="text" size="80" placeholder="Copyright" name="leafext_maps['.$i.'][attr]" value="'.esc_attr($option['attr']).'" /></td>';
		echo '</tr>';

		echo '<tr><th scope="row-title">Tile server:</th>';
		echo '<td><input type="url"  size="80" placeholder="https://{s}.tile.server.tld/{z}/{x}/{y}.png" name="leafext_maps['.$i.'][tile]" value="'.$option['tile'].'" /></td>';
		echo '</tr>';

		echo '<tr><th scope="row-title">Extra Options: (optional)</th>';
		if (!isset($option['options'])) $option['options'] = "";
		echo '<td>';
		if ($option['options'] == "") {
			echo __('The syntax is not checked!','extensions-leaflet-map').'<br>';
		}
		echo '<input type="text"  size="80"
			placeholder="'.
			esc_attr('minZoom: 1, maxZoom: 16, subdomains: "abcd", opacity: 0.5, bounds: [[22, -132], [51, -56]]').'"
			name="leafext_maps['.$i.'][options]"
			pattern='."'".'[a-zA-Z0-9_: ",\[\]\-.\{\}]*'."'".' value="'.esc_attr($option['options']).'" /></td>';
		echo '</tr>';

		echo '<tr><th scope="row-title">Overlay:</th>';
		if (!isset($option['overlay'])) $option['overlay'] = "0";
		$checked = $option['overlay'] == "1" ? "checked" : "";
		echo '<td><input type="checkbox"  name="leafext_maps['.$i.'][overlay]" value="1" '.$checked.'/>';
		echo '</td></tr>';

		echo '<tr><th scope="row-title">Opacity:</th>';
		if (!isset($option['opacity'])) $option['opacity'] = "0";
		$checked = $option['opacity'] == "1" ? "checked" : "";
		echo '<td><input type="checkbox"  name="leafext_maps['.$i.'][opacity]" value="1" '.$checked.'/>';

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
			$map['attr'] =
			//sanitize_text_field
			wp_kses_normalize_entities
			( $option['attr'] );
			$map['tile'] = sanitize_text_field ( $option['tile'] );
			$map['options'] = wp_kses_normalize_entities( $option['options'] );
			$map['overlay'] = $option['overlay'] ;
			$map['opacity'] = $option['opacity'] ;
			$maps[]=$map;
		}
	}
	return $maps;
}

// Erklaerung / Hilfe
function leafext_maps_help_text() {
	//echo '<h4 id="switching-tile-layers">'.__('Switching Tile Layers','extensions-leaflet-map').'</h4>
	echo '<img src="'.LEAFEXT_PLUGIN_PICTS.'layerswitch.png"><p>
	<h2>'.__('About Tile servers','extensions-leaflet-map').'</h2>
	<p>'.sprintf(__('The default Map Tiles are defined in the %s Settings%s
	with the options %s and %s. %s and %s are
	also important. You can define additionally Tile Servers here.','extensions-leaflet-map'),
	'<a href="'.get_admin_url().'admin.php?page=leaflet-map">Leaflet Map ',
	'</a>','tileurl, subdomains ','attribution','min_zoom','max_zoom').'</p>

	<h2>Shortcode</h2>
<pre><code>[leaflet-map mapid="..." ...]
[layerswitch]
</code></pre>';
	echo '<p>'.
		__("Configure a mapid, attribution and a tile url for each tile service.","extensions-leaflet-map").
		' mapid '.__("appears in the switching control. To delete a service simply clear the values.","extensions-leaflet-map")
		.'</p>';
	echo '<div colspan=2 style="border-top: 3px solid #646970"> </div>';
}
