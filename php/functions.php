<?php
/**
 * Functions for parameter handling
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Interpretiere !parameter und parameter als false und true
function leafext_clear_params_true_false( $atts ) {
	if ( is_array( $atts ) ) {
		foreach ( $atts as $attr => $value ) {
			if ( is_int( $attr ) ) {
				if ( strpos( $atts[ $attr ], '!' ) === false ) {
					$atts[ $atts[ $attr ] ] = true;
				} else {
					$atts[ substr( $atts[ $attr ], 1 ) ] = false;
				}
				unset( $atts[ $attr ] );
			}
		}
	}
	return( $atts );
}

// Interpretiere !parameter und parameter als false und true and validate
function leafext_clear_params( $atts ) {
	if ( is_array( $atts ) ) {
		foreach ( $atts as $attr => $value ) {
			if ( is_int( $attr ) ) {
				if ( strpos( $atts[ $attr ], '!' ) === false ) {
					$atts[ $atts[ $attr ] ] = true;
				} else {
					$atts[ substr( $atts[ $attr ], 1 ) ] = false;
				}
				unset( $atts[ $attr ] );
			} elseif ( preg_match( '%^[a-zA-Z0-9:\-/\._\ ,]+$%', $value ) ) {
				$atts[ $attr ] = $value;
			} else {
				// echo '<script>console.log(' . wp_json_encode( esc_js( 'wp_kses_post ' . $attr . '=' . $value ) ) . ')</script>';
				$atts[ $attr ] = wp_kses_post( $value );
			}
		}
	}
	return( $atts );
}

// shortcode_atts gibt nur Kleinbuchstaben zurueck, Javascript braucht aber gross und klein
// Parameter: array mit keys wie es sein soll, array mit keys in klein von shortcode_atts
function leafext_case( $params, $atts_array ) {
	foreach ( $params as $param ) {
		if ( strtolower( $param ) !== $param ) {
			if ( isset( $atts_array[ strtolower( $param ) ] ) ) {
				$atts_array[ $param ] = $atts_array[ strtolower( $param ) ];
				unset( $atts_array[ strtolower( $param ) ] );
			}
		}
	}
	return $atts_array;
}

// Suche bestimmten Wert in array im admin interface
function leafext_array_find( $needle, $haystack ) {
	foreach ( $haystack as $item ) {
		if ( $item[0] === $needle ) {
			return $item;
		}
	}
}

// Suche bestimmten Wert in array im admin interface
function leafext_array_find2( $needle, $haystack ) {
	foreach ( $haystack as $item ) {
		if ( $item['param'] === $needle ) {
			return $item;
		}
	}
}

// check position
function leafext_check_position_control( $value ) {
	$valid = array( 'topright', 'topleft', 'bottomleft', 'bottomright' );
	return in_array( $value, $valid, true );
}

// Backend Plugin extension-leaflet-map
function leafext_backend() {
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- no form
	$get          = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
	$backend_page = isset( $get['page'] ) ? sanitize_text_field( wp_unslash( $get['page'] ) ) : '';
	$server       = map_deep( wp_unslash( $_SERVER ), 'sanitize_text_field' );
	$url          = $server['REQUEST_URI'];
	if ( strpos( $backend_page, 'extensions-leaflet-map' ) !== false && strpos( $url, '/wp-admin/admin.php' ) !== false ) {
		return true;
	} else {
		return false;
	}
}

function leafext_should_interpret_shortcode( $shortcode, $atts ) {
	global $leafext_button;
	// var_dump($leafext_button);
	$server     = map_deep( wp_unslash( $_SERVER ), 'sanitize_text_field' );
	$scriptname = $server['SCRIPT_NAME'];
	if ( is_singular() || is_archive() || is_home() || is_front_page() || leafext_backend() ) {
		if ( strpos( $scriptname, '/wp-admin/post.php' ) === false ) {
			// return 'should interpret '.$shortcode;
			return '';
		} elseif ( ! isset( $leafext_button ) ) {
				$leafext_button = true;
				echo '<input type="button" value="' . esc_html__( 'Click to interpret shortcodes.', 'extensions-leaflet-map' ) . '" onclick="window.location.reload()">';
		}
	}
	$text = '[' . $shortcode . ' ';
	if ( is_array( $atts ) ) {
		foreach ( $atts as $key => $item ) {
			if ( is_int( $key ) ) {
				$text .= esc_html( "$item " );
			} else {
				$text .= esc_html( "$key=$item " );
			}
		}
	}
	$text .= ']';
	return $text;
}

	// ! is_admin() ||
	// ! is_singular() &&  ==  (is_single() || is_page() || is_attachment()) {
	// ! is_page() &&
	// ! is_single() &&
	// ! is_archive() &&
	// ! is_home() &&
	// ! is_front_page()

// Display array as table
if ( ! function_exists( 'leafext_html_table' ) ) {
	function leafext_html_table( $data = array() ) {
		$rows      = array();
		$cellstyle = ( is_singular() || is_archive() ) ? "style='border:1px solid #195b7a;'" : '';
		foreach ( $data as $row ) {
			$cells = array();
			foreach ( $row as $cell ) {
				$cells[] = '<td ' . $cellstyle . ">{$cell}</td>";
			}
			$rows[] = '<tr>' . implode( '', $cells ) . '</tr>' . "\n";
		}
		$head = '<div style="width:' . ( ( is_singular() || is_archive() ) ? '100' : '80' ) . '%;">';
		$head = $head . '<figure class="wp-block-table aligncenter is-style-stripes"><table border=1>';
		return $head . implode( '', $rows ) . '</table></figure></div>';
	}
}

// remove breaks in shortcode block
add_filter(
	'render_block',
	function ( $block_content, $block ) {
		if ( $block['blockName'] === 'core/shortcode' ) {
			$shortcodes = array( 'leaflet-map', 'elevation' );
			$match      = ( str_replace( $shortcodes, '', $block['innerHTML'] ) !== $block['innerHTML'] );
			if ( $match ) {
				return preg_replace( '/\](\r|\n)*\[/', '][', $block['innerHTML'] );
			}
		}
		return $block_content;
	},
	10,
	2
);

// remove </script>*< br/>
if ( ! is_admin() ) {
	function leafext_remove_script_br( $content ) {
		$content = preg_replace( '/<\/script>(\r|\n|<br \/>|<\/p>)*/', '</script>', $content );
		return $content;
	}
	add_filter( 'the_content', 'leafext_remove_script_br', 11 );
}

