<?php
/**
 * Functions for shortcode parentgroup of leaflet-featuregroup and leaflet-optiongroup
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_parentgroup_script( $parentgroup, $childs, $visible ) {
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		let parent = <?php echo wp_json_encode( $parentgroup ); ?>;
		let childs = <?php echo wp_json_encode( $childs ); ?>;
		let visible = <?php echo wp_json_encode( $visible ); ?>;
		leafext_parentgroup_js(parent,childs,visible);
	});
	<?php
	$javascript = ob_get_clean();
	$text       = $text . $javascript . '//-->' . "\n" . '</script>';

	$text = \JShrink\Minifier::minify( $text );
	return "\n" . $text . "\n";
}

function leafext_parentgroup_function( $atts, $content, $shortcode ) {
	$text = leafext_should_interpret_shortcode( $shortcode, $atts );
	if ( $text != '' ) {
		return $text;
	} else {
		// var_dump($atts); wp_die();
		leafext_enqueue_leafext( 'parentgroup', 'leafext_featuregroup' );
		$options = shortcode_atts(
			array(
				'parent'  => '',
				'childs'  => '',
				'visible' => false,
			),
			leafext_clear_params( $atts )
		);

		$parentgroup = sanitize_text_field( $options['parent'] );
		$childs      = array_map( 'trim', explode( ',', sanitize_text_field( $options['childs'] ) ) );

		if ( $options['visible'] === false ) {
			$cl_on = array_fill( 0, count( $childs ), '1' );
		} else {
			$cl_on = array_map( 'trim', explode( ',', $options['visible'] ) );
			if ( count( $cl_on ) == 1 ) {
				$cl_on = array_fill( 0, count( $childs ), '0' );
			} elseif ( count( $childs ) != count( $cl_on ) ) {
					$text = "['.$shortcode.' ";
				foreach ( $atts as $key => $item ) {
					$text = $text . "$key=$item ";
				}
					$text = $text . ' - groups and visible do not match. ';
					$text = $text . ']';
					return $text;
			}
		}

		$visible = array_combine( $childs, $cl_on );

		return leafext_parentgroup_script( $parentgroup, $childs, $visible );
	}
}
add_shortcode( 'parentgroup', 'leafext_parentgroup_function' );
