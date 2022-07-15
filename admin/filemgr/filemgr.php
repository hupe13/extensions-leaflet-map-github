<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//for listing files

function leafext_file_listing_init(){
	register_setting( 'leafext_file_listing', 'leafext_listing' );
	add_settings_section( 'leafext_listing_settings', '', '', 'leafext_file_listing' );
	add_settings_field("leafext_listing_types", __('Show files of type',"extensions-leaflet-map"), 'leafext_listing_form_types','leafext_file_listing','leafext_listing_settings'); //type
	add_settings_field("leafext_listing_all", __("Show all files","extensions-leaflet-map"), 'leafext_listing_form_all','leafext_file_listing','leafext_listing_settings'); //all
	add_settings_field("leafext_listing_dirs", __("or in the directory","extensions-leaflet-map"), 'leafext_listing_form_dirs','leafext_file_listing','leafext_listing_settings'); //verz, count,
	add_settings_field("leafext_listing_files", __("and show at the same time","extensions-leaflet-map"), 'leafext_listing_form_files','leafext_file_listing','leafext_listing_settings'); //anzahl
	add_settings_field("leafext_listing_default", __("Save query","extensions-leaflet-map"), 'leafext_listing_form_default','leafext_file_listing','leafext_listing_settings'); //anzahl
}
add_action('admin_init', 'leafext_file_listing_init');

function leafext_listing_form_types() {
	if (count($_POST) != 0) {
		$type =	isset($_POST["type"]) ? $_POST["type"] : "";
	} else if ( isset($_GET["type"] ) ) {
		$type = $_GET["type"];
	} else {
		$stored = get_option('leafext_file_listing_'.get_current_user_id());
		if (is_array($stored)) {
			$type = $stored['type'];
		} else {
			$type = array ("gpx");
		}
	}
	$types = array ("gpx","kml","geojson","json","tcx");

	foreach ( $types as $typ) {
		$checked = in_array($typ, $type) ? " checked " : "";
		echo ' <input type="checkbox" name="type[]" value="'.$typ.'" id="'.$typ.'" '.$checked.'>';
		echo '<label for="'.$typ.'" >'.$typ.' </label>';
	}
}

function leafext_listing_form_dirs() {
	if (count($_POST) != 0) {
		$verz =	isset($_POST["verz"]) ? $_POST["verz"] : (isset($_POST["dir"]) ? $_POST["dir"] : "");
		$count = isset($_POST["count"]) ? $_POST["count"] : "5";
		$type =	isset($_POST["type"]) ? $_POST["type"] : "";
		$all =	isset($_POST["all"]) ? $_POST["all"] : "";
	} else if (count($_GET) > 2) {  //mehr als page und tab
		$verz =	isset($_GET["verz"]) ? $_GET["verz"] : (isset($_GET["dir"]) ? $_GET["dir"] : "");
		$count = isset($_GET["count"]) ? $_GET["count"] : "5";
		$type =	isset($_GET["type"]) ? $_GET["type"] : "";
		$all =	isset($_GET["all"]) ? $_GET["all"] : "";
	} else {
		$stored = get_option('leafext_file_listing_'.get_current_user_id());
		if (is_array($stored)) {
			$verz = $stored['dir'];
			$count = $stored['count'];
			$type = $stored['type'];
			$all = $stored['all'];
		} else {
			$verz = "";
			$count = "5";
			$type = array("gpx");
			$all = "";
		}
	}

	$extensions = is_array($type) ? '{'.implode(",", $type).'}' : '{gpx,kml,geojson,json,tcx}';

	$upload_dir = wp_get_upload_dir();
	$upload_path = $upload_dir['path'];
	$disabled = ($all == "on") ? "disabled" : "";

	echo __("with at least","extensions-leaflet-map").' <input '.$disabled.' type="number" min="2" name="count" id="leafext_dirListnr" value="'.$count.'" size="3"> '.__("Files","extensions-leaflet-map").': ';
	echo '<select name="dir" id="leafext_dirList" '.$disabled.'>';
	if ($verz == "" ) echo '<option selected value="">'.__('Please select',"extensions-leaflet-map").' ...</option>';
	foreach (leafext_list_dirs($upload_path,$extensions,$count) as $dir) {
		if ($verz == $dir) {
			echo '<option selected ';
		} else {
			echo '<option ';
		}
		echo 'value="'.$dir.'">'.$dir.'</option>';
	}
	echo '</select>';
	echo '<p>'.__("If you change the number, submit the form to get the directories you want.","extensions-leaflet-map").'</p>';

}

function leafext_listing_form_all() {
	?>
	<script>
	function leafext_EnableDisableDirListing(leafext_filesall) {
		var leafext_dirList = document.getElementById("leafext_dirList");
		var leafext_dirListnr = document.getElementById("leafext_dirListnr");
		if (leafext_filesall.checked) {
			leafext_dirList.setAttribute('disabled', "disabled");
			leafext_dirListnr.setAttribute('disabled', true);
		} else {
			leafext_dirList.removeAttribute('disabled');
			leafext_dirListnr.removeAttribute('disabled');
		}
	}
	</script>
	<?php
	if (count($_POST) != 0) {
		$all = isset($_POST["all"]) ? $_POST["all"] : "";
	} else if (isset($_GET["all"])) {
		$all = $_GET["all"];
	} else {
		$stored = get_option('leafext_file_listing_'.get_current_user_id());
		if (is_array($stored)) {
			$all = $stored['all'];
		} else {
			$all = "";
		}
	}
	$checked = ($all == "on") ? "checked" : "";
	echo '<input type="checkbox" '.$checked.' name="all" id="leafext_filesall" onchange="leafext_EnableDisableDirListing(this)">';
}

