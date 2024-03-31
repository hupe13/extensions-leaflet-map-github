<?php
/**
 * Functions for markerclustergroup shortcode
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

add_filter(
	'pre_do_shortcode_tag',
	function ( $output, $shortcode ) {
		if ( 'leaflet-map' == $shortcode ) {
			global $leafext_group_menu;
			$leafext_group_menu = array();
		}
		return $output;
	},
	10,
	2
);

function leafext_clustergroup_function( $atts, $content, $shortcode ) {
	$text = leafext_should_interpret_shortcode( $shortcode, $atts );
	if ( $text != '' ) {
		return $text;
	} else {
		// var_dump($atts); wp_die();
		leafext_enqueue_leafext( 'featuregroup' );
		leafext_enqueue_markercluster();
		leafext_enqueue_clustergroup();
		$featuregroups = shortcode_atts(
			array(
				'feat'    => false,
				'strings' => false,
				'groups'  => false,
				'visible' => false,
				'substr'  => true,
			),
			$atts
		);
		// var_dump($featuregroups); wp_die();

		$cl_strings = array_map( 'trim', explode( ',', $featuregroups['strings'] ) );
		$cl_groups  = array_map( 'trim', explode( ',', $featuregroups['groups'] ) );

		$groups_array = array_map( 'trim', explode( ',', $featuregroups['groups'] ) );
		$grouptext    = array();
		$cl_groups    = array();
		foreach ( $groups_array as $group ) {
			$grouptext[] = esc_html( $group );
			$cl_groups[] = trim( wp_strip_all_tags( $group ) );
		}

		global $leafext_group_menu;
		$leafext_group_menu = array_merge( $leafext_group_menu, array_combine( $cl_groups, $grouptext ) );

		if ( $featuregroups['visible'] === false ) {
			$featuregroups['visible'] = array_fill( 0, count( $cl_strings ), '1' );
			$cl_on                    = array_fill( 0, count( $cl_strings ), '1' );
		} else {
			$cl_on = array_map( 'trim', explode( ',', $featuregroups['visible'] ) );
			if ( count( $cl_on ) == 1 ) {
				$cl_on = array_fill( 0, count( $cl_strings ), '0' );
			}
		}

		if ( count( $cl_strings ) != count( $cl_groups )
			&& count( $cl_strings ) != count( $cl_on )
		) {
			$text = '[markerclustergroup ';
			foreach ( $atts as $key => $item ) {
				$text = $text . "$key=$item ";
			}
			$text = $text . ']';
			$text = $text . ' - strings and groups (and visible) do not match. ';
			return $text;
		}

		$featuregroups = array(
			'feat'    => sanitize_text_field( $featuregroups['feat'] ),
			'groups'  => array_combine( $cl_strings, $cl_groups ),
			'visible' => array_combine( $cl_strings, $cl_on ),
		);

		$control     = array(
			'position'  => 'topright',
			'collapsed' => 'false',
		);
		$atts1       = leafext_clear_params( $atts );
		$ctl_options = shortcode_atts( $control, $atts1 );
		if ( ! leafext_check_position_control( $ctl_options['position'] ) ) {
			$ctl_options['position'] = 'topright';
		}

		$options = array(
			'property'  => '',
			'option'    => '',
			'groups'    => $featuregroups['groups'],
			'grouptext' => $leafext_group_menu,
			'visible'   => $featuregroups['visible'],
			'position'  => $ctl_options['position'],
			'collapsed' => $ctl_options['collapsed'],
		);

		if ( strpos( $featuregroups['feat'], 'properties' ) !== false ) {
			$options['property'] = substr( $featuregroups['feat'], 11 );
			$options['substr']   = false;
		} else {
			$options['option'] = $featuregroups['feat'];
			$options['substr'] = true;
		}

		$clusteroptions = leafext_cluster_atts( $atts );
		return leafext_featuregroup_script( $options, $clusteroptions );
	}
}
add_shortcode( 'markerClusterGroup', 'leafext_clustergroup_function' );
// WP Codex: Shortcode names should be all lowercase
add_shortcode( 'markerclustergroup', 'leafext_clustergroup_function' );
