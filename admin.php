<?php
// Admin Menu

add_action('admin_init', 'wp_leaflet_ext_init' );
add_action('admin_menu', 'wp_leaflet_ext_add_page');

// Init plugin options to white list our options
function wp_leaflet_ext_init(){
	register_setting( 'wp_leaflet_ext_options', 'wp_leaflet_ext', 'wp_leaflet_ext_validate' );
}

// Add menu page
function wp_leaflet_ext_add_page() {
	add_options_page('Extensions for Leaflet Map Options', 'Extensions for Leaflet Map', 'manage_options', 'wp_leaflet_ext_opts', 'wp_leaflet_ext_do_page');
}

// Draw the menu page itself
function wp_leaflet_ext_do_page() {
	//var_dump($options,$options[theme]);
	$colors = array("lime","steelblue","purple","yellow","red","magenta","lightblue","other");
	?>
	<div class="wrap">
	<h2>Extensions for Leaflet Map Options</h2>
	<h3>Elevation Theme</h3>
	<form method="post" action="options.php">
		<?php
		settings_fields('wp_leaflet_ext_options');
		$options = get_option('wp_leaflet_ext');
		?>
		<table class="form-table">
		<tr valign="top"><th scope="row">Theme</th>
			<td>
			<form method="post" action="options.php">
			<?php
			echo '<select name="wp_leaflet_ext[theme]">';
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
			<td><input type="text" name="wp_leaflet_ext[othertheme]" value="<?php echo $options['othertheme']; ?>" /></td>
		</tr>
		</table>
		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
	<?php if ($options['othertheme'] != "") echo 'For creating own styles please see
	<a href="https://github.com/hupe13/wp-leaflet-extensions/blob/main/CustomElevation.md"
	>CustomElevation.md</a>';?>
	</div>
	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function wp_leaflet_ext_validate($input) {
	// Say our second option must be safe text with no HTML tags
	$input['othertheme'] =  wp_filter_nohtml_kses($input['othertheme']);
	return $input;
}
