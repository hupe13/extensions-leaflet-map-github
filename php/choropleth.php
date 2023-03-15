<?php
/**
* Functions for choropleth shortcode
* extensions-leaflet-map
*/

/*
*	Doku choropleth deutsch https://doc.arcgis.com/de/insights/latest/create/choropleth-maps.htm
* https://gisgeography.com/choropleth-maps-data-classification/
*/

// mode: q for quantile, e for equidistant, k for k-means
// quantile maps try to arrange groups so they have the same quantity.
// equidistant: divide the classes into equal groups.
// k-means: each standard deviation becomes a class (?)

// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Parameter and Values
function leafext_choropleth_params() {
	$params = array(
		array('valueproperty', __('which property in the features to use',"extensions-leaflet-map"), "", 'property'),
		array('scale', __('a comma separated list of colors for scale - include as many as you like',"extensions-leaflet-map"), "white, red", '"white, red, blue"'),
		array('fillopacity', __('opacity of the colors in scale',"extensions-leaflet-map"), "0.8", "0.8"),
		array('steps', __('number of breaks or steps in range',"extensions-leaflet-map"), "5", "5"),
		array('mode', __('q for quantile, e for equidistant, k for k-means',"extensions-leaflet-map"), "q", "q"),
		array('legend', __('show legend',"extensions-leaflet-map"), true, "!legend"),
		array('hover', __('get a tooltip on mouse over',"extensions-leaflet-map"), true, "!hover"),
	);
	return $params;
}

//Shortcode: [choropleth]
function leafext_choropleth_script($atts,$content) {
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var att_valueProperty = <?php echo json_encode($atts['valueproperty']);?>;
		var att_scale = <?php echo json_encode($atts['scale']);?>.split(",");
		var att_steps = <?php echo json_encode($atts['steps']);?>;
		var att_mode = <?php echo json_encode($atts['mode']);?>;
		var att_popup = <?php echo json_encode($content);?>;
		var att_legend = <?php echo json_encode((bool)$atts['legend']);?>;
		var att_hover = <?php echo json_encode((bool)$atts['hover']);?>;
		var att_fillOpacity = <?php echo json_encode($atts['fillopacity']);?>;
		console.log(att_valueProperty,att_scale,att_steps,att_mode,att_legend,att_hover,att_fillOpacity);
		console.log(att_popup);
		map.eachLayer(function(layer) {
		  //console.log(layer.options.type);
		  if (layer.options.type == "json" ) {
		    layer.on("ready", function (layer){
		      // console.log(layer.target.json);
		      map.removeLayer(layer);
		      choropleth = L.choropleth(layer.sourceTarget.json, {
		        valueProperty: att_valueProperty,
		        scale: att_scale,
		        steps: att_steps,
		        mode: att_mode,
		        style: {
		          color: "#fff",
		          weight: 2,
		          fillOpacity: att_fillOpacity
		        },
		        onEachFeature: function (feature, layer) {
		          let this_popup = "";
		          for (var j = 0, len = att_popup.length; j < len; j++) {
		            //console.log(att_popup[j]);
		            if (att_popup[j].indexOf("{") !== -1) { // true; found
		              let property = att_popup[j].replace(/{|}/g, '');
		              //console.log(property);
		              this_popup = this_popup + feature.properties[property];
		            } else {
		              this_popup = this_popup + att_popup[j];
		            }
		          }
		          layer.bindPopup(this_popup);

		          layer.on("mouseover", function (e) {
		            e.target.setStyle({
		              weight: 4,
		              color: "#666",
		            });
		            e.target.bringToFront();
		          }),
		          layer.on("mouseout", function (e) {
		            e.target.setStyle({
		              weight: 2,
		              color: "#fff",
		            });
		          }),

		          layer.on("mouseover", function (e) {
		            if (att_hover) {
		              if ( layer.getPopup() ) {
		                //console.log("popup defined");
		                if (layer.getPopup().isOpen()) {
		                  //console.log("mouseover open "+layer.getPopup().isOpen());
		                  layer.unbindTooltip();
		                } else {
		                  //console.log("need tooltip");
		                  var content = layer.getPopup().getContent();
		                  layer.bindTooltip(content);
		                }
		              }
		            }
		          }),

		          layer.on("mousemove", function (e) {
		            if (att_hover) {
		              if ( layer.getPopup() ) {
		                if (layer.getPopup().isOpen()) {
		                  //console.log("mousemove open "+layer.getPopup().isOpen());
		                  layer.unbindTooltip();
		                } else {
		                  //console.log("mousemove close "+layer.getPopup().isOpen());
		                  map.closePopup();
		                  layer.openTooltip(e.latlng);
		                }
		              }
		            }
		          }),

		          layer.on("click", function (e) {
		            if (att_hover) {
		              if ( layer.getPopup() ) {
		                if (layer.getPopup().isOpen())
		                layer.unbindTooltip();
		              }
		            }
		          })

		        }
		      }); // choropleth
		      choropleth.addTo(map);

		      // Add legend (don't forget to add the CSS from index.html)
		      if (att_legend) {
		        var legend = L.control({ position: 'bottomright' });
		        legend.onAdd = function (map) {
		          var div = L.DomUtil.create('div', 'info legend');
		          var limits = choropleth.options.limits;
		          var colors = choropleth.options.colors;
		          var labels = [];
		          // Add min & max
		          div.innerHTML = '<div class="labels"><div class="min">' + limits[0] + '</div> \
		          <div class="max">' + limits[limits.length - 1] + '</div></div>';
		          limits.forEach(function (limit, index) {
		            labels.push('<li style="background-color: ' + colors[index] + '; opacity: '+ att_fillOpacity +';"></li>');
		          })
		          div.innerHTML += '<ul>' + labels.join('') + '</ul>';
		          return div;
		        }
		        legend.addTo(map);
		      }

		    }); // on ready
		  }; // if
		}); // map.eachLayer

	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_choropleth_function($atts,$content,$shortcode) {
	$text = leafext_should_interpret_shortcode($shortcode,$atts);
	if ( $text != "" ) {
		return $text;
	} else {
		leafext_enqueue_choropleth ();
		$params = leafext_choropleth_params();
		$defaults = array();
		foreach($params as $param) {
			$defaults[$param[0]] = $param[2];
		}
		//var_dump($params,$defaults);
		$options = shortcode_atts($defaults,leafext_clear_params($atts));
		$options['scale'] = str_replace(' ', '', $options['scale']);
		if ($content == "") $content = $options['valueproperty'].": {".$options['valueproperty']."}";
		$content = str_replace('{', "+{", $content);
		$content = str_replace('}', "}+", $content);
		$content = preg_split('/\+/',$content);
		//var_dump($atts); wp_die("test");
		return leafext_choropleth_script($options,$content);
	}
}
add_shortcode('choropleth', 'leafext_choropleth_function');
