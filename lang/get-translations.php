<?php
/**
 * Management locales without language pack
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();
// https://leafext.de/dev/languages/

// require_once ABSPATH . 'wp-admin/includes/translation-install.php';
// var_dump(wp_get_available_translations());

global $wp_filesystem;
require_once ABSPATH . 'wp-admin/includes/file.php';
WP_Filesystem();

$url  = 'https://translate.wordpress.org/projects/wp-plugins/extensions-leaflet-map/';
$what = '/export-translations/?filters[term]=&filters[term_scope]=scope_any&filters[status]=current_or_waiting';

// /ca/default/export-translations/
//
// ?filters%5Bterm%5D&filters%5Bterm_scope%5D=scope_any&filters%5Bstatus%5D=current_or_waiting_or_changesrequested&filters%5Buser_login%5D&format=po
//
// dev
// https://translate.wordpress.org/projects/wp-plugins/extensions-leaflet-map/dev/de/default/export-translations/?format=mo

$languages = array(
	array( 'default', 'ca', '_or_changesrequested' , 'stable/'),
	array( 'default', 'it_IT', '' , 'stable/'),
	array( 'default', 'pl_PL', '' , 'stable/'),
	array( 'default', 'pt_PT', '' , 'stable/'),
	array( 'default', 'sv_SE', '' , 'stable/'),
	// array( 'formal', 'de_DE_formal', '' , 'stable/'),
	array('default', 'de_DE', '', 'dev/'),
);
$formats   = array( 'mo', 'php', 'jed1x' );
// 'po',

echo '<ul>';

foreach ( $languages as $language ) {
	echo '<li>' . esc_html( $language[1] );
	echo '<ul>';
	foreach ( $formats as $format ) {
		$file     = LEAFEXT_PLUGIN_DIR . '/lang/extensions-leaflet-map-' . $language[1] . '.' . $format;
		$query    = substr( $language[1], 0, 2 ) . '/' . $language[0] . $what . $language[2] . '&format=' . $format;
		// var_dump($url . $language[3] . $query);
		$thisurl = $url . $language[3] . $query;
		$response = wp_remote_get( $thisurl );
		if ( wp_remote_retrieve_response_code( $response ) === 200 ) {
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				if ( $format === 'php' ) {
					$file = dirname( $file ) . '/' . basename( $file, '.php' ) . '.l10n.php';
				} elseif ( $format === 'jed1x' ) {
					$file = dirname( $file ) . '/' . basename( $file, '.jed1x' ) . '-elevation_js.json';
				}
				echo '<li>' . esc_url( $thisurl ) . '<br>' . esc_html( basename( $file ) ) . '</li>';
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
