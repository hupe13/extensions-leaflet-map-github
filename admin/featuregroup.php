<?php
/**
 * Help for leaflet-optiongroup leaflet-featuregroup
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_help_featuregroup() {
	$text      = '';
	$admintext = '
	<h2 id="leaflet.featuregroup.subgroup">Leaflet.FeatureGroup.SubGroup</h2>
	<img src="' . LEAFEXT_PLUGIN_PICTS . 'clustergroup.png">
	<p>' .
	__( 'Group elements and dynamically add/remove from map', 'extensions-leaflet-map' ) .
	'.</p>';

	$helptext = '<!-- wp:paragraph -->
<p><em>Extensions for Leaflet Map</em> ' .
	sprintf(
		__(
			'uses %1$s to group leaflet elements like %2$s and others by options and properties. There are two shortcodes: %3$s and %4$s.
Use %5$s to group elements by options and %6$s to group elements by properties.',
			'extensions-leaflet-map'
		),
		'<a href="https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup">Leaflet.FeatureGroup.SubGroup</a>',
		'<code>leaflet-marker</code>, <code>leaflet-geojson</code>',
		'<code>leaflet-optiongroup</code>',
		'<code>leaflet-featuregroup</code>',
		'<code>leaflet-optiongroup</code>',
		'<code>leaflet-featuregroup</code>'
	)
	. '</p>
<!-- /wp:paragraph -->
';

	$text = $text . '<h3>Shortcode</h3>
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
&#091;leaflet-featuregroup property="<span style="color: #d63638">prop0</span>" values="<span style="color: #4f94d4">value0</span>,..." groups="..., ..." <span style="color: #d63638">!</span>substr visible=...]
// optional one or more
&#091;parentgroup parent=... childs=... visible=...]</code></pre>';

	if ( is_singular() || is_archive() ) {
		$clusterref = get_site_url() . '/doku/cluster/';
	} else {
		$clusterref = '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=markercluster';
	}

	$text = $text . '<h3>' . __( 'Note', 'extensions-leaflet-map' ) . '</h3>' .
	'<p>' .
	sprintf(
		__( 'When markers are grouped, %1$s is automatically active and the parameter and settings for %2$s are valid too.', 'extensions-leaflet-map' ),
		'Leaflet.markercluster',
		'<a href="' . $clusterref . '"><code>cluster</code></a>'
	) . '</p>';

	$text = $text . '<h3>leaflet-optiongroup options</h3>' .
	sprintf( __( 'Each of the above leaflet elements has options. These are from the shortcode %s and are fixed.', 'extensions-leaflet-map' ), '<code>leaflet-...</code>' ) . ' ';

	sprintf( __( 'For %s you can use any option of the leaflet element.', 'extensions-leaflet-map' ), '<code>option</code>' ) . ' ' .
	__( 'Not every option is suitable for every element.', 'extensions-leaflet-map' );

	$text = $text . ' ' . __( 'Meaningful options may be:', 'extensions-leaflet-map' );

	$text = $text . '<ul>
	<li>' .
	'leaflet-marker: iconClass, title, iconUrl' .
	'<br>' .
	__( 'You can use iconclass to group, regardless of whether they affect the appearance of the icon or not.', 'extensions-leaflet-map' )
	. '</li>
	<li>' .
	'leaflet-extramarker: className, extraClasses, icon, iconColor, markerColor, number, prefix, shape, title'
	. '</li>
	<li>' .
	'leaflet-polygon (-circle, -line):  color, className'
	. '</li>
	<li>' .
	'leaflet-geojson (-gpx, -kml): iconUrl, alt, className, color' .
	'<ul>
	<li>' .
	sprintf( __( '%s switches only the markers.', 'extensions-leaflet-map' ), 'iconurl' ) .
	'</li>' .
	'<li>' .
	sprintf( __( 'Every marker with an icon has the option %s as default.', 'extensions-leaflet-map' ), '<code>alt="Marker"</code>' )
	. '</li>
	<li>' .
	sprintf( __( 'You can use %s for grouping, it is not used by leaflet-geojson but passed through.', 'extensions-leaflet-map' ), 'className' )
	. '</li>
	</ul>' .
	'</li>' .
	'<li>' . sprintf(
		__( 'See %1$sLeaflet Map Github page%2$s for more or less useful and possible options.', 'extensions-leaflet-map' ),
		'<a href="https://github.com/bozdoz/wp-plugin-leaflet-map#leaflet-marker">',
		'</a>'
	) . '</li>' .
	'<li>' . __( 'The options are case sensitive.', 'extensions-leaflet-map' )
	. '</li>
	</ul>';

	$text = $text . '</p><h3>leaflet-featuregroup property</h3>';
	$text = $text . '<p>' . __( 'In a geojson file there are features and properties. They are different for each application.', 'extensions-leaflet-map' ) . ' ';
	$text = $text . sprintf(
		__(
			'gpx and kml are similar to geojson, so you can also use %s there. But here it is not that easy to find relevant features.',
			'extensions-leaflet-map'
		),
		'<code>leaflet-featuregroup</code>'
	) . '</p>';

	$text = $text . '<p>' .
	sprintf(
		__(
			'A %1$s has %2$s, each of them has a %3$s and %4$s. %5$s is the label of a %6$s in %7$s, e.g.',
			'extensions-leaflet-map'
		),
		'"FeatureCollection"',
		'"features"',
		'"geometry"',
		'"properties"',
		'<code>property</code>',
		'property',
		'"properties"'
	) .
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

	$text = $text . '<p>' . sprintf(
		__( '%s is case sensitive.', 'extensions-leaflet-map' ),
		'<code>property</code>'
	) . '</p>';

	$text = $text . '<h3>Options values ' . __( 'and', 'extensions-leaflet-map' ) . ' groups</h3><p>';
	$text = $text . '<ul>
	<li><code>values</code> - ' . sprintf(
		__(
			'comma separated strings to distinguish the elements, e.g. the exact string or an unique substring in %1$s (e.g.%2$s)',
			'extensions-leaflet-map'
		),
		'option / property',
		'<code><span style="color: #4f94d4">value0</span></code>'
	) . '</li>

	<li>' . sprintf(
		__( 'The %s are case sensitive.', 'extensions-leaflet-map' ),
		'<code>values</code>'
	)
	. '</li>

	<li><code>substr</code> (' . __( 'optional', 'extensions-leaflet-map' ) . ') - ' .
	sprintf(
		__( 'search substring %1$s or the exact string %2$s in %3$s.', 'extensions-leaflet-map' ),
		'(<code>substr</code>)',
		'(<code><span style="color: #d63638">!</span>substr</code>)',
		'<code>values</code>'
	) . ' ' .
	sprintf(
		__( 'The default is %1$s for %2$s and %3$s for %4$s.', 'extensions-leaflet-map' ),
		'<i>true</i>',
		'<code>leaflet-optiongroup</code>',
		'<i>false</i>',
		'<code>leaflet-featuregroup</code>'
	) . '
	</li>

	<li><code>groups</code> - ' .
	__( 'comma separated labels appear in the selection menu', 'extensions-leaflet-map' ) . '.</li>

	<li>' . sprintf(
		__( 'The number of %1$s and %2$s must match.', 'extensions-leaflet-map' ),
		'<code>values</code>',
		'<code>groups</code>'
	) . '
	</li>
	<li>' .
	sprintf(
		__( 'You can specify multiple %1$s and %2$s. If the %3$s are called the same, the elements will be placed in the same group despite having different options resp. properties.', 'extensions-leaflet-map' ),
		'<code>leaflet-optiongroup</code>',
		'<code>leaflet-featuregroup</code>',
		'<code>groups</code>'
	) . '<br>' .
	__( 'However, make sure that they are unique. Otherwise unwanted, but sometimes interesting effects can occur. Test it with your application!', 'extensions-leaflet-map' )
	. '</li>

	<li><code>visible</code> (' . __( 'optional', 'extensions-leaflet-map' ) . ') - ' .
	sprintf(
		__(
			'initial visibility of a group, default: %1$s. Either %2$s (valid for all groups) or a comma-separated list of %3$s and %4$s, where the number must match those of %5$s.',
			'extensions-leaflet-map'
		),
		'1',
		'0',
		'0',
		'1',
		'<code>groups</code>',
	) . '</li>

	</ul></p>';

	$text = $text . '<pre><code>&#091;leaflet-optiongroup option="..." values="..., ..." groups="..., ..." visible=...]
&#091;leaflet-featuregroup property="<span style="color: #d63638">prop0</span>" values="<span style="color: #4f94d4">value0</span>,..." groups="..., ..." visible=...]</code></pre>';

	$text = $text . '<h3>Groups unknown ' . __( 'and', 'extensions-leaflet-map' ) . ' others</h3><p>' .
	sprintf(
		__( 'If %1$s contains %2$s and %3$s, then the elements for which the option / property specified in %4$s does not apply, are placed in the %5$s group. Elements whose %6$s is not known are placed in the %7$s group. See also the developer console.', 'extensions-leaflet-map' ),
		'<code>groups</code>',
		'"unknown"',
		'"others"',
		'<code>option / property</code>',
		'others',
		'<code>option / property</code>',
		'unknown'
	) .
	'</p>
	<pre><code>&#091;leaflet-optiongroup option="..." values="...,...,others,unknown" groups="...,...,Other elements,Unknown elements"]
&#091;leaflet-featuregroup property="..." values="...,...,others,unknown" groups="...,...,Other elements,Unknown elements"]</code></pre>';

	$text = $text . '<h3>parentgroup</h3><p>
<pre><code>&#091;parentgroup parent=... childs=... visible=...]</code></pre>';

	$text = $text . __(
		'Display nested groups.',
		'extensions-leaflet-map'
	);
	$text = $text . '<ul>
	<li><code>parent</code> - ' . __( 'Name of the parent', 'extensions-leaflet-map' ) . '</li>
	<li><code>childs</code> - ' .
	__( 'child names - comma separated group names (the same as in <code>groups</code> above)', 'extensions-leaflet-map' ) . '. ' .
	__( 'If you have html tags there, you can omit these here', 'extensions-leaflet-map' ) .
	'.</li>';
	$text = $text . '<li><code>visible</code> (' . __( 'optional, like above', 'extensions-leaflet-map' ) . ')</li></ul>';
	$text = $text . '<h3>' . __( 'Group Control', 'extensions-leaflet-map' ) . '</h3>' .
	'<p>' .
	'<pre><code>&#091;leaflet-optiongroup ... position=topleft|topright|bottomleft|bottomright collapsed=true|false]
&#091;leaflet-featuregroup ... position=topleft|topright|bottomleft|bottomright collapsed=true|false]</code></pre>' .
	'<p>' . __( 'The specification in the first command applies.', 'extensions-leaflet-map' ) . '</p>' .
	'<ul>
  <li>' .
	__( 'optional: <code>position</code> - position of group control', 'extensions-leaflet-map' ) . ': topleft, topright, bottomleft, bottomright. ' .
	__( 'Default', 'extensions-leaflet-map' ) . ': topright.
	</li>
	<li>' .
	__( 'optional: <code>collapsed</code> - group control collapsed or not:', 'extensions-leaflet-map' ) . ' true, false. ' .
	__( 'Default', 'extensions-leaflet-map' ) . ': false.
	</li>
	</ul>';

	if ( ! ( is_singular() || is_archive() ) ) {
		$text = $text . '<p>' . sprintf(
			__( 'The shortcode %1$s is a special case of %2$s respectively %3$s.', 'extensions-leaflet-map' ),
			'<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=markerclustergroup"><code>markerclustergroup</code></a>',
			'<code>leaflet-optiongroup</code>',
			'<code>leaflet-featuregroup</code>'
		)
		. '</p>';
	}
	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		leafext_escape_output( $admintext . $helptext . $text );
	}
}
leafext_help_featuregroup();
