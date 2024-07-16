<?php
/**
 * Help for leaflet-optiongroup leaflet-featuregroup
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_help_parentgroup() {

	if ( is_singular() || is_archive() ) {
		$codestyle = '';
		$text      = '';
	} else {
		leafext_enqueue_admin();
		$codestyle = ' class="language-coffeescript"';
		$text      = '<h2>Leaflet.Control.Layers.Tree</h2>';
		$text      = $text . '
		<img src="' . LEAFEXT_PLUGIN_PICTS . 'parent.png">
		<img src="' . LEAFEXT_PLUGIN_PICTS . 'parentall.png">';
	}

	$text     = $text . '<h3>leaflet-parentgroup</h3><p>' . __(
		'Display groups in a tree view.',
		'extensions-leaflet-map'
	);
		$text = $text . '</p>';
		$text = $text . '<h3>Shortcode</h3><p>
<pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-parentgroup parent=... childs=... expandall=... collapseall=...]</code></pre>';

	$text = $text . '</p>';
	$text = $text . '<h3>' . __( 'Options', 'extensions-leaflet-map' ) . '</h3>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}
