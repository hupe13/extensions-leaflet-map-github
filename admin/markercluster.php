<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

// init settings fuer cluster
function leafext_cluster_init(){
	add_settings_section( 'cluster_settings', 'Leaflet.markercluster and Leaflet.FeatureGroup.SubGroup', 'leafext_cluster_help_text', 'leafext_settings_cluster' );
	add_settings_field( 'leafext_cluster_disableClusteringAtZoom', 'disableClusteringAtZoom', 'leafext_form_cluster_disableClusteringAtZoom', 'leafext_settings_cluster', 'cluster_settings' );
	add_settings_field( 'leafext_cluster_maxClusterRadius', 'maxClusterRadius', 'leafext_form_cluster_maxClusterRadius', 'leafext_settings_cluster', 'cluster_settings' );
	add_settings_field( 'leafext_cluster_spiderfyOnMaxZoom', 'spiderfyOnMaxZoom', 'leafext_form_cluster_spiderfyOnMaxZoom', 'leafext_settings_cluster', 'cluster_settings' );
	register_setting( 'leafext_settings_cluster', 'leafext_cluster', 'leafext_validate_cluster' );
}
add_action('admin_init', 'leafext_cluster_init' );

//get Options
function leafext_form_cluster_get_options($reset=false) {
	$defaults = array(
		'zoom' => "17",
		'radius' => "80",
		'spiderfy' => true,
	);
	if ( $reset ) return $defaults;
	$options = shortcode_atts($defaults, get_option('leafext_cluster') );
	return $options;
}

// Baue Abfrage standard zoom
function leafext_form_cluster_disableClusteringAtZoom() {
	//echo "leafext_form_cluster_disableClusteringAtZoom";
	$options = leafext_form_cluster_get_options();
	echo '<p>'.__('At this zoom level and below, markers will not be clustered, see', 'extensions-leaflet-map').
	' <a href="https://leaflet.github.io/Leaflet.markercluster/example/marker-clustering-realworld-maxzoom.388.html">'.__('Example','extensions-leaflet-map').
	'</a>.</p><p>'.__('Plugins Default','extensions-leaflet-map').': 17. ';
	echo __('If 0, it is disabled.','extensions-leaflet-map').' ';
	echo __('You can change it for each map:','extensions-leaflet-map');
	echo '</p><pre><code>[cluster zoom=17]</code></pre>';
	echo '<input type="number" class="small-text" name="leafext_cluster[zoom]" value="'.$options['zoom'].'" min="0" max="19" />';
}

function leafext_form_cluster_maxClusterRadius() {
	//echo "leafext_form_cluster_maxClusterRadius";
	$options = leafext_form_cluster_get_options();
	//var_dump($options);
	echo '<p>'.
	__('The maximum radius that a cluster will cover from the central marker (in pixels). Decreasing will make more, smaller clusters.',
	'extensions-leaflet-map')
	.'</p><p>'.
	__('Default:','extensions-leaflet-map').' 80. ';
	echo __('You can change it for each map:','extensions-leaflet-map').'</p><pre><code>[cluster radius=80]</code></pre>';
	echo '<input type="number" class="small-text" name="leafext_cluster[radius]" value="'.$options['radius'].'" min="10" />';
}

function leafext_form_cluster_spiderfyOnMaxZoom() {
	//echo "leafext_form_cluster_spiderfyOnMaxZoom";
	//boolean
	$options = leafext_form_cluster_get_options();
	echo '<p>'.
	__('When you click a cluster at the bottom zoom level we spiderfy it so you can see all of its markers.','extensions-leaflet-map').'</p>';
	echo '<p>'.
	__('Default: true. You can change it for each map:','extensions-leaflet-map').'</p>';
	echo '<pre><code>[cluster spiderfy=1]</code></pre>';
	echo '<input type="checkbox" name="leafext_cluster[spiderfy]" ';
	echo $options['spiderfy'] ? 'checked' : '' ;
	echo '>';
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_cluster($input) {
	if (isset($_POST['submit'])) {
		//echo "submit";
		if( isset( $input['zoom'] ) && $input['zoom'] != "" &&
			isset( $input['radius'] ) && $input['radius'] != ""  ) {
			$input['zoom'] = intval($input['zoom']);
			$input['radius'] = intval($input['radius']);
			$input['spiderfy'] = (bool)($input['spiderfy']);
		} else {
			$input = array();
			$input = leafext_form_cluster_get_options(1);
		}
		return $input;
	}
	if (isset($_POST['delete'])) delete_option('leafext_cluster');
	return false;
}

// Erklaerung
function leafext_cluster_help_text() {
	//echo '<h4 id="leaflet.markercluster">Leaflet.markercluster and Leaflet.FeatureGroup.SubGroup</h4>
	echo '<img src="'.LEAFEXT_PLUGIN_PICTS.'cluster.png">
	<img src="'.LEAFEXT_PLUGIN_PICTS.'clustergroup.png">
	<p>'.__('Many markers on a map become confusing. That is why they are clustered','extensions-leaflet-map').'.</p>
	<p>'.__('You may be interested in dynamically add/remove groups of markers from Marker Cluster with Leaflet.FeatureGroup.SubGroup.','extensions-leaflet-map').
	'</p>
	<h2>Leaflet.markercluster</h2>
	<pre><code>[leaflet-map ....]
// many markers
[leaflet-marker lat=... lng=... ...]poi1[/leaflet-marker]
[leaflet-marker lat=... lng=... ...]poi2[/leaflet-marker]
...
[leaflet-marker lat=... lng=... ...]poixx[/leaflet-marker]
[cluster]
// or
[cluster radius="..." zoom="..." spiderfy=0]
[zoomhomemap]
</code></pre>';

	echo '<h2 id="leaflet.featuregroup.subgroup">Leaflet.FeatureGroup.SubGroup</h2>
<p>'.
__('dynamically add/remove groups of markers from Marker Cluster','extensions-leaflet-map').
'.<br>Parameter:</p>
<ul>
<li>feat - '.__('possible meaningful values','extensions-leaflet-map').': iconUrl, title</li>
<li>strings - '.
__('comma separated strings to distinguish the markers, e.g. an unique string in iconUrl or title',
'extensions-leaflet-map').'</li>
<li>groups - '.
__('comma separated labels appear in the selection menu','extensions-leaflet-map').'</li>
<li>'.
__('The number of strings and groups must match.','extensions-leaflet-map').'
</li></ul>
<pre><code>[leaflet-marker iconUrl="...red..." ... ] ... [/leaflet-marker]
[leaflet-marker iconUrl="...green..." ... ] ... [/leaflet-marker]
//many markers
[markerClusterGroup feat="iconUrl" strings="red,green" groups="rot,gruen"]
</code></pre>
'.__('or','extensions-leaflet-map').'
<pre><code>[leaflet-marker title="first ..."  ... ] ... [/leaflet-marker]
[leaflet-marker title="second ..." ... ] ... [/leaflet-marker]
//many markers
[markerClusterGroup feat="title" strings="first,second" groups="First Group,Second Group"]
</code></pre>
<h2>Options for Leaflet.markercluster</h2>';
echo '<p>'.
  __('Please see the <a href="https://github.com/Leaflet/Leaflet.markercluster#options">Leaflet.markercluster</a> page for options. If you want to change other ones, please post it to the forum.',
  'extensions-leaflet-map').' ';
	echo __('To reset all values to their defaults, simply click the Reset button',
	'extensions-leaflet-map').'.</p>';
}
