<?php
/**
 * Functions for shortcode parentgroup of leaflet-featuregroup and leaflet-optiongroup
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_parentgroup_script( $parentgroup, $childs, $grouptext, $expandall, $collapseall ) {
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		let parent = <?php echo wp_json_encode( $parentgroup ); ?>;
		let childs = <?php echo wp_json_encode( $childs ); ?>;
		let grouptext = <?php echo wp_json_encode( $grouptext ); ?>;
		let expandall = <?php echo wp_json_encode( $expandall ); ?>;
		let collapseall = <?php echo wp_json_encode( $collapseall ); ?>;

		leafext_parentgroup_js(parent,childs,grouptext,expandall,collapseall);
	});
	<?php
	$javascript = ob_get_clean();
	$text       = $text . $javascript . '//-->' . "\n" . '</script>';

	$text = \JShrink\Minifier::minify( $text );
	return "\n" . $text . "\n";
}

function leafext_parentgroup_function( $atts, $content, $shortcode ) {
	$text = leafext_should_interpret_shortcode( $shortcode, $atts );
	if ( $text !== '' ) {
		return $text;
	} else {
		// var_dump($atts); wp_die();
		leafext_enqueue_controltree();
		leafext_enqueue_awesome();
		$options = shortcode_atts(
			array(
				'parent'      => '',
				'childs'      => '',
				'collapseall' => '',
				'expandall'   => '',
			),
			leafext_clear_params( $atts )
		);

		$parentgroup = sanitize_text_field( $options['parent'] );
		$expandall   = sanitize_text_field( $options['expandall'] );
		$collapseall = sanitize_text_field( $options['collapseall'] );

		$childs = array();
		foreach ( array_map( 'trim', explode( ',', $options['childs'] ) ) as $group ) {
			$childs[] = trim( wp_strip_all_tags( $group ) );
		}

		global $leafext_group_menu;

		return leafext_parentgroup_script( $parentgroup, $childs, $leafext_group_menu, $expandall, $collapseall );
	}
}
add_shortcode( 'parentgroup', 'leafext_parentgroup_function' );
