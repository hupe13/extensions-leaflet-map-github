<?php
/**
 * Help for markerclustergroup shortcode
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_clustergroup_help_text() {
	$firsttext = '
	<h2 id="leaflet.featuregroup.subgroup">Leaflet.FeatureGroup.SubGroup</h2>
	<img src="' . LEAFEXT_PLUGIN_PICTS . 'clustergroup.png">
	<p>' .
	__( 'Dynamically add/remove groups of markers from Marker Cluster', 'extensions-leaflet-map' ) .
	'.</p>';
	$firsttext = $firsttext . '<h3>' . __( 'Note', 'extensions-leaflet-map' ) . '</h3>' .
	'<p>' . sprintf(
		/* translators: %s are shortcodes. */
		__( 'The shortcode %1$s is a special case of %2$s respectively %3$s.', 'extensions-leaflet-map' ),
		'<code>markerclustergroup</code>',
		'<code>leaflet-optiongroup</code>',
		'<code>leaflet-featuregroup</code>'
	)
	. '<br>' . sprintf(
		/* translators: %s is a href. */
		__( 'If you want more options see %1$shere%2$s.', 'extensions-leaflet-map' ),
		'<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=featuregroup">',
		'</a>'
	) . '</p>';

	$note = '<h3>' . __( 'Note', 'extensions-leaflet-map' ) . '</h3>' .
	'<p>' . sprintf(
		/* translators: %s are shortcodes. */
		__( 'The shortcode %1$s is a special case of %2$s respectively %3$s.', 'extensions-leaflet-map' ),
		'<code>markerclustergroup</code>',
		'<code>leaflet-optiongroup</code>',
		'<code>leaflet-featuregroup</code>'
	)
	. '<br>' . sprintf(
		/* translators: %s is a href. */
		__( 'If you don\'t use this shortcode yet, start %1$sthere%2$s.', 'extensions-leaflet-map' ),
		'<a href="/doku/featuregroup/">',
		'</a>'
	) . '</p>';

	$text = '<h3>' . __( 'Options for grouping leaflet-markers', 'extensions-leaflet-map' ) . '</h3>
	<ul>
	<li><code>feat</code> - ' . __( 'possible meaningful values', 'extensions-leaflet-map' ) . ': <code>iconUrl</code>, <code>title</code></li>
		<li><code>strings</code> - ' . sprintf(
			/* translators: %s are options. */
		__(
			'comma separated strings to distinguish the markers, e.g. an unique string in %1$s or %2$s',
			'extensions-leaflet-map'
		),
		'<code>iconUrl</code>',
		'<code>title</code>'
	) . '</li>
			<li><code>groups</code> - ' .
			__( 'comma separated labels appear in the selection menu', 'extensions-leaflet-map' ) . '</li>
		<li>' . sprintf(
			/* translators: %s are options. */
				__( 'The number of %1$s and %2$s must match.', 'extensions-leaflet-map' ),
				'<code>strings</code>',
				'<code>groups</code>'
			) . '
			</li>
			<li><code>visible</code> (' . __( 'optional', 'extensions-leaflet-map' ) . ') - ' .
			sprintf(
				/* translators: %s are options / values. */
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
			</ul>

			<h3>' . __( 'Shortcode for grouping leaflet-markers', 'extensions-leaflet-map' ) . '</h3>
			<pre><code>&#091;leaflet-marker iconUrl="...red..." ... ] ... &#091;/leaflet-marker]
&#091;leaflet-marker iconUrl="...green..." ... ] ... &#091;/leaflet-marker]
//many markers
&#091;markerclustergroup feat="iconUrl" strings="red,green" groups="rot,gruen" visible=...]</code></pre>'
			. __( 'or', 'extensions-leaflet-map' ) .
			'<pre><code>&#091;leaflet-marker title="first ..." ... ] ... &#091;/leaflet-marker]
&#091;leaflet-marker title="second ..." ... ] ... &#091;/leaflet-marker]
//many markers
&#091;markerclustergroup feat="title" strings="first,second" groups="First Group,Second Group" visible=...]</code></pre>
			<h3>' . __( 'Options for grouping markers (points) in leaflet-geojson', 'extensions-leaflet-map' ) . '</h3>
			<ul>
			<li><code>feat</code> - ' . __( 'possible meaningful values', 'extensions-leaflet-map' ) . ': <code>iconUrl</code>, <code>properties.<i>property</i></code></li>
			<li><code>properties.<i>property</i></code> - ' . __( 'is the name of a property in properties of the Point in the FeatureCollection', 'extensions-leaflet-map' ) .
			', e.g. <code>properties.<span style="color: #d63638"><i>prop0</i></span></code> in
			<pre>{
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
			 '
			. '</li>
			<li><code>strings</code> - ' . sprintf(
				/* translators: %s are options / values. */
				__(
					'comma separated strings to distinguish the markers, e.g. an unique substring in %1$s or the exact string (e.g.%2$s) in %3$s',
					'extensions-leaflet-map'
				),
				'<code>iconUrl</code>',
				'<code><span style="color: #4f94d4">value0</span></code>',
				'<code>properties.<i>property</i></code>'
			) . '</li>
				<li><code>groups</code> - ' .
				__( 'comma separated labels appear in the selection menu', 'extensions-leaflet-map' ) . '</li>
				<li>' . sprintf(
					/* translators: %s are options. */
					__( 'The number of %1$s and %2$s must match.', 'extensions-leaflet-map' ),
					'<code>strings</code>',
					'<code>groups</code>'
				) . '
					</li>
					<li><code>visible</code> (' . __( 'optional', 'extensions-leaflet-map' ) . ') - ' .
					sprintf(
						/* translators: %s are options / values. */
						__(
							'initial visibility of a group, default: %1$s. Either %2$s (valid for all groups) or a comma-separated list of %3$s and %4$s, where the number must match those of %5$s.',
							'extensions-leaflet-map'
						),
						'1',
						'0',
						'0',
						'1',
						'<code>groups</code>',
					) . '</li></ul>

					<h3>' . __( 'Shortcode for grouping markers (points) in leaflet-geojson', 'extensions-leaflet-map' ) . '</h3>
		<pre><code>&#091;leaflet-geojson src="..." iconUrl="...red..." ... ] ... &#091;/leaflet-geojson]
