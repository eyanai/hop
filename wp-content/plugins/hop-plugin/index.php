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
define( 'WP_DEBUG', true );

require_once('ajax.php');
require_once('formBuilder.php');
require_once('galleryProcces.php');
require_once('mail.php');

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



add_action('transition_post_status', 'my_function', 10, 3);
function my_function($new_status, $old_status, $post ) {
    if ( $new_status != $old_status ) {
        // Post status changed
		update_post_meta($post->ID, 'wpcf-user_firstname', 'publisaed');
    }
}


function authorNotification ($post_id) {
	global $wpdb;
/*	$post = get_post($post_id);
	$author = get_userdata($post->post_author);
	$message = "
	Hi ".$author->display_name.",
	Your post, ".$post->post_title." has been reviewed and published.";
	wp_mail($author->user_email, "Your article status", $message);*/
	$statyus=get_post_status( $post_id );
	$ststusPost=get_post_meta($post_id, 'post_report',true);
	
	
	update_post_meta($post_id, 'post_report', $statyus);
	
		$userMail=get_post_meta($post_id,'wpcf-parent_email',true);
			$userName=get_post_meta($post_id,'wpcf-user_parent',true);
			  $message = " שלום ".$userName.",התמונה, ".$post->post_title." פורסמה לפי שיקולי המערכת ".get_permalink( $post_id ).". בהצלחה!";
		   if($userMail) {
			   wp_mail($userMail, "התמונה שלך פורסמה !!!", $message);
				update_post_meta($post_id, 'post_report', 'mail send');
		   }
		
		
				
		
	
}

add_action('publish_user-gallery', 'authorNotification');

