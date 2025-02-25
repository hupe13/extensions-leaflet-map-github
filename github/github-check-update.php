<?php
/**
 *  Github Plugin Update Checker
 *
 * @package Updates for Leaflet Map Extensions and DSGVO Github Versions
 **/

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';
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
		if ( $slug === dirname( plugin_basename( $git_repos[ $git_repo ]['local'] ) ) ) {
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
