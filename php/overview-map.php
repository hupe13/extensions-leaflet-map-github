<?php
/**
 * Functions for an overview map from pages / posts
 * Idea and initial code from @codade
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// params to set
function leafext_overviewmap_settings() {
	$params = array(
		array(
			'param'   => 'latlngs',
			'desc'    => __( 'for markers lat and lng, required', 'extensions-leaflet-map' ),
			'content' => '<ul>' .
			'<li>' . __( 'either a comma or space separated pair of lat and lng', 'extensions-leaflet-map' ) . '</li>' .
			'<li>' . __( 'or', 'extensions-leaflet-map' ) . ' <code>lat=... lng=...</code> ' . __( 'like in', 'extensions-leaflet-map' ) . ' leaflet-marker</li>' .
			// '<li>'.' <s>'.__('or',"extensions-leaflet-map").' <code>leaflet-gpx / leaflet-kml src=... </code> '.
			// __('(like shortcode without brackets)',"extensions-leaflet-map").'</s>'.'</li>'.
			'</ul>',
			'default' => 'overview-latlng',
			'values'  => '',
		),
		array(
			'param'   => 'icons',
			'desc'    => __( 'for the marker icon, optional.', 'extensions-leaflet-map' ) . '<br>' .
			__( 'Default is taken from the overviewmap shortcode or it is the blue marker icon.', 'extensions-leaflet-map' ),
			'content' => '<ul>' .
			'<li>' . __( 'either a icon filename', 'extensions-leaflet-map' ) . ' <code>filename.ext</code></li>' .
			'<li>' . __( 'or', 'extensions-leaflet-map' ) . ' <code>leaflet-marker iconurl=... option=... ...</code> ' .
			__( '(like shortcode without brackets)', 'extensions-leaflet-map' ) . '</li>' .
			'<li>' . __( 'or', 'extensions-leaflet-map' ) . ' <code>leaflet-extramarker option=... ...</code> ' .
			__( '(like shortcode without brackets)', 'extensions-leaflet-map' ) . '</li>' .
			'</ul>',
			'default' => 'overview-icon',
			'values'  => '',
		),
		array(
			'param'   => 'popup',
			'desc'    => __( 'for the popup content, optional.', 'extensions-leaflet-map' ),
			'content' => '<ul>' .
			'<li> false - ' . __( 'default popup content', 'extensions-leaflet-map' ) . '</li>' .
			'<li> true - ' . __( 'the name of custom field is', 'extensions-leaflet-map' ) . ' <code>overview-popup</code></li>' .
			'<li>' . __( 'or the name of the popup custom field', 'extensions-leaflet-map' ) . '</li>' .
			'</ul>',
			'default' => false,
			'values'  => '',
		),
	);
	return $params;
}

function leafext_overviewmap_params() {
	$params = array(
		array(
			'param'   => 'show_thumbnails',
			'desc'    => __( 'Show page / post featured image', 'extensions-leaflet-map' ),
			'content' => '',
			'default' => false,
			'values'  => 'true / false',
		),
		array(
			'param'   => 'thumbsize',
			'desc'    => __( 'Size of featured image', 'extensions-leaflet-map' ),
			'content' => '',
			'default' => '75, 75',
			/* translators: %s is popup. */
			'values'  => sprintf( __( 'a comma separated pair of width and height in pixels. Only makes sense when you use the %s option.', 'extensions-leaflet-map' ), 'popup' ),
		),
		array(
			'param'   => 'show_category',
			'desc'    => __( 'Show a link to category page', 'extensions-leaflet-map' ),
			'content' => '',
			'default' => false,
			'values'  => 'true / false',
		),
		array(
			'param'   => 'newtab',
			'desc'    => __( 'Open page, post or category links in a new tab', 'extensions-leaflet-map' ),
			'content' => '',
			'default' => false,
			'values'  => 'true / false',
		),
		array(
			'param'   => 'category',
			'desc'    => __( 'Select only pages / posts from these categories', 'extensions-leaflet-map' ),
			'content' => '',
			'default' => '',
			'values'  => __( 'a comma separated list of category names, slugs or IDs', 'extensions-leaflet-map' ) . '. ' .
				sprintf(
					/* translators: %s is "AND". */
					__(
						'If the list starts with %s, only pages / posts that are contained in all of them are displayed, otherwise those that are contained in at least one.',
						'extensions-leaflet-map'
					),
					'<i>AND</i>'
				),
		),
		array(
			'param'   => 'leaflet-extramarker',
			/* translators: %s are "leaflet-marker" and "leaflet-extramarker". */
			'desc'    => sprintf( __( 'Specify this, if there are no any marker information in custom fields and the icons appear as %1$s instead of %2$s.', 'extensions-leaflet-map' ), '<code>leaflet-marker</code>', '<code>leaflet-extramarker</code>' ),
			'content' => '',
			'default' => false,
			'values'  => 'true / false',
		),
		array(
			'param'   => 'debug',
			'desc'    => __( 'Creates an overview table of posts / pages instead of markers to see any mistakes', 'extensions-leaflet-map' ),
			'content' => '',
			'default' => false,
			'values'  => 'true / false',
		),
	);
	return $params;
}

