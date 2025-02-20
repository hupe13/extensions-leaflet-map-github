<?php
/**
 *  Functions to use for PUC
 *
 * @package Updates for Leaflet Map Extensions and DSGVO Github Versions
 **/

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Repos on Github
function leafext_get_repos() {
	$git_repos                           = array();
	$git_repos['extensions-leaflet-map'] = array(
		'url'     => 'https://github.com/hupe13/extensions-leaflet-map-github/',
		'local'   => WP_PLUGIN_DIR . '/' . leafext_github_dir( 'extensions-leaflet-map' ) . '/extensions-leaflet-map.php',
		'release' => false,
	);
	$git_repos['dsgvo-leaflet-map']      = array(
		'url'     => 'https://github.com/hupe13/extensions-leaflet-map-dsgvo/',
		'local'   => WP_PLUGIN_DIR . '/' . leafext_github_dir( 'dsgvo-leaflet-map' ) . '/dsgvo-leaflet-map.php',
		'release' => true,
	);
	// this plugin
	$git_repos['leafext-update-github'] = array(
		'url'     => 'https://github.com/hupe13/leafext-update-github/',
		'local'   => WP_PLUGIN_DIR . '/' . leafext_github_dir( 'leafext-update-github' ) . '/leafext-update-github.php',
		'release' => true,
	);

	foreach ( $git_repos as $git_repo => $value ) {
		if ( ! file_exists( $git_repos[ $git_repo ]['local'] ) ) {
			unset( $git_repos[ $git_repo ] );
		}
	}
	return $git_repos;
}

// param name of php file, returns dir
// in welchem Verzeichnis ist das Plugin installiert?
function leafext_github_dir( $slug ) {
	$leafext_plugins = glob( WP_PLUGIN_DIR . '/*/' . $slug . '.php/' );
	if ( count( $leafext_plugins ) === 0 ) {
		return '';
	} else {
		foreach ( $leafext_plugins as $leafext_plugin ) {
			$dir = basename( dirname( $leafext_plugin ) );
			if ( $dir == 'leafext-update-github' ) {
				return $dir;
			}
			if ( $dir !== $slug ) {
				return $dir;
			}
		}
		return '';
	}
}

// To get Updates from Github, plugin must be active on main site
function leafext_active_on_main_site( $slug ) {
	if ( is_main_site() && is_plugin_active( $slug ) ) {
		return true;
	}
	if ( is_plugin_active_for_network( $slug ) ) {
		return true;
	}
	if ( is_multisite() ) {
		$active = false;
		switch_to_blog( get_main_site_id() );
		if ( is_plugin_active( $slug ) ) {
			$active = true;
		}
		restore_current_blog();
		if ( $active ) {
			return true;
		}
	}
	return false;
}

function leafext_can_updates() {
	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
	}
	if ( is_plugin_active_for_network( 'leafext-update-github/leafext-update-github.php' ) ) {
		return true;
	}
	$git_repos = leafext_get_repos();
	foreach ( $git_repos as $git_repo => $value ) {
		$split    = array_map( 'strrev', explode( '/', strrev( $git_repos[ $git_repo ]['local'] ) ) );
		$slug_php = trailingslashit( $split[1] ) . $split[0];
		if ( leafext_active_on_main_site( $slug_php ) ) {
			return true;
		}
	}
	return false;
}

// Updates from Github
function leafext_goto_main_site() {
	if ( ! leafext_can_updates() ) {
		echo '<h3>' . esc_html__( 'Updates in WordPress way', 'leafext-update-github' ) . '</h3>';
		printf(
			/* translators: %s is a link. */
			esc_html__(
				'If you want to receive updates in WordPress way, go to the %1$smain site dashboard%2$s and activate the plugin here or install and activate %3$s.',
				'leafext-update-github'
			),
			'<a href="' . esc_url( get_site_url( get_main_site_id() ) ) . '/wp-admin/plugins.php">',
			'</a>',
			'<a href="https://github.com/hupe13/leafext-update-github">Updates for Leaflet Map Extensions and DSGVO Github Versions</a>'
		);
	}
}
