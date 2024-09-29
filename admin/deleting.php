<?php
/**
 * Functions for delete all settings
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Init settings fuer deleting
function leafext_deleting_init() {
	add_settings_section( 'deleting_settings', '', '', 'leafext_settings_deleting' );
	add_settings_field( 'leafext_deleting', __( 'Delete all plugin settings when deleting the plugin?', 'extensions-leaflet-map' ), 'leafext_form_deleting', 'leafext_settings_deleting', 'deleting_settings' );
	register_setting( 'leafext_settings_deleting', 'leafext_deleting', 'leafext_validate_deleting' );
}
add_action( 'admin_init', 'leafext_deleting_init' );

// Baue Abfrage der Params
function leafext_form_deleting() {
	$setting = get_option( 'leafext_deleting' );
	// var_dump($setting);
	if ( ! current_user_can( 'manage_options' ) ) {
		$disabled = ' disabled ';
	} else {
		$disabled = '';
	}

	echo '<input ' . esc_attr( $disabled ) . ' type="radio" name="leafext_deleting[on]" value="1" ';
	checked( ! ( isset( $setting['on'] ) && $setting['on'] === '0' ) );
	echo '> ' . esc_html__( 'yes', 'extensions-leaflet-map' ) . ' &nbsp;&nbsp; ';
	echo '<input ' . esc_attr( $disabled ) . ' type="radio" name="leafext_deleting[on]" value="0" ';
	checked( isset( $setting['on'] ) && $setting['on'] === '0' );
	echo '> ' . esc_html__( 'no', 'extensions-leaflet-map' );
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_deleting( $input ) {
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_deleting', 'leafext_deleting_nonce' ) ) {
		if ( isset( $_POST['submit'] ) ) {
			return $input;
		}
	}
}
