<?php
/**
 * Functions for parameter handling
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Interpretiere !parameter und parameter als false und true
function leafext_clear_params($atts) {
	if (is_array($atts)) {
		for ($i = 0; $i < count($atts); $i++) {
			if (isset($atts[$i])) {
				if ( strpos($atts[$i],"!") === false ) {
					$atts[$atts[$i]] = true;
				} else {
					$atts[substr($atts[$i],1)] = false;
				}
				unset($atts[$i]);
			}
		}
	}
	return($atts);
}

//shortcode_atts gibt nur Kleinbuchstaben zurueck, Javascript braucht aber gross und klein
//Parameter: array mit keys wie es sein soll, array mit keys in klein von shortcode_atts
function leafext_case ($params,$array) {
	foreach ($params as $param) {
		if (strtolower($param) != $param) {
			if (isset($array[strtolower($param)])) {
				$array[$param] = $array[strtolower($param)];
				unset($array[strtolower($param)]);
			}
		}
	}
	return $array;
}

//Suche bestimmten Wert in array im admin interface
function leafext_array_find($needle, $haystack) {
	foreach ($haystack as $item) {
		if ($item[0] == $needle) {
			return $item;
			break;
		}
	}
}
//Suche bestimmten Wert in array im admin interface
function leafext_array_find2($needle, $haystack) {
	foreach ($haystack as $item) {
		if ($item['param'] == $needle) {
			return $item;
			break;
		}
	}
}

//Trage php array keys und values in javascript script ein.
function leafext_java_params ($params) {
	///var_dump($params); wp_die();
	$text = "";
	foreach ($params as $k => $v) {
		//var_dump($v,gettype($v),strpos($v,"["));
		$text = $text. "$k: ";
		switch (gettype($v)) {
			case "string":
			switch ($v) {
				case "false":
				case "0": $value = "false"; break;
				case "true":
				case "1": $value = "true"; break;
				case strpos($v,"{") !== false:
				case strpos($v,"}") !== false:
				case strpos($v,"[") !== false:
				case strpos($v,"]") !== false:
				case strpos($v,"screen.width") !== false:
				case is_numeric($v):
				$value = $v; break;
				default:
				$value = '"'.$v.'"';
			}
			break;
			case "boolean":
			$value = $v ? "true" : "false"; break;
			case "integer":
			case "double":
			$value = $v; break;
			default: var_dump($k, $v, gettype($v)); wp_die("Type");
		}
		$text = $text.$value;
		$text = $text.",\n";
	}
	//var_dump($text); wp_die();
	return $text;
}

/**
 * This function replaces the keys of an associate array by those supplied in the keys array
 *
 * @param $array target associative array in which the keys are intended to be replaced
 * @param $keys associate array where search key => replace by key, for replacing respective keys
 * @return  array with replaced keys
 * from https://www.php.net/manual/de/function.array-replace.php
*/
function leafext_array_replace_keys($array, $keys) {
	foreach ($keys as $search => $replace) {
		if ( isset($array[$search])) {
			$array[$replace] = $array[$search];
			unset($array[$search]);
		}
	}
	return $array;
}

// Backend Plugin extension-leaflet-map
function leafext_backend() {
	$backend_page = isset($_GET['page']) ? $_GET['page'] : "";
	$url=$_SERVER['REQUEST_URI'];
	if (strpos($backend_page, 'extensions-leaflet-map') !== false && strpos($url, '/wp-admin/admin.php') !== false ) {
		return true;
	} else {
		return false;
	}
}

function leafext_should_interpret_shortcode($shortcode,$atts) {
	if (is_singular() || is_archive() || is_home() || is_front_page() || leafext_backend()) {
		return "";
	} else {
		$text = "['.$shortcode.' ";
		if (is_array($atts)){
			foreach ($atts as $key=>$item){
				$text = $text. "$key=$item ";
			}
		}
		$text = $text. "]";
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

//Display array as table
function leafext_html_table_alt($data = array()) {
	$rows = array();
	foreach ($data as $row) {
		$cells = array();
		foreach ($row as $cell) {
			$cells[] = "<td>{$cell}</td>";
		}
		$rows[] = "<tr>" . implode('', $cells) . "</tr>";
	}
	return "<table border='1'>" . implode('', $rows) . "</table>";
}

function leafext_html_table($data = array()) {
	$rows = array();
	$cellstyle = (is_singular() || is_archive()) ? "style='border:1px solid #195b7a;'" : '';
	foreach ($data as $row) {
		$cells = array();
		foreach ($row as $cell) {
			$cells[] = "<td ".$cellstyle.">{$cell}</td>";
		}
		$rows[] = "<tr>" . implode('', $cells) . "</tr>";
	}
	$head='<div style="width:'.((is_singular() || is_archive()) ? '100':'80').'%;">';
	$head=$head.'<figure class="wp-block-table aligncenter is-style-stripes"><table border=1>';
	return $head . implode('', $rows) . "</table></figure></div>";
}
