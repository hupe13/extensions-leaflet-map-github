<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();
// init settings fuer elevation
function leafext_elevation_init(){
//	add_settings_section( 'theme_settings', 'Elevation Profile', 'leafext_elevation_help_text', 'leafext_settings_theme' );
	add_settings_section( 'theme_settings', leafext_elevation_tab(), 'leafext_elevation_help_text', 'leafext_settings_theme' );
	add_settings_field( 'leafext_values_1', 'Theme', 'leafext_form_theme', 'leafext_settings_theme', 'theme_settings' );
	add_settings_field( 'leafext_values_2', 'Other Theme', 'leafext_form_other_theme', 'leafext_settings_theme', 'theme_settings' );
	register_setting( 'leafext_settings_theme', 'leafext_values', 'leafext_validate_elevationtheme' );
}
add_action('admin_init', 'leafext_elevation_init' );

//Elevation / multielevation
function leafext_elevation_tab() {
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : '';
	$textheader = '';
	if ( $active_tab == 'elevation' ) {
		$textheader = $textheader.'
			Elevation Profile &nbsp;&nbsp;
			<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=multielevation">Multiple hoverable tracks</a>
		';
	} else {
			$textheader = $textheader.'
				Multiple hoverable tracks  &nbsp;&nbsp; 
				<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=elevation">Elevation Profile</a>
			';
	}
	return $textheader;
}

// Baue Abfrage Standardthema
function leafext_form_theme() {
	?>
	<script>
		function leafext_EnableDisableOtherTheme(leafext_elecolor) {
			var selectedValue = leafext_elecolor.options[leafext_elecolor.selectedIndex].value;
			var leafext_eleother = document.getElementById("leafext_eleother");
			leafext_eleother.disabled = selectedValue == "other" ? false : true;
			if (!leafext_eleother.disabled) {
				leafext_eleother.removeAttribute('readonly');
			}
		}
	</script>
	<?php
	$defaults = array(
		"theme" => "lime",
		"othertheme" => "" );
	$options = shortcode_atts($defaults, get_option('leafext_values') );
	echo '<select id="leafext_elecolor" name="leafext_values[theme]" onchange = "leafext_EnableDisableOtherTheme(this)">';
	$colors = array("lime","steelblue","purple","yellow","red","magenta","lightblue","other");
	foreach ($colors as $color) {
		if ($color == $options['theme']) {
			echo '<option selected ';
		} else {
			echo '<option ';
		}
		echo 'value="'.$color.'">'.$color.'</option>';
	}
	echo '</select>';
}

// Baue Abfrage eigenes Thema
function leafext_form_other_theme() {
	$defaults = array(
		"theme" => "lime",
		"othertheme" => "" );
	$options = shortcode_atts($defaults, get_option('leafext_values') );
	echo '<input id="leafext_eleother" type="text" name="leafext_values[othertheme]" placeholder="my-theme"
		pattern=".+-theme" title="'.__("must end with",'extensions-leaflet-map').' \'-theme\'"
		value="'.$options['othertheme'].'" ';
	echo ($options['theme'] == "other") ? "" : " readonly ";
	echo '/>';
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_elevationtheme($input) {
	$input['othertheme'] =  sanitize_text_field($input['othertheme']);
	return $input;
}

// Helptext
function leafext_elevation_help_text () {
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : '';
	if( $active_tab == 'multielevation' ) {
 		include LEAFEXT_PLUGIN_DIR . '/admin/help/multielevation.php';
	} else {
		include LEAFEXT_PLUGIN_DIR . '/admin/help/elevation.php';
	}
}
