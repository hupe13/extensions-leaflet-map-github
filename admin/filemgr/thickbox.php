<?php
/**
 * Function thickbox
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_thickbox_action_page() {
	// check_admin_referer does not work with thickbox
	// $_REQUEST thickbox: array(2) { ["action"]=> string(12) "filemgr-list" ["track"]=> string(..) "/path/myfile.gpx" }
	// $_REQUEST open in new tab:
	// array(4) { ["action"]=> string(12) "filemgr-list" ["track"]=> string(..) "/path/myfile.gpx" ["TB_iframe"]=> string(4) "true" ["leafext-thickbox_nonce"]=> string(10) "8147bcb2bc" }
	$tb_iframe = sanitize_text_field( wp_unslash( $_REQUEST['TB_iframe'] ?? '' ) );
	if ( $tb_iframe !== '' ) {
		if ( ! check_admin_referer( 'leafext-thickbox', 'leafext-thickbox_nonce' ) ) {
			wp_die( 'dead' );
		}
	}
	$site = sanitize_text_field( wp_unslash( $_SERVER['HTTP_SEC_FETCH_SITE'] ?? '' ) );
	if ( current_user_can( 'read' ) && $site === 'same-origin' ) {
		$track = filter_input(
			INPUT_GET,
			'track',
			FILTER_CALLBACK,
			array( 'options' => 'esc_html' )
		);
		if ( validate_file( $track ) !== 0 ) {
			return;
		}
		$upload_dir  = wp_get_upload_dir();
		$upload_path = $upload_dir['basedir'];
		if ( ! file_exists( realpath( $upload_path . $track ) ) ) {
			return;
		}
		$upload_url = $upload_dir['baseurl'];
		$path_parts = pathinfo( $track );
		$type       = strtolower( $path_parts['extension'] );
		if ( 'kml' !== $type && 'gpx' !== $type && 'geojson' !== $type && 'json' !== $type ) {
			return;
		}
		iframe_header( __( 'Preview', 'extensions-leaflet-map' ) );
		if ( $type === 'json' ) {
			$type = 'geojson';
		}
		echo '<style>html.wp-toolbar{padding-top: 0;}</style>';
		echo '<div class="attachment-info thickbox" align=center><div><h2>';
		esc_html_e( 'Details', 'extensions-leaflet-map' );
		echo '</h2><div><strong>';
		esc_html_e( 'Uploaded on:', 'extensions-leaflet-map' );
		echo '</strong> ' . esc_html( get_date_from_gmt( gmdate( 'Y-m-d G:i:s', filemtime( $upload_path . $track ) ) ) ) . '</div><div><strong>';
		esc_html_e( 'File name:', 'extensions-leaflet-map' );
		echo '</strong> ' . esc_html( basename( $track ) ) . '</div>';
		echo '<div ><strong>';
		esc_html_e( 'File size: ', 'extensions-leaflet-map' );
		echo '</strong> ' . esc_html( size_format( filesize( $upload_path . $track ) ) ) . '</div></div><p>';
		echo do_shortcode( '[leaflet-map  height=300 width=300 !scrollwheel !dragging fitbounds][leaflet-' . $type . ' src="' . $upload_url . $track . '"]{name}[/leaflet-' . $type . ']' );
		echo '</p>';

		$data = '';
		if ( 'gpx' === $type ) {
			$data = leafext_get_gpx_data( $upload_path . $track );
		}
		if ( 'kml' === $type ) {
			$data = leafext_get_kml_data( $upload_path . $track );
		}
		if ( is_array( $data ) ) {
			$form_fields = array();
			foreach ( $data as $key => $value ) {
				$form_fields[ $key ] = array(
					'key'   => $key,
					'value' => $value,
				);
			}
			echo wp_kses_post( leafext_html_table( $form_fields ) ) . '</div>';
		}
		iframe_footer();
	}
	exit;
}
add_action( 'admin_action_filemgr-list', 'leafext_thickbox_action_page' );
