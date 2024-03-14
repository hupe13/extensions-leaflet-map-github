<?php
/**
 * Functions for upload filetypes gpx, kml, (geo)json, tcx
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// https://wordpress.stackexchange.com/questions/396325/how-can-i-allow-upload-of-ttf-or-otf-font-files-when-hooking-upload-mimes-does
// angepasst
function leafext_correct_filetypes( $data, $file, $filename, $mimes ) {
	// var_dump($data, $file, $filename, $mimes, $real_mime);
	if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
		return $data;
	}
	$wp_file_type = wp_check_filetype( $filename, $mimes );
	$settings     = leafext_filemgr_settings();
	$allowed      = $settings['types'];

	// Check for the file type you want to enable, e.g. 'gpx'.
	if ( in_array( 'gpx', $allowed, true ) && 'gpx' === strtolower( $wp_file_type['ext'] ) ) {
		$data['ext']  = 'gpx';
		$data['type'] = 'application/gpx+xml';
	}
	if ( in_array( 'kml', $allowed, true ) && 'kml' === strtolower( $wp_file_type['ext'] ) ) {
		$data['ext']  = 'kml';
		$data['type'] = 'application/vnd.google-earth.kml+xml';
	}
	if ( in_array( 'geojson', $allowed, true ) && 'geojson' === strtolower( $wp_file_type['ext'] ) ) {
		$data['ext']  = 'geojson';
		$data['type'] = 'application/geo+json';
	}
	if ( in_array( 'json', $allowed, true ) && 'json' === strtolower( $wp_file_type['ext'] ) ) {
		$data['ext']  = 'json';
		$data['type'] = 'application/geo+json';
	}
	if ( in_array( 'tcx', $allowed, true ) && 'tcx' === strtolower( $wp_file_type['ext'] ) ) {
		$data['ext']  = 'tcx';
		$data['type'] = 'application/vnd.garmin.tcx+xml';
	}

	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'leafext_correct_filetypes', 10, 5 );

// Erlaube Upload gpx usw.
function leafext_add_mimes( $mime_types ) {
	$settings = leafext_filemgr_settings();
	$allowed  = $settings['types'];
	if ( in_array( 'gpx', $allowed, true ) ) {
		$mime_types['gpx'] = 'application/gpx+xml';
	}
	if ( in_array( 'kml', $allowed, true ) ) {
		$mime_types['kml'] = 'application/vnd.google-earth.kml+xml';
	}
	if ( in_array( 'geojson', $allowed, true ) ) {
		$mime_types['geojson'] = 'application/geo+json';
	}
	if ( in_array( 'json', $allowed, true ) ) {
		$mime_types['json'] = 'application/geo+json';
	}
	if ( in_array( 'tcx', $allowed, true ) ) {
		$mime_types['tcx'] = 'application/vnd.garmin.tcx+xml';
	}
	return $mime_types;
}
add_filter( 'upload_mimes', 'leafext_add_mimes' );

// https://wordpress.stackexchange.com/questions/47415/change-upload-directory-for-pdf-files
// angepasst
function leafext_pre_upload( $file ) {
	add_filter( 'upload_dir', 'leafext_custom_upload_dir' );
	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'leafext_pre_upload' );

function leafext_custom_upload_dir( $path ) {
	$options = leafext_filemgr_settings();
	if ( $options['gpxupload'] == true ) {
		if ( ! empty( $_POST ) && check_admin_referer( 'leafext_file', 'leafext_file_nonce' ) ) {
			$post = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
		}
		$extension = substr( strrchr( $post['name'], '.' ), 1 );
		if ( ! empty( $path['error'] ) || $extension != 'gpx' ) {
			return $path;
		} //error or other filetype; do nothing.
		$customdir      = '/' . $extension;
		$path['path']   = str_replace( $path['subdir'], '', $path['path'] ); // remove default subdir (year/month)
		$path['url']    = str_replace( $path['subdir'], '', $path['url'] );
		$path['subdir'] = $customdir;
		$path['path']  .= $customdir;
		$path['url']   .= $customdir;
	}
	return $path;
}

function leafext_post_upload( $fileinfo ) {
	remove_filter( 'upload_dir', 'leafext_custom_upload_dir' );
	return $fileinfo;
}
add_filter( 'wp_handle_upload', 'leafext_post_upload' );
