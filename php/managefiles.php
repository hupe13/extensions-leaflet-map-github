<?php
/**
 * Function managefiles functions viewing pages in media library
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_display_attachment_page( $file ) {
	if ( is_singular() ) {
		$type = pathinfo( $file, PATHINFO_EXTENSION );
		$type = ( $type === 'json' ) ? 'geojson' : $type;
		if ( in_array( $type, array( 'kml', 'gpx', 'geojson' ), true ) ) {
			// var_dump($type);
			return '[leaflet-map fitbounds !scrollwheel !dragging][leaflet-' . $type . ' src="' . $file . '"]';
		}
		return '';
	}
}

// Display content from Media Library in Permalink (Attachmentseite)
function leafext_media_library_content( $content ) {
	global $post;
	if ( is_attachment() && 'application/gpx+xml' === get_post_mime_type( $post->ID ) ) {
		$content  = leafext_display_attachment_page( $post->guid );
		$gpx_data = leafext_get_gpx_data( $post->guid );
		$fields   = array();
		$fields[] = array(
			'key'   => 'url',
			'value' => $post->guid,
		);
		$fields[] = array(
			'key'   => 'filename',
			'value' => basename( $post->guid ),
		);
		foreach ( $gpx_data as $key => $value ) {
			$fields[] = array(
				'key'   => $key,
				'value' => $value,
			);
		}
		$content = $content . leafext_html_table( $fields );
	}
	if ( is_attachment() && 'application/vnd.google-earth.kml+xml' === get_post_mime_type( $post->ID ) ) {
		$content  = leafext_display_attachment_page( $post->guid );
		$fields   = array();
		$fields[] = array(
			'key'   => 'url',
			'value' => $post->guid,
		);
		$fields[] = array(
			'key'   => 'filename',
			'value' => basename( $post->guid ),
		);
		$content  = $content . leafext_html_table( $fields );
	}
	if ( is_attachment() && 'application/geo+json' === get_post_mime_type( $post->ID ) ) {
		$content  = leafext_display_attachment_page( $post->guid );
		$fields   = array();
		$fields[] = array(
			'key'   => 'url',
			'value' => $post->guid,
		);
		$fields[] = array(
			'key'   => 'filename',
			'value' => basename( $post->guid ),
		);
		$content  = $content . leafext_html_table( $fields );
	}
		return $content;
}
add_filter( 'the_content', 'leafext_media_library_content' );

function leafext_media_library_help( $postid ) {
	$current_screen = get_current_screen();
	if ( $current_screen !== null ) {
		if ( isset( $current_screen->base ) && $current_screen->base === 'post' &&
			isset( $current_screen->post_type ) && $current_screen->post_type === 'attachment' ) {
			$file = wp_get_attachment_url( $postid );
			$type = pathinfo( $file, PATHINFO_EXTENSION );
			$type = ( $type === 'json' ) ? 'geojson' : $type;
			if ( in_array( $type, array( 'kml', 'gpx', 'geojson' ), true ) ) {
				return do_shortcode(
					'[leaflet-map height=300 width=300 !scrollwheel !dragging fitbounds]' .
					'[leaflet-' . $type . ' src="' . $file . '"]'
				);
			}
		}
	}
	return '';
}

// Display on edit page in Media Library
function leafext_attachment_fields_to_edit( $form_fields, $post ) {
	libxml_use_internal_errors( true );
	// get post mime type
	$type = get_post_mime_type( $post->ID );
	// get the attachment path
	$attachment_path = get_attached_file( $post->ID );

	if ( 'application/gpx+xml' === $type ) {
		$gpx_data = leafext_get_gpx_data( $attachment_path );
		foreach ( $gpx_data as $key => $value ) {
			$form_fields[ $key ] = array(
				'value' => $value,
				'label' => $key,
				'input' => 'html',
				'html'  => $value,
			);
		}
		$form_fields['overview'] = array(
			'value' => 'Map',
			'label' => __( 'Overview', 'extensions-leaflet-map' ),
			'input' => 'html',
			'html'  => 'Map',
			'helps' => leafext_media_library_help( $post->ID ),
		);
	}

	if ( 'application/vnd.google-earth.kml+xml' === $type ) {
		$name                    = leafext_get_kml_data( $attachment_path )['name'];
		$form_fields['overview'] = array(
			'value' => $name,
			'label' => __( 'Overview', 'extensions-leaflet-map' ),
			'input' => 'html',
			'html'  => $name,
			'helps' => leafext_media_library_help( $post->ID ),
		);
	}

	if ( 'application/geo+json' === $type ) {
		$form_fields['overview'] = array(
			'value' => basename( $attachment_path ),
			'label' => __( 'Overview', 'extensions-leaflet-map' ),
			'input' => 'html',
			'html'  => 'Map',
			'helps' => leafext_media_library_help( $post->ID ),
		);
	}

	// application/vnd.garmin.tcx+xml .tcx

	return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'leafext_attachment_fields_to_edit', 10, 2 );

// Get Name and Date from gpx track
function leafext_get_gpx_data( $file ) {
	$gpxdata = array();
	$gpx     = simplexml_load_file( $file );
	if ( isset( $gpx->trk->name ) ) {
		$gpxdata['trackname'] = $gpx->trk->name;
	} else {
		$gpxdata['trackname'] = ' ';
	}
	if ( isset( $gpx->trk->trkseg->trkpt[0]->time ) ) {
		$gpxdata['time'] = $gpx->trk->trkseg->trkpt[0]->time;
	} else {
		$gpxdata['time'] = ' ';
	}
	return $gpxdata;
}

// Get Name from kml
function leafext_get_kml_data( $file ) {
	$kmldata = array();
	$kml     = simplexml_load_file( $file, 'SimpleXMLElement', LIBXML_NOCDATA );
	// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- is gpx
	if ( isset( $kml->Document->name ) ) {
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- is gpx
		$kmldata['name'] = $kml->Document->name;
	} else {
		$kmldata['name'] = ' ';
	}
	return $kmldata;
}