function leafext_overviewmap_admin_params() {
	$params = array(
		array(
			'param'     => 'transients',
			'shortdesc' => __( 'Use transients', 'extensions-leaflet-map' ),
			/* translators: %s is "false". */
			'desc'      => sprintf( __( 'Set this to %s, if you have trouble to get the right markers.', 'extensions-leaflet-map' ), '<code>false</code>' ),
			'default'   => true,
			'values'    => 1,
		),
		// array(
		// 'param' => 'delete',
		// 'shortdesc' => __( 'Delete actual transients', 'extensions-leaflet-map' ),
		// 'desc' => '',
		// 'default' => '',
		// 'values' => 1,
		// ),
	);
	return $params;
}

function overviewmap_admin_settings() {
	$defaults = array();
	$params   = leafext_overviewmap_admin_params();
	foreach ( $params as $param ) {
		$defaults[ $param['param'] ] = $param['default'];
	}
	$options = shortcode_atts( $defaults, get_option( 'leafext_overviewmap' ) );
	// var_dump($options); wp_die();
	return $options;
}

function leafext_marker_options() {
	return array(
		'iconUrl',
		'iconSize',
		'iconAnchor',
		'shadowUrl',
		'shadowSize',
		'shadowAnchor',
		'popupAnchor',
		'tooltipAnchor',
		'alt',
		'background',
		'color',
		'iconClass',
		'opacity',
		'svg',
		'title',
		'zIndexOffset',
	);
}

function leafext_extramarker_options() {
	$extramarker_options = array(
		'draggable',
		'opacity',
		'title',
	);
	foreach ( leafext_extramarker_params() as $param ) {
		if ( $param['param'] !== 'lat' && $param['param'] !== 'lng' ) {
			$extramarker_options[] = $param['param'];
		}
	}
	return $extramarker_options;
}

function leafext_overview_wpdb_query( $latlngs, $category = '' ) {
	global $wpdb;

	$settings = overviewmap_admin_settings();
	if ( $settings['transients'] ) {
		echo '<script>console.log("' . esc_js( __( 'Use transients', 'extensions-leaflet-map' ) ) . '");</script>';
		// $startTime = microtime(true);
		// Check for transient. If none, then execute WP_Query
		$pageposts = get_transient( 'leafext_ovm_' . $latlngs );
	} else {
		$pageposts = false;
	}
	// $pageposts = false;
	if ( false === $pageposts ) {
		$args       = array(
			'public' => true,
		);
		$post_types = get_post_types( $args, 'names' );
		unset( $post_types['attachment'] );

		// $querystr = "
		// SELECT DISTINCT wposts.*
		// FROM $wpdb->posts wposts
		// LEFT JOIN $wpdb->postmeta wpostmeta ON wposts.ID = wpostmeta.post_id
		// WHERE wpostmeta.meta_key = '".$latlngs."'
		// AND wposts.post_status = 'publish'
		// AND (wposts.post_type = 'post' OR wposts.post_type = 'page')
		// ";
		// $pageposts = $wpdb->get_results($querystr, OBJECT);
		$query = new WP_Query(
			array(
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'     => $latlngs,
						'compare' => 'EXISTS',
					),
					array(
						'key'     => $latlngs,
						'compare' => '!=',
						'value'   => '',
					),
				),
				// 'post_type'      => array( 'post', 'page' ),
				'post_type'      => $post_types,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			)
		);
		$pageposts = $query->posts;
		if ( $settings['transients'] ) {
			set_transient( 'leafext_ovm_' . $latlngs, $pageposts, DAY_IN_SECONDS );
		}
	}
	// echo number_format((microtime(true) - $startTime), 5);
	// var_dump($pageposts);

	$catposts = array();
	if ( is_array( $category ) && $category[0] === 'AND' ) {
		// var_dump("AND");
		$all_cats = $category;
		array_shift( $all_cats );

		if ( $pageposts ) {
			foreach ( $pageposts as $post ) {
				$is_in_cats = array();
				foreach ( $all_cats as $cat ) {
					if ( ( has_term( $cat, 'category', $post->ID ) !== false ) || ( has_term( $cat, 'post_tag', $post->ID ) !== false ) ) {
						$is_in_cats[] = $cat;
					}
				}
				if ( count( array_diff( $all_cats, $is_in_cats ) ) === 0 ) {
					$catposts[] = $post;
				}
			}
		}
	} elseif ( $pageposts ) {
		foreach ( $pageposts as $post ) {
			// if ( in_category( $category, $post->ID ) !== false ) {
			// @codade
			if ( ( has_term( $category, 'category', $post->ID ) !== false ) || ( has_term( $category, 'post_tag', $post->ID ) !== false ) ) {
				$catposts[] = $post;
			}
		}
	}
	// var_dump($catposts);
	if ( $category !== '' ) {
		return $catposts;
	}
	// var_dump($pageposts);
	return $pageposts;
}