function leafext_listing_form_files() {
	if (count($_POST) != 0) {
		$anzahl = isset($_POST["anzahl"]) ? $_POST["anzahl"] : "10";
	} else if (isset($_GET["anzahl"])) {
		$anzahl = $_GET["anzahl"];
	} else {
		$stored = get_option('leafext_file_listing_'.get_current_user_id());
		if (is_array($stored)) {
			$anzahl = $stored['anzahl'];
		} else {
			$anzahl = "10";
		}
	}
	echo '<input type="number" min="1" name="anzahl" value="'.$anzahl.'" size="4"> '.__('entries',"extensions-leaflet-map");
}

function leafext_listing_form_default() {
	echo '<input type="checkbox" name="store" id="leafext_store">';
}

function leafext_managefiles() {

	$page = isset($_GET['page']) ? $_GET['page'] : "";
	$tab = isset($_GET['tab']) ? $_GET['tab'] : "";
	$track = isset($_GET['track']) ? $_GET['track'] : "";

	if ( $track != "") {
		include LEAFEXT_PLUGIN_DIR . '/admin/filemgr/thickbox.php';
		leafext_thickbox($track);
	} else {
		// echo '<pre>';
		// if (isset($_POST)) var_dump($_POST);
		// if (isset($_GET)) var_dump($_GET);
		// echo '</pre>';

		if (count($_POST) != 0) {
			if(wp_verify_nonce($_REQUEST['leafext_file_listing'], 'leafext_file_listing')){
				//echo "valid" ;   // Nonce is matched and valid. do whatever you want now.
			} else {
				echo "invalid" ; wp_die();
			}
		}

		//echo '<h2>'.__('Manage Files',"extensions-leaflet-map").'</h2>';

		if (count($_POST) != 0) {
			$dir = isset($_POST["dir"]) ? $_POST["dir"] : "";
			$all = isset($_POST['all']) ? $_POST['all'] : '';
			$type =	isset($_POST["type"]) ? $_POST["type"] : "";
			$anzahl = isset($_POST["anzahl"]) ? $_POST["anzahl"] : "10";
			$store = isset($_POST["store"]) ? $_POST["store"] : "";
			if ($store == "on") {
				$defaults = array();
				$defaults["type"]   = isset($_POST["type"])   ? $_POST["type"]   : array("gpx");
				$defaults["all"]    = isset($_POST["all"])    ? $_POST["all"]    : "";
				$defaults["count"]  = isset($_POST["count"])  ? $_POST["count"]  : "5";
				$defaults["dir"]    = isset($_POST["dir"])    ? $_POST["dir"]    : "";
				$defaults["anzahl"] = isset($_POST["anzahl"]) ? $_POST["anzahl"] : "10";
				update_option('leafext_file_listing_'.get_current_user_id(), $defaults);
			}
		} else {
			$dir = isset($_GET["dir"]) ? $_GET["dir"] : "";
			$all = isset($_GET['all']) ? $_GET['all'] : '';
			$type =	isset($_GET["type"]) ? $_GET["type"] : "";
			$anzahl = isset($_GET["anzahl"]) ? $_GET["anzahl"] : "10";
		}
		$extensions = is_array($type) ? '{'.implode(",", $type).'}' : '{gpx,kml,geojson,json,tcx}';

		if ( $dir == "" && $all == "" && !current_user_can('manage_options')) leafext_managefiles_help();

		// $stored = get_option('leafext_file_listing_'.get_current_user_id);
		// var_dump($stored);
		//var_dump(get_current_user_id());

		echo '<form method="post" action="'.admin_url( 'admin.php' ).'?page='.$page.'&tab='.$tab.'">';
		wp_nonce_field('leafext_file_listing', 'leafext_file_listing');
		settings_fields('leafext_file_listing');
		do_settings_sections( 'leafext_file_listing' );
		submit_button(__("List files","extensions-leaflet-map"));
		echo '</form>';

		if ( $dir != "" || $all != "" ) {
			leafext_createShortcode_js();
			leafext_createShortcode_css();
		}
		if ( $dir != "" ) {
			echo '<h3>'.__('Directory',"extensions-leaflet-map").' '.$dir.'</h3>';
			if ($dir != "/") {
				echo '<div><a href="?page='.$page.'&tab=filemgr-dir">Shortcode</a> '.__('for showing all files of this directory on a map',"extensions-leaflet-map").':'.
				'<span class="leafexttooltip" href="#" onclick="leafext_createShortcode('.
				"'leaflet-directory  src='".','.
				"'',".
				"'/".trim($dir,'/')."/'".')"'.
				'onmouseout="leafext_outFunc()">'.
				'<span class="leafextcopy" id="leafextTooltip">Copy to clipboard</span>'.
				'<code>[leaflet-directory src="/'.trim($dir,'/').'/"]</code>'.
				'</span>'.
				'</div>';
			}
			echo '<p>';
			leafext_list_paginate(leafext_list_dir($dir,$extensions),$anzahl);
			echo '</p>';
		} else if ($all != "") {
			$upload_dir = wp_get_upload_dir();
			$upload_path = $upload_dir['path'].'/';
			leafext_list_paginate(leafext_list_allfiles($upload_path,$extensions),$anzahl);
		}
	}
}
