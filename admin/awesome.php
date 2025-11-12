<?php
/**
 * Functions for delete all settings
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Init settings fuer awesome
function leafext_awesome_init() {
	add_settings_section( 'awesome_settings', '', '', 'leafext_settings_awesome' );
	add_settings_field(
		'leafext_awesome',
		__(
			'Check',
			'extensions-leaflet-map'
		),
		'leafext_form_awesome',
		'leafext_settings_awesome',
		'awesome_settings'
	);
	register_setting( 'leafext_settings_awesome', 'leafext_awesome', 'leafext_validate_awesome' );
}
add_action( 'admin_init', 'leafext_awesome_init' );

// Baue Abfrage der Params
function leafext_form_awesome() {
	$setting = get_option( 'leafext_awesome' );
	if ( ! current_user_can( 'manage_options' ) ) {
		$disabled = ' disabled ';
	} else {
		$disabled = '';
	}

	echo '<input ' . esc_attr( $disabled ) . ' type="radio" name="leafext_awesome[on]" value="1" ';
	checked( ! ( isset( $setting['on'] ) && $setting['on'] === '0' ) );
	echo '> ' . esc_html__( 'enable (default)', 'extensions-leaflet-map' ) . ' &nbsp;&nbsp; ';
	echo '<input ' . esc_attr( $disabled ) . ' type="radio" name="leafext_awesome[on]" value="0" ';
	checked( isset( $setting['on'] ) && $setting['on'] === '0' );
	echo '> ' . esc_html__( 'disable', 'extensions-leaflet-map' );
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_awesome( $input ) {
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_awesome', 'leafext_awesome_nonce' ) ) {
		if ( isset( $_POST['submit'] ) ) {
			return $input;
		}
	}
}

function leafext_help_awesome() {
	if ( ! ( is_singular() || is_archive() ) ) {
		$text = '<h2 id="leaflet.zoomhome">Font Awesome</h2>';
	} else {
		$text = '';
	}
	$text = $text . '<p>' . wp_sprintf(
		/* translators: %1$s is a link, %2$s is the plugin name,. */
		esc_html__( 'This shortcode requires %1$s, version 6 is included in the plugin.', 'extensions-leaflet-map' ),
		'<a href="https://fontawesome.com/download">Font Awesome</a>'
	) . '</p>';
	$text .= '<p>' . __( 'Your theme or one of your plugins may provide Font Awesome.', 'extensions-leaflet-map' ) . '<br>';
	$text .= __( 'The plugin checks this and only loads its own if no others are found.', 'extensions-leaflet-map' ) . '<br>';
	$text .= __( 'If the check does not work for you, you can disable it so that your fonts are loaded.', 'extensions-leaflet-map' ) . '</p>';

	if ( leafext_plugin_active( 'elementor' ) ) {
		$text .= '<p>' . wp_sprintf(
			/* translators: %1$s is Elentor, %2$s is the link to the bug. */
			__( 'You are using %1$s, there is a %2$sbug%3$s.', 'extensions-leaflet-map' ),
			'Elementor',
			'<a href="https://github.com/elementor/elementor/issues/30358" target="_blank">',
			'</a>'
		) . '</p>';
	}

	$text .= '<p>' . wp_sprintf(
		/* translators: %1$s is zoomhomemap, %2$s is leaflet-extramarker, %3$s parentgroup. */
		__( 'This setting is valid for %1$s, %2$s and %3$s.', 'extensions-leaflet-map' ),
		'<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=zoomhome"><code>zoomhomemap</code></a>',
		'<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=extramarker"><code>leaflet-extramarker</code></a>',
		'<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=parentgroup"><code>parentgroup</code></a>'
	) . '</p>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		$allowed_html          = wp_kses_allowed_html( 'post' );
		$allowed_html['style'] = true;
		echo wp_kses( $text, $allowed_html );
	}
}
