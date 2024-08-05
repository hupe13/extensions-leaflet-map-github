<?php
/**
 * Management locales without language pack
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// require_once ABSPATH . 'wp-admin/includes/translation-install.php';
// var_dump(wp_get_available_translations());

global $wp_filesystem;
require_once ABSPATH . 'wp-admin/includes/file.php';
WP_Filesystem();

$url  = 'https://translate.wordpress.org/projects/wp-plugins/extensions-leaflet-map/stable/';
$what = '/default/export-translations/?filters[term]=&filters[term_scope]=scope_any&filters[status]=current_or_waiting';

$languages = array( 'ca', 'it_IT', 'pl_PL', 'pt_PT', 'sv_SE' );
$formats   = array( 'mo', 'php', 'jed1x' );

// $languages = array( 'pt_PT' );
// $formats   = array( 'jed1x' );

echo '<ul>';

foreach ( $languages as $language ) {
	echo '<li>' . esc_html( $language );
	echo '<ul>';
	foreach ( $formats as $format ) {
		$file     = LEAFEXT_PLUGIN_DIR . '/lang/extensions-leaflet-map-' . $language . '.' . $format;
		$query    = substr( $language, 0, 2 ) . $what . '&format=' . $format;
		$response = wp_remote_get( $url . $query );
		if ( wp_remote_retrieve_response_code( $response ) === 200 ) {
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				if ( $format === 'php' ) {
					$file = dirname( $file ) . '/' . basename( $file, '.php' ) . '.l10n.php';
				} elseif ( $format === 'jed1x' ) {
					$file = dirname( $file ) . '/' . basename( $file, '.jed1x' ) . '-elevation_js.json';
				}
				echo '<li>' . esc_html( basename( $file ) ) . '</li>';
				// file_put_contents( $file, $response['body'] );
				if ( ! $wp_filesystem->put_contents( $file, $response['body'] ) ) {
					echo 'error saving file!';
				}
			}
		} else {
			echo 'error getting file!';
		}
	}
	echo '</ul></li>';
}

echo '</ul>';
