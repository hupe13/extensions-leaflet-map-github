<?php
// Admin Menu

add_action('admin_init', 'leafext_init' );
add_action('admin_menu', 'leafext_add_page', 99);

// Init plugin options to white list our options
function leafext_init(){
	add_option( 'leafext_values' );
	register_setting( 'leafext_options', 'leafext_values', 'leafext_validate_elevationtheme' );
	add_option( 'leafext_maps' );
	register_setting( 'leafext_options', 'leafext_maps', 'leafext_validate_mapswitch' );
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_elevationtheme($input) {
	// Say our second option must be safe text with no HTML tags
	$input['othertheme'] =  wp_filter_nohtml_kses($input['othertheme']);
	return $input;
}
function leafext_validate_mapswitch($options) {
	//
	$maps = array();
	foreach ($options as $option) {
		if ( $option['mapid'] !="" && $option['attr'] !="" && $option['tile'] != "" ) {
			$map=array();
			$map['mapid'] = sanitize_text_field ( $option['mapid'] );
			$map['attr'] = sanitize_text_field ( $option['attr'] );
			$map['tile'] = sanitize_text_field ( $option['tile'] );
			$maps[]=$map;
		}
	}
	return $maps;
}

// Add menu page
function leafext_add_page() {
	//Add Submenu
	add_submenu_page( 'leaflet-map', 'Extensions for Leaflet Map Options', 'Extensions for Leaflet Map',
    'manage_options', 'extensions-leaflet-map-main', 'leafext_do_page');

	// Adds my_help_tab when my_admin_page loads / Noch nicht das Gelbe vom Ei.
	add_action( 'load-leaflet-map_page_extensions-leaflet-map-main', 'leafext_elevation_help' );
	add_action( 'load-leaflet-map_page_extensions-leaflet-map-main', 'leafext_tilelayer_help' );
}

// Draw the menu page itself
function leafext_do_page() {
	//var_dump($options,$options[theme]);
	?>
	<div class="wrap">
	<h2>Extensions for Leaflet Map Options</h2>

	<?php
	if( isset( $_GET[ 'tab' ] ) ) {
    $active_tab = $_GET[ 'tab' ];
	} else {
		$active_tab = 'elevation_options';
	}// end if
	?>

	<h3 class="nav-tab-wrapper">
  	<a href="?page=extensions-leaflet-map-main&tab=elevation_options" class="nav-tab <?php echo $active_tab == 'elevation_options' ? 'nav-tab-active' : ''; ?>">Elevation Theme</a>
    <a href="?page=extensions-leaflet-map-main&tab=tilelayers_options" class="nav-tab <?php echo $active_tab == 'tilelayers_options' ? 'nav-tab-active' : ''; ?>">Switching Tilelayers</a>
	</h3>


	<form method="post" action="options.php">
		<?

		if( $active_tab == 'elevation_options' ) {
			echo '<h3>Elevation Theme</h3>
			<div class="wrap">';
		settings_fields('leafext_options');
		$options = get_option('leafext_values');
		if ( ! $options ) {
			$options = array(
				"theme" => "lime",
				"othertheme" => "" );
			}
		?>
		<table class="form-table">
		<tr valign="top"><th scope="row">Theme</th>
			<td>
			<?php
			echo '<select name="leafext_values[theme]">';
			$colors = array("lime","steelblue","purple","yellow","red","magenta","lightblue","other");
			foreach ($colors as $color) {
				if ($color == $options['theme']) {
					echo '<option selected ';
				} else {
					echo '<option ';
				}
				echo 'value="'.$color.'">'.$color.'</option>';
			}
			echo '</select>';
			?>
			</td>
		</tr>
		<tr valign="top"><th scope="row">Other Theme</th>
			<td><input type="text" name="leafext_values[othertheme]" value="<?php echo $options['othertheme']; ?>" />  (see help)</td>
		</tr>
		</table>
	</div>
<?php } else if ( $active_tab == 'tilelayers_options' ) { ?>
	<div class="wrap">
	<h3>Switching Tilelayers</h3>

		<?php
		settings_fields('leafext_options');
		$options = get_option('leafext_maps');
		//echo '<pre>';var_dump($options);echo '</pre>';
		if ( ! $options ) $options = array();
		$map=array(
			"mapid" => "",
			"attr" => "" ,
			"tile" => "");
		$options[]=$map;
		?>

		<table class="form-table">
			<?php
			$i=0;
			foreach ($options as $option) {
				echo '<tr><td>mapid:</td><td><input class="full-width" type="text" placeholder="mapid" name="leafext_maps['.$i.'][mapid]" value="'.$option['mapid'].'" /></td></tr>';
				echo '<tr><td>Attribution:</td><td><input type="text" size="80" placeholder="Copyright" name="leafext_maps['.$i.'][attr]" value="'.$option['attr'].'" /></td></tr>';
				echo '<tr><td>Tile server:</td><td><input type="url"  size="80" placeholder="https://{s}.tile.server.tld/{z}/{x}/{y}.png" name="leafext_maps['.$i.'][tile]" value="'.$option['tile'].'" /></td></tr>';
				$i++;
			}
			?>
		</table>
 </div>
	<?php } ?>

		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
	</div>
	<?php
}

///
function leafext_elevation_help () {
    $screen = get_current_screen();
    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'    => 'elevation',
        'title' => __('Elevation Theme'),
        'content'   => "<p>".__('If you want use an own style, you can it do so:</p>
				<p>Select other theme in the Options Page and give it a name.</p>
				<p>Put in your functions.php following code:',"extensions-leaflet-map")."</p>
<pre>
//Shortcode: [elevation]
function leafext_custom_elevation_function() {
	// custom
	wp_enqueue_style( 'elevation_mycss',
		'url/to/css/elevation.css', array('elevation_css'));
}
add_filter('pre_do_shortcode_tag', function ( &#36;output, &#36;shortcode ) {
	if ( 'elevation' == &#36;shortcode ) {
		leafext_custom_elevation_function();
	}
	return &#36;output;
}, 10, 2);
</pre>".
				__("In your elevation.css put the styles like the theme styles in
				<a href='https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.css'>https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.css</a>","extensions-leaflet-map")
    ) );
}

function leafext_tilelayer_help () {
    $screen = get_current_screen();
    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'    => 'tilelayer',
        'title' => __('Tilelayers'),
        'content'   => "<p>".__("Configure a short name (mapid), attribution and a tile url for each map tile service. To delete a service simply clear the values.","extensions-leaflet-map")."</p>"
    ) );
}
