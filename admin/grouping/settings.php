<?php
/**
 * Admin page for parentgroup functions
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_parentgroup_init() {
	add_settings_section( 'parentgroup_settings', '', '', 'leafext_settings_parentgroup' );
	$fields = leafext_parentgroup_params();
	foreach ( $fields as $field ) {
		//if ( $field['size'] > 0 ) {
			add_settings_field(
				'leafext_parentgroup[' . $field['param'] . ']',
				$field['desc'],
				'leafext_form_parentgroup',
				'leafext_settings_parentgroup',
				'parentgroup_settings',
				$field['param']
			);
		//}
	}
	register_setting( 'leafext_settings_parentgroup', 'leafext_parentgroup', 'leafext_validate_parentgroup' );
}
add_action( 'admin_init', 'leafext_parentgroup_init' );

function leafext_form_parentgroup( $field ) {
	$params   = leafext_parentgroup_params();
	$defaults = array();
	foreach ( $params as $param ) {
		$defaults[ $param['param'] ] = $param['default'];
	}

	$options = leafext_parentgroup_settings();
	// var_dump($options);
	$option = leafext_array_find2( $field, $params );
	// var_dump($option);

	if ( ! current_user_can( 'manage_options' ) ) {
		$disabled = ' disabled ';
	} else {
		$disabled = '';
	}

	foreach ( $options as $key => $value ) {
		if ( $key === $field ) {
			echo wp_kses_post( __( 'You can change it with', 'extensions-leaflet-map' ) . ' <code>' . $key . '</code><br>' );
			if ( isset( $option['moredesc'] ) && $option['moredesc'] !== '' ) {
				echo wp_kses_post( $option['moredesc'] ) . '<br>';
			}
			if ( $option['size'] > 0 ) {
				if ( $value !== $defaults[ $key ] ) {
					echo wp_kses_post( __( 'Plugins Default', 'extensions-leaflet-map' ) . ': <code>' . esc_html( $defaults[ $key ] ) . '</code><br>' );
				}
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<input ' . $disabled . ' type="text" size=' . $option['size'] . ' name="leafext_parentgroup[' . $key . ']" value="' . htmlspecialchars_decode( htmlentities( $value, ENT_QUOTES ), ENT_NOQUOTES ) . '" />';
			}
		}
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_parentgroup( $options ) {
	$post = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
	if ( ! empty( $post ) && check_admin_referer( 'leafext_parentgroup', 'leafext_parentgroup_nonce' ) ) {
		if ( isset( $post['submit'] ) ) {
			$options['class']      = sanitize_text_field( $options['class'] );
			$options['tolerance']  = (int) $options['tolerance'];
			$options['popupclose'] = (int) $options['popupclose'];
			delete_option( 'leafext_canvas' ); // old option
			return $options;
		}
		if ( isset( $post['delete'] ) ) {
			delete_option( 'leafext_parentgroup' );
			delete_option( 'leafext_canvas' );
		}
		return false;
	}
}

// Draw the menu page itself
function leafext_parentgroup_admin_page() {
	if ( current_user_can( 'manage_options' ) ) {
		echo '<form method="post" action="options.php">';
	} else {
		echo '<form>';
	}
	settings_fields( 'leafext_settings_parentgroup' );
	do_settings_sections( 'leafext_settings_parentgroup' );
	if ( current_user_can( 'manage_options' ) ) {
		wp_nonce_field( 'leafext_parentgroup', 'leafext_parentgroup_nonce' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false );
	}
	echo '</form>';
}