// Create a function to delete our transient
function leafext_delete_ovm_transient() {
	$meta = get_post_meta( get_the_ID() );
	// var_dump($meta);
	if ( is_array( $meta ) ) {
		foreach ( $meta as $key => $val ) {
			if ( false !== get_transient( 'leafext_ovm_' . $key ) ) {
				delete_transient( 'leafext_ovm_' . $key );
			}
		}
	}
}
// Add the function to the edit_term hook so it runs when categories/tags are edited
add_action( 'save_post', 'leafext_delete_ovm_transient' );

function leafext_check_duplicates_meta( $postid, $meta ) {
	$fields = get_post_meta( $postid, $meta, false );
	// $fields:
	// An array of values if $single is false.
	// The value of the meta field if $single is true.
	if ( count( $fields ) > 1 ) {
		echo '<script>console.log("' . esc_js( __( 'Multiple custom fields with the same name', 'extensions-leaflet-map' ) ) . ' ' . esc_js( $postid ) . ' ' . esc_js( $meta ) . '");</script>';
		return '*';
	}
	return '';
}

function leafext_get_overview_data( $post, $overview_options ) {
	$leaflet_error = '';
	// setup data for specific post
	setup_postdata( $post );
	$overview_data = array();
	//
	// For the Link
	$overview_data['permalink'] = get_permalink( $post->ID );
	$overview_data['title']     = get_the_title( $post->ID );
	//
	$overview_data['newtab'] = $overview_options['newtab'] ? ' target="_blank"' : '';

	// check if post has a thumnail
	$overview_data['thumbnail'] = '';
	if ( $overview_options['show_thumbnails'] === true ) {
		if ( has_post_thumbnail( $post->ID ) ) {
			$size = explode( ',', $overview_options['thumbsize'] );
			if ( count( $size ) === 2 ) {
				$x = (int) $size[0];
				$y = (int) $size[1];
			} else {
				$x = 75;
				$y = 75;
			}
			$overview_data['thumbnail'] = get_the_post_thumbnail( $post->ID, array( $x, $y ), array( 'loading' => false ) );
		}
	}
	//
	// categories
	$overview_data['categories'] = '';
	if ( $overview_options['show_category'] === true ) {
		// $overview_data['categories'] = get_the_category_list( ', ', '', $post->ID );
		$cat_list = get_the_category_list( ', ', '', $post->ID );
		if ( $overview_options['newtab'] ) {
			$cat_list = preg_replace( '#<a href="#', '<a target="_blank" href="', $cat_list );
		}
		$overview_data['categories'] = $cat_list;
	}
	//
	// the marker latlng
	$overview_data['latlng']           = ''; // wegen der Reihenfolge in der Tabelle
	$leaflet_latlng                    = get_post_meta( $post->ID, $overview_options['latlngs'], true );
	$overview_data['latlng-orig']      = $leaflet_latlng;
	$overview_data['error_latlng']     = '';
	$overview_data['multiple_latlngs'] = leafext_check_duplicates_meta( $post->ID, $overview_options['latlngs'] );

	// var_dump($leaflet_latlng);
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
				$leaflet_latlng = 'lat=' . trim( $latlng_atts['lat'], ', ' ) . ' lng=' . trim( $latlng_atts['lng'], ', ' );
			} else {
				$leaflet_latlng = '*';
			}
		} else {
			$leaflet_latlng = 'lat=' . trim( $latlng[0], ', ' ) . ' lng=' . trim( $latlng[1], ', ' );
		}
	} else {
		$leaflet_latlng = '*';
	}
	$leaflet_latlng = str_replace( ',', '.', $leaflet_latlng );
	if ( ! preg_match( '/^[ -0123456789\.latlng=]+$/', $leaflet_latlng ) ) {
		echo '<script>console.log("' . esc_js( __( 'Error detecting lanlngs', 'extensions-leaflet-map' ) ) . ' ' . esc_js( $post->ID ) . ': ' . esc_js( $overview_options['latlngs'] ) . ' = ' . esc_js( $leaflet_latlng ) . '");</script>';
		$leaflet_latlng = '*';
	}
	$overview_data['latlng'] = $leaflet_latlng;
	if ( $overview_data['latlng'] === $overview_data['latlng-orig'] ) {
		$overview_data['latlng-orig'] = '';
	}
	//
	// the marker icon
	$overview_data['icon']           = trim( get_post_meta( $post->ID, $overview_options['icons'], true ) );
	$overview_data['multiple_icons'] = leafext_check_duplicates_meta( $post->ID, $overview_options['icons'] );
	//
	// popup
	$overview_data['popup'] = '';
	if ( $overview_options['popup'] !== false ) {
		if ( $overview_options['popup'] === true ) {
			$overview_options['popup'] = 'overview-popup';
		}
		$overview_data['popup']          = trim( get_post_meta( $post->ID, $overview_options['popup'], true ) );
		$overview_data['multiple_popup'] = leafext_check_duplicates_meta( $post->ID, $overview_options['popup'] );
	}
	//
	wp_reset_postdata();
	return $overview_data;
}

