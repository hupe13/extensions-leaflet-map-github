<?php
/**
 * Backend Menus
 *
 * @package Extensions for Leaflet Map Github Version
 **/

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

/**
 * For translating
 */
function leafext_extensions_update_textdomain( $mofile, $domain ) {
	if ( 'extensions-leaflet-map' === $domain ) {
		if ( file_exists( LEAFEXT_PLUGIN_DIR . '/lang/extensions-leaflet-map-' . get_locale() . '.mo' ) ) {
			$mofile = LEAFEXT_PLUGIN_DIR . '/lang/extensions-leaflet-map-' . get_locale() . '.mo';
		}
	}
	return $mofile;
}
add_filter( 'load_textdomain_mofile', 'leafext_extensions_update_textdomain', 10, 2 );

// https://make.wordpress.org/core/2024/03/05/introducing-plugin-dependencies-in-wordpress-6-5/
function leafext_extensions_leaflet_map_to_github( $slug ) {
	if ( 'extensions-leaflet-map' === $slug ) {
		$slug = LEAFEXT_PLUGIN_SETTINGS;
	}
	return $slug;
}
add_filter( 'wp_plugin_dependencies_slug', 'leafext_extensions_leaflet_map_to_github' );

// prevent unnecessary API calls to wordpress.org
function leafext_prevent_requests( $res, $action, $args ) {
	if ( 'plugin_information' !== $action ) {
		return $res;
	}
	if ( $args->slug !== LEAFEXT_PLUGIN_SETTINGS ) {
		return $res;
	}
	$plugin_data = get_plugin_data( __FILE__, true, false );
	$res         = new stdClass();
	$res->name   = $plugin_data['Name'];
	return $res;
}
add_filter( 'plugins_api', 'leafext_prevent_requests', 10, 3 );

// Updates from Github
// Updates from Github
function leafext_updates_from_github() {
	$name             = 'Updates created by hupe13 hosted on GitHub';
	$ghu_url          = 'https://github.com/hupe13/ghu-update-puc';
	$ghu_php_old      = 'leafext-update-github.php';
	$ghu_settings_old = 'admin.php?page=github-settings">Github settings</a>';
	$ghu_php          = 'ghu-update-puc.php';
	$ghu_settings     = 'options-general.php?page=ghu-update-puc">Github Update PUC</a>';
	$settings_page    = '';
	echo '<h2>' . wp_kses_post( 'Updates in WordPress way' ) . '</h2>';
	if ( is_multisite() ) {
		if ( strpos(
			implode(
				',',
				array_keys(
					get_site_option( 'active_sitewide_plugins', array() )
				)
			),
			$ghu_php_old
		) !== false ) {
			$settings_page = $ghu_settings_old;
		} elseif ( strpos(
			implode(
				',',
				array_keys(
					get_site_option( 'active_sitewide_plugins', array() )
				)
			),
			$ghu_php
		) !== false
		) {
			$settings_page = $ghu_settings;
		}
		if ( $settings_page !== '' ) {
			echo wp_kses_post(
				'To manage and receive updates, open <a href="' .
				get_site_url( get_main_site_id() ) .
				'/wp-admin/' . $settings_page . '.'
			);
		} else {
			echo wp_kses_post(
				'To receive updates, go to the <a href="' .
				esc_url( network_admin_url() ) .
				'plugins.php">network dashboard</a> and install and network activate ' .
				'<a href=' . $ghu_url . '>' . $name . '</a>.'
			);
		}
	} else {
		// Single site
		if ( strpos(
			implode(
				',',
				get_option( 'active_plugins', array() )
			),
			$ghu_php_old
		) !== false ) {
			$settings_page = $ghu_settings_old;
		} elseif ( strpos(
			implode(
				',',
				get_option( 'active_plugins', array() )
			),
			$ghu_php
		) !== false ) {
			$settings_page = $ghu_settings;
		}
		if ( $settings_page !== '' ) {
			echo wp_kses_post(
				'To manage and receive updates, open <a href="' .
				esc_url( admin_url() ) .
				$settings_page . '.'
			);
		} else {
			echo wp_kses_post(
				'To receive updates, go to the <a href="' .
				esc_url( admin_url() ) .
				'plugins.php">dashboard</a> and install and activate ' .
				'<a href=' . $ghu_url . '>' . $name . '</a>.'
			);
		}
	}
}
