<?php
/**
 * Help for Leaflet.FeatureGroup.SubGroup
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_help_featuregroup() {
	$text='';
	$admintext = '
	<h2 id="leaflet.featuregroup.subgroup">Leaflet.FeatureGroup.SubGroup</h2>
	<img src="'.LEAFEXT_PLUGIN_PICTS.'clustergroup.png">
	<p>'.
	__('Group elements and dynamically add/remove from map','extensions-leaflet-map').
	'.</p>';

	$helptext='<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p><a href="https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup">Leaflet.FeatureGroup.SubGroup</a> '.
__('is a simple plugin to create Feature Groups that add their child layers into a parent group. Typical usage is to switch them through L.Control.Layers to dynamically add/remove groups of markers from Leaflet.markercluster.','extensions-leaflet-map').'</p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph -->
<p><em>Extensions for Leaflet Map</em> '.
sprintf(__('uses this Leaflet plugin to group leaflet elements like %s and others by options and properties. There are two shortcodes: %s and %s.
Use %s to group elements by options and %s to group elements by properties.','extensions-leaflet-map'),
'<code>leaflet-marker</code>, <code>leaflet-geojson</code>',
'<code>leaflet-optiongroup</code>',
'<code>leaflet-featuregroup</code>',
'<code>leaflet-optiongroup</code>',
'<code>leaflet-featuregroup</code>')
.'</p>
<!-- /wp:paragraph -->
';

  $text=$text.'<h3>Shortcode</h3>
  <pre><code>&#091;leaflet-map fitbounds]
//any many
&#091;leaflet-marker      ...] ... &#091;/leaflet-marker]
&#091;leaflet-extramarker ...] ... &#091;/leaflet-extramarker]
&#091;leaflet-polygon     ...] ... &#091;/leaflet-polygon]
&#091;leaflet-circle      ...] ... &#091;/leaflet-circle]
&#091;leaflet-line        ...] ... &#091;/leaflet-line]
&#091;leaflet-geojson     ...] ... &#091;/leaflet-geojson]
&#091;leaflet-gpx         ...] ... &#091;/leaflet-gpx]
&#091;leaflet-kml         ...] ... &#091;/leaflet-kml]
//one or more
//suitable for all leaflet-elements above
&#091;leaflet-optiongroup option="..." values="..., ..." groups="..., ..." substr visible=...]
//suitable for leaflet-geojson, leaflet-gpx, leaflet-kml.
&#091;leaflet-featuregroup property="<span style="color: #d63638">prop0</span>" values="<span style="color: #4f94d4">value0</span>,..." groups="..., ..." <span style="color: #d63638">!</span>substr visible=...]</code></pre>';
	$text=$text.'<h3>leaflet-optiongroup option</h3>'.
	sprintf(__('Each of the above leaflet elements has options. These are from the shortcode %s and are fixed.','extensions-leaflet-map'),'<code>leaflet-...</code>').' ';

	sprintf(__('For %s you can use any option of the leaflet element.','extensions-leaflet-map'),'<code>option</code>').' '.
	__('Not every option is suitable for every element.','extensions-leaflet-map');

	$text=$text.' '.__('Meaningful options may be:','extensions-leaflet-map');

	$text=$text.'<ul>
	<li>'.
	'leaflet-marker: iconClass, title, iconUrl'.'<br>'.
	__("You can use iconclass to group, regardless of whether they affect the appearance of the icon or not.",'extensions-leaflet-map')
	.'</li>
	<li>'.
	'leaflet-extramarker: className, extraClasses, icon, iconColor, markerColor, number, prefix, shape, title'
	.'</li>
	<li>'.
	'leaflet-polygon (-circle, -line):  color, className'
	.'</li>
	<li>'.
	'leaflet-geojson (-gpx, -kml): iconUrl, alt, className, color'.
	'<ul>
	<li>'.
	sprintf(__("%s switches only the markers.",'extensions-leaflet-map'),'iconurl').
	'</li>'.
	'<li>'.
	sprintf(__('Every marker with an icon has the option %s as default.','extensions-leaflet-map'),'<code>alt="Marker"</code>')
	.'</li>
	<li>'.
	sprintf(__('You can use %s for grouping, it is not used by leaflet-geojson but passed through.','extensions-leaflet-map'),'className')
	.'</li>
	</ul>'.
	'</li>'.
	'<li>'.sprintf(__('See %sLeaflet Map Github page%s for more or less useful and possible options.',"extensions-leaflet-map"),
  '<a href="https://github.com/bozdoz/wp-plugin-leaflet-map#leaflet-marker">','</a>').'</li>'.
	'<li>'.__('The options are case sensitive.','extensions-leaflet-map')
	.'</li>
	</ul>';

	$text=$text.'<h3>leaflet-featuregroup property</h3>';
	$text=$text.'<p>'.__('In a geojson file there are features and properties. They are different for each application.','extensions-leaflet-map').' ';
	$text=$text.sprintf(__("gpx and kml are similar to geojson, so you can also use %s there. But it's not that easy to find relevant features.",
	'extensions-leaflet-map'),
	'<code>leaflet-featuregroup</code>').'</p>';

	$text=$text.'<p>'.
	sprintf(__('A %s has %s, each of them has a %s and %s. %s is the label of a %s in %s, e.g.',
	'extensions-leaflet-map'),
	'"FeatureCollection"',
	'"features"',
	'"geometry"',
	'"properties"',
	'<code>property</code>',
	'property',
	'"properties"').
	'<code><span style="color: #d63638">prop0</span></code> in <pre>{
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

	$text=$text.'<p>'.sprintf(__('%s is case sensitive.','extensions-leaflet-map'),
	'<code>property</code>').'</p>';

	$text=$text.'<h3>values '.__('and','extensions-leaflet-map').' groups</h3><p>';
	$text=$text.'<ul>
	<li><code>values</code> - '.sprintf( __('comma separated strings to distinguish the elements, e.g. the exact string or an unique substring in %s (e.g.%s)',
	'extensions-leaflet-map'),
	"option / property",
	'<code><span style="color: #4f94d4">value0</span></code>'
	).'</li>

	<li>'.sprintf(__('The %s are case sensitive.','extensions-leaflet-map'),
	'<code>values</code>')
	.'</li>

	<li><code>substr</code> ('.__('optional','extensions-leaflet-map').') - '.
	sprintf(__('search substring %s or the exact string %s in %s.','extensions-leaflet-map'),
	"(<code>substr</code>)",
	'(<code><span style="color: #d63638">!</span>substr</code>)',
	"<code>values</code>"
	).' '.
	sprintf(
		__('The default is %s for %s and %s for %s.','extensions-leaflet-map'),
		"<i>true</i>",
		"<code>leaflet-optiongroup</code>",
		"<i>false</i>",
		"<code>leaflet-featuregroup</code>").'
		</li>

		<li><code>groups</code> - '.
		__('comma separated labels appear in the selection menu','extensions-leaflet-map').'.</li>

		<li>'.sprintf(
			__('The number of %s and %s must match.','extensions-leaflet-map'),"<code>values</code>","<code>groups</code>").'
			</li>
			<li>'.
			sprintf(__('You can specify multiple %s and %s. If the %s are called the same, the elements will be placed in the same group despite having different options resp. properties.','extensions-leaflet-map'),
			'<code>leaflet-optiongroup</code>',
			'<code>leaflet-featuregroup</code>',
			'<code>groups</code>').'<br>'.
			__('However, make sure that they are unique. Otherwise unwanted, but sometimes interesting effects can occur. Test it with your application!','extensions-leaflet-map')
			.'</li>

			<li><code>visible</code> ('.__('optional','extensions-leaflet-map').') - '.
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
	<pre><code>&#091;leaflet-optiongroup option="..." values="...,...,others,unknown" groups="...,...,Other elements,Unknown elements"]
&#091;leaflet-featuregroup property="..." values="...,...,others,unknown" groups="...,...,Other elements,Unknown elements"]</code></pre>';

if (is_singular() || is_archive() ) {
	$clusterref = get_site_url().'/doku/cluster/';
} else {
	$clusterref = '?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=markercluster';
}

$text = $text.'<h3>'.__('Notes','extensions-leaflet-map').'</h3>'.'<p>'.
sprintf ( __('When markers are grouped, %s is automatically active and the parameter and settings for %s are valid too.','extensions-leaflet-map'),
'Leaflet.markercluster',
'<a href="'.$clusterref.'"><code>cluster</code></a>').'</p>';

if (is_singular() || is_archive() ) {
	//$text = $text;
} else {
	$text = $text.'<p>'.sprintf(__('The shortcode %s is a special case of %s respectively %s.','extensions-leaflet-map'),
	'<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=markerclustergroup"><code>markerClusterGroup</code></a>',
	'<code>leaflet-optiongroup</code>',
	'<code>leaflet-featuregroup</code>')
	.'</p>';
}
$text = $text.'<pre><code>&#091;leaflet-optiongroup option="..." values="..., ..." groups="..., ..." visible=...]
&#091;leaflet-featuregroup property="<span style="color: #d63638">prop0</span>" values="<span style="color: #4f94d4">value0</span>,..." groups="..., ..." visible=...]</code></pre>';
	if (is_singular() || is_archive() ) {
		return $text;
	} else {
		echo $admintext.$helptext.$text;
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
