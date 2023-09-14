<?php
/**
* Functions for an overview map from pages / posts
* extensions-leaflet-map
* Idea and initial code from @codade
*/

// params to set
function leafext_overviewmap_settings() {
$params = array(
	array(
		'param' => 'latlngs',
		'desc' => __('for markers lat and lng, required',"extensions-leaflet-map"),
		'content' => '<ul>'.
		'<li>'.__('either a comma or space separated pair of lat and lng',"extensions-leaflet-map").'</li>'.
		'<li>'.__('or',"extensions-leaflet-map").' <code>lat=... lng=...</code> '.__('like in',"extensions-leaflet-map").' leaflet-marker'.'</li>'.
		// '<li>'.' <s>'.__('or',"extensions-leaflet-map").' <code>leaflet-gpx / leaflet-kml src=... </code> '.
		// 		__('(like shortcode without brackets)',"extensions-leaflet-map").'</s>'.'</li>'.
		'</ul>',
		'default' => 'overview-latlng',
		'values' => '',
	),
	array(
		'param' => 'icons',
		'desc' => sprintf(__('for the marker icon, optional.%s Default is taken from the overviewmap shortcode or it is the blue marker icon.',
		"extensions-leaflet-map"),'<br>'),
		'content' => '<ul>'.
		'<li>'.__('either a icon filename',"extensions-leaflet-map").' <code>filename.ext</code></li>'.
		'<li>'.__('or',"extensions-leaflet-map").' <code>leaflet-marker iconurl=... option=... ...</code> '.
		__('(like shortcode without brackets)',"extensions-leaflet-map").'</li>'.
		'<li>'.__('or',"extensions-leaflet-map").' <code>leaflet-extramarker option=... ...</code> '.
		__('(like shortcode without brackets)',"extensions-leaflet-map").'</li>'.
		'</ul>',
		'default' => 'overview-icon',
		'values' => '',
	),
	// array(
	// 	'param' => '',
	// 	'desc' => __('',"extensions-leaflet-map"),
	//  'content' => '',
	// 	'default' => '',
	// 	'values' => __('',"extensions-leaflet-map"),
	// ),
);
return $params;
}

function leafext_overviewmap_params() {
$params = array(
	array(
		'param' => 'show_thumbnails',
		'desc' => __('Show page / post featured image',"extensions-leaflet-map"),
		'content' => __('',"extensions-leaflet-map"),
		'default' => false,
		'values' => 'true / false',
	),
	array(
		'param' => 'show_category',
		'desc' => __('Show a link to category page',"extensions-leaflet-map"),
		'content' => __('',"extensions-leaflet-map"),
		'default' => false,
		'values' => 'true / false',
	),
	array(
		'param' => 'category',
		'desc' => __('Select only pages / posts from these categories',"extensions-leaflet-map"),
		'content' => __('',"extensions-leaflet-map"),
		'default' => '',
		'values' => __('a comma separated list of category names, slugs or IDs',"extensions-leaflet-map"),
	),
	array(
		'param' => 'debug',
		'desc' => __('Creates an overview table of posts / pages instead of markers to see any mistakes',"extensions-leaflet-map"),
		'content' => '',
		'default' => false,
		'values' => 'true / false',
	),
);
return $params;
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
$extramarker_options = array();
foreach (leafext_extramarker_params() as $param) {
	if ($param['param'] != 'lat' && $param['param'] != 'lng') {
		$extramarker_options[] = $param['param'];
	}
}
return $extramarker_options;
}

function leafext_overview_wpdb_query($latlngs,$category="") {
global $wpdb;
$querystr = "
SELECT DISTINCT wposts.*
FROM $wpdb->posts wposts
LEFT JOIN $wpdb->postmeta wpostmeta ON wposts.ID = wpostmeta.post_id
WHERE wpostmeta.meta_key = '".$latlngs."'
AND wposts.post_status = 'publish'
AND (wposts.post_type = 'post' OR wposts.post_type = 'page')
";
$pageposts = $wpdb->get_results($querystr, OBJECT);
//var_dump($pageposts);
//var_dump($category);
$catposts = array();
if ($pageposts) {
	foreach ($pageposts as $post) {
		if (in_category( $category, $post->ID)  !== false) {
			$catposts[] = $post;
		}
	}
}
//var_dump($catposts);
if (!empty($catposts)) return $catposts;
//var_dump($pageposts);
return $pageposts;
}

function leafext_check_duplicates_meta($postid,$meta) {
$fields = get_post_meta($postid, $meta, false);
// $fields:
// An array of values if $single is false.
// The value of the meta field if $single is true.
if (count($fields) > 1) {
	echo '<script>console.log("'.'Multiple keys '.$postid.' '.$meta.'");</script>';
	return '*';
}
return '';
}

