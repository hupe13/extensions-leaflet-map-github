<?php

function leafext_admin_cluster($active_tab) {
	if( $active_tab == 'clustergroup') {
		echo '<h2>'.leafext_cluster_tab().'</h2>';
		include LEAFEXT_PLUGIN_DIR . '/admin/cluster/clustergroup.php';
	} else if( $active_tab == 'markercluster' ) {
		echo '<form method="post" action="options.php">';
		settings_fields('leafext_settings_cluster');
		do_settings_sections( 'leafext_settings_cluster' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		echo '</form>';
	} else if ( $active_tab == 'clusterplacementstrategies' ) {
		leafext_admin_placementstrategies();
	}
}

function leafext_cluster_tab() {
	$tabs = array (
		array (
			'tab' => 'markercluster',
			'title' => __('Leaflet.markercluster','extensions-leaflet-map'),
		),
		array (
			'tab' => 'clustergroup',
			'title' => __('Leaflet.FeatureGroup.SubGroup','extensions-leaflet-map'),
		),
		array (
			'tab' => 'clusterplacementstrategies',
			'title' => __('Leaflet.MarkerCluster.PlacementStrategies','extensions-leaflet-map'),
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