<?php

function leafext_elevation_tab() {
	$tabs = array (
		array (
			'tab' => 'elevation',
			'title' => __('Elevation Profile','extensions-leaflet-map'),
			),
		array (
			'tab' => 'elevationoptions',
			'title' => __('Elevation Chart Options','extensions-leaflet-map'),
		),
		array (
			'tab' => 'multielevation',
			'title' => __('Multiple hoverable tracks','extensions-leaflet-map'),
		),
		array (
			'tab' => 'elevationtheme',
			'title' => __('Own theme','extensions-leaflet-map'),
		),
	);

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : '';
	$textheader = '<div class="nav-tab-wrapper">';

	foreach ( $tabs as $tab) {
		$textheader = $textheader. '<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab='.$tab['tab'].'" class="nav-tab';
		$active = ( $active_tab == $tab['tab'] ) ? ' nav-tab-active' : '' ;
		$textheader = $textheader. $active;
		$textheader = $textheader. '">'.$tab['title'].'</a>'."\n";
	}

	//
	$textheader = $textheader. '</div>';
	return $textheader;
}

function leafext_admin_elevation($active_tab) {
	if( $active_tab == 'elevation') {
		echo '<h2>'.leafext_elevation_tab().'</h2>';
		include LEAFEXT_PLUGIN_DIR . '/admin/elevation/elevation.php';
	} else if( $active_tab == 'multielevation') {
		echo '<h2>'.leafext_elevation_tab().'</h2>';
		include LEAFEXT_PLUGIN_DIR . '/admin/elevation/multi.php';
	} else if( $active_tab == 'elevationtheme') {
		echo '<form method="post" action="options.php">';
		settings_fields('leafext_settings_theme');
		do_settings_sections( 'leafext_settings_theme' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		echo '</form>';
	} else if( $active_tab == 'elevationoptions' ) {
		echo '<form method="post" action="options.php">';
		settings_fields('leafext_settings_eleparams');
		do_settings_sections( 'leafext_settings_eleparams' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		echo '</form>';
	}
}
?>
