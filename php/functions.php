<?php
/**
 * Functions for parameter handling
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Interpretiere !parameter und parameter als false und true
function leafext_clear_params( $atts ) {
	if ( is_array( $atts ) ) {
		$count_atts = count( $atts );
		for ( $i = 0; $i < $count_atts; $i++ ) {
			if ( isset( $atts[ $i ] ) ) {
				if ( strpos( $atts[ $i ], '!' ) === false ) {
					$atts[ $atts[ $i ] ] = true;
				} else {
					$atts[ substr( $atts[ $i ], 1 ) ] = false;
				}
				unset( $atts[ $i ] );
			}
		}
	}
	return( $atts );
}

// shortcode_atts gibt nur Kleinbuchstaben zurueck, Javascript braucht aber gross und klein
// Parameter: array mit keys wie es sein soll, array mit keys in klein von shortcode_atts
function leafext_case( $params, $atts_array ) {
	foreach ( $params as $param ) {
		if ( strtolower( $param ) != $param ) {
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
		if ( $item[0] == $needle ) {
			return $item;
		}
	}
}

// Suche bestimmten Wert in array im admin interface
function leafext_array_find2( $needle, $haystack ) {
	foreach ( $haystack as $item ) {
		if ( $item['param'] == $needle ) {
			return $item;
		}
	}
}

// Trage php array keys und values in javascript script ein.
function leafext_java_params( $params ) {
	// var_dump($params); wp_die();
	$text = '';
	foreach ( $params as $k => $v ) {
		// var_dump($v,gettype($v),strpos($v,"["));
		$text = $text . "$k: ";
		switch ( gettype( $v ) ) {
			case 'string':
				switch ( $v ) {
					case 'false':
					case '0':
						$value = 'false';
						break;
					case 'true':
					case '1':
						$value = 'true';
						break;
					case strpos( $v, '{' ) !== false:
					case strpos( $v, '}' ) !== false:
					case strpos( $v, '[' ) !== false:
					case strpos( $v, ']' ) !== false:
					case strpos( $v, 'screen.width' ) !== false:
					case is_numeric( $v ):
						$value = $v;
						break;
					default:
						$value = '"' . $v . '"';
				}
				break;
			case 'boolean':
				$value = $v ? 'true' : 'false';
				break;
			case 'integer':
			case 'double':
				$value = $v;
				break;
			default:
			  // phpcs:ignore
				var_dump( $k, $v, gettype( $v ) );
				wp_die( 'Type' );
		}
		$text = $text . $value;
		$text = $text . ",\n";
	}
	// var_dump($text); wp_die();
	return $text;
}

/**
 * This function replaces the keys of an associate array by those supplied in the keys array
 *
 * @param array $atts_array target associative array in which the keys are intended to be replaced
 * @param string $keys associate array where search key => replace by key, for replacing respective keys
 * @return array with replaced keys
 * from https://www.php.net/manual/de/function.array-replace.php
 */
function leafext_array_replace_keys( $atts_array, $keys ) {
	foreach ( $keys as $search => $replace ) {
		if ( isset( $atts_array[ $search ] ) ) {
			$atts_array[ $replace ] = $atts_array[ $search ];
			unset( $atts_array[ $search ] );
		}
	}
	return $atts_array;
}

// check position
function leafext_check_position_control( $value ) {
	$valid = array( 'topright', 'topleft', 'bottomleft', 'bottomright' );
	return in_array( $value, $valid, true );
}

// Backend Plugin extension-leaflet-map
function leafext_backend() {
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
	if ( is_singular() || is_archive() || is_home() || is_front_page() || leafext_backend() ) {
		return '';
	} else {
		$text = "['.$shortcode.' ";
		if ( is_array( $atts ) ) {
			foreach ( $atts as $key => $item ) {
				if ( is_int( $key ) ) {
					$text = $text . "$item ";
				} else {
					$text = $text . "$key=$item ";
				}
			}
		}
		$text = $text . ']';
		return $text;
	}
}

	// ! is_admin() ||
	// ! is_singular() &&  ==  (is_single() || is_page() || is_attachment()) {
	// ! is_page() &&
	// ! is_single() &&
	// ! is_archive() &&
	// ! is_home() &&
	// ! is_front_page()

// Display array as table
function leafext_html_table_alt( $data = array() ) {
	$rows = array();
	foreach ( $data as $row ) {
		$cells = array();
		foreach ( $row as $cell ) {
			$cells[] = "<td>{$cell}</td>";
		}
		$rows[] = '<tr>' . implode( '', $cells ) . '</tr>';
	}
	return "<table border='1'>" . implode( '', $rows ) . '</table>';
}

function leafext_html_table( $data = array() ) {
	$rows      = array();
	$cellstyle = ( is_singular() || is_archive() ) ? "style='border:1px solid #195b7a;'" : '';
	foreach ( $data as $row ) {
		$cells = array();
		foreach ( $row as $cell ) {
			$cells[] = '<td ' . $cellstyle . ">{$cell}</td>";
		}
		$rows[] = '<tr>' . implode( '', $cells ) . '</tr>';
	}
	$head = '<div style="width:' . ( ( is_singular() || is_archive() ) ? '100' : '80' ) . '%;">';
	$head = $head . '<figure class="wp-block-table aligncenter is-style-stripes"><table border=1>';
	return $head . implode( '', $rows ) . '</table></figure></div>';
}

function leafext_escape_output( $output ) {
	// https://wp-mix.com/allowed-html-tags-wp_kses/
	$allowed_tags = wp_kses_allowed_html( 'post' );
	echo wp_kses( $output, $allowed_tags );
}
