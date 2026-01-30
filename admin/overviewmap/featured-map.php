<?php
/**
 * Help for featured map for pages / posts
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Admin page for the plugin
function leafext_featuredmap_admin() {
	leafext_featuredmap_help1();
	if ( current_user_can( 'manage_options' ) ) {
		echo '<form method="post" action="options.php">';
	} else {
		echo '<form>';
	}
	settings_fields( 'leafext_featuredmap_settings' );
	do_settings_sections( 'leafext_featuredmap_settings' );
	if ( current_user_can( 'manage_options' ) ) {
		wp_nonce_field( 'leafext_featuredmap', 'leafext_featuredmap_nonce' );
		submit_button();
		submit_button( esc_html__( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false );
	}
	echo '</form>';
	leafext_featuredmap_help2();
}

// Init settings
function leafext_featuredmap_init() {
	add_settings_section( 'leafext_featuredmap_settings', '', '', 'leafext_featuredmap_settings' );
	$fields = leafext_featuredmap_params();
	foreach ( $fields as $field ) {
		add_settings_field( 'leafext_featuredmap[' . $field['param'] . ']', $field['shortdesc'], 'leafext_featuredmap_form', 'leafext_featuredmap_settings', 'leafext_featuredmap_settings', $field['param'] );
	}
	register_setting( 'leafext_featuredmap_settings', 'leafext_featuredmap', 'leafext_featuredmap_validate' );
}
add_action( 'admin_init', 'leafext_featuredmap_init' );

// Baue Abfrage der Params
function leafext_featuredmap_form( $field ) {
	$setting = leafext_featuredmap_settings();
	$options = leafext_featuredmap_params();
	if ( ! current_user_can( 'manage_options' ) ) {
		$disabled = ' disabled ';
	} else {
		$disabled = '';
	}

	foreach ( $options as $option ) {
		if ( $option['param'] === $field ) {
			if ( $option['change'] !== '' ) {
				echo wp_kses_post( __( 'You can change it for each map with', 'extensions-leaflet-map' ) . ' <code>' . $option['change'] . '</code>' );
			}
			echo '<p>' . wp_kses_post( $option['desc'] ) . '</p>';
			echo '<input size=50 ' . esc_attr( $disabled ) . 'name="leafext_featuredmap[' . esc_attr( $field ) . ']" placeholder="' . esc_attr( $option['placeholder'] ) . '" value="' . esc_attr( $setting[ $field ] ) . '"' . esc_attr( $setting[ $field ] ) . '>' . "\r\n";
			echo '<p>' . wp_kses_post( $option['to-use'] ) . '</p>';
		}
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_featuredmap_validate( $input ) {
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_featuredmap', 'leafext_featuredmap_nonce' ) ) {
		if ( isset( $_POST['submit'] ) ) {
			return $input;
		}
	}
}

function leafext_featuredmap_help1() {
	if ( is_singular() || is_archive() ) {
		$codestyle      = '';
		$text           = '';
		$overmapref     = '<code>overviewmap</code>';
		$extramarkerref = '<code>leaflet-extramarker</code>';
	} else {
		leafext_enqueue_admin();
		$codestyle      = ' class="language-coffeescript"';
		$text           = '<h2>' . __( 'Featured Map', 'extensions-leaflet-map' ) . '</h2>';
		$overmapref     = '<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=overviewmap"><code>overviewmap</code></a>';
		$extramarkerref = '<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=extramarker"><code>leaflet-extramarker</code></a>';
	}

	$text .= '<p>' . __( 'Generates a map from geo information in pages and posts.', 'extensions-leaflet-map' ) . '</p>';
	$text .= '<h3>' . __( 'Preparation', 'extensions-leaflet-map' ) . '</h3>';

	$text .= '<p>' . wp_sprintf(
		/* translators: %s are link and options */
		__( 'There is a %1$s for %2$s. The codex lists metadata, of which the shortcode uses the following in custom fields: %3$s and %4$s.', 'extensions-leaflet-map' ),
		'<a href="https://codex.wordpress.org/Geodata">Codex</a>',
		'WordPress Geodata',
		'<code>geo_latitude</code>, <code>geo_longitude</code>',
		'<code>geo_address</code>'
	) . '</p>';
		$text .= '<p>' . wp_sprintf(
			/* translators: %s is link */
			__( 'You can also use the custom fields you defined for %s for the Featured Map.', 'extensions-leaflet-map' ),
			$overmapref,
		) . '</p>';

		$text .= '<h4>' . __( 'Create following custom fields for the page / post:', 'extensions-leaflet-map' ) . '</h4>';
		$text .= '<ul>';
		$text .= '<li>' . wp_sprintf(
		/* translators: %s are options and a link */
			__( 'For latitude and longitude: %1$s and %2$s or %3$s like in %4$s (required)', 'extensions-leaflet-map' ),
			'<code>geo_latitude</code>',
			'<code>geo_longitude</code>',
			'<code>lanlngs</code>',
			$overmapref,
		) . '</li>';
	$text .= '<li>' . wp_sprintf(
		/* translators: %s are options and a link */
		__( 'For popup content: a custom field named %1$s or any other name (optional)', 'extensions-leaflet-map' ),
		'<code>geo_address</code>'
	) . '</li>';
	$text .= '<li>' . wp_sprintf(
		/* translators: %s are options and a link */
		__( 'For the icon: a custom field with a shortcode for %1$s or %2$s, but without %3$s and %4$s and brackets (optional)', 'extensions-leaflet-map' ),
		'<code>leaflet-marker</code>',
		$extramarkerref,
		'<code>lat</code>',
		'<code>lng</code>',
	) .
		'</li></ul>';

		$text .= '<h3>Shortcode</h3>
	  <pre' . $codestyle . '><code' . $codestyle . '>&#091;featured-map options ...]</code></pre>';

		$text .= '<h3>' . __( 'Options', 'extensions-leaflet-map' ) . '</h3>';
	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}

function leafext_featuredmap_help2() {
	if ( is_singular() || is_archive() ) {
		$codestyle = '';
		$text      = '';
	} else {
		leafext_enqueue_admin();
		$codestyle = ' class="language-coffeescript"';
		$text      = '';
	}

	/* translators: %s is an href. */
	$text .= '<p>' . wp_sprintf( __( 'See %1$sexamples%2$s.', 'extensions-leaflet-map' ), '<a href="https://leafext.de/extra/categories/featuredmap/">', '</a>' ) . '</p>';

	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}
