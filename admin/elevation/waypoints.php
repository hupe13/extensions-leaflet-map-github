<?php
/**
 * Admin for extra waypoints
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

// init settings fuer extra waypoint options
function leafext_waypoints_init(){
	$section_group = 'leafext_waypoints';
	$section_name = 'leafext_waypoints';
	$validate = 'leafext_validate_waypoints';
	register_setting( $section_group, $section_name, $validate );
	$settings_section = 'leafext_waypoints_main';
	add_settings_section($settings_section,leafext_elevation_tab(),'leafext_waypoints_help_text',$section_group);
	add_settings_field($section_name,'<span style="color: #4f94d4">'.__('Text of GPS symbol name','extensions-leaflet-map').'</span>:','leafext_form_waypoints',$section_group,$settings_section);
}
add_action('admin_init', 'leafext_waypoints_init' );

// Baue Form
function leafext_form_waypoints() {
  $options = get_option('leafext_waypoints');
	if ( ! $options ) $options = array();
	$waypoint = array(
		"sym" => "",
		"css" => "",
		"js" => "" ,
	);
	$options[]=$waypoint;
	$i=0;$count=count($options);
	foreach ($options as $option) {
		if ( $i > 0 ) {
			echo '<tr><th colspan=2 style="border-top: 3px solid #646970"> </th></tr>';
			echo '<tr><th scope="row-title"><span style="color: #4f94d4">'.__('Text of GPS symbol name','extensions-leaflet-map').'</span>:</th>';
			echo '<td>';
		} else {
			//echo '<td>';
		}

		echo '<input class="full-width" type="text" ';
		if ($option['js'] == "") echo 'placeholder="name" ';
		echo 'name="leafext_waypoints['.$i.'][sym]" value="'.$option['sym'].'" pattern="-?[_a-zA-Z]+[_a-zA-Z0-9- ,]*" />';
		if ($option['sym'] == "" && $option['js'] != "" ) echo ' (Default)';
		if ($option['sym'] == "" && $option['js'] == "" ) {
			echo '<p>'.__('It may be empty, then its javascript is the default. Valid characters: lowercase, uppercase, numbers, -, _, comma, blank character.','extensions-leaflet-map').'</p>';
		}
		echo '</td>';
		echo '</tr>';

		if ($option['sym'] != "") {
			echo '<tr><th scope="row-title"><span style="color: #d63638">waypoint-css</span>:</th>';
			echo '<td>'.esc_attr($option['css']).'</td>';
			echo '</tr>';
		}

		echo '<tr><th scope="row-title"><span style="color: #00a32a">Javascript</span>:</th>';
		if (!isset($option['js'])) $option['js'] = "";
		echo '<td>';
		if ($option['js'] == "") {
			echo __('The syntax is not checked!','extensions-leaflet-map').'<br>';
		}
		echo '<input type="text" name="leafext_waypoints['.$i.'][js]"
		placeholder='."'".'iconSize: [xx,xx], iconAnchor: [xx,xx], popupAnchor: [xx,xx],'
		."'".' value = "'.$option['js'].'" size="80">';

		if ($option['sym'] != "" || $option['js'] != "") {
			echo '</td></tr>';
			echo '<tr><th scope="row-title">'.__('Delete','extensions-leaflet-map').'</th>';
			echo '<td><input type="checkbox" name="leafext_waypoints['.$i.'][delete]" value="1" />';
		}
		$i++;
		if ($i < $count) echo '</td></tr>';
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_waypoints($options) {
	if (isset($_POST['submit'])) {
		$wpts = array();
		foreach ($options as $option) {
			if ( $option['sym'] !="" || $option['js'] != "" ) {
				if ($option['delete'] == 1) continue;
				$wpt=array();
				//$wpt['sym'] = str_replace('-', '', strtolower(sanitize_text_field ( $option['sym'] )));
				//$wpt['sym'] = strtolower(sanitize_text_field ( $option['sym'] ));
				$wpt['sym'] = $option['sym'];
				$wpt['css'] = str_replace(array(' ',','), array('-','\,'), strtolower( $option['sym'] ));
				$wpt['js'] = wp_kses_normalize_entities( $option['js'] );

				if(array_search($wpt['sym'], array_column($wpts, 'sym')) === false) {
					if ($wpt['sym'] == "") {
						array_unshift($wpts, $wpt);
					} else {
						$wpts[]=$wpt;
					}
				}
			}
		}
		return $wpts;
	}
	if (isset($_POST['delete'])) delete_option('leafext_waypoints');
	return false;
}

// Erklaerung / Hilfe
function leafext_waypoints_help_text() {
  $text = "";
  if (!(is_singular()|| is_archive())) {
    $text = $text.__('Here you can define extra waypoint options.','extensions-leaflet-map');
  }

	$text = $text.'<h3>'.__('Waypoint specified in file','extensions-leaflet-map').'</h3>';
	$text = $text.'GPX: <pre>&lt;sym&gt;<span style="color: #4f94d4">'.__('Text of GPS symbol name','extensions-leaflet-map').'</span>&lt;/sym&gt;</pre>';
	$text = $text.'Geojson: <pre>"properties": {
  "name": "...",
  "desc": "...",
  "sym": "<span style="color: #4f94d4">'.__('Text of GPS symbol name','extensions-leaflet-map').'</span>"
},</pre>';

	$text = $text.'<h3>'.sprintf(__('CSS to define as HTML block (between %s and %s) or in css file','extensions-leaflet-map'),
	'&lt;style&gt;','&lt;/style&gt;')
	.'</h3>';
	$text = $text.'<pre>.elevation-waypoint-icon.<span style="color: #d63638">waypoint-css</span>:before {
	background: url(https://my-domain.tld/path/to/icon.png) no-repeat 50%/contain;
}</pre>';

	//waypoint-css: -?[_a-zA-Z]+[_a-zA-Z0-9-]* anderes escapen
	$text = $text.'<h3>'.__('Generated Javascript','extensions-leaflet-map').'</h3>';
	$text = $text.sprintf(__('More options see %sLeaflet API reference%s.','extensions_leaflet_map'),
	'<a href="https://leafletjs.com/reference.html#divicon">','</a>');
	$text = $text.'<pre>';
	$text = $text.'wptIcons: {
  "<span style="color: #d63638">waypoint-css</span>": L.divIcon({
  className: "elevation-waypoint-marker",
  html: &apos;&lt;i class="elevation-waypoint-icon <span style="color: #d63638">waypoint-css</span>"&gt;&lt;/i&gt;&apos;,
  <span style="color: #00a32a">iconSize: [xx,xx],
  iconAnchor: [xx,xx],
  popupAnchor: [xx,xx],
  ...</span>
 }),
},';
	$text = $text.'</pre>';
	if (!(is_singular()|| is_archive())) {
		$text = $text.'<h3>'.__('Settings','extensions-leaflet-map').'</h3>';
	}
  if (is_singular()|| is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
