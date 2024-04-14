<?php
/**
 * Admin page for filemgr functions
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// for listing files

function leafext_file_listing_init() {
	register_setting( 'leafext_file_listing', 'leafext_listing' );
	add_settings_section( 'leafext_listing_settings', '', '', 'leafext_file_listing' );
	add_settings_field( 'leafext_listing_types', __( 'Show files of type', 'extensions-leaflet-map' ), 'leafext_listing_form_types', 'leafext_file_listing', 'leafext_listing_settings' ); // type
	add_settings_field( 'leafext_listing_all', __( 'Show all files', 'extensions-leaflet-map' ), 'leafext_listing_form_all', 'leafext_file_listing', 'leafext_listing_settings' ); // all
	add_settings_field( 'leafext_listing_dirs', __( 'or in the directory', 'extensions-leaflet-map' ), 'leafext_listing_form_dirs', 'leafext_file_listing', 'leafext_listing_settings' ); // verz, count,
	add_settings_field( 'leafext_listing_files', __( 'and show at the same time', 'extensions-leaflet-map' ), 'leafext_listing_form_files', 'leafext_file_listing', 'leafext_listing_settings' ); // anzahl
	add_settings_field( 'leafext_listing_default', __( 'Save query', 'extensions-leaflet-map' ), 'leafext_listing_form_default', 'leafext_file_listing', 'leafext_listing_settings' ); // anzahl
}
add_action( 'admin_init', 'leafext_file_listing_init' );

function leafext_listing_form_types() {
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_file', 'leafext_file_nonce' ) ) {
		$post = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
	} else {
		$post = array();
	}
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- form is with $_POST
	$get = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
	if ( count( $post ) != 0 ) {
		$type = isset( $post['type'] ) ? $post['type'] : '';
	} elseif ( isset( $get['type'] ) ) {
		$type = array( filter_input( INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS ) );
	} else {
		$stored = get_option( 'leafext_file_listing_' . get_current_user_id() );
		if ( is_array( $stored ) ) {
			$type = $stored['type'];
		} else {
			$type = array( 'gpx', 'kml', 'geojson', 'json', 'tcx' );
		}
	}
	$types = array( 'gpx', 'kml', 'geojson', 'json', 'tcx' );

	foreach ( $types as $typ ) {
		$checked = in_array( $typ, $type, true ) ? ' checked ' : '';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo ' <input type="checkbox" name="type[]" value="' . $typ . '" id="' . $typ . '" ' . $checked . '>';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<label for="' . $typ . '" >' . $typ . ' </label>';
	}
}

function leafext_listing_form_dirs() {
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_file', 'leafext_file_nonce' ) ) {
		$post = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
	} else {
		$post = array();
	}
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- form is with $_POST
	$get = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
	if ( count( $post ) != 0 ) {
		$verz  = isset( $post['verz'] ) ? $post['verz'] : ( isset( $post['dir'] ) ? $post['dir'] : '' );
		$count = isset( $post['count'] ) ? $post['count'] : '5';
		$type  = isset( $post['type'] ) ? $post['type'] : '';
		$all   = isset( $post['all'] ) ? $post['all'] : '';
	} elseif ( count( $get ) > 2 ) {  // mehr als page und tab
		$verz  = isset( $get['verz'] ) ? filter_input( INPUT_GET, 'verz', FILTER_SANITIZE_SPECIAL_CHARS ) :
		( ( isset( $get['dir'] ) ? filter_input( INPUT_GET, 'dir', FILTER_SANITIZE_SPECIAL_CHARS ) : '' ) );
		$count = isset( $get['count'] ) ? filter_input( INPUT_GET, 'count', FILTER_VALIDATE_INT ) : '5';
		$type  = isset( $get['type'] ) ? filter_input( INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS ) : '';
		$all   = isset( $get['all'] ) ? filter_input( INPUT_GET, 'all', FILTER_SANITIZE_SPECIAL_CHARS ) : '';
	} else {
		$stored = get_option( 'leafext_file_listing_' . get_current_user_id() );
		if ( is_array( $stored ) ) {
			$verz  = $stored['dir'];
			$count = $stored['count'];
			$type  = $stored['type'];
			$all   = $stored['all'];
		} else {
			$verz  = '';
			$count = '5';
			// $type = array("gpx");
			$type = array( 'gpx', 'kml', 'geojson', 'json', 'tcx' );
			$all  = '';
		}
	}

	$extensions  = is_array( $type ) ? '{' . implode( ',', $type ) . '}' : '{gpx,kml,geojson,json,tcx}';
	$upload_dir  = wp_get_upload_dir();
	$upload_path = trailingslashit( $upload_dir['basedir'] );
	$disabled    = ( $all == 'on' ) ? 'disabled' : '';

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo esc_html__( 'with at least', 'extensions-leaflet-map' ) . ' <input ' . $disabled . ' type="number" min="2" name="count" id="leafext_dirListnr" value="' . $count . '" size="3"> ' . esc_html__( 'Files', 'extensions-leaflet-map' ) . ': ';
	echo '<select name="dir" id="leafext_dirList" ' . $disabled . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	if ( $verz == '' ) {
		echo '<option selected value="">' . esc_html__( 'Please select', 'extensions-leaflet-map' ) . ' ...</option>';
	}
	foreach ( leafext_list_dirs( $upload_path, $extensions, $count ) as $dir ) {
		if ( $verz == $dir ) {
			echo '<option selected ';
		} else {
			echo '<option ';
		}
		echo 'value="' . $dir . '">/' . $dir . '</option>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</select>';
	echo '<p>' . esc_html__( 'If you change the number, submit the form to get the directories you want.', 'extensions-leaflet-map' ) . '</p>';
}

function leafext_listing_form_all() {
	?>
	<script>
	function leafext_EnableDisableDirListing(leafext_filesall) {
		var leafext_dirList = document.getElementById("leafext_dirList");
		var leafext_dirListnr = document.getElementById("leafext_dirListnr");
		if (leafext_filesall.checked) {
			leafext_dirList.setAttribute('disabled', "disabled");
			leafext_dirListnr.setAttribute('disabled', true);
		} else {
			leafext_dirList.removeAttribute('disabled');
			leafext_dirListnr.removeAttribute('disabled');
		}
	}
	</script>
	<?php
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_file', 'leafext_file_nonce' ) ) {
		$post = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
	} else {
		$post = array();
	}
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- form is with $_POST
	$get = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
	if ( count( $post ) != 0 ) {
		$all = isset( $post['all'] ) ? $post['all'] : '';
	} elseif ( isset( $get['all'] ) ) {
		$all = filter_input( INPUT_GET, 'all', FILTER_SANITIZE_SPECIAL_CHARS );
	} else {
		$stored = get_option( 'leafext_file_listing_' . get_current_user_id() );
		if ( is_array( $stored ) ) {
			$all = $stored['all'];
		} else {
			$all = '';
		}
	}
	$checked = ( $all == 'on' ) ? 'checked' : '';
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '<input type="checkbox" ' . $checked . ' name="all" id="leafext_filesall" onchange="leafext_EnableDisableDirListing(this)">';
}

function leafext_listing_form_files() {
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_file', 'leafext_file_nonce' ) ) {
		$post = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
	} else {
		$post = array();
	}
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- form is with $_POST
	$get = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
	if ( count( $post ) != 0 ) {
		$anzahl = isset( $post['anzahl'] ) ? $post['anzahl'] : '10';
	} elseif ( isset( $get['anzahl'] ) ) {
		$anzahl = filter_input( INPUT_GET, 'anzahl', FILTER_VALIDATE_INT );
	} else {
		$stored = get_option( 'leafext_file_listing_' . get_current_user_id() );
		if ( is_array( $stored ) ) {
			$anzahl = $stored['anzahl'];
		} else {
			$anzahl = '10';
		}
	}
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '<input type="number" min="1" name="anzahl" value="' . $anzahl . '" size="4"> ' . esc_html__( 'entries', 'extensions-leaflet-map' );
}

function leafext_listing_form_default() {
	echo '<input type="checkbox" name="store" id="leafext_store">';
}

function leafext_managefiles() {
	$post  = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
	$get   = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
	$page  = isset( $get['page'] ) ? filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS ) : '';
	$tab   = isset( $get['tab'] ) ? filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_SPECIAL_CHARS ) : '';
	$track = isset( $get['track'] ) ? filter_input( INPUT_GET, 'track', FILTER_SANITIZE_SPECIAL_CHARS ) : '';

	if ( $track != '' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/filemgr/thickbox.php';
		leafext_thickbox( $track );
	} else {
		// echo '<pre>';
		// if (isset($post)) var_dump($post);
		// if (isset($_GET)) var_dump($_GET);
		// echo '</pre>';

		if ( count( $post ) != 0 ) {
			if ( ! empty( $post ) && check_admin_referer( 'leafext_file', 'leafext_file_nonce' ) ) {
				$noop = true; // phpcs
			}
		}

		if ( count( $get ) > 2 ) {
			if ( ! empty( $get ) && check_admin_referer( 'leafext_file', 'leafext_file_nonce' ) ) {
				$noop = true; // phpcs
			}
		}

		// echo '<h2>'.__('Manage Files',"extensions-leaflet-map").'</h2>';

		if ( count( $post ) != 0 ) {

			if ( isset( $post['delete'] ) ) {
				delete_option( 'leafext_file_listing_' . get_current_user_id() );
				// return false;
			}
			$dir    = isset( $post['dir'] ) ? $post['dir'] : '';
			$all    = isset( $post['all'] ) ? $post['all'] : '';
			$type   = isset( $post['type'] ) ? $post['type'] : '';
			$anzahl = isset( $post['anzahl'] ) ? $post['anzahl'] : '10';
			$store  = isset( $post['store'] ) ? $post['store'] : '';
			if ( $store == 'on' ) {
				$defaults           = array();
				$defaults['type']   = isset( $post['type'] ) ? $post['type'] : array( 'gpx' );
				$defaults['all']    = isset( $post['all'] ) ? $post['all'] : '';
				$defaults['count']  = isset( $post['count'] ) ? $post['count'] : '5';
				$defaults['dir']    = isset( $post['dir'] ) ? $post['dir'] : '';
				$defaults['anzahl'] = isset( $post['anzahl'] ) ? $post['anzahl'] : '10';
				update_option( 'leafext_file_listing_' . get_current_user_id(), $defaults );
			}
		} else {
			$dir    = isset( $get['dir'] ) ? filter_input( INPUT_GET, 'dir', FILTER_SANITIZE_SPECIAL_CHARS ) : '';
			$all    = isset( $get['all'] ) ? filter_input( INPUT_GET, 'all', FILTER_SANITIZE_SPECIAL_CHARS ) : '';
			$type   = isset( $get['type'] ) ? array( filter_input( INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS ) ) : '';
			$anzahl = isset( $get['anzahl'] ) ? filter_input( INPUT_GET, 'anzahl', FILTER_VALIDATE_INT ) : '10';
		}
		$extensions = is_array( $type ) ? '{' . implode( ',', $type ) . '}' : '{gpx,kml,geojson,json,tcx}';
		// var_dump($extensions);

		if ( $dir == '' && $all == '' && ! current_user_can( 'manage_options' ) ) {
			leafext_managefiles_help();
		}

		// $stored = get_option('leafext_file_listing_'.get_current_user_id);
		// var_dump($stored);
		// var_dump(get_current_user_id());

		echo '<form method="post" action="' . esc_url( admin_url( 'admin.php' ) . '?page=' . $page . '&tab=' . $tab ) . '">';
		wp_nonce_field( 'leafext_file', 'leafext_file_nonce' );
		settings_fields( 'leafext_file_listing' );
		do_settings_sections( 'leafext_file_listing' );
		submit_button( __( 'List files', 'extensions-leaflet-map' ) );
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false );
		echo '</form>';

		if ( $dir != '' || $all != '' ) {
			leafext_create_shortcode_js();
			leafext_create_shortcode_css();
		}
		if ( $dir != '' ) {

			echo '<h3>' . esc_html( __( 'Directory', 'extensions-leaflet-map' ) . ' /' . $dir ) . '</h3>';
			if ( $dir != '/' ) {
				echo '<div><a href="?page=' . esc_html( $page ) . '&tab=filemgr-dir">Shortcode</a> ' . esc_html__( 'for showing all files of this directory on a map', 'extensions-leaflet-map' ) . ':<br>';
				$shortcode = '[leaflet-map fitbounds][leaflet-directory src=';
				$uploadurl = '';
				$file      = '/' . trim( $dir, '/' ) . '/';
				$end       = ' elevation][multielevation]';
				// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<span class="leafexttooltip" href="#" ' .
				'onclick="leafext_create_shortcode(\'' . $shortcode . '\',\'' . $uploadurl . '\',\'' . $file . '\',\'' . $end . '\')" ' .
				'onmouseout="leafext_outFunc()">' .
				'<span class="leafextcopy" id="leafextTooltip">Copy to clipboard</span>' .
				'<code>[leaflet-directory src="/' . trailingslashit( $dir ) . '" elevation]</code>' .
				'</span>';
				// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
				echo ' (' . esc_html__( 'does only work with gpx files', 'extensions-leaflet-map' ) . ')';
				echo '<br>';
				$shortcode = '[leaflet-map fitbounds][leaflet-directory src=';
				$uploadurl = '';
				$file      = '/' . trim( $dir, '/' ) . '/';
				$end       = ' leaflet]';
				// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<span class="leafexttooltip" href="#" ' .
				'onclick="leafext_create_shortcode(\'' . $shortcode . '\',\'' . $uploadurl . '\',\'' . $file . '\',\'' . $end . '\')" ' .
				'onmouseout="leafext_outFunc()">' .
				'<span class="leafextcopy" id="leafextTooltip">Copy to clipboard</span>' .
				'<code>[leaflet-directory src="/' . trailingslashit( $dir ) . '" leaflet]</code>' .
				'</span>' .
				'</div>';
				// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			echo '<p>';
			leafext_list_paginate( leafext_list_dir( $dir, $extensions ), $anzahl );
			echo '</p>';
		} elseif ( $all != '' ) {
			$upload_dir  = wp_get_upload_dir();
			$upload_path = $upload_dir['basedir'] . '/';
			leafext_list_paginate( leafext_list_allfiles( $upload_path, $extensions ), $anzahl );
		}
	}
}