&#091;leaflet-geojson src="..." iconUrl="...green..." ... ] ... &#091;/leaflet-geojson]
//any more leaflet-geojson
&#091;markerclustergroup feat="iconUrl" strings="red,green" groups="rot,gruen" visible=...]</code></pre>'
					. __( 'or', 'extensions-leaflet-map' ) .
	'<pre><code>&#091;leaflet-geojson src="..."  ... ] ... &#091;/leaflet-geojson]
//any more leaflet-geojson
&#091;markerclustergroup feat="properties.<span style="color: #d63638">prop0</span>" strings="<span style="color: #4f94d4">value0</span>,..." groups="Description0,..." visible=...]</code></pre>
<h3>groups unknown ' . __( 'and', 'extensions-leaflet-map' ) . ' others</h3><p>' .
	sprintf(
		/* translators: %s are options / values. */
		__( 'If %1$s contains %2$s and %3$s, then markers (respectively Points) for which the property %4$s does not apply are placed in the %5$s group. Markers (respectively Points) whose property is not known are placed in the %6$s group. See also the developer console.', 'extensions-leaflet-map' ),
		'<code>groups</code>',
		'"unknown"',
		'"others"',
		'(<code>iconUrl</code>, <code>title</code>, <code>properties.<i>property</i></code>)',
		'others',
		'unknown'
	) .
			'</p><h3>Shortcode groups unknown ' . __( 'and', 'extensions-leaflet-map' ) . ' others</h3>
<pre><code>&#091;markerclustergroup feat="..." strings="...,...,others,unknown" groups="...,...,Other properties,Unknown properties"]</code></pre>';

	if ( is_singular() || is_archive() ) {
		$server = map_deep( wp_unslash( $_SERVER ), 'sanitize_text_field' );
		if ( strpos( $server['REQUEST_URI'], '/en/' ) !== false ) {
			$lang = '/en';
		} else {
			$lang = '';
		}
		$clusterurl = $lang . '/doku/markercluster/';
	} else {
		$clusterurl = '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=markercluster';
	}

	$textoptions = '<h3>' . __( 'More options', 'extensions-leaflet-map' ) . '</h3><p>' . sprintf(
		/* translators: %s is a shortcode. */
		__( 'The parameter and settings for %s are valid too.', 'extensions-leaflet-map' ),
		'<a href="' . $clusterurl . '">Leaflet.markercluster</a>'
	);
	$textoptions = $textoptions . '</p>';
	$textoptions = $textoptions . '<ul>
	<li>' . sprintf(
	/* translators: %s is an option. */
		__( 'optional: %s - position of group control', 'extensions-leaflet-map' ),
		'<code>position</code>'
	) . ': topleft, topright, bottomleft, bottomright. ' .
	__( 'Default', 'extensions-leaflet-map' ) . ': topright.
	</li>
	<li>' . sprintf(
	/* translators: %s is an option. */
		__( 'optional: %s - group control collapsed or not:', 'extensions-leaflet-map' ),
		'<code>collapsed</code>'
	) . ' true, false. ' .
	__( 'Default', 'extensions-leaflet-map' ) . ': false.
	</li>
	</ul>';

	if ( is_singular() || is_archive() ) {
		return $note . $text . $textoptions;
	} else {
		echo wp_kses_post( $firsttext . $text . $textoptions );
	}
}
leafext_clustergroup_help_text();
