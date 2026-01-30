<?php
/**
 * Functions for a featured map for pages / posts
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_featuredmap_params() {
	if ( is_singular() || is_archive() ) {
		$overmapref     = '<code>overviewmap</code>';
		$extramarkerref = '<code>leaflet-extramarker</code>';
	} else {
		$overmapref     = '<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=overviewmap"><code>overviewmap</code></a>';
		$extramarkerref = '<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=extramarker"><code>leaflet-extramarker</code></a>';
	}
	$mapoptions = 'width=' . get_option( 'leaflet_default_width', '100%' ) . ' height=' . get_option( 'leaflet_default_height', '250' ) . ' zoom=' . get_option( 'leaflet_default_zoom', '12' );
	$params     = array(
		array(
			'param'       => 'latlngs',
			'shortdesc'   => __( 'custom field name for lat and lng', 'extensions-leaflet-map' ),
			'desc'        => wp_sprintf(
				/* translators: %s are "overviewmap" and "codex". */
				__( 'see %1$s, default: %2$s', 'extensions-leaflet-map' ),
				$overmapref,
				'codex'
			),
			'placeholder' => 'codex',
			'to-use'      => '',
			'change'      => 'latlngs',
		),
		array(
			'param'       => 'popup',
			'shortdesc'   => 'Popup',
			'desc'        => __( 'custom field name of popup or popup content, default: no popup', 'extensions-leaflet-map' ),
			'placeholder' => '',
			'to-use'      => '',
			'change'      => 'popup',
		),
		array(
			'param'       => 'marker',
			'shortdesc'   => 'Icon',
			'desc'        => __( 'Default: blue marker icon', 'extensions-leaflet-map' ) .
			'</br>' .
			wp_sprintf(
				/* translators: %s are leaflet-marker, leaflet-extramarker, lat, lng */
				__( 'Enter here either the default icons custom field name or a shortcode for %1$s or %2$s without brackets and the options for %3$s and %4$s.', 'extensions-leaflet-map' ),
				'<code>leaflet-marker</code>',
				$extramarkerref,
				'<code>lat</code>',
				'<code>lng</code>',
			),
			'placeholder' => '',
			'to-use'      => '<p>' .
			wp_sprintf(
				/* translators: %s are leaflet-marker, leaflet-extramarker, lat, lng */
				__( 'Specify in %1$s shortcode either the icons custom field name or %2$s respectively %3$s and all options for the marker shortcode as usual but without %4$s and %5$s.', 'extensions-leaflet-map' ),
				'<code>featured-map</code>',
				'<code>marker=leaflet-marker</code>',
				'<code>marker=leaflet-extramarker</code>',
				'<code>lat</code>',
				'<code>lng</code>',
			),
			'change'      => 'marker',
		),
		array(
			'param'       => 'leaflet-map',
			'shortdesc'   => 'leaflet-map ' . __( 'options', 'extensions-leaflet-map' ),
			'desc'        => wp_sprintf(
				/* translators: %s is "leaflet-map"*/
				__( 'Defaults from %1$s settings:', 'extensions-leaflet-map' ),
				'leaflet-map'
			) . ' ' . $mapoptions,
			'placeholder' => $mapoptions,
			'to-use'      => '<p>' . wp_sprintf(
				/* translators: %s is a shortcode. */
				__( 'You can use following options from %s here and in shortcode:', 'extensions-leaflet-map' ),
				'<code>leaflet-map</code>'
			) . '<p>' . implode( ', ', leafext_leaflet_options() ) . '</p>',
			'change'      => '',
		),
	);
	return $params;
}

function leafext_featuredmap_settings() {
	$defaults = array();
	$params   = leafext_featuredmap_params();
	foreach ( $params as $param ) {
		$defaults[ $param['param'] ] = '';
	}
	$setting = shortcode_atts( $defaults, get_option( 'leafext_featuredmap', $defaults ) );
	return $setting;
}

function leafext_leaflet_options( $atts = false ) {
	$leaflet_options = array(
		// 'tileurl',
		// 'subdomains',
		'width',
		'height',
		'zoom',
		'attribution',
	);
	if ( $atts === false ) {
		return $leaflet_options;
	}
	$options = array();
	foreach ( $atts as $key => $value ) {
		if ( in_array( strtolower( $key ), array_map( 'strtolower', $leaflet_options ), true ) ) {
			$options[ strtolower( $key ) ] = $value;
		}
	}
	// var_dump($options);
	$params = '';
	if ( count( $options ) > 0 ) {
		$params = implode(
			' ',
			array_map(
				function ( $a, $b ) {
					return "$a=\"$b\""; },
				array_keys( $options ),
				array_values( $options )
			)
		);
	} else {
		$options = leafext_featuredmap_settings();
		$params  = $options['leaflet-map'];
	}
	return $params;
}

