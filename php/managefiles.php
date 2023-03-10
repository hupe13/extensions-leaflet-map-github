<?php
/**
 * managefiles functions viewing pages in media library
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Display content from Media Library in Permalink (Attachmentseite)
function leafext_media_library_content( $content ){
  global $post;
  //
  if ( is_attachment() && 'application/gpx+xml' == get_post_mime_type( $post->ID ) ) {
    $content = '[leaflet-map fitbounds !scrollwheel !dragging][leaflet-gpx src="'. $post->guid . '"]';
    $gpx_data = leafext_get_gpx_data($post->guid);
    $fields = array();
    $fields[] = array(
      'key' => 'url',
      'value' => $post->guid,
    );
    $fields[] = array(
      'key' => 'filename',
      'value' => basename($post->guid),
    );
    foreach ( $gpx_data as $key => $value ) {
      $fields[] = array(
        'key' => __( $key ),
        'value' => $value,
      );
    }
    $content = $content . leafext_html_table($fields);
  }
  //
  if ( is_attachment() && 'application/vnd.google-earth.kml+xml' == get_post_mime_type( $post->ID ) ) {
    $content = '[leaflet-map fitbounds !scrollwheel !dragging][leaflet-kml src="'. $post->guid . '"]';
    $fields = array();
    $fields[] = array(
      'key' => 'url',
      'value' => $post->guid,
    );
    $fields[] = array(
      'key' => 'filename',
      'value' => basename($post->guid),
    );
    $content = $content . leafext_html_table($fields);
  }
  //
  if ( is_attachment() && 'application/geo+json' == get_post_mime_type( $post->ID ) ) {
    $content = '[leaflet-map fitbounds !scrollwheel !dragging][leaflet-geojson src="'. $post->guid . '"]';
    $fields = array();
    $fields[] = array(
      'key' => 'url',
      'value' => $post->guid,
    );
    $fields[] = array(
      'key' => 'filename',
      'value' => basename($post->guid),
    );
    $content = $content . leafext_html_table($fields);
  }
  //
  return $content;
}
add_filter( 'the_content', 'leafext_media_library_content' );

//Display on edit page in Media Library
//Klappt nicht im Grid Mode, da modal -> Ansatz: map.invalidateSize()?
function leafext_attachment_fields_to_edit( $form_fields, $post ){
  libxml_use_internal_errors(true);
  // get post mime type
  $type = get_post_mime_type( $post->ID );
  // get the attachment path
  $attachment_path = get_attached_file( $post->ID );

  if ( 'application/gpx+xml' == $type ){
    $gpx_data = leafext_get_gpx_data($attachment_path);
    foreach ( $gpx_data as $key => $value ) {
      $form_fields[$key] = array(
        'value' => $value,
        'label' => __( $key ),
        'input' => 'html',
        'html'  => $value,
      );
    }
    $form_fields['overview'] = array(
      'value' => "Map",
      'label' => __( 'Overview' ),
      'input' => 'html',
      'html'  => "Map",
      'helps' => do_shortcode('[leaflet-map height=300 width=300 !scrollwheel !dragging fitbounds][leaflet-gpx src="'.wp_get_attachment_url( $post->ID ).'"]'),
    );
  }

  if ( 'application/vnd.google-earth.kml+xml' == $type ){
    $name = leafext_get_kml_data($attachment_path)['name'];
    $form_fields['overview'] = array(
      'value' => $name,
      'label' => __( 'Overview' ),
      'input' => 'html',
      'html'  => $name,
      'helps' => do_shortcode('[leaflet-map height=300 width=300 !scrollwheel !dragging fitbounds][leaflet-kml src="'.wp_get_attachment_url( $post->ID ).'"]'),
    );
  }

  if ( 'application/geo+json' == $type ){
    $form_fields['overview'] = array(
      'value' => basename($attachment_path),
      'label' => __( 'Overview' ),
      'input' => 'html',
      'html'  => 'Map',
      'helps' => do_shortcode('[leaflet-map height=300 width=300 !scrollwheel !dragging fitbounds][leaflet-geojson src="'.wp_get_attachment_url( $post->ID ).'"]'),
    );
  }

  // application/vnd.garmin.tcx+xml .tcx

  return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'leafext_attachment_fields_to_edit', 10, 2 );

//Get Name and Date from gpx track
function leafext_get_gpx_data($file) {
  $gpxdata = array();
	$gpx = simplexml_load_file($file);
  if (isset($gpx->trk->name)){
	   $gpxdata['trackname']= $gpx->trk->name;
   } else {
      $gpxdata['trackname']= " ";
   }
  if (isset($gpx->trk->trkseg->trkpt[0]->time)){
    $gpxdata['time'] = $gpx->trk->trkseg->trkpt[0]->time;
  } else {
    $gpxdata['time'] = " ";
  }
  return $gpxdata;
}

//Get Name from kml
function leafext_get_kml_data($file) {
  $kmldata=array();
  $kml=simplexml_load_file($file,"SimpleXMLElement",LIBXML_NOCDATA);
  if (isset($kml->Document->name)) {
    $kmldata['name'] = $kml->Document->name;
  } else {
    $kmldata['name'] = " ";
  }
  return $kmldata;
}
