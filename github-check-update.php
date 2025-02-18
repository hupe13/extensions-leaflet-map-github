<?php
/**
 *  Github Update Management
 *
 * @package Update Management for Leaflet Map and its Extensions Github
 **/

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// param name of php file, returns dir
// in welchem Verzeichnis ist das Plugin installiert?
function leafext_github_dir( $slug ) {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$all_plugins  = get_plugins();
	$is_installed = preg_grep( '/' . $slug . '.php/', array_keys( $all_plugins ) );
	if ( count( $is_installed ) === 0 ) {
		return '';
	} else {
		foreach ( $is_installed as $installed ) {
			$split = explode( '/', $installed );
			if ( $installed == 'leafext-update-github/leafext-update-github.php' ) {
				return $split[0];
			}
			if ( $split[0] . '.php' !== $split[1] ) {
				return $split[0];
			}
		}
		return '';
	}
}

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

require_once __DIR__ . '/pkg/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
use YahnisElsts\PluginUpdateChecker\v5p5\Vcs\GitHubApi;

$token = leafext_query_token_needed();

if ( false === $token['leafext_github_denied'] || $token['leafext_update_token'] !== '' ) {
	$git_repos         = leafext_get_repos();
	$my_update_checker = array();
	foreach ( $git_repos as $git_repo => $value ) {
		if ( $git_repos[ $git_repo ]['local'] !== $git_repo ) {
			$my_update_checker[ $git_repo ] = PucFactory::buildUpdateChecker(
				$git_repos[ $git_repo ]['url'],
				$git_repos[ $git_repo ]['local'],
				basename( dirname( $git_repos[ $git_repo ]['local'] ) ),
			);

			// Set the branch that contains the stable release.
			$my_update_checker[ $git_repo ]->setBranch( 'main' );

			if ( $token['leafext_update_token'] !== '' ) {
				// Optional: If you're using a private repository, specify the access token like this:
				$my_update_checker[ $git_repo ]->setAuthentication( $token['leafext_update_token'] );
			}

			// update tags or release
			if ( ! $git_repos[ $git_repo ]['release'] ) {
				$my_update_checker[ $git_repo ]->addFilter(
					'vcs_update_detection_strategies',
					function ( $strategies ) {
						unset( $strategies[ GitHubApi::STRATEGY_LATEST_RELEASE ] );
						return $strategies;
					}
				);
			}
		}
	}
}

function leafext_update_puc_error( $error, $response = null, $url = null, $slug = null ) {
	if ( ! isset( $slug ) ) {
		return;
	}
	$git_repos  = leafext_get_repos();
	$valid_slug = false;
	foreach ( $git_repos as $git_repo => $value ) {
		if ( $slug === basename( dirname( $git_repos[ $git_repo ]['local'] ) ) ) {
			$valid_slug = true;
		}
	}
	if ( ! $valid_slug ) {
		return;
	}
	if ( wp_remote_retrieve_response_code( $response ) === 403 ) {
		// var_dump( 'Permission denied' );
		set_transient( 'leafext_github_403', true, DAY_IN_SECONDS );
	}
}
add_action( 'puc_api_error', 'leafext_update_puc_error', 10, 4 );
