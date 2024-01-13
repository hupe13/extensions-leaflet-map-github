<?php
/**
 * Admin functions for filemgr functions
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// list all files with extensions in dir and subdirs
function leafext_list_allfiles( $directory, $extensions ) {
	$files = array();
	$dir   = trailingslashit( $directory );
	foreach ( glob( $dir . '*.' . $extensions, GLOB_BRACE ) as $file ) {
		$files[] = $file;
	}
	foreach ( glob( $dir . '*', GLOB_ONLYDIR ) as $filedir ) {
		$files = array_merge( $files, leafext_list_allfiles( $filedir, $extensions ) );
	}
	sort( $files, SORT_NATURAL | SORT_FLAG_CASE );
	return $files;
}

// list all dirs with at least count files with extensions in each dir and subdir
function leafext_list_dirs( $directory, $extensions, $count ) {
	$upload_dir  = wp_get_upload_dir();
	$upload_path = trailingslashit( $upload_dir['basedir'] );
	$directories = array();
	$dir         = trailingslashit( $directory );
	if ( count( glob( $dir . '*.' . $extensions, GLOB_BRACE ) ) >= $count ) {
		$directories [] = str_replace( $upload_path, '', $dir );
	}
	foreach ( glob( $dir . '*', GLOB_ONLYDIR ) as $file ) {
		$directories = array_merge( $directories, leafext_list_dirs( $file, $extensions, $count ) );
	}
	sort( $directories, SORT_NATURAL | SORT_FLAG_CASE );
	return $directories;
}

// list all files with extensions in dir
function leafext_list_dir( $directory, $extensions ) {
	$upload_dir  = wp_get_upload_dir();
	$upload_path = trailingslashit( $upload_dir['basedir'] );
	$directory   = trailingslashit( $directory );
	$dir         = $upload_path . $directory;
	$files       = glob( $dir . '*.' . $extensions, GLOB_BRACE );
	return $files;
}

// Unterteile Liste aller Files in pages
function leafext_list_paginate( $files, $anzahl ) {
	$post = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
	$get  = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
	if ( count( $files ) > 0 ) {
		$page = isset( $get['page'] ) ? filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS ) : '';
		$tab  = isset( $get['tab'] ) ? filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_SPECIAL_CHARS ) : '';
		if ( count( $post ) != 0 ) {
			// var_dump($_POST);
			$all  = isset( $post['all'] ) ? '&all="on"' : '';
			$dir  = isset( $post['dir'] ) ? '&dir=' . $post['dir'] : '';
			$type = isset( $post['type'] ) ? '&type=' . implode( ',', $post['type'] ) : '';
		} else {
			// var_dump($_GET);
			$all  = isset( $get['all'] ) ? '&all="on"' : '';
			$dir  = isset( $get['dir'] ) ? '&dir=' . filter_input( INPUT_GET, 'dir', FILTER_SANITIZE_SPECIAL_CHARS ) : '';
			$type = isset( $get['type'] ) ? '&type=' . filter_input( INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS ) : '';
		}
		$pageurl   = admin_url( 'admin.php' ) . '?page=' . $page . '&tab=' . $tab . '&anzahl=' . $anzahl . $type . $all . $dir . '&nr=%_%';
		$pages     = intdiv( count( $files ), $anzahl ) + 1;
		$pagenr    = max( 1, isset( $get['nr'] ) ? $get['nr'] : '1' );
		$pagefiles = array_chunk( $files, $anzahl );

		echo '<h2>' . __( 'Listing - page', 'extensions-leaflet-map' ) . ' ' . $pagenr . '/' . $pages . '</h2>';
		echo '<p>';
		if ( count( $pagefiles ) > 1 ) {
			echo paginate_links(
				array(
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
					'add_args'           => array( 'leafext_file_nonce' => wp_create_nonce( 'leafext_file' ) ),
					'add_fragment'       => '',
					'before_page_number' => '',
					'after_page_number'  => '',
				)
			);
		}
		echo '</p><p>';
		echo leafext_files_table( $pagefiles[ $pagenr - 1 ] );
		echo '</p>';
	} else {
		echo '<h2>' . __( 'Listing Files', 'extensions-leaflet-map' ) . '</h2>';
		echo '<p>';
		echo __( 'no files', 'extensions-leaflet-map' );
		echo '</p>';
	}
}

// enqueue javascript and css for creating shortcode for copy
function leafext_create_shortcode_js() {
	wp_enqueue_script(
		'leafext_create_shortcode_js',
		plugins_url(
			'admin/filemgr/create_copy/createShortcode.js',
			LEAFEXT_PLUGIN_FILE
		),
		array(),
		null,
		true
	);
}
function leafext_create_shortcode_css() {
	wp_enqueue_style(
		'leafext_create_shortcode_css',
		plugins_url(
			'admin/filemgr/create_copy/createShortcode.css',
			LEAFEXT_PLUGIN_FILE
		)
	);
}

// Baue Tabelle
function leafext_files_table( $track_files ) {
	$get = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );

	// https://codex.wordpress.org/Javascript_Reference/ThickBox
	add_thickbox();
	//
	// date_default_timezone_set(wp_timezone_string());

	$track_table   = array();
	$entry         = array(
		'<b>' . __( 'Date', 'extensions-leaflet-map' ) . '</b>',
		'<b>' . __( 'Name', 'extensions-leaflet-map' ) . '</b>',
		'<b>' . __( 'Preview', 'extensions-leaflet-map' ) . '</b>',
		'<b>' . __( 'Media Library', 'extensions-leaflet-map' ) . '</b>',
		'<b>' . __( 'leaflet Shortcode', 'extensions-leaflet-map' ) . '</b>',
		'<b>' . __( 'elevation<sup>1</sup> Shortcode', 'extensions-leaflet-map' ) . '</b>',
		'<b>' . __( 'track in multielevation<sup>1,2</sup>', 'extensions-leaflet-map' ) . '</b>',
	);
	$track_table[] = $entry;

	foreach ( $track_files as $file ) {
		$upload_dir  = wp_get_upload_dir();
		$upload_path = trailingslashit( $upload_dir['basedir'] );
		$upload_url  = trailingslashit( $upload_dir['baseurl'] );
		$page        = isset( $get['page'] ) ? $get['page'] : '';
		$tab         = isset( $get['tab'] ) ? $get['tab'] : '';
		$entry       = array();
		$myfile      = str_replace( $upload_path, '/', $file );
		$path_parts  = pathinfo( $myfile );
		$type        = strtolower( $path_parts['extension'] );
		switch ( $type ) {
			case 'geojson':
				break;
			case 'json':
				$type = 'geojson';
				break;
			case 'kml':
				break;
			case 'gpx':
				break;
			default:
				$type = '';
		}
		global $wpdb;
		$sql = "SELECT post_id FROM $wpdb->postmeta WHERE meta_value LIKE '" . substr( $myfile, 1 ) . "'";
		// phpcs:ignore
		$results = $wpdb->get_results( $sql );
		if ( count( $results ) > 0 ) {
			foreach ( $results as $result ) {
				$key                 = get_post( get_object_vars( $result )['post_id'] );
				$entry['post_date']  = $key->post_date;
				$entry['post_title'] = $key->post_title;
				if ( current_user_can( 'edit_post', $key->ID ) ) {
					// View as thickbox
					$entry['view']                     = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=' . $page ) ) . '&tab=' . $tab . '&track='
					. $myfile . '&TB_iframe=true" class="thickbox">' . __( 'Preview', 'extensions-leaflet-map' ) . '</a>';
										$entry['edit'] = '<a href ="' . get_admin_url() . 'post.php?post=' . $key->ID . '&action=edit">' . __( 'Edit' ) . '</a>';
				} elseif ( current_user_can( 'read', $key->ID ) ) {
					// View as thickbox
					$entry['view']                     = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=' . $page ) ) . '&tab=' . $tab . '&track='
					. $myfile . '&TB_iframe=true" class="thickbox">' . __( 'Preview', 'extensions-leaflet-map' ) . '</a>';
										$entry['edit'] = '<a href ="' . get_admin_url() . 'upload.php?item=' . $key->ID . '">' . __( 'View' ) . '</a>';
				} else {
					$entry['view'] = 'none';
					$entry['edit'] = 'none';
				}
			}
		} else {
			$entry['post_date']  = get_date_from_gmt( gmdate( 'Y-m-d G:i:s', filemtime( $file ) ) );
			$entry['post_title'] = $myfile;
			if ( $type != '' ) {
				$entry['view'] = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=' . $page ) ) . '&tab=' . $tab . '&track='
				. $myfile . '&TB_iframe=true" class="thickbox">' . __( 'Preview', 'extensions-leaflet-map' ) . '</a>'; // &width=600&height=550
			} else {
				$entry['view'] = 'none';
			}
			$entry['edit'] = 'no media';
		}

		$uploadurl = $upload_url;
		$file      = trim( $myfile, '/' );
		if ( $type != '' ) {
			$shortcode        = '[leaflet-' . $path_parts['extension'] . ' src=';
			$end              = ']';
			$entry['leaflet'] = '<span class="leafexttooltip" href="#" ' .
			'onclick="leafext_create_shortcode(\'' . $shortcode . '\',\'' . $uploadurl . '\',\'' . $file . '\',\'' . $end . '\')" ' .
			'onmouseout="leafext_outFunc()">
			<span class="leafextcopy" id="leafextTooltip">Copy to clipboard</span>
			<code>[leaflet-' . $type . ' src="..."]</code></span>';
		} else {
			$entry['leaflet'] = '';
		}

		$shortcode          = '[elevation gpx=';
		$end                = ']';
		$entry['elevation'] = '<span class="leafexttooltip" href="#" ' .
		'onclick="leafext_create_shortcode(\'' . $shortcode . '\',\'' . $uploadurl . '\',\'' . $file . '\',\'' . $end . '\')" ' .
		'onmouseout="leafext_outFunc()">
		<span class="leafextcopy" id="leafextTooltip">Copy to clipboard</span>
		<code>[elevation gpx="..."]</code></span>';

		if ( $path_parts['extension'] == 'gpx' || $path_parts['extension'] == 'kml' ) {
			$shortcode               = '[elevation-track file=';
			$end                     = ']';
			$entry['multielevation'] = '<span class="leafexttooltip" href="#" ' .
			'onclick="leafext_create_shortcode(\'' . $shortcode . '\',\'' . $uploadurl . '\',\'' . $file . '\',\'' . $end . '\')" ' .
			'onmouseout="leafext_outFunc()">
			<span class="leafextcopy" id="leafextTooltip">Copy to clipboard</span>
			<code>[elevation-track file="..."]</code></span>';
		} else {
			$entry['multielevation'] = '';
		}
		$track_table[] = $entry;
	}

	$text = leafext_html_table( $track_table );
	$text = $text . '<small>&nbsp;&nbsp;<sup>1</sup> - ' . __( 'It is not checked whether the file contains a track with elevation data.', 'extensions-leaflet-map' ) . '</small>';
	$text = $text . '<br><small>&nbsp;&nbsp;<sup>2</sup> - ' . __( 'It works with gpx and kml files.', 'extensions-leaflet-map' ) . ' ';
	$text = $text . sprintf( __( "Don't forget to declare %s at last statement.", 'extensions-leaflet-map' ), '<code>[multielevation]</code>' ) . '</small>';
	return $text;
}

// Bug https://core.trac.wordpress.org/ticket/36418
// add_filter( 'wp_mime_type_icon', function( $icon, $mime, $post_id )
// {
// if( 'application/gpx+xml' === $mime && $post_id > 0 )
// $icon = LEAFEXT_PLUGIN_URL . '/icons/gpx-file.svg';
// return $icon;
// }, 10, 3 );
