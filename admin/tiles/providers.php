<?php
/**
 * Admin page for providers functions
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_providers_init() {
	// Create Setting
	$section_group = 'leafext_providers';
	$section_name  = 'leafext_providers';
	$validate      = 'leafext_validate_providers';
	register_setting( $section_group, $section_name, $validate );

	// Create section of Page
	$settings_section = 'leafext_providers_main';
	$page             = $section_group;
	add_settings_section(
		$settings_section,
		'Leaflet-providers',
		'leafext_providers_help',
		$page
	);

	// Add fields to that section
	add_settings_field(
		$section_name,
		__( 'Providers requiring registration', 'extensions-leaflet-map' ),
		'leafext_providers_form',
		$page,
		$settings_section
	);
}
add_action( 'admin_init', 'leafext_providers_init' );

function leafext_providers_form() {
	$require_registration = leafext_providers_registration();
	$allnames             = leafext_providers_regnames();
	$regtiles             = get_option( 'leafext_providers', array() );
	$options              = array();
	$count                = count( $regtiles );
	for ( $i = 0; $i < $count; $i++ ) {
		$allnames = array_diff( $allnames, array( $regtiles[ $i ]['name'] ) );
		echo '<h4>' . esc_html( $regtiles[ $i ]['name'] ) . '</h4>' . "\n";
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<input type="hidden" name="leafext_providers[' . $i . '][name]" value="' . $regtiles[ $i ]['name'] . '">' . "\n";
		$size = max( array_map( 'strlen', $regtiles[ $i ]['keys'] ) );
		foreach ( $regtiles[ $i ]['keys'] as $key => $value ) {
			echo '<p>' . esc_html( $key ) . ': ';
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<input type="text" size=' . $size . ' name="leafext_providers[' . $i . '][keys][' . $key . ']" value="' . $value . '"></p>' . "\n";
		}
	}
	$i = $count;
	foreach ( $allnames as $name ) {
		$id = array_search( $name, array_column( $require_registration, 'name' ), true );
		echo '<h4>' . esc_html( $require_registration[ $id ]['name'] ) . '</h4>' . "\n";
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<input type="hidden" name="leafext_providers[' . $i . '][name]" value="' . $require_registration[ $id ]['name'] . '">' . "\n";
		$size = max( array_map( 'strlen', $require_registration[ $id ]['keys'] ) );
		foreach ( $require_registration[ $id ]['keys'] as $key => $value ) {
			echo '<p>' . esc_html( $key ) . ': ';
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<input type="text" size=' . $size . ' name="leafext_providers[' . $i . '][keys][' . $key . ']" placeholder="' . $value . '" value=""></p>' . "\n";
		}
		++$i;
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_providers( $options ) {
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_tiles', 'leafext_tiles_nonce' ) ) {
		if ( isset( $_POST['submit'] ) ) {
			foreach ( $options as $option ) {
				foreach ( $option['keys'] as $key => $value ) {
					if ( $value !== '' ) {
						$providers[] = $option;
					} break;
				}
			}
			return $providers;
		}
		if ( isset( $_POST['delete'] ) ) {
			delete_option( 'leafext_providers' );
		}
		return false;
	}
}

// Erklaerung / Hilfe

function leafext_providers_help() {
	if ( is_singular() || is_archive() ) {
		$codestyle = '';
	} else {
		leafext_enqueue_admin();
		$codestyle = ' class="language-coffeescript"';
	}
	if ( ! ( is_singular() || is_archive() ) ) { // backend
		$tilesproviders = '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=tilesproviders';
		$tileswitch     = '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=tileswitch';
	} else { // for my frontend leafext.de
		$server = map_deep( wp_unslash( $_SERVER ), 'sanitize_text_field' );
		if ( strpos( $server['REQUEST_URI'], '/en/' ) !== false ) {
			$lang = '/en';
		} else {
			$lang = '';
		}
		$tilesproviders = $lang . '/doku/tilesproviders/';
		$tileswitch     = $lang . '/doku/tileswitch/';
	}
	$text = '<pre' . $codestyle . '><code' . $codestyle . '>&#91;leaflet-map]' . "\n" .
	'&#91;layerswitch mapids=hiking providers="WaymarkedTrails.hiking"]' . "\n" .
	"\n" .
	'&#91;leaflet-map mapid="OSM"]' . "\n" .
	'&#91;layerswitch mapids="hiking,OPNV" providers="WaymarkedTrails.hiking,OPNVKarte"]</code></pre><p>' .
	sprintf(
		/* translators: %s is an option. */
		__( 'The option %s is optional.', 'extensions-leaflet-map' ),
		'<code>mapids</code>'
	) . ' ' .
	sprintf(
		/* translators: %s is an option. */
		__( 'You can use the parameter %s also.', 'extensions-leaflet-map' ),
		'<a href="' . $tileswitch . '"><code>tiles</code></a>'
	) .
	'</p><p>' .
	__( 'For a list of providers see', 'extensions-leaflet-map' ) .
	' <a href="http://leaflet-extras.github.io/leaflet-providers/preview/">http://leaflet-extras.github.io/leaflet-providers/preview/</a>.'
	. '</p>' . sprintf(
		/* translators: %s is styling (bold). */
		__( '%1$sPlease note%2$s (Quote from Leaflet Providers page):', 'extensions-leaflet-map' ),
		'<b>',
		'</b>'
	) . '<p> <i>' .
	__(
		"We try to maintain leaflet-providers in such a way that you'll be able to use the layers we include without paying money.<br>
	This doesn't mean no limits apply, you should always check before using these layers for anything serious.",
		'extensions-leaflet-map'
	) . '</i></p>';
	if ( current_user_can( 'manage_options' ) ) {
		if ( ! ( is_singular() || is_archive() ) ) {
			$text = $text . '<p>' .
			__( 'I did not tested all, I have only checked the Javascript code. If something is not working, please let me know.', 'extensions-leaflet-map' ) .
			'</p>';
		}
	}
	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		if ( current_user_can( 'manage_options' ) ) {
			$text = $text . '<h2>' . __( 'Settings', 'extensions-leaflet-map' ) . '</h2>';
		}
		echo wp_kses_post( $text );
	}
}

// Draw the menu page itself
function leafext_providers_do_page() {
	if ( current_user_can( 'manage_options' ) ) {
		echo '<form method="post" action="options.php">';
	} else {
		echo '<form>';
	}
	settings_fields( 'leafext_providers' );
	do_settings_sections( 'leafext_providers' );
	if ( current_user_can( 'manage_options' ) ) {
		wp_nonce_field( 'leafext_tiles', 'leafext_tiles_nonce' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false );
	}
	echo '</form>';
}
