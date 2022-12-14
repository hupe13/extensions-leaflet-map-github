<?php
/**
* main admin page for elevation functions
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

include LEAFEXT_PLUGIN_DIR . '/admin/elevation/elevation.php';
include LEAFEXT_PLUGIN_DIR . '/admin/elevation/owntheme.php';
include LEAFEXT_PLUGIN_DIR . '/admin/elevation/waypoints.php';
include LEAFEXT_PLUGIN_DIR . '/admin/elevation/owncolors.php';
include LEAFEXT_PLUGIN_DIR . '/admin/elevation/sgpx.php';
include LEAFEXT_PLUGIN_DIR . '/admin/elevation/multielevation.php';

function leafext_elevation_tab() {
	$tabs = array (
		array (
			'tab' => 'elevation',
			'title' => __('Elevation Profile','extensions-leaflet-map'),
		),
		array (
			'tab' => 'elevationtheme',
			'title' => __('Theme / Colors','extensions-leaflet-map'),
		),
		array (
			'tab' => 'elevationwaypoints',
			'title' => __('Customize waypoints','extensions-leaflet-map'),
		),
		array (
			'tab' => 'multielevation',
			'title' => __('Multiple hoverable tracks','extensions-leaflet-map'),
		),
	);

	if ( LEAFEXT_SGPX_ACTIVE || LEAFEXT_SGPX_UNCLEAN_DB || LEAFEXT_SGPX_SGPX ) {
		if (current_user_can('manage_options')) {
			$tabs[] = array (
				'tab' => 'sgpxelevation',
				'title' => __('Migrate from wp-gpx-maps','extensions-leaflet-map'),
			);
		}
	}

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
	if( $active_tab == 'multielevation') {
		if (current_user_can('manage_options')) {
			echo '<form method="post" action="options.php">';
		} else {
			echo '<form>';
		}
		settings_fields('leafext_settings_multieleparams');
		do_settings_sections( 'leafext_settings_multieleparams' );
		if (current_user_can('manage_options')) {
			submit_button();
			submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		}
		echo '</form>';
	} else if( $active_tab == 'elevationtheme') {
		$ownoptions = get_option('leafext_values');
		if (!is_array($ownoptions)) {
			if (current_user_can('manage_options')) {
				echo '<form method="post" action="options.php">';
			} else {
				echo '<form>';
			}
			settings_fields('leafext_settings_elethemes');
			do_settings_sections( 'leafext_settings_elethemes' );
			if (current_user_can('manage_options')) {
				submit_button();
			}
			echo '</form>';
		}
		if (current_user_can('manage_options')) {
			echo '<form method="post" action="options.php">';
		} else {
			echo '<form>';
		}
		settings_fields('leafext_settings_color');
		do_settings_sections( 'leafext_settings_color' );
		if (current_user_can('manage_options')) {
			submit_button();
			submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		}
		echo '</form>';
		//
		if (current_user_can('manage_options')) {
			echo '<form method="post" action="options.php">';
		} else {
			echo '<form>';
		}
		settings_fields('leafext_settings_theme');
		do_settings_sections( 'leafext_settings_theme' );
		if (current_user_can('manage_options')) {
			submit_button();
			submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		}
		echo '</form>';
	} else if( $active_tab == 'sgpxelevation' ) {
		if (current_user_can('manage_options')) {
			echo '<form method="post" action="options.php">';
		} else {
			echo '<form>';
		}
		if ( LEAFEXT_SGPX_ACTIVE ) {
			settings_fields('leafext_settings_sgpxparams');
			do_settings_sections( 'leafext_settings_sgpxparams' );
			if (current_user_can('manage_options')) {
				submit_button();
			}
		} else if ( LEAFEXT_SGPX_UNCLEAN_DB ) {
			settings_fields('leafext_settings_sgpx_unclean_db');
			do_settings_sections( 'leafext_settings_sgpx_unclean_db' );
			if (current_user_can('manage_options')) {
				submit_button( __( 'Delete all settings from wp-gpx-maps!', 'extensions-leaflet-map' ), 'delete', 'delete', false);
			}
		} else if ( LEAFEXT_SGPX_SGPX ) {
			settings_fields('leafext_settings_sgpxparams');
			do_settings_sections( 'leafext_settings_sgpxparams' );
			if (current_user_can('manage_options')) {
				submit_button( __( "I don't need this anymore. sgpx is always interpreted as elevation.", 'extensions-leaflet-map' ), 'delete', 'delete', false);
			}
		} else {
			echo '<h2>'.leafext_elevation_tab().'</h2>';
			echo __('wp-gpx-maps is not installed and nothing is configured.',"extensions-leaflet-map");
		}
		echo '</form>';
	} else if( $active_tab == 'elevationwaypoints' ) {
		if (current_user_can('manage_options')) {
			echo '<form method="post" action="options.php">';
		} else {
			echo '<form>';
		}
		settings_fields('leafext_waypoints');
		do_settings_sections( 'leafext_waypoints' );
		if (current_user_can('manage_options')) {
			submit_button();
			submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		}
		echo '</form>';
	} else if( $active_tab == 'elevation' ) { //Last tab!!!
		echo '<h2>'.leafext_elevation_tab().'</h2>';
		if (current_user_can('manage_options')) {
			echo '<form method="post" action="options.php">';
		} else {
			echo '<form>';
		}
		settings_fields('leafext_settings_eleparams');
		do_settings_sections( 'leafext_settings_eleparams' );
		if (current_user_can('manage_options')) {
			submit_button();
			submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		}
		echo '</form>';
	} else {
		echo "Error";
	}
}
