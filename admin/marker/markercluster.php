<?php
/**
 * Admin page for markercluster functions
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_admin_markercluster() {
	if ( current_user_can( 'manage_options' ) ) {
		echo '<form method="post" action="options.php">';
	} else {
		echo '<form>';
	}
	settings_fields( 'leafext_settings_clusterparams' );
	do_settings_sections( 'leafext_settings_clusterparams' );
	if ( current_user_can( 'manage_options' ) ) {
		wp_nonce_field( 'leafext_marker', 'leafext_marker_nonce' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false );
	}
	echo '</form>';
}

function leafext_cluster_init() {
	add_settings_section( 'clusterparams_settings', leafext_marker_tab(), 'leafext_markercluster_help_text', 'leafext_settings_clusterparams' );
	$fields = leafext_cluster_params();
	foreach ( $fields as $field ) {
		add_settings_field( 'leafext_cluster[' . $field['param'] . ']', $field['desc'], 'leafext_form_markercluster', 'leafext_settings_clusterparams', 'clusterparams_settings', $field['param'] );
	}
	register_setting( 'leafext_settings_clusterparams', 'leafext_cluster', 'leafext_validate_markercluster_options' );
}
add_action( 'admin_init', 'leafext_cluster_init' );

// Baue Abfrage der Params
function leafext_form_markercluster( $field ) {
	// var_dump($field);
	$options = leafext_cluster_params();
	// var_dump($options);
	$option = leafext_array_find2( $field, $options );
	// var_dump($option);echo '<br>';
	$settings = leafext_cluster_settings();
	$setting  = $settings[ $field ];

	if ( ! current_user_can( 'manage_options' ) ) {
		$disabled = ' disabled ';
	} else {
		$disabled = '';
	}

	echo wp_kses_post( __( 'You can change it for each map with', 'extensions-leaflet-map' ) . ' <code>' . $option['param'] . '</code><br>' . "\n" );
	if ( ! is_array( $option['values'] ) ) {

		if ( $setting !== $option['default'] ) {
			echo esc_html__( 'Plugins Default', 'extensions-leaflet-map' ) . ': ';
			echo $option['default'] ? '1' : '0';
			echo '<br>' . "\n";
		}
		echo '<input ' . esc_attr( $disabled ) . ' type="radio" name="' . esc_attr( 'leafext_cluster[' . $option['param'] . ']' ) . '" value="1" ';
		echo $setting ? 'checked' : '';
		echo '> true &nbsp;&nbsp; ';
		echo '<input ' . esc_attr( $disabled ) . ' type="radio" name="' . esc_attr( 'leafext_cluster[' . $option['param'] . ']' ) . '" value="0" ';
		echo ( ! $setting ) ? 'checked' : '';
		echo '> false ';
	} else {
		$plugindefault = is_string( $option['default'] ) ? $option['default'] : ( $option['default'] ? '1' : '0' );
		$setting       = is_string( $setting ) ? $setting : ( $setting ? '1' : '0' );
		if ( $setting !== $plugindefault ) {
			// var_dump("Option: ",$option[2],"Plugindefault: ",$plugindefault,"Setting: ",$setting);
			echo esc_html( __( 'Plugins Default:', 'extensions-leaflet-map' ) . ' ' . $plugindefault ) . '<br>' . "\n";
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			$select_disabled = ' disabled multiple size=' . count( $option['values'] ) . ' ';
		} else {
			$select_disabled = '';
		}
		echo '<select ' . esc_attr( $select_disabled ) . ' name="' . esc_attr( 'leafext_cluster[' . $option['param'] . ']' ) . '">' . "\n";
		foreach ( $option['values'] as $para ) {
			echo '<option ';
			if ( is_bool( $para ) ) {
				$para = ( $para ? '1' : '0' );
			}
			if ( $para === $setting ) {
				echo ' selected="selected" ';
			}
			echo 'value="' . esc_attr( $para ) . '" >' . esc_attr( $para ) . '</option>' . "\n";
		}
		echo '</select>' . "\n";
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_markercluster_options( $options ) {
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_marker', 'leafext_marker_nonce' ) ) {
		if ( isset( $_POST['submit'] ) ) {
			return $options;
		}
		if ( isset( $_POST['delete'] ) ) {
			delete_option( 'leafext_cluster' );
		}
		return false;
	}
}

// Helptext
function leafext_markercluster_help_text() {
	if ( is_singular() || is_archive() ) {
		$codestyle = '';
	} else {
		leafext_enqueue_admin();
		$codestyle = ' class="language-coffeescript"';
	}
	$text = '
	<h2>Leaflet.markercluster</h2>
	<img src="' . LEAFEXT_PLUGIN_PICTS . 'cluster.png" alt="cluster">
	<p>' . __( 'Many markers on a map become confusing. That is why they are clustered', 'extensions-leaflet-map' ) . '.</p>

	<h3>Shortcode</h3>
	<h4>' . __( 'Create Map', 'extensions-leaflet-map' ) . '</h4>
	<pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-map ....]
</code></pre>
<h4>' . __( 'and markers with leaflet-marker', 'extensions-leaflet-map' ) . '</h4>
<pre' . $codestyle . '><code' . $codestyle . '>// many markers
&#091;leaflet-marker lat=... lng=... ...]poi1&#091;/leaflet-marker]
&#091;leaflet-marker lat=... lng=... ...]poi2&#091;/leaflet-marker]
...
&#091;leaflet-marker lat=... lng=... ...]poixx&#091;/leaflet-marker]
</code></pre>
<h4>' . __( 'and/or with leaflet-gpx and/or leaflet-kml and/or leaflet-geojson', 'extensions-leaflet-map' ) . '</h4>
<pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-gpx src="url/to/....gpx" ...]{name}&#091;/leaflet-gpx]
</code></pre>
<pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-kml src="url/to/....kml" ...]{name}&#091;/leaflet-kml]
</code></pre>
<pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-geojson src="url/to/....geojson" ...]{popup-text}&#091;/leaflet-geojson]
</code></pre>
<h4>' . __( 'Create cluster', 'extensions-leaflet-map' ) . '</h4>
<pre' . $codestyle . '><code' . $codestyle . '>&#091;cluster]
// or
&#091;cluster option1=value1 option2 !option3 ...]
&#091;zoomhomemap]
</code></pre>';

	$textoptions = '<h3>' . __( 'Options', 'extensions-leaflet-map' ) . '</h3>

<p>' .
	sprintf(
		/* translators: %s is a link. */
		__(
			'Please see the %s page for options. If you want to change other ones, please post it to the forum.',
			'extensions-leaflet-map'
		),
		'<a href="https://github.com/Leaflet/Leaflet.markercluster#options">Leaflet.markercluster</a>'
	) . ' ';
	$textoptions = $textoptions . __(
		'To reset all values to their defaults, simply click the Reset button',
		'extensions-leaflet-map'
	) . '.</p>';

	$textoptions = $textoptions . '<p>';
	$textoptions = $textoptions . __( 'For boolean values applies', 'extensions-leaflet-map' ) . ':<br>';
	$textoptions = $textoptions . '<code>false</code> = <code>!option</code> || <code>option="0"</code> || <code>option=0</code></br>';
	$textoptions = $textoptions . '<code>true</code> = <code>option</code> || <code>option="1"</code> || <code>option=1</code>';
	$textoptions = $textoptions . '</p>';

	$defaults = get_option( 'leafext_cluster' );
	if ( is_array( $defaults ) ) {
		if ( array_key_exists( 'zoom', $defaults ) ||
		array_key_exists( 'radius', $defaults ) ||
		array_key_exists( 'spiderfy', $defaults ) ) {
			$textoptions = $textoptions . '<p>';
			$textoptions = $textoptions . __( 'The options zoom, radius and spiderfy have been renamed to disableClusteringAtZoom, maxClusterRadius and spiderfyOnMaxZoom, but they are still valid. Your settings:', 'extensions-leaflet-map' );
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			$textoptions = $textoptions . '<pre>' . substr( substr( print_r( get_option( 'leafext_cluster' ), true ), 8 ), 0, -3 ) . '</pre>';
			$textoptions = $textoptions . __( 'Before you click the submit button, please compare your settings with the new ones and change them if they are different.', 'extensions-leaflet-map' );
			$textoptions = $textoptions . '</p>';
		}
	}
	if ( is_singular() || is_archive() ) {
		$text = $text . '<h3>' . __( 'Options', 'extensions-leaflet-map' ) . '</h3>';
		return $text;
	} else {
		echo wp_kses_post( $text . $textoptions );
	}
}
