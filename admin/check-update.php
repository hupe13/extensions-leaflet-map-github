<?php
/**
 * Functions for update via Github
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

if ( leafext_is_github() ) {
	function leafext_plugin_meta_links( $links, $file ) {
		// var_dump($file); //"extensions-leaflet-map-github/extensions-leaflet-map.php"
		if ( strpos( LEAFEXT_PLUGIN_FILE, $file ) !== false ) {
			$local  = get_file_data(
				LEAFEXT_PLUGIN_DIR . 'extensions-leaflet-map.php',
				array(
					'Version' => 'Version',
				)
			);
			$remote = get_file_data(
				'https://raw.githubusercontent.com/hupe13/extensions-leaflet-map-github/main/extensions-leaflet-map.php',
				array( 'Version' => 'Version' )
			);
			if ( $local['Version'] != $remote['Version'] ) {
				$links[] = '<a href="https://github.com/hupe13/extensions-leaflet-map-github" target="_blank">' .
				'<span class="update-message notice inline notice-warning notice-alt">' .
				__( 'Update available', 'extensions-leaflet-map' ) .
				'</span>' .
				'</a>';
			}
		}
		return $links;
	}
	add_filter( 'plugin_row_meta', 'leafext_plugin_meta_links', 9, 2 );

	function leafext_updating_help() {
		echo '<h3>'.__( 'Github token to receive updates in WordPress way', 'extensions-leaflet-map' ).'</h3>';
		$setting = get_option( 'leafext_updating', array( 'token' => '' ) );
		if ( $setting && isset( $setting['token'] ) && $setting['token'] !== '' ) {
			$token = $setting['token'];
		} else {
			$token = '';
		}
		if ( $token === '' ) {
			$perm_denied = get_transient( 'leafext_github_403' );
			// var_dump($perm_denied);
			if ( false !== $perm_denied ) {
				echo sprintf(
					__( 'You need a %1$sGithub token%2$s to receive updates successfully.', 'extensions-leaflet-map' ),
					'<a href="https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens">',
					'</a>'
				) . '<br>';
			} else {
				echo sprintf(
					__( 'Maybe you need a %1$sGithub token%2$s to receive updates successfully.', 'extensions-leaflet-map' ),
					'<a href="https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens">',
					'</a>'
				) . '<br>';
			}
		}
	}

	// Init settings fuer update
	function leafext_updating_init() {
		add_settings_section( 'updating_settings', '', 'leafext_updating_help', 'leafext_settings_updating' );
		add_settings_field( 'leafext_updating', __( 'Github token', 'extensions-leaflet-map' ), 'leafext_form_updating', 'leafext_settings_updating', 'updating_settings' );
		register_setting( 'leafext_settings_updating', 'leafext_updating', 'leafext_validate_updating' );
	}
	add_action( 'admin_init', 'leafext_updating_init' );

	// Baue Abfrage der Params
	function leafext_form_updating() {
		$setting = get_option( 'leafext_updating', array( 'token' => '' ) );
		// var_dump($setting);
		if ( ! current_user_can( 'manage_options' ) ) {
			$disabled = ' disabled ';
		} else {
			$disabled = '';
		}
		//var_dump($setting);
		echo '<input ' . $disabled . ' type="text" size="30" name="leafext_updating[token]" value="' . $setting['token'] . '" />';
	}

	// Sanitize and validate input. Accepts an array, return a sanitized array.
	function leafext_validate_updating( $input ) {
		if ( ! empty( $_POST ) && check_admin_referer( 'leafext_updating', 'leafext_updating_nonce' ) ) {
			if ( isset( $_POST['submit'] ) ) {
				return $input;
			}
		}
		if ( isset( $_POST['delete'] ) ) {
			delete_option( 'leafext_updating' );
		}
		return false;
	}
}