function leafext_ovm_setup_icon( $overview_data, $atts ) {
	$markeroptions      = '';
	$leaflet_marker_cmd = 'leaflet-marker';
	$iconerror          = '';
	$pathinfo           = array();
	$iconoptions        = leafext_marker_options();
	if ( $overview_data['icon'] !== '' ) {
		// var_dump($overview_data['icon']);
		$params = array();
		if ( strpos( $overview_data['icon'], 'leaflet-extramarker' ) === 0 ) { // beginnt mit leaflet-extramarker
			leafext_enqueue_extramarker();
			$leaflet_marker_cmd = 'leaflet-extramarker';
			$iconoptions        = leafext_extramarker_options();
		} elseif ( strpos( $overview_data['icon'], 'leaflet-marker' ) === 0 ) {
			$noop = true; // phpcs
		} elseif ( strpos( $overview_data['icon'], '=' ) === false ) {
			$overview_data['icon'] = sanitize_file_name( $overview_data['icon'] );
			$pathinfo              = pathinfo( $overview_data['icon'] );
			if ( ! ( array_key_exists( 'filename', $pathinfo ) && array_key_exists( 'extension', $pathinfo ) ) ) {
				echo '<script>console.log("' . esc_js( __( 'Error - no valid filename:', 'extensions-leaflet-map' ) ) . '");</script>';
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
				echo '<script>console.log(' . wp_json_encode( var_export( $overview_data, true ) ) . ');</script>';
				$iconerror = '*';
			}
		}
		// atts from overviewmap shortcode
		foreach ( $atts as $key => $value ) {
			if ( in_array( strtolower( $key ), array_map( 'strtolower', $iconoptions ), true ) ) {
				$params[ strtolower( $key ) ] = $value;
			}
		}
		// atts from custom field
		$marker_atts = shortcode_parse_atts( $overview_data['icon'] );
		foreach ( $marker_atts as $key => $value ) {
			// var_dump($key,$value);
			if ( in_array( strtolower( $key ), array_map( 'strtolower', $iconoptions ), true ) ) {
				$params[ strtolower( $key ) ] = $value;
			}
		}

		if ( count( $pathinfo ) > 0 ) {
			if ( array_key_exists( 'iconurl', $params ) ) {
				$params['iconurl'] = dirname( $params['iconurl'] ) . '/' . $pathinfo['filename'] . '.' . $pathinfo['extension'];
			} else {
				$leaflet_error = '* ERROR: icon problem - no iconurl found: ' . $overview_data['icon'] . ' * ';
			}
		}

		// var_dump($params);
		$markeroptions = implode(
			' ',
			array_map(
				function ( $a, $b ) {
					return "$a=\"$b\""; },
				array_keys( $params ),
				array_values( $params )
			)
		);
	} else {
		// var_dump("icon from shortcode");
		$params = array();
		if ( array_search( 'leaflet-extramarker', $atts, true ) === false ) {
			$iconoptions = leafext_marker_options();
			foreach ( $atts as $key => $value ) {
				if ( in_array( strtolower( $key ), array_map( 'strtolower', $iconoptions ), true ) ) {
					$params[ strtolower( $key ) ] = $value;
				}
			}
		}
		if ( count( $params ) > 0 ) {
			$leaflet_marker_cmd = 'leaflet-marker';
		} else {
			$iconoptions = leafext_extramarker_options();
			foreach ( $atts as $key => $value ) {
				if ( in_array( strtolower( $key ), array_map( 'strtolower', $iconoptions ), true ) ) {
					$params[ strtolower( $key ) ] = $value;
				}
			}
			if ( count( $params ) > 0 ) {
				$leaflet_marker_cmd = 'leaflet-extramarker';
			}
		}
		if ( is_array( $params ) ) {
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
	}
	return array( $leaflet_marker_cmd, $markeroptions, $iconerror );
}

function leafext_overview_popup( $overview_data ) {
		// check if post has a thumnail
	if ( $overview_data['thumbnail'] !== '' ) {
		$overview_data['thumbnail'] = '<div class="leafext-overview-popup-img">' . $overview_data['thumbnail'] . '</div>';
	}
		// categories
	if ( $overview_data['categories'] !== '' ) {
		$overview_data['categories'] = '<div class="leafext-overview-popup-cat">' . $overview_data['categories'] . '</div>';
	}
		// Link
		// $link_to_page = '<a href="' . $overview_data['permalink'] . '"><strong>' . $overview_data['title'] . '</strong></a>';
		$link_to_page = '<a href="' . $overview_data['permalink'] . '"' . $overview_data['newtab'] . '><strong>' . $overview_data['title'] . '</strong></a>';

		//
	if ( $overview_data['popup'] !== '' ) {
		$search       = array(
			'{permalink}',
			'{title}',
			'{thumbnail}',
			'{categories}',
			'{link}',
		);
		$replace      = array(
			$overview_data['permalink'],
			$overview_data['title'],
			$overview_data['thumbnail'],
			$overview_data['categories'],
			$link_to_page,
		);
		$popupcontent = str_replace( $search, $replace, $overview_data['popup'] );
	} else {
		//
		if ( $overview_data['thumbnail'] === '' || $overview_data['categories'] === '' ) {
			$popupcss = 'leafext-overview-popup-one';
		} else {
			$popupcss = 'leafext-overview-popup';
		}
		$popupcontent = '<div class="' . $popupcss . '">' .
		'<div class="leafext-overview-popup-header">' . $link_to_page . '</div>' .
		$overview_data['thumbnail'] .
		$overview_data['categories'] .
		'</div>';
	}
	//
	return $popupcontent;
}

function leafext_ovm_setup_leafletmarker( $overview_data, $atts ) {

	// check if post has a thumnail
	if ( $overview_data['thumbnail'] !== '' ) {
		$overview_data['thumbnail'] = '<div class="leafext-overview-popup-img">' . $overview_data['thumbnail'] . '</div>';
	}
	//
	// categories
	if ( $overview_data['categories'] !== '' ) {
		$overview_data['categories'] = '<div class="leafext-overview-popup-cat">' . $overview_data['categories'] . '</div>';
	}
	//
	// Link
	$link_to_page = '<a href="' . $overview_data['permalink'] . '"><strong>' . $overview_data['title'] . '</strong></a>';
	//
	// the marker icon
	list($leaflet_marker_cmd, $markeroptions, $overview_data['iconerror']) = leafext_ovm_setup_icon( $overview_data, $atts );
	//
	// latlng
	if ( $overview_data['latlng'] === '*' ) {
		$leaflet_marker_cmd = '**' . $leaflet_marker_cmd;
	}
	//
	$leaflet_marker_code = '[' . $leaflet_marker_cmd . ' ' . $overview_data['latlng'] . ' ' . $markeroptions . ']' .
	leafext_overview_popup( $overview_data ) .
	'[/' . $leaflet_marker_cmd . ']';
	if ( $overview_data['latlng'] === '*' ) {
		echo '<script>console.log("' . esc_js( __( 'Error - please check overviewmap data: Some data are wrong.', 'extensions-leaflet-map' ) ) . '");</script>';
		return '';
	} else {
		return $leaflet_marker_code;
	}
}

function leafext_overview_debug( $overview_data, $post ) {
	// Link
	$overview_data['permalink'] = '<a href="' . $overview_data['permalink'] . '" target="_blank"><strong>' . $overview_data['title'] . '</strong></a>';
	if ( current_user_can( 'edit_post', $post->ID ) ) {
		$overview_data['permalink'] = $overview_data['permalink'] . '<p><a href="' . get_edit_post_link( $post->ID ) . '" target="_blank">' . __( 'Edit', 'extensions-leaflet-map' ) . '</a></p>';
	}
	unset( $overview_data['title'] );
	return $overview_data;
}

// Shortcode für Wegpunkte aus Posts:
function leafext_overviewmap_function( $atts, $content, $shortcode ) {
	$text = leafext_should_interpret_shortcode( $shortcode, $atts );
	if ( $text !== '' ) {
		return $text;
	} else {
		leafext_enqueue_overview();
		$defaults = array();
		$params   = leafext_overviewmap_settings();
		foreach ( $params as $param ) {
			$defaults[ $param['param'] ] = $param['default'];
		}
		$params = leafext_overviewmap_params();
		foreach ( $params as $param ) {
			$defaults[ $param['param'] ] = $param['default'];
		}
		$overview_options = shortcode_atts( $defaults, leafext_clear_params( $atts ) );
		// var_dump($overview_options);

		if ( $overview_options['category'] !== '' && strpos( $overview_options['category'], ',' ) !== false ) {
			$overview_options['category'] = explode( ',', esc_sql( $overview_options['category'] ) );
		}
		$pageposts = leafext_overview_wpdb_query( esc_sql( $overview_options['latlngs'] ), $overview_options['category'] );

		// var_dump($pageposts);
		$text = '';
		if ( $pageposts ) {
			$debugtable = array();
			foreach ( $pageposts as $post ) {
				$overview_data = leafext_get_overview_data( $post, $overview_options );
				if ( $overview_options['debug'] === true ) {
					$debugtable[] = leafext_overview_debug( $overview_data, $post );
				} else {
					$leaflet_marker_code = leafext_ovm_setup_leafletmarker( $overview_data, $atts );
					$text                = $text . do_shortcode( $leaflet_marker_code );
				}
			}
			if ( $overview_options['debug'] === true ) {
				$debugtable = array_map( 'array_filter', $debugtable ); // entferne alle leeren Felder, also mit value == ''
				// var_dump(max($debugtable));
				$header   = array();
				$newtable = array();
				foreach ( max( $debugtable ) as $key => $value ) {
					$header[ $key ] = '<strong>' . $key . '</strong>';
				}
				$newtable[] = $header;
				foreach ( $debugtable as $entry ) {
					foreach ( max( $debugtable ) as $key => $value ) {
						if ( ! array_key_exists( $key, $entry ) ) {
							$entry[ $key ] = '';
						}
					}
					$newtable[] = $entry;
				}
				$text = $text . leafext_html_table( $newtable );
			}
		} else {
			$text = '<script>console.log("' . esc_js( __( 'no leaflet-marker custom fields', 'extensions-leaflet-map' ) ) . '");</script>';
		}
		return $text;
	}
}
add_shortcode( 'overviewmap', 'leafext_overviewmap_function' );
