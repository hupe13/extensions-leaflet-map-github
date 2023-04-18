<?php
/**
 * managefiles functions
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

// list all files with extensions in dir and subdirs
function leafext_list_allfiles($directory,$extensions) {
	$files = array();
	$dir = '/'.trim($directory,'/').'/';
	foreach(glob($dir.'*.'.$extensions, GLOB_BRACE) as $file) {
		$files[] = $file;
	}
	foreach(glob($dir.'*', GLOB_ONLYDIR) as $filedir) {
		$files = array_merge($files, leafext_list_allfiles($filedir,$extensions));
	}
	sort($files, SORT_NATURAL | SORT_FLAG_CASE);
	return $files;
}

// list all dirs with at least count files with extensions in each dir and subdir
function leafext_list_dirs($directory,$extensions,$count) {
	$upload_dir = wp_get_upload_dir();
	$upload_path = $upload_dir['path'];
	$directories = array();
	$dir = '/'.trim($directory,'/').'/';
	if (count(glob($dir.'*.'.$extensions, GLOB_BRACE)) >= $count ) {
		$directories [] = str_replace($upload_path,'',$dir);
	}
	foreach(glob($dir.'*', GLOB_ONLYDIR) as $file) {
		$directories = array_merge($directories, leafext_list_dirs($file,$extensions,$count));
	}
	sort($directories, SORT_NATURAL | SORT_FLAG_CASE);
	return $directories;
}

// list all files with extensions in dir
function leafext_list_dir($directory,$extensions) {
	$upload_dir = wp_get_upload_dir();
	$upload_path = $upload_dir['path'].'/';
	$directory = trim($directory,'/');
	$dir = str_replace('//','/',$upload_path.$directory.'/');
	$files = glob($dir.'*.'.$extensions, GLOB_BRACE);
	return $files;
}

// Unterteile Liste aller Files in pages
function leafext_list_paginate($files,$anzahl) {
	if (count($files)>0) {
		$page = isset($_GET['page']) ? filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS) : "";
		$tab = isset($_GET['tab']) ? filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_SPECIAL_CHARS) : "";
		if (count($_POST) != 0) {
			//var_dump($_POST);
			$all =	isset($_POST["all"]) ? '&all="on"' : "";
			$dir =	isset($_POST["dir"]) ? "&dir=".$_POST["dir"] : "";
			$type =	isset($_POST["type"]) ? "&type=".implode(",", $_POST["type"]) : "";
		} else {
			//var_dump($_GET);
			$all =	isset($_GET["all"])  ? '&all="on"' : "";
			$dir =	isset($_GET["dir"])  ? "&dir=".filter_input(INPUT_GET, 'dir', FILTER_SANITIZE_SPECIAL_CHARS) : "";
			$type = isset($_GET["type"]) ? "&type=".filter_input(INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS) : "";
		}
		$pageurl = admin_url( 'admin.php' ).'?page='.$page.'&tab='.$tab.'&anzahl='.$anzahl.$type.$all.$dir.'&nr=%_%';
		$pages = intdiv(count($files), $anzahl) + 1;
		$pagenr = max(1,isset($_GET["nr"]) ? $_GET["nr"] : "1");
		$pagefiles = array_chunk($files, $anzahl);

		echo '<h2>'.__('Listing - page','extensions-leaflet-map').' '.$pagenr.'/'.$pages.'</h2>';
		echo '<p>';
		if (count($pagefiles) > 1 ) {
			echo paginate_links( array(
				'base'               => $pageurl, // http://example.com/all_posts.php%_% : %_% is replaced by format (below).
				'format'             => '%#%', // ?page=%#% : %#% is replaced by the page number.
				'total'              => $pages,
				'current'            => $pagenr,
				'aria_current'       => 'page',
				'show_all'           => false,
				'prev_next'          => true,
				'prev_text'          => __( '&laquo; Previous' ),
				'next_text'          => __( 'Next &raquo;' ),
				'end_size'           => 1,
				'mid_size'           => 2,
				'type'               => 'plain',
				'add_args'           => array('_wpnonce' => wp_create_nonce( 'leafext_file_listing' )), // Array of query args to add.
				'add_fragment'       => '',
				'before_page_number' => '',
				'after_page_number'  => '',
			));
		}
		echo '</p><p>';
		echo leafext_files_table($pagefiles[$pagenr - 1]);
		echo '</p>';
	} else {
		echo '<h2>'.__('Listing Files','extensions-leaflet-map').'</h2>';
		echo '<p>';
		echo __('no files','extensions-leaflet-map');
		echo '</p>';
	}
}

// enqueue javascript and css for creating shortcode for copy
function leafext_createShortcode_js() {
	wp_enqueue_script('leafext_createShortcode_js',
	plugins_url('admin/filemgr/create_copy/createShortcode.js',
	LEAFEXT_PLUGIN_FILE));
}
function leafext_createShortcode_css() {
	wp_enqueue_style( 'leafext_createShortcode_css',
	plugins_url('admin/filemgr/create_copy/createShortcode.css',
	LEAFEXT_PLUGIN_FILE));
}

// Baue Tabelle
function leafext_files_table($track_files) {
	//https://codex.wordpress.org/Javascript_Reference/ThickBox
	add_thickbox();
	//
	date_default_timezone_set(wp_timezone_string());

	$track_table = array();
	$entry = array('<b>'.__('Date','extensions-leaflet-map').'</b>',
	'<b>'.__('Name','extensions-leaflet-map').'</b>',
	'<b>'.__(''.__('Preview','extensions-leaflet-map').'','extensions-leaflet-map').'</b>',
	'<b>'.__('Media Library','extensions-leaflet-map').'</b>',
	'<b>'.__('leaflet Shortcode','extensions-leaflet-map').'</b>',
	'<b>'.__('elevation Shortcode','extensions-leaflet-map').'</b>');
	$track_table[] = $entry;

	foreach ($track_files as $file) {
		$upload_dir = wp_get_upload_dir();
		$upload_path = $upload_dir['path'].'/';
		$upload_url = $upload_dir['url'].'/';
		$page = isset($_GET['page']) ? $_GET['page'] : "";
		$tab = isset($_GET['tab']) ? $_GET['tab'] : "";
		$entry = array();
		$myfile = str_replace($upload_path,'/',$file);
		$path_parts = pathinfo($myfile);
		$type = strtolower($path_parts['extension']);
		switch ($type) {
			case "geojson": break;
			case "json": $type = "geojson"; break;
			case "kml": break;
			case "gpx": break;
			default: $type = "";
		}
		global $wpdb;
		$sql = "SELECT post_id FROM $wpdb->postmeta WHERE meta_value LIKE '".substr($myfile, 1)."'";
		$results = $wpdb->get_results($sql);
		if (count($results) > 0 ) {
			foreach ($results as $result) {
				$key = get_post(get_object_vars($result)["post_id"]);
				$entry['post_date'] = $key -> post_date;
				$entry['post_title'] = $key -> post_title;
				if ( current_user_can( 'edit_post', $key -> ID ) ) {
					// View as thickbox
					$entry['view'] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page='.$page) ) .'&tab='.$tab.'&track=/'
					.$myfile.'&TB_iframe=true" class="thickbox">'.__('Preview','extensions-leaflet-map').'</a>';
					//
					$entry['edit'] = '<a href ="'.get_admin_url().'post.php?post='.$key -> ID.'&action=edit">'.__('Edit').'</a>';
				} else if ( current_user_can( 'read', $key -> ID ) ) {
					// View as thickbox
					$entry['view'] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page='.$page) ) .'&tab='.$tab.'&track=/'
					.$myfile.'&TB_iframe=true" class="thickbox">'.__('Preview','extensions-leaflet-map').'</a>';
					//
					$entry['edit'] = '<a href ="'.get_admin_url().'upload.php?item='.$key -> ID.'">'.__('View').'</a>';
				} else {
					$entry['view'] = 'none';
					$entry['edit'] = 'none';
				}
			}
		} else {
			$entry['post_date'] = date('Y-m-d G:i:s', filemtime($file));
			$entry['post_title'] = $myfile;
			if ($type != "") {
				$entry['view'] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page='.$page) ) .'&tab='.$tab.'&track=/'
				.$myfile.'&TB_iframe=true" class="thickbox">'.__('Preview','extensions-leaflet-map').'</a>'; //&width=600&height=550
			} else {
				$entry['view'] = 'none';
			}
			$entry['edit'] = "no media";
		}

		if ($type != "") {
			$entry['leaflet'] = '<span class="leafexttooltip" href="#" onclick="leafext_createShortcode('.
			"'leaflet-".$path_parts['extension']." src='".','.
			"'".$upload_url."',".
			"'".$myfile."'".')"
			onmouseout="leafext_outFunc()">
			<span class="leafextcopy" id="leafextTooltip">Copy to clipboard</span>
			<code>[leaflet-'.$type.' src="..."]</code></span>';
		} else {
			$entry['leaflet'] = "";
		}

		$entry['elevation'] = '<span class="leafexttooltip" href="#" onclick="leafext_createShortcode('.
		"'elevation gpx='".','.
		"'".$upload_url."',".
		"'".$myfile."'".')"
		onmouseout="leafext_outFunc()">
		<span class="leafextcopy" id="leafextTooltip">Copy to clipboard</span>
		<code>[elevation gpx="..."]</code></span>';

		//if (!( $entry['edit'] == "" && $entry['view'] == "" ))
		$track_table[] = $entry;
	}

	$text = leafext_html_table($track_table);
	return $text;
}

//Bug https://core.trac.wordpress.org/ticket/36418
// add_filter( 'wp_mime_type_icon', function( $icon, $mime, $post_id )
// {
//     if( 'application/gpx+xml' === $mime && $post_id > 0 )
//         $icon = LEAFEXT_PLUGIN_URL . '/icons/gpx-file.svg';
//     return $icon;
// }, 10, 3 );
