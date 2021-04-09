<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$option_names = array ('leafext_values', 'leafext_maps');
foreach ( $otion_names as $option_name ) {
	delete_option($option_name);
	// for site options in Multisite
	delete_site_option($option_name);
}
