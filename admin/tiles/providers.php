<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_providers_init(){
	// Create Setting
	$section_group = 'leafext_providers';
 	$section_name = 'leafext_providers';
 	$validate = 'leafext_validate_providers';
	register_setting( $section_group, $section_name, $validate );

	// Create section of Page
	$settings_section = 'leafext_providers_main';
	$page = $section_group;
	add_settings_section(
		$settings_section,
		'leafext_providers',
		'leafext_providers_help',
		$page
	);

	// Add fields to that section
	add_settings_field(
		$section_name,
		'Providers requiring registration',
		'leafext_providers_form',
		$page,
		$settings_section
	);
}
add_action( 'admin_init', 'leafext_providers_init' );

function leafext_providers_form() {
	$require_registration = leafext_providers_registration();
  $allnames = leafext_providers_regnames();

  $regtiles = get_option('leafext_providers', array());
  $options=array();
	$count=count($regtiles);
	for ($i = 0; $i < $count; $i++) {
		$allnames = array_diff($allnames, array($regtiles[$i]['name']));
		echo '<h4>'.$regtiles[$i]['name'].'</h4>'."\n";
		echo '<input type="hidden" name="leafext_providers['.$i.'][name]" value="'.$regtiles[$i]['name'].'">'."\n";
		$size = max(array_map('strlen', $regtiles[$i]['keys']));
		foreach ( $regtiles[$i]['keys'] as $key => $value ) {
			echo '<p>'.$key.': ';
			echo '<input type="text" size='.$size.' name="leafext_providers['.$i.'][keys]['.$key.']" value="'.$value.'"></p>'."\n";
		}
	}
	$i=$count;
	foreach ( $allnames as $name ) {
		$id = array_search($name, array_column($require_registration, 'name')) ;
    echo '<h4>'.$require_registration[$id]['name'].'</h4>'."\n";
    echo '<input type="hidden" name="leafext_providers['.$i.'][name]" value="'.$require_registration[$id]['name'].'">'."\n";
    $size = max(array_map('strlen', $require_registration[$id]['keys']));
    foreach ( $require_registration[$id]['keys'] as $key => $value ) {
      echo '<p>'.$key.': ';
      echo '<input type="text" size='.$size.' name="leafext_providers['.$i.'][keys]['.$key.']" placeholder="'.$value.'" value=""></p>'."\n";
    }
		$i++;
  }
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_providers($options) {
	if (isset($_POST['submit'])) {
    foreach ($options as $option) {
      foreach ($option['keys'] as $key => $value ) {
        if ($value != "") $providers[] = $option; break;
      }
    }
		return $providers;
	}
	if (isset($_POST['delete'])) delete_option('leafext_providers');
	return false;
}

// Erklaerung / Hilfe
function leafext_providers_help() {
	$text = '<pre><code>[leaflet-map]
[layerswitch providers="WaymarkedTrails.hiking"]

[leaflet-map mapid="OSM"]
[layerswitch providers="WaymarkedTrails.hiking,OPNVKarte"]</code></pre>'.
	'<p>'.'For a list of providers see <a href="http://leaflet-extras.github.io/leaflet-providers/preview/"
	>http://leaflet-extras.github.io/leaflet-providers/preview/</a>.'
	.'</p>';
	if (!(is_singular()|| is_archive())) {
		$text = $text.'<p>I did not tested all, I have only checked the Javascript code. If something is not working, please let me know.</p>';
	}
	if (is_singular()|| is_archive() ) {
		return $text;
	} else {
		echo $text;
	}
}

// Draw the menu page itself
function leafext_providers_do_page (){
	echo '<form method="post" action="options.php">';
	settings_fields('leafext_providers');
	do_settings_sections( 'leafext_providers' );
	submit_button();
	submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
	echo '</form>';
}
