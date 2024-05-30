<?php
/**
 * Admin functions for wp-gpx-maps
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_sgpxparams_init() {
	add_settings_section( 'sgpxparams_settings', leafext_elevation_tab(), 'leafext_sgpx_help_text', 'leafext_settings_sgpxparams' );
	$fields = leafext_sgpx_params();
	foreach ( $fields as $field ) {
		$trenn = '';
		if ( isset( $field['next'] ) ) {
			$trenn = '<div style="border-top: ' . $field['next'] . 'px solid #646970"></div>';
		}
		add_settings_field( 'leafext_sgpxparams[' . $field['param'] . ']', $trenn . $field['shortdesc'], 'leafext_form_sgpx', 'leafext_settings_sgpxparams', 'sgpxparams_settings', $field['param'] );
	}
	register_setting( 'leafext_settings_sgpxparams', 'leafext_sgpxparams', 'leafext_validate_sgpx_options' );
}
add_action( 'admin_init', 'leafext_sgpxparams_init' );

// Baue Abfrage der Params
function leafext_form_sgpx( $field ) {
	$options  = leafext_sgpx_params();
	$option   = leafext_array_find2( $field, $options );
	$settings = leafext_sgpx_settings();
	$setting  = $settings[ $field ];
	if ( isset( $option['next'] ) ) {
		echo '<div style="border-top: ' . esc_html( $option['next'] ) . 'px solid #646970"></div>';
	}
	if ( $option['desc'] !== '' ) {
		echo '<p>' . wp_kses_post( $option['desc'] ) . '</p>';
	}

	// echo __("You can change it for each map with", "extensions-leaflet-map").' <code>'.$option['param']. '</code><br>';
	if ( ! is_array( $option['values'] ) ) {

		if ( $setting !== $option['default'] ) {
			// var_dump($setting,$option['default']);
			echo esc_html__( 'Plugins Default', 'extensions-leaflet-map' ) . ': ';
			echo $option['default'] ? 'true' : 'false';
			echo '<br>';
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<input type="radio" name="leafext_sgpxparams[' . $option['param'] . ']" value="1" ';
		echo $setting ? 'checked' : '';
		echo '> true &nbsp;&nbsp; ';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<input type="radio" name="leafext_sgpxparams[' . $option['param'] . ']" value="0" ';
		echo ( ! $setting ) ? 'checked' : '';
		echo '> false ';
	} else {
		$plugindefault = is_string( $option['default'] ) ? $option['default'] : ( $option['default'] ? '1' : '0' );
		$setting       = is_string( $setting ) ? $setting : ( $setting ? '1' : '0' );
		if ( $setting !== $plugindefault ) {
			// var_dump("Option: ",$option['default'],"Plugindefault: ",$plugindefault,"Setting: ",$setting);
			echo esc_html( __( 'Plugins Default:', 'extensions-leaflet-map' ) . ' ' . $plugindefault ) . '<br>';
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<select name="leafext_sgpxparams[' . $option['param'] . ']">';
		foreach ( $option['values'] as $para ) {
			echo '<option ';
			if ( is_bool( $para ) ) {
				$para = ( $para ? '1' : '0' );
			}
			if ( $para === $setting ) {
				echo ' selected="selected" ';
			}
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo 'value="' . $para . '" >' . $para . '</option>';
		}
		echo '</select>';
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_sgpx_options( $options ) {
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_elevation', 'leafext_elevation_nonce' ) ) {
		if ( isset( $_POST['submit'] ) ) {
			return $options;
		}
		if ( isset( $_POST['delete'] ) ) {
			delete_option( 'leafext_sgpxparams' );
		}
		return false;
	}
}

// init sgpx_unclean_db
function leafext_sgpx_unclean_db_init() {
	add_settings_section( 'leafext_sgpx_unclean_db', leafext_elevation_tab(), 'leafext_sgpx_help_text', 'leafext_settings_sgpx_unclean_db' );
	add_settings_field( 'leafext_sgpx_unclean_db', 'leafext_sgpx_unclean_db', 'leafext_form_sgpx_unclean_db', 'leafext_settings_sgpx_unclean_db', 'leafext_sgpx_unclean_db_settings' );
	register_setting( 'leafext_settings_sgpx_unclean_db', 'leafext_sgpx_unclean_db', 'leafext_validate_sgpx_unclean_db' );
}
add_action( 'admin_init', 'leafext_sgpx_unclean_db_init' );

function leafext_form_sgpx_unclean_db() {
}

// Submit sgpx_unclean_db
function leafext_validate_sgpx_unclean_db() {
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_elevation', 'leafext_elevation_nonce' ) ) {
		if ( isset( $_POST['delete'] ) ) {
			if ( $_POST['delete'] === esc_html__( 'Delete all settings from wp-gpx-maps!', 'extensions-leaflet-map' ) ) {
				global $wpdb;
				// phpcs:ignore
				$option_names = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'wpgpxmaps_%' " );
				foreach ( $option_names as $key => $value ) {
					delete_option( $value->option_name );
				}
			}
		}
		return false;
	}
}

// Helptext
function leafext_sgpx_help_text() {
	echo '<h2>WP GPX Maps</h2>';
	printf(
		/* translators: %1$s - a name ,%2$s %3$s - href, %4$s %5$s - shortcodes  */
		esc_html__( 'Many thanks to %1$s for his %2$sexcellent plugin%3$s, which I used myself for a long time. Unfortunately it needed some rework, especially to make wp-gpx-maps and leaflet-map work together. At some point it didn\'t work for me at all. So some of its features are included in the shortcode %4$s. Since version 2.2. it interprets the shortcode %5$s.', 'extensions-leaflet-map' ),
		'<a href="https://profiles.wordpress.org/bastianonm/">Bastianon Massimo</a>',
		'<a href="https://wordpress.org/plugins/wp-gpx-maps/">',
		'</a>',
		'<code>elevation</code>',
		'<code>sgpx</code>'
	);
	echo '<h2>' . esc_html__( 'Migration', 'extensions-leaflet-map' ) . '</h2>';
	printf(
		/* translators: %s are shortcodes. */
		esc_html__( 'This page helps you to migrate from %1$s to %2$s.', 'extensions-leaflet-map' ),
		'<code>sgpx</code>',
		'<code>elevation</code>'
	);

	echo ' ' . sprintf(
		/* translators: %s is a href. */
		esc_html__(
			'See documentation and examples in %1$shere%2$s.',
			'extensions-leaflet-map'
		),
		'<a href="https://leafext.de/en/doku/sgpxelevation/">',
		'</a>'
	);

	echo '<ul>';
	if ( LEAFEXT_SGPX_ACTIVE ) {
		echo '<li>';
		/* translators: %s is a link. */
		printf( esc_html__( 'Configure your default %s settings.', 'extensions-leaflet-map' ), '<a href="?page=' . esc_html( LEAFEXT_PLUGIN_SETTINGS ) . '&tab=elevation">elevation</a>' );
		echo '</li><li>';
		/* translators: %s are values. */
		echo sprintf( esc_html__( 'Select %1$s to interpret the %2$s parameters as %3$s.', 'extensions-leaflet-map' ), '"1"', 'sgpx', 'elevation' ) . ' ';
		printf(
			/* translators: %s is a plugin name. */
			esc_html__( 'It may be that %s does not work with your theme. Then this is the only option.', 'extensions-leaflet-map' ),
			'WP GPX Maps'
		);
		echo '</li><li>';
		/* translators: %s are shortcodes / values. */
		printf( esc_html__( 'If you want to test it first: select %1$s and write in your test page / post %2$s.', 'extensions-leaflet-map' ), '"leaflet"', '<code>[leaflet-map height="1"]</code>' );
		echo '</li><li>';
		/* translators: %s are a plugin name. */
		printf( esc_html__( 'If you are happy with this and if you don\'t use the track management of %1$s, you can deactivate and delete the plugin %2$s.', 'extensions-leaflet-map' ), 'wp-gpx-maps', 'wp-gpx-maps' );
		echo '</li>';
		echo '<li>';
		/* translators: %s is a link. */
		printf( esc_html__( 'To manage your tracks now, you can use %1$sFiles for Leaflet Map%2$s.', 'extensions-leaflet-map' ), '<a href="?page=' . esc_html( LEAFEXT_PLUGIN_SETTINGS ) . '&tab=filemgr">', '</a>' );
		echo '</li>';
		echo '<li>';
		echo esc_html__( 'If you have deleted the plugin, call this page again.', 'extensions-leaflet-map' );
		echo '</li>';
	} else { // nicht mehr aktiv
		/* translators: %s are plugin names / option. */
		echo '<li>' . sprintf( esc_html__( '%1$s is not active, %2$s parameters will interpreted with %3$s.', 'extensions-leaflet-map' ), 'wp-gpx-maps', 'sgpx', 'elevation' ) . '</li>';
		if ( LEAFEXT_SGPX_UNCLEAN_DB ) {
			echo '<li>' . esc_html__( 'You have wp-gpx-maps uninstalled, but some of its options exist in the database. You should delete them.', 'extensions-leaflet-map' ) . '</li>';
		} elseif ( LEAFEXT_SGPX_SGPX ) {
			echo '<li>' . esc_html__( 'You can delete all settings on this page.', 'extensions-leaflet-map' ) . '</li>';
		}
	}
	echo '</ul>';
}
