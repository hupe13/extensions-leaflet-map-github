<?php
/**
 *  Admin PUC Settings
 *
 * @package Updates for plugins from hupe13 hosted on Github
 **/

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Init settings fuer update
if ( ! function_exists( 'leafext_update_init' ) ) {
	function leafext_update_init() {
		add_settings_section( 'updating_settings', '', '', 'leafext_settings_updating' );
		add_settings_field( 'leafext_updating', esc_html__( 'Github token', 'leafext-update-github' ), 'leafext_form_updating', 'leafext_settings_updating', 'updating_settings' );
		register_setting( 'leafext_settings_updating', 'leafext_updating', 'leafext_validate_updating' );
	}
}
add_action( 'admin_init', 'leafext_update_init' );

// Baue Abfrage der Params
if ( ! function_exists( 'leafext_form_updating' ) ) {
	function leafext_form_updating() {
		$setting = get_option( 'leafext_updating', array( 'token' => '' ) );
		if ( ! current_user_can( 'manage_options' ) ) {
			$disabled = ' disabled ';
		} else {
			$disabled = '';
		}
		// var_dump($setting);
		//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- string not changeable
		echo '<input ' . $disabled . ' type="text" size="30" name="leafext_updating[token]" value="' . $setting['token'] . '" />';
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
if ( ! function_exists( 'leafext_validate_updating' ) ) {
	function leafext_validate_updating( $input ) {
		if ( ! empty( $_POST ) && check_admin_referer( 'leafext_updating', 'leafext_updating_nonce' ) ) {
			if ( isset( $_POST['submit'] ) ) {
				return $input;
			}
			if ( isset( $_POST['delete'] ) ) {
				delete_option( 'leafext_updating' );
			}
			return false;
		}
	}
}

if ( ! function_exists( 'leafext_query_token_needed' ) ) {
	function leafext_query_token_needed() {
		$main_site_id = get_main_site_id();
		if ( is_multisite() ) {
			$setting = get_blog_option( $main_site_id, 'leafext_updating', array( 'token' => '' ) );
		} else {
			$setting = get_option( 'leafext_updating', array( 'token' => '' ) );
		}
		if ( $setting && isset( $setting['token'] ) && $setting['token'] !== '' ) {
			$leafext_update_token = $setting['token'];
		} else {
			$leafext_update_token = '';
		}
		if ( is_multisite() ) {
			switch_to_blog( $main_site_id );
			$leafext_github_denied = get_transient( 'leafext_github_403' );
			restore_current_blog();
		} else {
			$leafext_github_denied = get_transient( 'leafext_github_403' );
		}
		return array(
			'leafext_update_token'  => $leafext_update_token,
			'leafext_github_denied' => $leafext_github_denied,
		);
	}
}

// Github Token form
if ( ! function_exists( 'leafext_token_form' ) ) {
	function leafext_token_form() {
		$token = leafext_query_token_needed();
		echo '<h3>' . esc_html__( 'Github token to receive updates in WordPress way', 'leafext-update-github' ) . '</h3>';
		if ( $token['leafext_update_token'] === '' ) {
			// var_dump($leafext_github_denied);
			if ( false !== $token['leafext_github_denied'] ) {
				echo sprintf(
					/* translators: %s is a link. */
					esc_html__( 'You need a %1$sGithub token%2$s to receive updates successfully.', 'leafext-update-github' ),
					'<a href="https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens">',
					'</a>'
				) . '<br>';
			} else {
				echo sprintf(
					/* translators: %s is a link. */
					esc_html__( 'Maybe you need a %1$sGithub token%2$s to receive updates successfully.', 'leafext-update-github' ),
					'<a href="https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens">',
					'</a>'
				) . '<br>';
			}
		}
		echo '<form method="post" action="options.php">';
		settings_fields( 'leafext_settings_updating' );
		do_settings_sections( 'leafext_settings_updating' );
		if ( current_user_can( 'manage_options' ) ) {
			wp_nonce_field( 'leafext_updating', 'leafext_updating_nonce' );
			submit_button();
			submit_button( esc_html__( 'Reset', 'leafext-update-github' ), 'delete', 'delete', false );
		}
		echo '</form>';
	}
}
