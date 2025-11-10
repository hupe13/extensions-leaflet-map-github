<?php
/**
 * Uninstall handler.
 *
 * @package Extensions for Leaflet Map
 */

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

global $wpdb;

$leafext_setting = get_option( 'leafext_deleting' );
if ( ! ( isset( $leafext_setting['on'] ) && $leafext_setting['on'] === '0' ) ) {
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$leafext_option_names = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'leafext_%' " );
	foreach ( $leafext_option_names as $leafext_key => $leafext_value ) {
		delete_option( $leafext_value->option_name );
		// for site options in Multisite
		delete_site_option( $leafext_value->option_name );
	}
}
