<?php
/**
 *  Functions to use for PUC
 *
 * @package Updates for plugins from hupe13 hosted on Github
 **/

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// for translating, geklaut von PUC
function leafext_update_textdomain() {
	$domain  = 'leafext-update-github';
	$locale  = apply_filters(
		'plugin_locale',
		( is_admin() && function_exists( 'get_user_locale' ) ) ? get_user_locale() : get_locale(),
		$domain
	);
	$mo_file = $domain . '-' . $locale . '.mo';
	$path    = realpath( __DIR__ ) . '/lang/';
	if ( $path && file_exists( $path ) ) {
		load_textdomain( $domain, $path . $mo_file );
	}
}
add_action( 'plugins_loaded', 'leafext_update_textdomain' );

// Display array as table
if ( ! function_exists( 'leafext_html_table' ) ) {
	function leafext_html_table( $data = array() ) {
		$rows      = array();
		$cellstyle = ( is_singular() || is_archive() ) ? "style='border:1px solid #195b7a;'" : '';
		foreach ( $data as $row ) {
			$cells = array();
			foreach ( $row as $cell ) {
				$cells[] = '<td ' . $cellstyle . ">{$cell}</td>";
			}
			$rows[] = '<tr>' . implode( '', $cells ) . '</tr>' . "\n";
		}
		$head = '<div style="width:' . ( ( is_singular() || is_archive() ) ? '100' : '80' ) . '%;">';
		$head = $head . '<figure class="wp-block-table aligncenter is-style-stripes"><table border=1>';
		return $head . implode( '', $rows ) . '</table></figure></div>';
	}
}

