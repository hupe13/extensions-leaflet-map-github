<?php
/**
 * Admin functions for extra waypoints
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// init settings fuer extra waypoint options
function leafext_waypoints_init() {
	$section_group = 'leafext_waypoints';
	$section_name  = 'leafext_waypoints';
	$validate      = 'leafext_validate_waypoints';
	register_setting( $section_group, $section_name, $validate );
	$settings_section = 'leafext_waypoints_main';
	add_settings_section( $settings_section, leafext_elevation_tab(), 'leafext_waypoints_help_text', $section_group );
	add_settings_field( $section_name, '<span style="color: #4f94d4">' . __( 'Text of GPS symbol name', 'extensions-leaflet-map' ) . '</span>:', 'leafext_form_waypoints', $section_group, $settings_section );
}
add_action( 'admin_init', 'leafext_waypoints_init' );

// Baue Form
function leafext_form_waypoints() {
	$options = get_option( 'leafext_waypoints' );
	if ( ! $options ) {
		$options = array();
	}
	$waypoint  = array(
		'sym' => '',
		'css' => '',
		'js'  => '',
	);
	$options[] = $waypoint;
	$i         = 0;
	$count     = count( $options );
	foreach ( $options as $option ) {
		if ( $i > 0 ) {
			echo '<tr><th colspan=2 style="border-top: 3px solid #646970"> </th></tr>';
			echo '<tr><th scope="row-title"><span style="color: #4f94d4">' . esc_html__( 'Text of GPS symbol name', 'extensions-leaflet-map' ) . '</span>:</th>';
			echo '<td>';
			// } else {
			// echo '<td>';
		}

		echo '<input class="full-width" type="text" ';
		if ( $option['js'] === '' ) {
			echo 'placeholder="name" ';
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo 'name="leafext_waypoints[' . $i . '][sym]" value="' . $option['sym'] . '" pattern="[a-zA-Z]+[a-zA-Z0-9\- ,]*" />';
		if ( $option['sym'] === '' && $option['js'] !== '' ) {
			echo ' (' . esc_html__( 'Default', 'extensions-leaflet-map' ) . ')';
		}
		if ( ( $option['sym'] === '' && $option['js'] === '' ) ) {
			echo '<p>' . esc_html__( 'Valid characters: lowercase, uppercase, numbers, -, comma, blank character.', 'extensions-leaflet-map' ) . '</p>';
			$symkey = array_search( '', array_column( $options, 'sym' ), true );
			if ( $options[ $symkey ]['js'] === '' ) {
				echo '<p>' . esc_html__( 'It may be empty, then its javascript is the default for all waypoints.', 'extensions-leaflet-map' ) . '</p>';
			}
		}
		echo '</td>';
		echo '</tr>';

		if ( $option['sym'] !== '' ) {
			echo '<tr><th scope="row-title"><span style="color: #d63638">waypoint-css</span>:</th>';
			echo '<td>' . esc_attr( $option['css'] ) . '</td>';
			echo '</tr>';
		}

		echo '<tr><th scope="row-title"><span style="color: #00a32a">Javascript</span>:</th>';
		if ( ! isset( $option['js'] ) ) {
			$option['js'] = '';
		}
		echo '<td>';
		if ( $option['js'] === '' ) {
			echo esc_html__( 'The syntax is not checked!', 'extensions-leaflet-map' ) . '<br>';
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<input type="text" name="leafext_waypoints[' . $i . '][js]"
		placeholder=' . "'" . 'iconSize: [xx,xx], iconAnchor: [xx,xx], popupAnchor: [xx,xx],'
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		. "'" . ' value = "' . $option['js'] . '" size="80">';

		if ( $option['sym'] !== '' || $option['js'] !== '' ) {
			echo '</td></tr>';
			echo '<tr><th scope="row-title">' . esc_html__( 'Delete', 'extensions-leaflet-map' ) . '</th>';
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<td><input type="checkbox" name="leafext_waypoints[' . $i . '][delete]" value="1" />';
		}
		++$i;
		if ( $i < $count ) {
			echo '</td></tr>';
		}
	}
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_waypoints( $options ) {
	if ( ! empty( $_POST ) && check_admin_referer( 'leafext_elevation', 'leafext_elevation_nonce' ) ) {
		if ( isset( $_POST['submit'] ) ) {
			$wpts = array();
			foreach ( $options as $option ) {
				if ( $option['sym'] !== '' || $option['js'] !== '' ) {
					if ( $option['delete'] === 1 ) {
						continue;
					}
					$wpt = array();
					// $wpt['sym'] = str_replace('-', '', strtolower(sanitize_text_field ( $option['sym'] )));
					// $wpt['sym'] = strtolower(sanitize_text_field ( $option['sym'] ));
					$wpt['sym'] = $option['sym'];
					$wpt['css'] = str_replace( array( ' ', ',' ), array( '-', '\,' ), strtolower( $option['sym'] ) );
					$wpt['js']  = htmlspecialchars( $option['js'] );

					if ( array_search( $wpt['sym'], array_column( $wpts, 'sym' ), true ) === false ) {
						if ( $wpt['sym'] === '' ) {
							array_unshift( $wpts, $wpt );
						} else {
							$wpts[] = $wpt;
						}
					}
				}
			}
			return $wpts;
		}
		if ( isset( $_POST['delete'] ) ) {
			delete_option( 'leafext_waypoints' );
		}
		return false;
	}
}

// Erklaerung / Hilfe
function leafext_waypoints_help_text() {
	if ( is_singular() || is_archive() ) {
		$codestyle = '';
	} else {
		leafext_enqueue_admin();
		$codestyle = ' class="language-coffeescript"';
	}
	$text = '';
	if ( ! ( is_singular() || is_archive() ) ) {
		$text = $text . '<p>' . __( 'Here you can define extra waypoint options.', 'extensions-leaflet-map' ) . '</p>';
		// } else {
	}

	$text = $text . '<p>' . sprintf(
		/* translators: %s are shortcodes. */
		__(
			'You can of course use %1$s and define waypoints with additional %2$s and %3$s shortcodes.',
			'extensions-leaflet-map'
		),
		'<code>&#091;elevation ... waypoints=0 ...]</code>',
		'<code>leaflet-marker</code>',
		'<code>leaflet-extramarker</code>'
	) . '</p>';

	$text = $text . '<h3>' . __( 'Introduction', 'extensions-leaflet-map' ) . '</h3>';
	$text = $text . __( 'To change the display of waypoints in the elevation profile, 3 things are important:', 'extensions-leaflet-map' );
	$text = $text . '<ul>
	<li>' . __( 'the waypoint in the track file,', 'extensions-leaflet-map' ) . '</li>
	<li>' . __( 'css describing the waypoint, e.g. the definition of the icon url,', 'extensions-leaflet-map' ) . '</li>
	<li>' . __( 'and Javascript to describe the icon.', 'extensions-leaflet-map' ) . '</li>
