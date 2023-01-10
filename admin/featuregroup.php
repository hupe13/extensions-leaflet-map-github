<?php
/**
 * Help for Leaflet.FeatureGroup.SubGroup
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_help_featuregroup() {
	$firsttext = '
	<h2 id="leaflet.featuregroup.subgroup">Leaflet.FeatureGroup.SubGroup</h2>
	<img src="'.LEAFEXT_PLUGIN_PICTS.'clustergroup.png">
	<p>'.
	__('Group elements and dynamically add/remove from map','extensions-leaflet-map').
	'.</p>';
	$text='';

  $text=$text.'<h3>Shortcode</h3>
  <pre>
  [leaflet-map fitbounds]
  //any many
  [leaflet-marker      ...] ... [/leaflet-marker]
  [leaflet-extramarker ...] ... [/leaflet-extramarker]
  [leaflet-geojson     ...] ... [/leaflet-geojson]
  [leaflet-gpx         ...] ... [/leaflet-gpx]
  [leaflet-kml         ...] ... [/leaflet-kml]
  //one or more
  //suitable for all leaflet-elements above
  [leaflet-optiongroup option="..." values="..., ..." groups="..., ..." visible=...]
  //suitable for leaflet-geojson, leaflet-gpx, leaflet-kml.
  [leaflet-featuregroup property="<span style="color: #d63638">prop0</span>" values="<span style="color: #4f94d4">value0</span>,..." groups="..., ..." visible=...]
  </pre>
  ';
	$text=$text.'<h3>leaflet-optiongroup option</h3>'.
	__('For <code>option</code> you can use any option of the leaflet element.','extensions-leaflet-map').' '.
	__('Not every option is suitable for every element.','extensions-leaflet-map');

	$text=$text.' '.__('Meaningful options may be:','extensions-leaflet-map');

	$text=$text.'<ul style="list-style: disc;">
	<li style="margin-left: 1.5em;">'.
	'leaflet-marker: iconClass, title, iconUrl'.'<br>'.
	__("You can use iconclass to group, regardless of whether they affect the appearance of the icon or not.",'extensions-leaflet-map').' '.
	__("If not, you must specify an iconurl.",'extensions-leaflet-map')
	.'</li>
	<li style="margin-left: 1.5em;">'.
	'leaflet-extramarker: className, extraClasses, icon, iconColor, markerColor, number, prefix, shape'
	.'</li>
	<li style="margin-left: 1.5em;">'.
	'leaflet-geojson (-gpx, -kml): iconUrl, alt, className, color'.
	'<ul style="list-style: disc;">
	<li style="margin-left: 1.5em;">'.
	__("iconurl switches only markers.",'extensions-leaflet-map').
	'</li>'.
	'<li style="margin-left: 1.5em;">'.
	__('Every marker with an icon has the option alt="Marker".','extensions-leaflet-map')
	.'</li>
	<li style="margin-left: 1.5em;">'.
	__('You can use className for grouping, it is not used by leaflet-geojson but passed through.','extensions-leaflet-map')
	.'</li>
	</ul>'
	.'</li>
	</ul>';


	$text=$text.'<h3>leaflet-featuregroup property</h3>';
	$text=$text.'<p>'.'<code>property</code> '.
	__('is the name of a property in properties of an element in the FeatureCollection','extensions-leaflet-map').
	', e.g. <code><span style="color: #d63638">prop0</span></code> in <pre>{
  "type": "FeatureCollection",
    "features": [{
      "type": "Feature",
        "geometry": {
          "type": "Point",
          "coordinates": [102.0, 0.5]
        },
        "properties": {
          "<span style="color: #d63638">prop0</span>": "<span style="color: #4f94d4">value0</span>"
        }
      },...</pre>
		 </p>';



	$text=$text.'<h3>values '.__('and','extensions-leaflet-map').' groups</h3><p>';
	$text=$text.'<ul style="list-style: disc;">
	<li style="margin-left: 1.5em;"><code>values</code> - '.sprintf( __('comma separated strings to distinguish the elements, e.g. the exact string or an unique substring in %s or the exact string (e.g.%s) in %s',
	'extensions-leaflet-map'),"<code>option</code>",
	'<code><span style="color: #4f94d4">value0</span></code>',
	"<code>property</code>").'</li>

	<li style="margin-left: 1.5em;">'.sprintf(__('Note that the %s are case sensitive, but the options in the shortcode are not.','extensions-leaflet-map'),
	'<code>values</code>')
	.'</li>

		<li style="margin-left: 1.5em;"><code>groups</code> - '.
		__('comma separated labels appear in the selection menu','extensions-leaflet-map').'</li>

		<li style="margin-left: 1.5em;">'.sprintf(
			__('The number of %s and %s must match.','extensions-leaflet-map'),"<code>values</code>","<code>groups</code>").'
			</li>
<li style="margin-left: 1.5em;">'.
			sprintf(__('You can specify multiple %s and %s. If the %s are called the same, the elements will be placed in the same group despite having different options resp. properties.','extensions-leaflet-map'),
			'<code>leaflet-optionsgroup</code>',
			'<code>leaflet-featuregroup</code>',
			'<code>groups</code>').'<br>'.
			__('However, make sure that it is unique. Otherwise unwanted, but sometimes interesting effects can occur. Test it with your application!','extensions-leaflet-map')

			.'</li>

			<li style="margin-left: 1.5em;"><code>visible</code> ('.__('optional','extensions-leaflet-map').') - '.
			sprintf(__('initial visibility of a group, default: %s. Either %s (valid for all groups) or a comma-separated list of %s and %s, where the number must match those of %s.',
			'extensions-leaflet-map'),
			'1',
			'0',
			'0',
			'1',
			'<code>groups</code>',
			).'</li>

			</ul></p>';


	$text=$text.'<h3>Groups unknown '.__('and','extensions-leaflet-map').' others</h3><p>'.
	sprintf(
		__('If %s contains %s and %s, then the elements for which the option / property specified in %s does not apply, are placed in the %s group. Elements whose %s is not known are placed in the %s group. See also the developer console.','extensions-leaflet-map'),
		"<code>groups</code>",
		'"unknown"',
		'"others"',
		'<code>option / property</code>',
		"others",
		'<code>option / property</code>',
		"unknown"
		).
		'</p><h3>Shortcode groups unknown '.__('and','extensions-leaflet-map').' others</h3>
	<pre><code>[leaflet-optiongroup option="..." values="...,...,others,unknown" groups="...,...,Other elements,Unknown elements"]
[leaflet-featuregroup property="..." values="...,...,others,unknown" groups="...,...,Other elements,Unknown elements"]</code></pre>';

	$othertext = '<h3>'.__('Notes','extensions-leaflet-map').'</h3>'.'<p>'.sprintf ( __('The parameter and settings for %s are valid too.','extensions-leaflet-map'),
			'<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=markercluster">Leaflet.markercluster</a>').'</p>'.
			'<p>'.sprintf(__('The shortcode %s is a special case of %s respectively %s.','extensions-leaflet-map'),
			'<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=markerclustergroup"><code>markerClusterGroup</code></a>',
			'<code>leaflet-optiongroup</code>',
			'<code>leaflet-featuregroup</code>')
			.'</p>'.'<pre><code>[leaflet-optiongroup option="..." values="..., ..." groups="..., ..." visible=...]
[leaflet-featuregroup property="<span style="color: #d63638">prop0</span>" values="<span style="color: #4f94d4">value0</span>,..." groups="..., ..." visible=...]</code></pre>';
	if (is_singular() || is_archive() ) {
		return $text;
	} else {
		echo $firsttext.$text.$othertext;
	}
}
leafext_help_featuregroup();

// .
// ' <a href="https://github.com/bozdoz/wp-plugin-leaflet-map#leaflet-marker-options">leaflet-marker</a>,'.
// ' <a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=extramarker"">leaflet-extramarker</a>,'.
// ' <a href="https://github.com/bozdoz/wp-plugin-leaflet-map#leaflet-geojson-options">leaflet-geojson, leaflet-gpx, leaflet-kml</a>. '
// sprintf(__('%s as a %s may also be of interest.','extensions-leaflet-map'),
// 'className',
// '<a href="https://leafletjs.com/reference.html#path">Path Option</a>');
