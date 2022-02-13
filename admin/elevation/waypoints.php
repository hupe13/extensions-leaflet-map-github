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
	add_settings_field($section_name,'sym','leafext_form_waypoints',$section_group,$settings_section);
}
add_action('admin_init', 'leafext_waypoints_init' );

// Baue Form
function leafext_form_waypoints() {
  $options = get_option('leafext_waypoints');
	if ( ! $options ) $options = array();
	$waypoint = array(
		"sym" => "",
		"js" => "" ,
	);
	$options[]=$waypoint;
	$i=0;$count=count($options);
	foreach ($options as $option) {
		if ( $i > 0 ) {
			echo '<tr><th colspan=2 style="border-top: 3px solid #646970"> </th></tr>';
			echo '<tr><th scope="row-title">symname:</th>';
			echo '<td>';
		} else {
			//echo '<td>';
		}
		echo '<input class="full-width" type="text" placeholder="name" name="leafext_waypoints['.$i.'][sym]" value="'.$option['sym'].'" /></td>';
		echo '</tr>';

		echo '<tr><th scope="row-title">Javascript:</th>';
		if (!isset($option['js'])) $option['js'] = "";
		echo '<td>';
		if ($option['js'] == "") {
			echo __('The syntax is not checked!','extensions-leaflet-map').'<br>';
		}
		echo '<input type="text" name="leafext_waypoints['.$i.'][js]"
		placeholder='."'".'iconSize: [25,41], iconAnchor: [12,41], popupAnchor: [1,-34],'
		."'".' value = "'.$option['js'].'" size="80">';

		if ($option['sym'] != "" ) {
			echo '</td></tr>';
			echo '<tr><th scope="row-title">Delete</th>';
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
				$wpt['sym'] = str_replace('-', '', strtolower(sanitize_text_field ( $option['sym'] )));
				$wpt['js'] = wp_kses_normalize_entities( $option['js'] );
				$wpts[]=$wpt;
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
  $text = $text.'<h2>Waypoints</h2>';
	$text = $text.'<h3>CSS</h3>';
	$text = $text.'<pre>.elevation-waypoint-icon.<span style="color: #d63638">symname</span>:before {
	background: url(https://my-domain.tld/path/to/icon.png) no-repeat 50%/contain;
}</pre>';
	$text = $text.'<h3>Waypoint in file</h3>';
	$text = $text.'GPX: <pre>&lt;sym&gt;<span style="color: #d63638">symname</span>&lt;/sym&gt;</pre>';
	$text = $text.'Geojson: <pre>"properties": {
  "name": "...",
  "desc": "...",
  "sym": "<span style="color: #d63638">symname</span>"
},</pre>';


	$text = $text.'<h3>Settings</h3>';
	$text = $text.'"<span style="color: #d63638">symname</span>" kann auch leer sein. ';
	$text = $text.'symname nur Kleinbuchstaben, Leerzeichen werden durch - ersetzt. "," (vielleicht auch noch mehr) geht nicht.';
	$text = $text.'<pre>';
	$text = $text.'wptIcons: {
  "<span style="color: #d63638">symname</span>": L.divIcon({
  className: "elevation-waypoint-marker",
  html: &apos;&lt;i class="elevation-waypoint-icon"&gt;&lt;/i&gt;&apos;,
  <span style="color: #d63638">iconSize: [25,41],
  iconAnchor: [12,41],
  popupAnchor: [1,-34],</span>
 }),
},';
	$text = $text.'</pre>';

  if (is_singular()|| is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
