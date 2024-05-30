<?php
/**
 * Admin functions for an overview map from pages / posts
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// init settings fuer overviewmap
function leafext_overviewmap_init() {
	add_settings_section( 'overviewmap_settings', '', 'leafext_overviewmap_help', 'leafext_settings_overviewmap' );
	$fields = leafext_overviewmap_admin_params();
	foreach ( $fields as $field ) {
		add_settings_field( 'leafext_overviewmap[' . $field['param'] . ']', $field['shortdesc'], 'leafext_form_overviewmap', 'leafext_settings_overviewmap', 'overviewmap_settings', $field['param'] );
	}
	register_setting( 'leafext_settings_overviewmap', 'leafext_overviewmap', 'leafext_validate_overviewmap' );
}
add_action( 'admin_init', 'leafext_overviewmap_init' );

// Baue Abfrage der Params
function leafext_form_overviewmap( $field ) {
	$options  = leafext_overviewmap_admin_params();
	$option   = leafext_array_find2( $field, $options );
	$settings = overviewmap_admin_settings();
	$setting  = $settings[ $field ];
	if ( $option['desc'] !== '' ) {
		echo '<p>' . wp_kses_post( $option['desc'] ) . '</p>';
	}
	// echo __("You can change it for each map with", "extensions-leaflet-map").' <code>'.$option['param']. '</code><br>';

	if ( ! current_user_can( 'manage_options' ) ) {
		$disabled = ' disabled ';
	} else {
		$disabled = '';
	}

	if ( ! is_array( $option['values'] ) ) {
		if ( $setting !== $option['default'] ) {
			// var_dump($setting,$option['default']);
			echo esc_html__( 'Plugins Default', 'extensions-leaflet-map' ) . ': ';
			echo $option['default'] ? 'true' : 'false';
			echo '<br>';
		}
		//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- string not changeable
		echo '<input ' . $disabled . ' type="radio" name="leafext_overviewmap[' . $option['param'] . ']" value="1" ';
		echo $setting ? 'checked' : '';
		echo '> true &nbsp;&nbsp; ';
		//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- string not changeable
		echo '<input ' . $disabled . ' type="radio" name="leafext_overviewmap[' . $option['param'] . ']" value="0" ';
		echo ( ! $setting ) ? 'checked' : '';
		echo '> false ';
		// } else {
		// others
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_overviewmap( $input ) {
	// var_dump($_REQUEST,$_POST,$input); wp_die();
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_overviewmap', 'leafext_overviewmap_nonce' ) ) {
		if ( isset( $_POST['submit'] ) ) {
			$input['transients'] = (bool) ( $input['transients'] );
		}
		return $input;
	}
}

function leafext_overviewmap_help() {
	if ( is_singular() || is_archive() ) {
		$codestyle = '';
		$text      = '';
	} else {
		leafext_enqueue_admin();
		$codestyle = ' class="language-coffeescript"';
		$text      = '<h2>' . __( 'Overview Map', 'extensions-leaflet-map' ) . '</h2>';
	}

	$text = $text . '<p>' . __( 'Generates an overview map with geo positions provided in the pages and posts.', 'extensions-leaflet-map' ) . '</p>';
	$text = $text . '<h3>' . __( 'Preparation', 'extensions-leaflet-map' ) . '</h3>';

	$text = $text . '<ul><li>' . __( 'Create for every desired page or post the below mentioned customs fields.', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '<li>' . __( 'Optional: Create a featured image for page or post.', 'extensions-leaflet-map' ) . '</li>';
	$text = $text . '<li>' . __( 'Optional: Place the page or post in one or more categories or tag it. The word "category" in this documentation means category or tag.', 'extensions-leaflet-map' ) . '</li></ul>';

	$text = $text . '<h3>' . __( 'Custom fields', 'extensions-leaflet-map' ) . '</h3>';

	$text = $text . '<p>' . __( 'The custom fields names are as you like. You can these specify in overviewmap options.', 'extensions-leaflet-map' ) . '</p>';

	$options = leafext_overviewmap_settings();
	$new     = array();
	$new[]   = array(
		'desc'    => '<strong>' . __( 'Custom field', 'extensions-leaflet-map' ) . '</strong>',
		'content' => '<strong>' . __( 'Content', 'extensions-leaflet-map' ) . '</strong>',
		'default' => '<strong>' . __( 'Default name', 'extensions-leaflet-map' ) . '</strong>',
		'param'   => '<strong>' . __( 'Option to change', 'extensions-leaflet-map' ) . '</strong>',
	);

	foreach ( $options as $option ) {
		$new[] = array(
			'desc'    => $option['desc'],
			'content' => $option['content'],
			'default' => $option['default'],
			'param'   => $option['param'],
		);
	}
	$text = $text . leafext_html_table( $new );

	$text = $text . '<h3>Shortcode</h3>
  <pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-map fitbounds]
&#091;overviewmap latlngs=... icons=... options= ...]
// optional
&#091;hover class=leafext-overview-tooltip]
</code></pre>';

	$text = $text . '<h3>' . __( 'Options for overviewmap', 'extensions-leaflet-map' ) . '</h3>';

	$options = leafext_overviewmap_params();
	$new     = array();
	$new[]   = array(
		'param'   => '<strong>Option</strong>',
		'desc'    => '<strong>' . __( 'Description', 'extensions-leaflet-map' ) . '</strong>',
		// 'content' => "<strong>".__('Content','extensions-leaflet-map').'</strong>',
		'default' => '<strong>' . __( 'Default', 'extensions-leaflet-map' ) . '</strong>',
		'values'  => '<strong>' . __( 'Possible values', 'extensions-leaflet-map' ) . '</strong>',
	);

	foreach ( $options as $option ) {
		$new[] = array(
			'param'   => $option['param'],
			'desc'    => $option['desc'],
			// 'content' => $option['content'],
			'default' => is_string( $option['default'] ) ? $option['default'] : ( ( $option['default'] === false ) ? 'false' : 'true' ),
			'values'  => $option['values'],
		);
	}
	$text = $text . leafext_html_table( $new );

	$text = $text . '<p>' . __( 'If it does not work as expected, see the developer console of your browser.', 'extensions-leaflet-map' ) . '</p>';

	$text = $text . '<h3>' . __( 'Options for marker', 'extensions-leaflet-map' ) . '</h3>';
	$text = $text . '<p>' . sprintf(
		/* translators: %s is a shortcode. */
		__( 'You can use these in the custom field for the icon and in shortcode %s.', 'extensions-leaflet-map' ),
		'<code>overviewmap</code>'
	) . '</p>';

	$text = $text . '<h4>leaflet-marker</h4>';
	$text = $text . '<p>' . implode( ', ', leafext_marker_options() ) . '</p>';
	$text = $text . '<p>' . sprintf(
		/* translators: %s are options. */
		__( '%1$s is for icon filename required. The icons directory must be the same as from %2$s.', 'extensions-leaflet-map' ),
		'<b>iconUrl</b>',
		'<b>iconUrl</b>'
	) . '</p>';

	$text = $text . '<h4>leaflet-extramarker</h4>';
	$text = $text . '<p>' . implode( ', ', leafext_extramarker_options() ) . '</p>';

	/* translators: %s is an href. */
	$text = $text . '<p>' . sprintf( __( 'See %1$sexamples%2$s.', 'extensions-leaflet-map' ), '<a href="https://leafext.de/extra/category/overviewmap/">', '</a>' ) . '</p>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}
