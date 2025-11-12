<?php
/**
 * Admin page for placementstrategies functions
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_admin_placementstrategies() {
	if ( current_user_can( 'manage_options' ) ) {
		echo '<form method="post" action="options.php">';
	} else {
		echo '<form>';
	}
	settings_fields( 'leafext_settings_placementparams' );
	do_settings_sections( 'leafext_settings_placementparams' );
	if ( current_user_can( 'manage_options' ) ) {
		wp_nonce_field( 'leafext_marker', 'leafext_marker_nonce' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false );
	}
	echo '</form>';
}

function leafext_placementparams_init() {
	add_settings_section( 'placementparams_settings', leafext_marker_tab(), 'leafext_placement_help_text', 'leafext_settings_placementparams' );
	$fields = leafext_placementstrategies_params();
	foreach ( $fields as $field ) {
		add_settings_field( 'leafext_placementparams[' . $field[0] . ']', $field[1], 'leafext_form_placement', 'leafext_settings_placementparams', 'placementparams_settings', $field[0] );
	}
	register_setting( 'leafext_settings_placementparams', 'leafext_placementparams', 'leafext_validate_placement_options' );
}
add_action( 'admin_init', 'leafext_placementparams_init' );

// Baue Abfrage der Params
function leafext_form_placement( $field ) {
	// var_dump($field);
	$options = leafext_placementstrategies_params();
	// var_dump($options);
	$option = leafext_array_find( $field, $options );
	// var_dump($option);echo '<br>';
	$settings = leafext_placementstrategies_settings();
	$setting  = $settings[ $field ];
	if ( ! current_user_can( 'manage_options' ) ) {
		$disabled = ' disabled ';
	} else {
		$disabled = '';
	}

	if ( $option[0] === 'elementsPlacementStrategy' ) {
		echo '<p>' . esc_html__( '"default" means: one-circle strategy up to 8 elements, else spiral strategy', 'extensions-leaflet-map' ) . '</p>';
	}

	echo wp_kses_post( __( 'You can change it for each map with', 'extensions-leaflet-map' ) . ' <code>' . $option[0] . '</code><br>' . "\n" );
	if ( ! is_array( $option[3] ) ) {

		if ( $setting !== $option[2] ) {
			// var_dump($setting,$option[2]);
			echo esc_html__( 'Plugins Default', 'extensions-leaflet-map' ) . ': ';
			echo $option[2] ? '1' : '0';
			echo '<br>' . "\n";
		}

		echo '<input ' . esc_attr( $disabled ) . ' type="radio" name="' . esc_attr( 'leafext_placementparams[' . $option[0] . ']' ) . '" value="1" ';
		echo $setting ? 'checked' : '';
		echo '> true &nbsp;&nbsp; ';
		echo '<input ' . esc_attr( $disabled ) . ' type="radio" name="' . esc_attr( 'leafext_placementparams[' . $option[0] . ']' ) . '" value="0" ';
		echo ( ! $setting ) ? 'checked' : '';
		echo '> false ';
	} else {
		$plugindefault = is_string( $option[2] ) ? $option[2] : ( $option[2] ? '1' : '0' );
		$setting       = is_string( $setting ) ? $setting : ( $setting ? '1' : '0' );
		if ( $setting !== $plugindefault ) {
			// var_dump("Option: ",$option[2],"Plugindefault: ",$plugindefault,"Setting: ",$setting);
			echo esc_html( __( 'Plugins Default:', 'extensions-leaflet-map' ) . ' ' . $plugindefault ) . '<br>' . "\n";
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			$select_disabled = ' disabled multiple size=' . count( $option[3] ) . ' ';
		} else {
			$select_disabled = '';
		}
		echo '<select ' . esc_attr( $select_disabled ) . ' name="' . esc_attr( 'leafext_placementparams[' . $option[0] . ']' ) . '">' . "\n";
		foreach ( $option[3] as $para ) {
			echo '<option ';
			if ( is_bool( $para ) ) {
				$para = ( $para ? '1' : '0' );
			}
			if ( $para === $setting ) {
				echo ' selected="selected" ';
			}
			echo 'value="' . esc_attr( $para ) . '">' . esc_attr( $para ) . '</option>' . "\n";
		}
		echo '</select>' . "\n";
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_placement_options( $options ) {
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_marker', 'leafext_marker_nonce' ) ) {
		if ( isset( $_POST['submit'] ) ) {
			return $options;
		}
		if ( isset( $_POST['delete'] ) ) {
			delete_option( 'leafext_placementparams' );
		}
		return false;
	}
}

// Helptext
function leafext_placement_help_text() {
	if ( is_singular() || is_archive() ) {
		$codestyle = '';
	} else {
		leafext_enqueue_admin();
		$codestyle = ' class="language-coffeescript"';
	}
	$text = '<h2>Leaflet.MarkerCluster.PlacementStrategies</h2>';
	$text = $text . '<p>';
	$text = $text . '<a href="https://github.com/adammertel/Leaflet.MarkerCluster.PlacementStrategies">' . __( 'Demo and Documentation', 'extensions-leaflet-map' ) . '</a>';
	$text = $text . ' - ' . __( 'Not all parameters are implemented in the plugin.', 'extensions-leaflet-map' ) . '';
	$text = $text . '</p>';
	$text = $text . '<h3>Shortcode</h3>
	<pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-map ....]
// many markers
&#091;leaflet-marker lat=... lng=... ...]poi1[/leaflet-marker]
&#091;leaflet-marker lat=... lng=... ...]poi2[/leaflet-marker]
...
&#091;leaflet-marker lat=... lng=... ...]poixx[/leaflet-marker]
&#091;placementstrategies ...]
//optional
&#091;hover]
&#091;zoomhomemap]
</code></pre><p>
' . __( 'It also works with', 'extensions-leaflet-map' ) . ' <code>leaflet-extramarkers</code>. </p>
	<h3>' . __( 'Options', 'extensions-leaflet-map' ) . '</h3>';

	if ( is_singular() || is_archive() ) {
		$text = $text . '<p>' . __( 'Please see the admin page for options.', 'extensions-leaflet-map' ) . '</p>';
		return $text;
	} else {
		/* translators: %s is code. */
		$text = $text . '<p>' . wp_sprintf( __( 'The parameter maxZoom has been removed, please use %s instead.', 'extensions-leaflet-map' ), '<code>[leaflet-map max_zoom="xx" ...]</code>' ) . '</p>';
		echo wp_kses_post( $text );
	}
}
