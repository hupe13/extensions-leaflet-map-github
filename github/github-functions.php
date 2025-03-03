<?php
/**
 *  Functions to use for PUC
 *
 * @package Updates for Leaflet Map Extensions and DSGVO Github Versions
 **/

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

require_once ABSPATH . 'wp-admin/includes/plugin.php';

// Repos on Github
function leafext_get_repos() {
	$git_repos = array();
	// this plugin
	$git_repos['leafext-update-github']  = array(
		'url'     => 'https://github.com/hupe13/leafext-update-github/',
		'local'   => WP_PLUGIN_DIR . '/' . leafext_github_dir( 'leafext-update-github' ) . '/leafext-update-github.php',
		'release' => true,
	);
	$git_repos['extensions-leaflet-map'] = array(
		'url'     => 'https://github.com/hupe13/extensions-leaflet-map-github/',
		'local'   => WP_PLUGIN_DIR . '/' . leafext_github_dir( 'extensions-leaflet-map' ) . '/extensions-leaflet-map.php',
		'release' => false,
	);
	$git_repos['dsgvo-leaflet-map']      = array(
		'url'     => 'https://github.com/hupe13/dsgvo-leaflet-map-github/',
		'local'   => WP_PLUGIN_DIR . '/' . leafext_github_dir( 'dsgvo-leaflet-map' ) . '/dsgvo-leaflet-map.php',
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
	if ( count( $leafext_plugins ) > 0 ) {
		foreach ( $leafext_plugins as $leafext_plugin ) {
			$plugin_data = get_plugin_data( $leafext_plugin, true, false );
			if ( strpos( $plugin_data['Name'], 'Github' ) !== false ) {
				return dirname( plugin_basename( $leafext_plugin ) );
			}
		}
	}
	return '';
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
	if ( is_plugin_active_for_network( 'leafext-update-github/leafext-update-github.php' ) ) {
		return true;
	}
	$git_repos = leafext_get_repos();
	foreach ( $git_repos as $git_repo => $value ) {
		if ( leafext_active_on_main_site( plugin_basename( $git_repos[ $git_repo ]['local'] ) ) ) {
			return true;
		}
	}
	return false;
}

if ( ! is_main_site() ) {
	// Updates from Github
	function leafext_goto_main_site() {
		echo '<h1>' . esc_html__( 'Updates in WordPress way', 'leafext-update-github' ) . '</h1>';
		$repos = leafext_get_repos();
		if ( ! leafext_can_updates() ) {
			printf(
				/* translators: %s is a link. */
				esc_html__(
					'To receive updates, go to the %1$smain site dashboard%2$s and activate one %3$s plugin here or install and activate %4$s.',
					'leafext-update-github'
				),
				'<a href="' . esc_url( get_site_url( get_main_site_id() ) ) . '/wp-admin/plugins.php">',
				'</a>',
				'<b>Github</b>',
				'<a href="https://github.com/hupe13/leafext-update-github">Updates for Leaflet Map Extensions and DSGVO Github Versions</a>'
			);
		} else {
			printf(
				/* translators: %s is a link. */
				esc_html__(
					'To manage and receive updates, go to the %1$smain site%2$s.',
					'leafext-update-github'
				),
				'<a href="' . esc_url( get_site_url( get_main_site_id() ) ) . '/wp-admin/admin.php?page=github-settings">',
				'</a>'
			);
			echo '<h3>' . esc_html__( 'Github Repositories managed on main site', 'leafext-update-github' ) . '</h3>';
		}
		leafext_table_repos();
	}

	function leafext_update_add_page() {
		// Add Submenu.
		$leafext_admin_page = add_submenu_page(
			'leaflet-map',
			'Github Update Options',
			'Github Update',
			'manage_options',
			'leafext-update-github',
			'leafext_goto_main_site'
		);
	}
	add_action( 'admin_menu', 'leafext_update_add_page', 100 );

} elseif ( is_plugin_active( 'leaflet-map/leaflet-map.php' ) ) { // on main site
	function leafext_update_add_page() {
		// Add Submenu.
		$leafext_admin_page = add_submenu_page(
			'leaflet-map',
			'Github Update',
			'Github Update',
			'manage_options',
			'github-settings',
			'leafext_update_admin'
		);
	}
	add_action( 'admin_menu', 'leafext_update_add_page', 100 );

} else {
	// leaflet Map not active, create new Leaflet Menu
	function leafext_add_page_single() {
		// from Leaflet Map Plugin
		$leaf = 'data:image/svg+xml;base64,PHN2ZyBhcmlhLWhpZGRlbj0idHJ1ZSIgZm9jdXNhYmxlPSJmYWxzZSIgZGF0YS1wcmVmaXg9ImZhcyIgZGF0YS1pY29uPSJsZWFmIiBjbGFzcz0ic3ZnLWlubGluZS0tZmEgZmEtbGVhZiBmYS13LTE4IiByb2xlPSJpbWciIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDU3NiA1MTIiPjxwYXRoIGZpbGw9ImN1cnJlbnRDb2xvciIgZD0iTTU0Ni4yIDkuN2MtNS42LTEyLjUtMjEuNi0xMy0yOC4zLTEuMkM0ODYuOSA2Mi40IDQzMS40IDk2IDM2OCA5NmgtODBDMTgyIDk2IDk2IDE4MiA5NiAyODhjMCA3IC44IDEzLjcgMS41IDIwLjVDMTYxLjMgMjYyLjggMjUzLjQgMjI0IDM4NCAyMjRjOC44IDAgMTYgNy4yIDE2IDE2cy03LjIgMTYtMTYgMTZDMTMyLjYgMjU2IDI2IDQxMC4xIDIuNCA0NjhjLTYuNiAxNi4zIDEuMiAzNC45IDE3LjUgNDEuNiAxNi40IDYuOCAzNS0xLjEgNDEuOC0xNy4zIDEuNS0zLjYgMjAuOS00Ny45IDcxLjktOTAuNiAzMi40IDQzLjkgOTQgODUuOCAxNzQuOSA3Ny4yQzQ2NS41IDQ2Ny41IDU3NiAzMjYuNyA1NzYgMTU0LjNjMC01MC4yLTEwLjgtMTAyLjItMjkuOC0xNDQuNnoiLz48L3N2Zz4=';
		add_menu_page(
			'leaflet-map',
			'Leaflet Map',
			'manage_options',
			'leaflet-map', // parent slug
			'none', // fake
			$leaf // icon
		);
		add_submenu_page(
			'leaflet-map',  // parent slug
			'',
			'',
			'manage_options',
			'leaflet-map',
			'none',
		);
		add_submenu_page(
			'leaflet-map', // parent page slug
			'Github Update',
			'Github Update',
			'manage_options',
			'github-settings',
			'leafext_update_admin'
		);
		// remove fake
		remove_submenu_page(
			'leaflet-map',
			'leaflet-map'
		);
	}
	add_action( 'admin_menu', 'leafext_add_page_single' );
}

// Admin page for the plugin
function leafext_update_admin() {
	leafext_token_form();
	leafext_table_repos();
	echo '<h3>' . esc_html__( 'Github Repositories managed by Plugin Update Checker', 'leafext-update-github' ) . '</h3>';
	echo '<pre>';
	//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_dump
	var_dump( leafext_get_repos() );
	echo '</pre>';
}

function leafext_table_repos() {

	$slugs     = array_keys( leafext_get_repos() );
		$table = array();

	foreach ( $slugs as $slug ) {
		$leafext_plugins = glob( WP_PLUGIN_DIR . '/*/' . $slug . '.php/' );
		if ( count( $leafext_plugins ) > 0 ) {
			foreach ( $leafext_plugins as $leafext_plugin ) {
				$entry           = array();
				$plugin_data     = get_plugin_data( $leafext_plugin );
				$entry['name']   = str_replace( 'Github', '<b>Github</b>', $plugin_data['Name'] );
				$entry['active'] = array();
				$blogs           = array();
				if ( function_exists( 'get_sites' ) ) {
					$sites = get_sites();

					foreach ( $sites as $site ) {
						switch_to_blog( $site->blog_id );
						if ( is_plugin_active( plugin_basename( $leafext_plugin ) ) ) {
							$entry['active'][] = $site->blog_id;
						}
						restore_current_blog();
					}
					if ( count( $entry['active'] ) === count( $sites ) && $slug === 'leafext-update-github' ) {
						$entry['active'] = array( '1' );
					}
					foreach ( $entry['active'] as $site ) {
						$blogs[] = '<a href="' . get_admin_url( $site ) .
						'plugins.php?s=leaflet&plugin_status=all">' .
						get_blog_option( $site, 'blogname' )
						. '</a>';
					}
				} else {
					if ( is_plugin_active( plugin_basename( $leafext_plugin ) ) ) {
						$entry['active'][] = 1;
					}
					foreach ( $entry['active'] as $site ) {
						$blogs[] = '<a href="' . get_admin_url( $site ) .
						'plugins.php?s=leaflet&plugin_status=all">' .
						get_option( 'blogname' )
						. '</a>';
					}
				}
				$entry['active'] = '<ul><li style="text-align:center">' . implode( '<li style="text-align:center">', $entry['active'] ) . '</ul>';
				$entry['links']  = '<ul><li>' . implode( '<li>', $blogs ) . '</ul>';
				$table[]         = $entry;
			}
		}
	}
	$header = array(
		'<b>' . __( 'Name', 'leafext-update-github' ) . '</b>',
		'<b>' . __( 'active', 'leafext-update-github' ) . '</b>',
		'<b>' . __( 'link to blog', 'leafext-update-github' ) . '</b>',
	);

	array_unshift( $table, $header );
	echo wp_kses_post( leafext_html_table( $table ) );
}
