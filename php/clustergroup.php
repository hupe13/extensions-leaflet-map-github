<?php
/**
 * Functions for markerClusterGroup shortcode
 * extensions-leaflet-map
 */

// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_clustergroup_function( $atts ){
	if (is_singular() || is_archive()) {
		//var_dump($atts); wp_die();
		leafext_enqueue_markercluster ();
		leafext_enqueue_clustergroup ();
		$featuregroups = shortcode_atts(
			array(
				'feat' => false,
				'strings' => false,
				'groups' => false,
				'visible' => false
			), $atts
		);
		//var_dump($featuregroups); wp_die();

		$cl_strings= array_map('trim', explode( ',', $featuregroups['strings'] ));
		$cl_groups = array_map('trim', explode( ',', $featuregroups['groups'] ));
		if ($featuregroups['visible'] === false) {
			$featuregroups['visible'] = array_fill(0, count($cl_strings), '1');
			$cl_on = array_fill(0, count($cl_strings), '1');
		} else {
			$cl_on = array_map('trim', explode( ',', $featuregroups['visible'] ));
			if (count($cl_on) == 1) {
				$cl_on = array_fill(0, count($cl_strings), '0');
			}
		}

		if ( count($cl_strings) != count($cl_groups) && count($cl_strings) != count($cl_on) ) {
			$text = "[markerClusterGroup ";
			foreach ($atts as $key=>$item){
				$text = $text. "$key=$item ";
			}
			$text = $text. "]";
			$text = $text." - strings and groups (and visible) do not match. ";
			return $text;
		}

		$featuregroups = array(
			'feat' => sanitize_text_field($featuregroups['feat']),
			'groups'  => array_combine($cl_strings, $cl_groups),
			'visible' => array_combine($cl_strings, $cl_on),
		);

		$options = array(
			'property' => '',
			'option' => '',
			'groups'  => $featuregroups['groups'],
			'visible' => $featuregroups['visible'],
		);
		if (strpos($featuregroups['feat'],"properties") !== false) {
			$options['property'] = substr($featuregroups['feat'],11);
		} else {
			$options['option'] = $featuregroups['feat'];
		}

		$clusteroptions = leafext_cluster_atts ($atts);
		return leafext_featuregroup_script($options,$clusteroptions);
	} else {
		$text = "[markerClusterGroup ";
		foreach ($atts as $key=>$item){
			$text = $text. "$key=$item ";
		}
		$text = $text. "]";
		return $text;
	}
}
add_shortcode('markerClusterGroup', 'leafext_clustergroup_function' );
