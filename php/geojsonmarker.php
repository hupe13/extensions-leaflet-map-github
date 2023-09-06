<?php
/**
* Functions for geojsonmarker
* extensions-leaflet-map
*/

// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [geojsonmarker]
function leafext_geojsonmarker_script($propertyoptions,$extramarkericon,$clusteroptions,$featuregroupoptions){
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	function leafext_geojsonmarker_extramarker_js(markerColor) {
		var markericon = L.ExtraMarkers.icon({<?php echo $extramarkericon;?>,markerColor:markerColor});
		return markericon;
	}
	//
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		let property = <?php echo json_encode($propertyoptions['property']);?>;
		// console.log("property: "+property);
		let iconprops = <?php echo json_encode($propertyoptions['iconprops']);?>;
		// console.log(iconprops);
		let icondefault = <?php echo json_encode($propertyoptions['icondefault']);?>;
		// console.log(icondefault);
		let auto = <?php echo json_encode($propertyoptions['auto']);?>;
		// console.log(auto);
		//
		let groups  = <?php echo json_encode($featuregroupoptions['groups']);?>;
		// console.log(groups);
		let visible = <?php echo json_encode($featuregroupoptions['visible']);?>;
		//
		let clmarkers = L.markerClusterGroup({
			<?php echo leafext_java_params ($clusteroptions);?>
		});
		let extramarkericon = <?php echo json_encode($extramarkericon);?>;
		leafext_geojsonmarker_js(property,iconprops,icondefault,auto,groups,visible,clmarkers,extramarkericon);
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_geojsonmarker_function($atts,$content,$shortcode) {
	// property - required
	// values - required for leaflet-featuregroup and markers with iconURL, otherwise optional, if not specified collect
	// groups - required for leaflet-featuregroup, otherwise no grouping
	// visible - only if groups specified see leaflet-featuregroup
	// iconprops - required for iconUrl, if not specified like values, if specified must match values
	// icondefault - blue

	// auto: values werden gesammelt, farben erzeugt, alles wird gruppiert und angezeigt

	$text = leafext_should_interpret_shortcode($shortcode,$atts);
	if ( $text != "" ) {
		return $text;
	} else {
		$propertyoptions = shortcode_atts(
			array(
				'property' => "",
				'values' => '',
				'iconprops' => '',
				'icondefault' => "blue",
				'auto' => 0,
			), leafext_clear_params($atts));
			//

			// property - required
			if ( $propertyoptions['property'] == '' ) {
				$text = "['.$shortcode.' ";
				if (is_array($atts)){
					foreach ($atts as $key=>$item){
						$text = $text. "$key=$item ";
					}
				}
				$text = $text." - NO PROPERTY ";
				$text = $text. "]";
				return $text;
			}

			if ( $propertyoptions['values'] != "" ) {
				$prop_values = array_map('trim', explode( ',', $propertyoptions['values'] ));
			} else {
				$prop_values = array();
			}

			if ( $propertyoptions['iconprops'] == "" ) {
				$propertyoptions['iconprops'] = array();
			} else {
				$iconprops = array_map('trim', explode( ',', $propertyoptions['iconprops'] ));
				if ( count($prop_values) == count($iconprops) ) {
					$propertyoptions['iconprops'] = array_combine($prop_values,$iconprops);
					// } else if (($key = array_search('others', $prop_values)) !== false) {
					// 	unset($prop_values[$key]);
					// 	$propertyoptions['iconprops'] = array_combine($prop_values,$iconprops);
				} else {
					$text = "['.$shortcode.' ";
					if (is_array($atts)){
						foreach ($atts as $key=>$item){
							$text = $text. "$key=$item ";
						}
					}
					$text = $text." - property values and iconprops do not match. ";
					$text = $text. "]";
					return $text;
				}
			}
			leafext_enqueue_leafext("geojsonmarker","leaflet_subgroup");
			//
			leafext_enqueue_extramarker ();
			$extramarker = leafext_case(array_keys(leafext_extramarker_defaults()),leafext_clear_params($atts));
			$extramarkeroptions = shortcode_atts(leafext_extramarker_defaults(), $extramarker);
			$extramarkericon = leafext_extramarkers_params ($extramarkeroptions).'tooltipAnchor:[12,-24]';
			//
			leafext_enqueue_markercluster ();
			$clusteroptions = leafext_cluster_atts ($atts);
			//
			$groupingoptions = shortcode_atts(
				array(
					//'property' => '',
					'values' => '',
					'groups' => '',
					'visible' => false,
				), leafext_clear_params($atts)
			);

			if ( substr_count($groupingoptions['values'],',') != substr_count($groupingoptions['groups'],',')
			&& substr_count($groupingoptions['groups'],',') != 0 ) {
				$text = "['.$shortcode.' ";
				if (is_array($atts)){
					foreach ($atts as $key=>$item){
						$text = $text. "$key=$item ";
					}
				}
				$text = $text." - values and groups do not match. ";
				$text = $text. "]";
				return $text;
			}

			if ($groupingoptions['groups'] != "") {

				$cl_values= array_map('trim', explode( ',', $groupingoptions['values'] ));
				$cl_groups = array_map('trim', explode( ',', $groupingoptions['groups'] ));

				if ($groupingoptions['visible'] === false) {
					$groupingoptions['visible'] = array_fill(0, count($cl_values), '1');
					$cl_on = array_fill(0, count($cl_values), '1');
				} else {
					$cl_on = array_map('trim', explode( ',', $groupingoptions['visible'] ));
					if (count($cl_on) == 1) {
						$cl_on = array_fill(0, count($cl_values), '0');
					} else {
						if ( count($cl_values) != count($cl_on) ) {
							$text = "['.$shortcode.' ";
							foreach ($atts as $key=>$item){
								$text = $text. "$key=$item ";
							}
							$text = $text." - groups and visible do not match. ";
							$text = $text. "]";
							return $text;
						}
					}
				}
				if (!in_array('others',$cl_groups)) {
					$cl_values[] = 'others';
					$cl_groups[] = 'others';
					$cl_on['others'] = '1';
				}

				$featuregroupoptions = array(
					//'property' => sanitize_text_field($groupingoptions['property']),
					'values' => sanitize_text_field($groupingoptions['values']),
					'groups'  => array_combine($cl_values, $cl_groups),
					'visible' => array_combine($cl_values, $cl_on),
				);
				leafext_enqueue_clustergroup ();
			} else {
				$featuregroupoptions = array();
			}
			if ($propertyoptions['auto']) {
				leafext_enqueue_clustergroup ();
			}
			//
			return leafext_geojsonmarker_script($propertyoptions,$extramarkericon,$clusteroptions,$featuregroupoptions);
		}
	}
	add_shortcode('geojsonmarker', 'leafext_geojsonmarker_function');