function leafext_featured_setup_icon( $atts ) {
	$markeroptions      = '';
	$leaflet_marker_cmd = 'leaflet-marker';
	$params             = array();

	if ( array_key_exists( 'marker', $atts ) ) {
		if ( $atts['marker'] === 'leaflet-extramarker' ) {
			$leaflet_marker_cmd = 'leaflet-extramarker';
			$iconoptions        = leafext_extramarker_options();
			foreach ( $atts as $key => $value ) {
				//var_dump($key,$value);
				if ( in_array( strtolower( $key ), array_map( 'strtolower', $iconoptions ), true ) ) {
					$params[ strtolower( $key ) ] = $value;
				}
			}
		} elseif ( $atts['marker'] === 'leaflet-marker' ) {
			$iconoptions = leafext_marker_options();
			foreach ( $atts as $key => $value ) {
				if ( in_array( strtolower( $key ), array_map( 'strtolower', $iconoptions ), true ) ) {
					$params[ strtolower( $key ) ] = $value;
				}
			}
		}
		if ( count( $params ) > 0 ) {
			$markeroptions = implode(
				' ',
				array_map(
					function ( $a, $b ) {
						return "$a=\"$b\""; },
					array_keys( $params ),
					array_values( $params )
				)
			);
		}
	} else {
		$options                = leafext_featuredmap_settings();
			$markeroptions      = $options['marker'];
			$leaflet_marker_cmd = strtok( $markeroptions, ' ' );
			$markeroptions      = substr( $markeroptions, strpos( $markeroptions, ' ' ) );
	}
	return array( $leaflet_marker_cmd, $markeroptions );
}

// Shortcode für featured map for pages and posts
function leafext_featuredmap_function( $atts, $content, $shortcode ) {
	$text = leafext_should_interpret_shortcode( $shortcode, $atts );
	if ( $text !== '' ) {
		return $text;
	}
	if ( ! array_key_exists( 'latlngs', $atts ) || $atts['latlngs'] === '' || $atts['latlngs'] === 'codex' ) {
		$lat = get_post_meta( get_the_ID(), 'geo_latitude', true );
		$lng = get_post_meta( get_the_ID(), 'geo_longitude', true );
		if ( $lat === '' || $lng === '' ) {
			return '[' . $shortcode . ' ERROR lat=??? lng=???]';
		}
	} elseif ( array_key_exists( 'latlngs', $atts ) ) {
		$leaflet_latlng = get_post_meta( get_the_ID(), $atts['latlngs'], true );
		$leaflet_latlng = preg_replace( '/\s+/', ' ', $leaflet_latlng ); // doppelte Leerzeichen entfernen
		$latlng         = explode( ' ', $leaflet_latlng );
		if ( count( $latlng ) !== 2 ) {
			$latlng = explode( ',', $leaflet_latlng );
		}
		if ( count( $latlng ) === 2 ) {
			if ( strpos( $leaflet_latlng, '=' ) !== false ) {
				$latlng_atts = shortcode_parse_atts( $leaflet_latlng );
				// var_dump($latlng_atts);
				if ( isset( $latlng_atts['lat'] ) && isset( $latlng_atts['lng'] ) ) {
					$lat = trim( $latlng_atts['lat'], ', ' );
					$lng = trim( $latlng_atts['lng'], ', ' );
				} else {
					$leaflet_latlng = '*';
				}
			} else {
				$lat = trim( $latlng[0], ', ' );
				$lng = trim( $latlng[1], ', ' );
			}
		} else {
			$leaflet_latlng = '*';
		}
		$leaflet_latlng = str_replace( ',', '.', $leaflet_latlng );
		if ( ! preg_match( '/^[ -0123456789\.latlng=]+$/', $leaflet_latlng ) ) {
			$leaflet_latlng = '*';
		}
		if ( $leaflet_latlng === '*' ) {
			return '[' . $shortcode . ' ERROR lat=??? lng=??? ' . $atts['latlngs'] . ']';
		}
	} else {
		return '[' . $shortcode . ' ERROR lat=??? lng=???]';
	}

	// popup
	if ( array_key_exists( 'popup', $atts ) ) {
		$popup = get_post_meta( get_the_ID(), $atts['popup'], true );
		if ( ! $popup ) {
			$popup = $atts['popup'];
		}
	} else {
		$popup = '';
	}
	// var_dump( $popup );

	// icon
	$marker    = 'leaflet-marker';
	$markerend = 'leaflet-marker';
	if ( array_key_exists( 'marker', $atts ) ) {
		$marker = get_post_meta( get_the_ID(), $atts['marker'], true );
		if ( $marker !== '' ) {
			$markerend = strtok( $marker, ' ' );
		} else {
			list ($markerend, $markeroptions) = leafext_featured_setup_icon( $atts );
			$marker                           = $markerend . ' ' . $markeroptions;
		}
	}

	// map
	$text  = do_shortcode( '[leaflet-map lat=' . $lat . ' lng=' . $lng . ' ' . leafext_leaflet_options( $atts ) . ']' );
	$text .= do_shortcode( '[' . $marker . ' lat=' . $lat . ' lng=' . $lng . ']' . $popup . '[/' . $markerend . ']' );
	$text .= do_shortcode( '[gestures]' );
	return $text;
}
add_shortcode( 'featured-map', 'leafext_featuredmap_function' );

function leafext_display_metas( $atts, $content, $shortcode ) {
	$text = leafext_should_interpret_shortcode( $shortcode, $atts );
	if ( $text !== '' ) {
		return $text;
	}
	$my_metas = get_post_meta( get_the_ID() );
	$table    = array();
	foreach ( $my_metas as $meta => $value ) {
		if ( ! str_starts_with( $meta, '_' ) ) {
			$table[] = array( $meta, $value[0] );
		}
	}
	return wp_kses_post( leafext_html_table( $table ) );
}
add_shortcode( 'leafext-meta', 'leafext_display_metas' );