function leafext_get_overview_data($post,$overview_options) {
$leaflet_error = "";
// setup data for specific post
setup_postdata($post);
$overview_data=array();
//
// For the Link
$overview_data['permalink'] = get_permalink($post->ID);
$overview_data['title'] = get_the_title($post->ID);
//
// check if post has a thumnail
$overview_data['thumbnail'] = "";
if ($overview_options['show_thumbnails'] == true) {
	if ( has_post_thumbnail( $post->ID ) ){
		$overview_data['thumbnail'] = get_the_post_thumbnail( $post->ID, array(75, 75) , array('loading' => false));
	}
}
//
// categories
$overview_data['categories'] = "";
if ($overview_options['show_category'] == true) {
	$overview_data['categories'] = get_the_category_list( ', ' ,'', $post->ID );
}
//
// the marker latlng
$overview_data['latlng'] = ''; // wegen der Reihenfolge in der Tabelle
$leaflet_latlng = get_post_meta($post->ID, $overview_options["latlngs"], true);
$overview_data['latlng-orig'] = $leaflet_latlng;
$overview_data['error_latlng'] = '';
$overview_data['multiple_latlngs'] = leafext_check_duplicates_meta($post->ID, $overview_options["latlngs"]);

// var_dump($leaflet_latlng);
$leaflet_latlng = preg_replace('/\s+/',' ',$leaflet_latlng); // doppelte Leerzeichen entfernen
$latlng=explode(' ',$leaflet_latlng);
if (count($latlng) != 2) {
	$latlng=explode(',',$leaflet_latlng);
}
if (count($latlng) == 2) {
	if (strpos($leaflet_latlng,'=') !== false) {
		$latlng_atts=shortcode_parse_atts($leaflet_latlng);
		//var_dump($latlng_atts);
		if ( isset($latlng_atts['lat']) && isset($latlng_atts['lng']) ) {
			$leaflet_latlng = 'lat='.trim($latlng_atts['lat'],', ').' lng='.trim($latlng_atts['lng'],', ');
		} else {
			$leaflet_latlng = '*';
		}
	} else {
		$leaflet_latlng = 'lat='.trim($latlng[0],', ').' lng='.trim($latlng[1],', ');
	}
} else {
	$leaflet_latlng = '*';
}
$leaflet_latlng = str_replace(',','.',$leaflet_latlng);
if (!preg_match('/^[ 0123456789\.latlng=]+$/', $leaflet_latlng)) {
	echo '<script>console.log("'.'Error detecting lanlngs '.$post->ID.': '.$overview_options["latlngs"].' = '.$leaflet_latlng.'");</script>';
	$leaflet_latlng = '*';
}
$overview_data['latlng'] = $leaflet_latlng;
if ($overview_data['latlng'] == $overview_data['latlng-orig']) $overview_data['latlng-orig'] = '';
//
// the marker icon
$overview_data['icon'] = trim(get_post_meta($post->ID, $overview_options["icons"], true));
$overview_data['multiple_icons'] = leafext_check_duplicates_meta($post->ID, $overview_options["icons"]);
//
wp_reset_postdata();
return $overview_data;
}

function leafext_ovm_setup_icon ($overview_data,$atts) {
$markeroptions = '';
$leaflet_marker_cmd = 'leaflet-marker';
$iconerror = '';
$pathinfo = array();
$iconoptions = leafext_marker_options();
if ($overview_data['icon'] != "") {
	// var_dump($overview_data['icon']);
	$params = array();
	if (strpos($overview_data['icon'],'leaflet-extramarker') === 0) { //beginnt mit leaflet-extramarker
		leafext_enqueue_extramarker ();
		$leaflet_marker_cmd = 'leaflet-extramarker';
		$iconoptions = leafext_extramarker_options();
	} else if (strpos($overview_data['icon'],'leaflet-marker') === 0) {
		//
	} else if (strpos($overview_data['icon'],'=') === false) {
		$overview_data['icon'] = sanitize_file_name($overview_data['icon']);
		$pathinfo = pathinfo($overview_data['icon']);
		if (!(array_key_exists('filename', $pathinfo) && array_key_exists('extension', $pathinfo))) {
			echo '<script>console.log("'.'Error - no valid filename: '.$overview_options["icons"].' - '.$overview_data['icon'].'");</script>';
			$iconerror = '*';
		}
	} else {
		echo '<script>console.log("'.'Error - please check data: '.$overview_options["icons"].' - '.$overview_data['icon'].'");</script>';
		$iconerror = '*';
	}
	// atts from overviewmap shortcode
	foreach ($atts as $key => $value) {
		if ( in_array(strtolower($key), array_map('strtolower', $iconoptions)) ) {
			$params[ strtolower($key) ] = $value;
		}
	}
	// atts from custom field
	$marker_atts=shortcode_parse_atts($overview_data['icon']);
	foreach ( $marker_atts as $key => $value) {
		// var_dump($key,$value);
		if ( in_array(strtolower($key), array_map('strtolower', $iconoptions)) ) {
			$params[ strtolower($key) ] = $value;
		}
	}

	if (count($pathinfo) > 0) {
		if (array_key_exists('iconurl',$params)) {
			$params['iconurl'] = dirname($params['iconurl']) . '/' . $pathinfo['filename'] .'.'. $pathinfo['extension'];
		} else {
			$leaflet_error = '* ERROR: icon problem - no iconurl found: '.$overview_data['icon'].' * ';
		}
	}

	// var_dump($params);
	$markeroptions = implode(' ', array_map (
		function ($a, $b) { return "$a=\"$b\""; },
		array_keys($params),array_values($params))
	);
}
//
return array($leaflet_marker_cmd, $markeroptions,$iconerror);
}