</ul>';

	$text = $text . '<h3>Shortcode</h3>
<pre' . $codestyle . '><code' . $codestyle . '>&#091;leaflet-map ....]
&#091;elevation gpx="url_gpx_file" waypoints=1 wptIcons=defined ...]
//or
&#091;elevation gpx="url_gpx_file" waypoints=markers wptIcons=defined ...]
</code></pre>';

	if ( is_singular() || is_archive() ) {
		$text = $text . '<p>' . sprintf(
			/* translators: %s is a href. */
			__( 'Or set this in the %1$selevation settings%2$s.', 'extensions-leaflet-map' ),
			'<a href="' . get_site_url() . '/elevation/wpts/">',
			'</a>'
		);
	} else {
		$text = $text . '<p>' . sprintf(
			/* translators: %s is a href. */
			__( 'Or set this in the %1$selevation settings%2$s.', 'extensions-leaflet-map' ),
			'<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=elevation#markers">',
			'</a>'
		);
	}
	$text = $text . '</p>';
	$text = $text . '<h3>' . __( 'Waypoint specified in file', 'extensions-leaflet-map' ) . '</h3>';
	$text = $text . 'GPX: <pre>&lt;sym&gt;<span style="color: #4f94d4">' . __( 'Text of GPS symbol name', 'extensions-leaflet-map' ) . '</span>&lt;/sym&gt;</pre>';
	$text = $text . 'Geojson: <pre>"properties": {
  "name": "...",
  "desc": "...",
  "sym": "<span style="color: #4f94d4">' . __( 'Text of GPS symbol name', 'extensions-leaflet-map' ) . '</span>"
},</pre>';

	$text = $text . '<h3>' . __( 'The waypoint CSS class Selector', 'extensions-leaflet-map' ) . '</h3>';

	$text = $text . '<ul>

