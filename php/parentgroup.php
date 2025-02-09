<?php
/**
 * Functions for shortcode parentgroup of leaflet-featuregroup and leaflet-optiongroup
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

function leafext_parentgroup_script( $parentgroup, $childs, $grouptext, $expandall, $collapseall, $closedsymbol, $openedsymbol ) {
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
		let closedSymbol = <?php echo wp_json_encode( $closedsymbol ); ?>;
		let openedSymbol = <?php echo wp_json_encode( $openedsymbol ); ?>;

		leafext_parentgroup_js(parent,childs,grouptext,expandall,collapseall,closedSymbol,openedSymbol);
	});
	<?php
	$javascript = ob_get_clean();
	$text       = $text . $javascript . '//-->' . "\n" . '</script>';

	$text = \JShrink\Minifier::minify( $text );
	return "\n" . $text . "\n";
}

function leafext_parentgroup_params() {
	$params = array(
		array(
			'param'   => 'parent',
			'desc'    => __( 'Name of the parent', 'extensions-leaflet-map' ),
			'default' => '',
			'size'    => 0,
		),
		array(
			'param'    => 'childs',
			'desc'     => __( 'child names', 'extensions-leaflet-map' ),
			'moredesc' => sprintf(
				/* translators: %s is option groups. */
				__( 'Comma separated group names. These are the same as in %s. If you have html tags there, you can omit these here', 'extensions-leaflet-map' ),
				'<code>groups</code>'
			) . '.',
			'default'  => '',
			'size'     => 0,
		),
		array(
			'param'    => 'collapseall',
			'desc'     => __( 'Text for an entry in control that collapses the tree', 'extensions-leaflet-map' ),
			'moredesc' => __( 'If empty, no entry is created.', 'extensions-leaflet-map' ),
			'default'  => '',
			'size'     => 50,
		),
		array(
			'param'    => 'expandall',
			'desc'     => __( 'Text for an entry in control that expands the tree', 'extensions-leaflet-map' ),
			'moredesc' => __( 'If empty, no entry is created.', 'extensions-leaflet-map' ),
			'default'  => '',
			'size'     => 50,
		),
		array(
			'param'    => 'closedsymbol',
			'desc'     => __( 'Symbol displayed on a opened node (that you can click to close)', 'extensions-leaflet-map' ),
			/* translators: %s is &lt;. */
			'moredesc' => sprintf( __( "You can't use the character %s allone.", 'extensions-leaflet-map' ), '<code>&lt;</code>' ),
			'default'  => '<i class="fas fa-chevron-right"></i>&nbsp; ',
			'size'     => 50,
		),
		array(
			'param'    => 'openedsymbol',
			'desc'     => __( 'Symbol displayed on a closed node (that you can click to open)', 'extensions-leaflet-map' ),
			'moredesc' => '',
			'default'  => '<i class="fas fa-chevron-down"></i>&nbsp; ',
			'size'     => 50,
		),
		array(
			'param'    => 'padding',
			'desc'     => __( 'padding of the subentries', 'extensions-leaflet-map' ),
			'moredesc' => __( 'Valid for all maps on the page/post.', 'extensions-leaflet-map' ),
			'default'  => '10px',
			'size'     => 10,
		),
		// array(
		// 'param'   => '',
		// 'desc'    => __( '', 'extensions-leaflet-map' ),
		// 'default' => '',
		//
		// ),
	);
	return $params;
}

function leafext_parentgroup_settings() {
	$defaults = array();
	$params   = leafext_parentgroup_params();
	foreach ( $params as $param ) {
		$defaults[ $param['param'] ] = $param['default'];
	}
	$options = shortcode_atts(
		$defaults,
		get_option( 'leafext_parentgroup' )
	);
	return $options;
}

function leafext_parentgroup_function( $atts, $content, $shortcode ) {
	$text = leafext_should_interpret_shortcode( $shortcode, $atts );
	if ( $text !== '' ) {
		return $text;
	} else {
		// var_dump( $atts ); //wp_die();
		leafext_enqueue_controltree();
		leafext_enqueue_awesome();
		$options = shortcode_atts(
			leafext_parentgroup_settings(),
			leafext_clear_params( $atts )
		);

		$parentgroup  = sanitize_text_field( $options['parent'] );
		$expandall    = sanitize_text_field( $options['expandall'] );
		$collapseall  = sanitize_text_field( $options['collapseall'] );
		$closedsymbol = $options['closedsymbol'];
		$openedsymbol = $options['openedsymbol'];

		if ( $options['padding'] !== '10px' ) {
			$text = '<style> .leaflet-layerstree-children:not(.leaflet-layerstree-children-nopad) {padding-left:' . $options['padding'] . ';}</style>';
		} else {
			$text = '';
		}

		$childs = array();
		foreach ( array_map( 'trim', explode( ',', $options['childs'] ) ) as $group ) {
			$childs[] = trim( wp_strip_all_tags( $group ) );
		}

		global $leafext_group_menu;
		return $text . leafext_parentgroup_script( $parentgroup, $childs, $leafext_group_menu, $expandall, $collapseall, $closedsymbol, $openedsymbol );
	}
}
add_shortcode( 'parentgroup', 'leafext_parentgroup_function' );
add_shortcode( 'leaflet-parentgroup', 'leafext_parentgroup_function' );
