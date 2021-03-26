<?php
// Admin Menu

add_action('admin_init', 'leafext_init' );
add_action('admin_menu', 'leafext_add_page', 99);

// Init plugin options to white list our options
function leafext_init(){
	register_setting( 'leafext_options', 'leafext_values', 'leafext_validate' );
}

// Add menu page
function leafext_add_page() {
	//add_options_page('Extensions for Leaflet Map Options', 'Extensions for Leaflet Map', 'manage_options', 'extensions-leaflet-map', 'leafext_do_page');
	//Add Submenu
	add_submenu_page( 'leaflet-map', 'Extensions for Leaflet Map Options', 'Extensions for Leaflet Map',
    'manage_options', 'extensions-leaflet-map', 'leafext_do_page');
}

// Draw the menu page itself
function leafext_do_page() {
	//var_dump($options,$options[theme]);
	$colors = array("lime","steelblue","purple","yellow","red","magenta","lightblue","other");
	?>
	<div class="wrap">
	<h2>Extensions for Leaflet Map Options</h2>
	<h3>Elevation Theme</h3>
	<form method="post" action="options.php">
		<?php
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
			<form method="post" action="options.php">
			<?php
			echo '<select name="leafext_values[theme]">';
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
			<td><input type="text" name="leafext_values[othertheme]" value="<?php echo $options['othertheme']; ?>" /></td>
		</tr>
		</table>
		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
	<?php
	echo "
<p>If you want use an own style, you can it do so:</p>

<p>Select other theme in the Options Page and give it a name.</p>

<p>Put in your functions.php following code:</p>

<pre>
//Shortcode: [elevation]
function leafext_custom_elevation_function(){
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
</pre>

In your elevation.css put the styles like the theme styles in
<a href='https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.css'>https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.css</a>
";?>
	</div>
	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate($input) {
	// Say our second option must be safe text with no HTML tags
	$input['othertheme'] =  wp_filter_nohtml_kses($input['othertheme']);
	return $input;
}
