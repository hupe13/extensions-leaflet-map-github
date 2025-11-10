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

$leafext_url  = 'https://translate.wordpress.org/projects/wp-plugins/extensions-leaflet-map/';
$leafext_what = '/export-translations/?filters[term]=&filters[term_scope]=scope_any&filters[status]=current_or_waiting';

// /ca/default/export-translations/
//
// ?filters%5Bterm%5D&filters%5Bterm_scope%5D=scope_any&filters%5Bstatus%5D=current_or_waiting_or_changesrequested&filters%5Buser_login%5D&format=po
//
// dev
// https://translate.wordpress.org/projects/wp-plugins/extensions-leaflet-map/dev/de/default/export-translations/?format=mo

$leafext_languages = array(
	// Sprachpakete: englisch, deutsch, niederlaendisch (dutch), spanisch
	array( 'default', 'de_DE', '', 'dev/'),
	array( 'default', 'nl_NL', '' , 'stable/'),
	//array( 'default', 'nl_NL', '' , 'dev/'),
	array( 'default', 'es_ES', '_or_changesrequested' , 'stable/'),
	//
	array( 'default', 'ca', '_or_changesrequested' , 'stable/'),
	array( 'default', 'it_IT', '' , 'stable/'),
	array( 'default', 'pl_PL', '' , 'stable/'),
	array( 'default', 'pt_PT', '' , 'stable/'),
	array( 'default', 'sv_SE', '' , 'stable/'),
	array( 'formal', 'de_DE_formal', '' , 'stable/'),
);
$leafext_formats   = array( 'mo', 'php', 'jed1x' );
// 'po',

echo '<ul>';

foreach ( $leafext_languages as $leafext_language ) {
	echo '<li>' . esc_html( $leafext_language[1] );
	echo '<ul>';
	foreach ( $leafext_formats as $leafext_format ) {
		$leafext_file     = LEAFEXT_PLUGIN_DIR . '/lang/extensions-leaflet-map-' . $leafext_language[1] . '.' . $leafext_format;
		$leafext_query    = substr( $leafext_language[1], 0, 2 ) . '/' . $leafext_language[0] . $leafext_what . $leafext_language[2] . '&format=' . $leafext_format;
		// var_dump($leafext_url . $leafext_language[3] . $leafext_query);
		$leafext_thisurl = $leafext_url . $leafext_language[3] . $leafext_query;
		$leafext_response = wp_remote_get( $leafext_thisurl );
		if ( wp_remote_retrieve_response_code( $leafext_response ) === 200 ) {
			if ( is_array( $leafext_response ) && ! is_wp_error( $leafext_response ) ) {
				if ( $leafext_format === 'php' ) {
					$leafext_file = dirname( $leafext_file ) . '/' . basename( $leafext_file, '.php' ) . '.l10n.php';
				} elseif ( $leafext_format === 'jed1x' ) {
					$leafext_file = dirname( $leafext_file ) . '/' . basename( $leafext_file, '.jed1x' ) . '-elevation_js.json';
				}
				echo '<li>' . esc_url( $leafext_thisurl ) . '<br>' . esc_html( basename( $leafext_file ) ) . '</li>';
				if ( ! $wp_filesystem->put_contents( $leafext_file, $leafext_response['body'] ) ) {
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
