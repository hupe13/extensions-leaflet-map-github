<?php
/**
 * Backend Menus
 *
 * @package Extensions for Leaflet Map Github Version
 **/

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// for translating, geklaut von PUC
function leafext_extensions_update_textdomain() {
	$domain = 'extensions-leaflet-map';
	unload_textdomain( $domain );
	$locale  = apply_filters(
		'plugin_locale',
		( is_admin() && function_exists( 'get_user_locale' ) ) ? get_user_locale() : get_locale(),
		$domain
	);
	$mo_file = $domain . '-' . $locale . '.mo';
	$path    = LEAFEXT_PLUGIN_DIR . '/lang/';
	if ( $path && file_exists( $path ) ) {
		load_textdomain( $domain, $path . $mo_file );
	}
}
add_action( 'plugins_loaded', 'leafext_extensions_update_textdomain' );

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
if ( ! function_exists( 'leafext_updates_from_github' ) ) {
	function leafext_updates_from_github() {
		echo '<h2>' . wp_kses_post( 'Updates in WordPress way' ) . '</h2>';
		if ( is_multisite() ) {
			if ( strpos(
				implode(
					',',
					array_keys(
						get_site_option( 'active_sitewide_plugins', array() )
					)
				),
				'leafext-update-github.php'
			) !== false ) {
						echo wp_kses_post(
							'To manage and receive updates, open <a href="' .
							get_site_url( get_main_site_id() ) .
							'/wp-admin/admin.php?page=github-settings">Github settings</a>.'
						);
			} else {
					echo wp_kses_post(
						'To receive updates, go to the <a href="' .
						esc_url( network_admin_url() ) .
						'plugins.php">network dashboard</a> and install and network activate ' .
						'<a href="https://github.com/hupe13/leafext-update-github">Updates for plugins from hupe13 hosted on Github</a>.'
					);
			}
		} elseif ( strpos(
			implode(
				',',
				get_option( 'active_plugins', array() )
			),
			'leafext-update-github.php'
		) !== false ) {
						echo wp_kses_post(
							'To manage and receive updates, open <a href="' .
							esc_url( admin_url() ) .
							'admin.php?page=github-settings">Github settings</a>.'
						);
		} else {
				echo wp_kses_post(
					'To receive updates, go to the <a href="' .
					esc_url( admin_url() ) .
					'plugins.php">dashboard</a> and install and activate ' .
					'<a href="https://github.com/hupe13/leafext-update-github">Updates for plugins from hupe13 hosted on Github</a>.'
				);
		}
	}
}