<li>'
	. sprintf(
		/* translators: %s is code. */
		__( 'CSS to define as HTML block (between %1$s and %2$s) or in css file', 'extensions-leaflet-map' ),
		'<code>&lt;style&gt;</code>',
		'<code>&lt;/style&gt;</code>'
	) . '</li>

<li>'
	. __(
		'Must be defined for each waypoint, also for the default.',
		'extensions-leaflet-map'
	) . '</li>

<li>'
	. __(
		'You can use any css describing the waypoint.',
		'extensions-leaflet-map'
	) . '</li>

<li>'
	. sprintf(
		/* translators: %s is styling. */
		__(
			'Any blank character from %1$sText of GPS symbol name%2$s is converted to a minus sign, uppercase to lowercase, a comma will be escaped.',
			'extensions-leaflet-map'
		),
		'<span style="color: #4f94d4">',
		'</span>'
	) . '</li>

<li>'
	. __( 'e.g.', 'extensions-leaflet-map' )
	. ' "<span style="color: #4f94d4">Flag, Blue</span>" --&gt; "<span style="color: #d63638">flag\,-blue</span>"</li>

<li>'
	. sprintf(
		/* translators: %s are special characters. */
		__(
			'If you need more special characters than %s for your waypoints, please ask in the forum.',
			'extensions-leaflet-map'
		),
		' <code>- ,</code> '
	) . '</li>

</ul>';

	$text = $text . '<h4>' . __( 'Example', 'extensions-leaflet-map' ) . '</h4>';
	$text = $text . '<pre>.elevation-waypoint-icon.<span style="color: #d63638">waypoint-css</span>:before {
	background: url(https://my-domain.tld/path/to/icon.png) no-repeat 50%/contain;
}</pre>';

	// waypoint-css: -?[_a-zA-Z]+[_a-zA-Z0-9-]* anderes escapen
	$text = $text . '<h3>' . __( 'Generated Javascript', 'extensions-leaflet-map' ) . '</h3>';
	$text = $text .
	__( 'More options see', 'extensions-leaflet-map' );
	$text = $text . ' <a href="https://leafletjs.com/reference.html#divicon">Leaflet API reference</a>';
	$text = $text . '<pre>';
	$text = $text . 'wptIcons: {
  "<span style="color: #d63638">waypoint-css</span>": L.divIcon({
  className: "elevation-waypoint-marker",
  html: &apos;&lt;i class="elevation-waypoint-icon <span style="color: #d63638">waypoint-css</span>"&gt;&lt;/i&gt;&apos;,
  <span style="color: #00a32a">iconSize: [xx,xx],
  iconAnchor: [xx,xx],
  popupAnchor: [xx,xx],
  ...</span>
 }),
},';
	$text = $text . '</pre>';
	if ( ! ( is_singular() || is_archive() ) ) {
		$text = $text . '<h3>' . __( 'Settings', 'extensions-leaflet-map' ) . '</h3>';
	}
	if ( is_singular() || is_archive() ) {
		return $text;
	} else {
		echo wp_kses_post( $text );
	}
}
