<?php
/**
 * Functions for layerswitch shortcode, parameter providers
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_providers_registration () {
	$tiles = array(
	  array(
	    'name' => 'HEREv3',
	    'keys' => array(
	      'apiKey' => '<insert apiKey here>',
	    ),
	  ),
	  array(
	    'name' => 'HERE',
	    'keys' => array(
	      'app_id' => '<insert ID here>',
	      'app_code' =>  '<insert ID here>',
	    ),
	  ),
	  array(
	    'name' => 'Jawg',
	    'keys' => array(
	      'variant' =>  '<insert map id here or blank for default variant>',
	      'accessToken' =>  '<insert access token here>',
	    ),
	  ),
	  array(
	    'name' => 'MapBox',
	    'keys' => array(
	      'id' =>  '<insert map_ID here>',
	      'accessToken' =>  '<insert ACCESS_TOKEN here>',
	    ),
	  ),
	  array(
	    'name' => 'MapTiler',
	    'keys' => array(
	      'key' =>  '<insert key here>',
	    ),
	  ),
	  array(
	    'name' => 'Thunderforest',
	    'keys' => array(
	      'apikey' =>  '<insert api_key here>',
	    ),
	  ),
	  array(
	    'name' => 'TomTom',
	    'keys' => array(
	      'apikey' =>  '<insert your API key here>',
	    ),
	  ),
	  array(
	    'name' => 'GeoportailFrance',
	    'keys' => array(
	      'variant' =>  '<insert resource ID here>',
	      'apikey' =>  '<insert api key here>',
	    ),
	  ),
	);
	return $tiles;
}

function leafext_providers_regnames () {
	$tiles = leafext_providers_registration ();
	return array_column($tiles, 'name');
}