/**
 * Convert rgb() or rgba() to #rrggbbaa for hex codes.
 *
 * @param   string $color The RGB(A) color
 * @return  string The color hex code
 *
 * https://epiph.yt/blog/2023/rgb-rgba-in-php-zu-hex-werten-transformieren/
 */
function leafext_rgb_to_hex( string $color ): string {
	$has_alpha = false;
	if ( strpos( $color, 'rgba' ) === 0 ) {
		$has_alpha = true;
	}
	$color = preg_replace( '/^rgba?\(/', '', $color );
	$color = preg_replace( '/\)$/', '', $color );
	if ( strpos( $color, ',' ) !== false ) {
		$color  = str_replace( ' ', '', $color );
		$values = explode( ',', $color );
	} else {
		$color  = str_replace( '/', ' ', $color );
		$color  = preg_replace( '/\s{2,}/', ' ', $color );
		$values = explode( ' ', $color );
	}
	if ( $has_alpha && count( $values ) === 4 ) {
		$color = sprintf( '%02x%02x%02x%02x', $values[0], $values[1], $values[2], (float) $values[3] * 255 );
	} elseif ( ! $has_alpha && count( $values ) === 3 ) {
		$color = sprintf( '%02x%02x%02x', $values[0], $values[1], $values[2] ) . 'ff';
	} else {
		$color = '';
	}
	if ( empty( $color ) ) {
		return '';
		// throw new Exception( 'Color could not be transformed into hex.' );
	}
	return '#' . $color;
}

function leafext_valid_color( $color ) {
	if ( sanitize_hex_color( $color ) !== null ) {
		return $color;
	}
	if ( leafext_rgb_to_hex( $color ) !== '' ) {
		return $color;
	}
	if ( preg_match( '/^[a-z]*$/', $color ) === 1 ) {
		return $color;
	}
	return '';
}