// Repos on Github
function leafext_get_repos() {
	$releases  = array(
		'extensions-leaflet-map'         => false,
		'extensions-leaflet-map-testing' => false,
	);
	$git_repos = array();
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$all_plugins = get_plugins();
	foreach ( $all_plugins as $plugin => $plugin_data ) {
		if ( $plugin_data['Author'] === 'hupe13' ) {
			if ( strpos( $plugin_data['UpdateURI'], 'https://github.com/hupe13/' ) !== false
				|| strpos( $plugin_data['PluginURI'], 'https://github.com/hupe13/' ) !== false
				|| file_exists( WP_PLUGIN_DIR . '/' . dirname( $plugin ) . '/github' )
			) {
				$slug    = basename( $plugin, '.php' );
				$release = isset( $releases[ $slug ] ) ? $releases[ $slug ] : true;
				$url     = $plugin_data['UpdateURI'];
				if ( $url === '' ) {
					$url = $plugin_data['PluginURI'];
				}
				if ( $url !== '' ) {
					$git_repos[ $slug ] = array(
						'url'     => $url,
						'local'   => WP_PLUGIN_DIR . '/' . $plugin,
						'release' => $release,
					);
				}
			}
		}
	}
	return $git_repos;
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

function leafext_submenu_of() {
	$groups = array();
	// from Leaflet Map Plugin
	$leaf                     = 'data:image/svg+xml;base64,PHN2ZyBhcmlhLWhpZGRlbj0idHJ1ZSIgZm9jdXNhYmxlPSJmYWxzZSIgZGF0YS1wcmVmaXg9ImZhcyIgZGF0YS1pY29uPSJsZWFmIiBjbGFzcz0ic3ZnLWlubGluZS0tZmEgZmEtbGVhZiBmYS13LTE4IiByb2xlPSJpbWciIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDU3NiA1MTIiPjxwYXRoIGZpbGw9ImN1cnJlbnRDb2xvciIgZD0iTTU0Ni4yIDkuN2MtNS42LTEyLjUtMjEuNi0xMy0yOC4zLTEuMkM0ODYuOSA2Mi40IDQzMS40IDk2IDM2OCA5NmgtODBDMTgyIDk2IDk2IDE4MiA5NiAyODhjMCA3IC44IDEzLjcgMS41IDIwLjVDMTYxLjMgMjYyLjggMjUzLjQgMjI0IDM4NCAyMjRjOC44IDAgMTYgNy4yIDE2IDE2cy03LjIgMTYtMTYgMTZDMTMyLjYgMjU2IDI2IDQxMC4xIDIuNCA0NjhjLTYuNiAxNi4zIDEuMiAzNC45IDE3LjUgNDEuNiAxNi40IDYuOCAzNS0xLjEgNDEuOC0xNy4zIDEuNS0zLjYgMjAuOS00Ny45IDcxLjktOTAuNiAzMi40IDQzLjkgOTQgODUuOCAxNzQuOSA3Ny4yQzQ2NS41IDQ2Ny41IDU3NiAzMjYuNyA1NzYgMTU0LjNjMC01MC4yLTEwLjgtMTAyLjItMjkuOC0xNDQuNnoiLz48L3N2Zz4=';
	$groups['leaflet-map']    = array(
		'name' => 'Leaflet Map',
		'icon' => $leaf,
	);
	$groups['album-medialib'] = array(
		'name' => 'Album Media Library',
		'icon' => '',
	);
	$git_repos                = leafext_get_repos();
	foreach ( $git_repos as $git_repo => $value ) {
		if ( is_plugin_active( plugin_basename( $git_repos[ $git_repo ]['local'] ) ) ) {
			foreach ( $groups as $key => $group ) {
				if ( strpos( $git_repos[ $git_repo ]['local'], $key ) !== false ) {
					$github_menu = array(
						'slug' => $key,
						'name' => $group['name'],
						'icon' => $group['icon'],
					);
					return $github_menu;
				}
			}
		}
	}
	$github_menu = array(
		'slug' => 'github',
		'name' => 'Github',
		'icon' => '',
	);
	return $github_menu;
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
				'<a href="https://github.com/hupe13/leafext-update-github">Updates for plugins from hupe13 hosted on Github</a>'
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
		$github_menu = leafext_submenu_of();
		// Add Submenu.
		add_submenu_page(
			$github_menu['slug'],
			'Github Update Options',
			'Github Update',
			'manage_options',
			'leafext-update-github',
			'leafext_goto_main_site'
		);
	}
	add_action( 'admin_menu', 'leafext_update_add_page', 100 );

} else {
	$github_menu = leafext_submenu_of();
	if ( $github_menu['slug'] !== 'github' ) {
		function leafext_update_add_page() {
			$github_menu = leafext_submenu_of();
			// Add Submenu.
			add_submenu_page(
				$github_menu['slug'],
				'Github Update',
				'Github Update',
				'manage_options',
				'github-settings',
				'leafext_update_admin'
			);
		}
		add_action( 'admin_menu', 'leafext_update_add_page', 100 );

	} else {
		// plugins not active, create new Menu
		function leafext_add_page_single() {
			$github_menu = leafext_submenu_of();
			add_menu_page(
				$github_menu['slug'],
				$github_menu['name'],
				'manage_options',
				$github_menu['slug'], // parent slug
				'none', // fake
				$github_menu['icon'] // icon
			);
			add_submenu_page(
				$github_menu['slug'],  // parent slug
				'',
				'',
				'manage_options',
				$github_menu['slug'],
				'none',
			);
			add_submenu_page(
				$github_menu['slug'], // parent page slug
				'Github Update',
				'Github Update',
				'manage_options',
				'github-settings',
				'leafext_update_admin'
			);
			// remove fake
			remove_submenu_page(
				$github_menu['slug'],
				$github_menu['slug']
			);
		}
		add_action( 'admin_menu', 'leafext_add_page_single' );
	}
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
	$slugs = array_keys( leafext_get_repos() );
	$table = array();

	foreach ( $slugs as $slug ) {
		$leafext_plugins = glob( WP_PLUGIN_DIR . '/*/' . $slug . '.php/' );
		if ( count( $leafext_plugins ) > 0 ) {
			foreach ( $leafext_plugins as $leafext_plugin ) {
				$entry         = array();
				$plugin_data   = get_plugin_data( $leafext_plugin );
				$entry['name'] = $plugin_data['Name'];
				if ( strpos( $plugin_data['UpdateURI'], 'https://github.com/hupe13/' ) !== false ) {
					$entry['hosted'] = 'Github';
				} elseif ( file_exists( dirname( $leafext_plugin ) . '/github' ) ) {
					$entry['hosted'] = 'Github';
				} elseif ( strpos( $plugin_data['PluginURI'], 'https://github.com/hupe13/' ) !== false ) {
					$entry['hosted'] = 'Github';
				} else {
					$entry['hosted'] = 'WordPress';
				}
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
				$entry['hosted'] = '<div style="text-align:center">' . $entry['hosted'] . '</div>';
				$entry['active'] = '<ul><li style="text-align:center">' . implode( '<li style="text-align:center">', $entry['active'] ) . '</ul>';
				$entry['links']  = '<ul><li>' . implode( '<li>', $blogs ) . '</ul>';
				$table[]         = $entry;
			}
		}
	}
	$header = array(
		'<b>' . __( 'Name', 'leafext-update-github' ) . '</b>',
		'<b>' . __( 'hosted on', 'leafext-update-github' ) . '</b>',
		'<b>' . __( 'active', 'leafext-update-github' ) . '</b>',
		'<b>' . __( 'link to blog', 'leafext-update-github' ) . '</b>',
	);

	array_unshift( $table, $header );
	echo wp_kses_post( leafext_html_table( $table ) );
}
