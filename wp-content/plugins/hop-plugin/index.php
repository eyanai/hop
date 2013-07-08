<?php
/*
Plugin Name: hop cannel plugin by yanai
Plugin URI: 
Description: This plugin does things you never thought were possible NEEDED FOR TYPES PLUGIN!!!.
Author: yanai edri
Version: 1.0
Author URI: http://www.cambium.co.il/

Copyright 2013  yanai edri
*/


require_once('ajax.php');
require_once('formBuilder.php');
require_once('galleryProcces.php');
/* Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
 */
add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );

/**
 * Enqueue plugin style-file
 */
function prefix_add_my_stylesheet() {
	// Respects SSL, Style.css is relative to the current file
	wp_register_style( 'prefix-style', plugins_url('/css/main.css', __FILE__) );
	wp_enqueue_style( 'prefix-style' );
}