function leafext_ovm_setup_leafletmarker ($overview_data,$atts) {

	// check if post has a thumnail
	if ($overview_data['thumbnail'] != "") {
		$overview_data['thumbnail'] = '<div class="leafext-overview-popup-img">'.$overview_data['thumbnail'].'</div>';
	}
	//
	// categories
	if ($overview_data['categories'] != "") {
		$overview_data['categories'] = '<div class="leafext-overview-popup-cat">'.$overview_data['categories'].'</div>';
	}
	//
	// Link
	$link_to_page = '<a href="'.$overview_data['permalink'].'" target="_blank" rel="noopener">'.'<strong>'.$overview_data['title'].'</strong>'.'</a>';
	//
	// the marker icon
	list($leaflet_marker_cmd, $markeroptions, $overview_data['iconerror']) = leafext_ovm_setup_icon($overview_data,$atts);
	//
	// latlng
	if ($overview_data['latlng'] == '*') {
		$leaflet_marker_cmd = '**'.$leaflet_marker_cmd;
	}
	//
	if ($overview_data['thumbnail'] == '' || $overview_data['categories'] == '') {
		$popupcss = 'leafext-overview-popup-one';
	} else {
		$popupcss = "leafext-overview-popup";
	}
	$leaflet_marker_code = '['.$leaflet_marker_cmd.' '.$overview_data['latlng'].' '.$markeroptions.']'.
	'<div class="'.$popupcss.'">'.
	'<div class="leafext-overview-popup-header">'.$link_to_page.'</div>'.
	$overview_data['thumbnail'].
	$overview_data['categories'].
	'</div>'.
	'[/'.$leaflet_marker_cmd.']';
	return $leaflet_marker_code;
}

function leafext_overview_debug($overview_data,$post) {
// Link
$overview_data['permalink'] = '<a href="'.$overview_data['permalink'].'" target="_blank" rel="noopener">'.'<strong>'.$overview_data['title'].'</strong>'.'</a>';
if (current_user_can( 'edit_post', $post->ID )) {
	$overview_data['permalink'] = $overview_data['permalink'].'<p><a href="'.get_edit_post_link($post->ID).'">'.__( 'Edit' ).'</a></p>';
}
unset($overview_data['title']);
return $overview_data;
}

// Shortcode für Wegpunkte aus Posts:
function leafext_overviewmap_function( $atts,$content,$shortcode ) {
$text = leafext_should_interpret_shortcode($shortcode,$atts);
if ( $text != "" ) {
	return $text;
} else {
	leafext_enqueue_overview();
	$defaults=array();
	$params = leafext_overviewmap_settings();
	foreach($params as $param) {
		$defaults[$param['param']] = $param['default'];
	}
	$params = leafext_overviewmap_params();
	foreach($params as $param) {
		$defaults[$param['param']] = $param['default'];
	}
	$overview_options = shortcode_atts($defaults,leafext_clear_params($atts));
	// var_dump($overview_options);

	if (strpos(",",$overview_options["category"]) !== false) $overview_options["category"] = explode(',',esc_sql($overview_options["category"]));
	$pageposts = leafext_overview_wpdb_query(esc_sql($overview_options["latlngs"]),$overview_options["category"]);

	// var_dump($pageposts);
	$text = '';
	if ($pageposts) {
		$debugtable = array();
		foreach ($pageposts as $post) {
			$overview_data = leafext_get_overview_data($post,$overview_options);
			if ($overview_options['debug'] == true) {
				$debugtable[] = leafext_overview_debug($overview_data,$post);
			} else  {
				$leaflet_marker_code = leafext_ovm_setup_leafletmarker($overview_data,$atts);
				$text = $text. do_shortcode($leaflet_marker_code);
			}
		}
		if ($overview_options['debug'] == true) {
			$debugtable = array_map('array_filter', $debugtable); // entferne alle leeren Felder, also mit value == ''
			//var_dump(max($debugtable));
			$header = array();
			$newtable = array();
			foreach (max($debugtable) as $key => $value) {
				$header[$key] = '<strong>'.$key.'</strong>';
			}
			$newtable[] = $header;
			foreach ($debugtable as $entry) {
				foreach (max($debugtable) as $key => $value) {
					if (!array_key_exists($key, $entry)) {
						$entry[$key] = '';
					}
				}
				$newtable[] = $entry;
			}
			$text=$text.leafext_html_table($newtable);
		}
	} else {
		$text = "no leaflet-marker custom fields";
	}
	return $text;
}
}
add_shortcode('overviewmap', 'leafext_overviewmap_function');
