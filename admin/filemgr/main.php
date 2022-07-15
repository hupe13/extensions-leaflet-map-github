<?php
/**
* filemgr main
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

include LEAFEXT_PLUGIN_DIR . '/admin/filemgr/uploader.php';
include LEAFEXT_PLUGIN_DIR . '/admin/filemgr/filemgr-settings.php';
include LEAFEXT_PLUGIN_DIR . '/admin/filemgr/filemgr.php';
include LEAFEXT_PLUGIN_DIR . '/admin/filemgr/managefiles_functions.php';

function leafext_filemgr_tab() {
	$TB_iframe = isset($_GET['TB_iframe']) ? $_GET['TB_iframe'] : "";
	if ( $TB_iframe == true ) return "";
	$tabs = array ();
	if (current_user_can('manage_options')) {
		$tabs[] = array (
			'tab' => 'filemgr',
			'title' => __('Files for Leaflet Map','extensions-leaflet-map'),
		);
	}
	$tabs[] =	array (
		'tab' => 'filemgr-list',
		'title' => __('List Files','extensions-leaflet-map'),
	);
	$tabs[] =	array (
		'tab' => 'filemgr-dir',
		'title' => __('Tracks from all files in a directory','extensions-leaflet-map'),
	);


	$page = isset($_GET['page']) ? $_GET['page'] : "";
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : '';
	$textheader = '<div class="nav-tab-wrapper">';

	foreach ( $tabs as $tab) {
		$textheader = $textheader. '<a href="?page='.$page.'&tab='.$tab['tab'].'" class="nav-tab';
		$active = ( $active_tab == $tab['tab'] ) ? ' nav-tab-active' : '' ;
		$textheader = $textheader. $active;
		$textheader = $textheader. '">'.$tab['title'].'</a>'."\n";
	}

	$textheader = $textheader. '</div>';
	return $textheader;
}

function leafext_admin_filemgr($active_tab) {
	echo '<h2>'.leafext_filemgr_tab().'</h2>';
	if( $active_tab == 'filemgr') {
		echo '<form method="post" action="options.php">';
		settings_fields('leafext_settings_filemgr');
		do_settings_sections( 'leafext_settings_filemgr' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		echo '</form>';
	} else if( $active_tab == 'filemgr-list') {
		leafext_managefiles();
	} else if ( $active_tab == 'filemgr-dir') {
		include "leaflet-directory.php";
		echo leafext_directory_help_text();
	}
}
