<?php
	
	// Add RSS links to <head> section
	automatic_feed_links();
	
	// Load jQuery
    if (!is_admin()) {
    
    	wp_deregister_script('jquery'); // Deregister WordPress jQuery
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js', array(), '1.9.1'); 		
		// Google CDN jQuery
			wp_enqueue_script('jquery'); // Enqueue it!
			
			wp_register_script('conditionizr', 'http://cdnjs.cloudflare.com/ajax/libs/conditionizr.js/2.2.0/conditionizr.min.js', array(), '2.2.0'); // Conditionizr
			wp_enqueue_script('conditionizr'); // Enqueue it!
			
			wp_register_script('modernizr', 'http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js', array(), '2.6.2'); // Modernizr
			wp_enqueue_script('modernizr'); // Enqueue it!
			
			wp_register_script('hopScript', get_template_directory_uri() . '/js/scripts.js', array(), '1.0.0'); // Custom scripts
			wp_enqueue_script('hopScript'); // Enqueue it!
			
			do_action('wp_enqueue_scripts');
		
		}
	
	
add_action( 'wp_enqueue_scripts', 'so_enqueue_scripts' );
	function so_enqueue_scripts(){
  		wp_register_script( 'ajaxHandle', get_template_directory() . '/js/scripts.js', array('jquery'), false, true );
  		wp_enqueue_script( 'ajaxHandle' );
  	//	wp_localize_script( 'ajaxHandle', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin_ajax.php' ) ) );
	}
	
	
	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');
    
	// Declare sidebar widget zone
    if (function_exists('register_sidebar')) {
    	register_sidebar(array(
    		'name' => 'Sidebar Widgets',
    		'id'   => 'sidebar-widgets',
    		'description'   => 'These are widgets for the sidebar.',
    		'before_widget' => '<div id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</div>',
    		'before_title'  => '<h2>',
    		'after_title'   => '</h2>'
    	));
    }






@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );


function custom_taxonomies_terms_links() {
	global $post, $post_id;
	// get post by post id
	$post = &get_post($post->ID);
	// get post type by post
	$post_type = $post->post_type;
	// get post type taxonomies
	$taxonomies = get_object_taxonomies($post_type);
	foreach ($taxonomies as $taxonomy) {
		// get the terms related to post
		$terms = get_the_terms( $post->ID, $taxonomy );
		if ( !empty( $terms ) ) {
			$out = array();
			foreach ( $terms as $term )
				$out[] = '<a href="' .get_term_link($term->slug, $taxonomy) .'">'.$term->name.'</a>';
			$return = join( ', ', $out );
		}
	}
	return $return;
}


function get_previous_post_id($post_id) {
    // Get a global post reference since get_adjacent_post() references it
    global $post;
   // Store the existing post object for later so we don't lose it
    $oldGlobal = $post;
    // Get the post object for the specified post and place it in the global variable
    $post = get_post($post_id);
    // Get the post object for the previous post
    $previous_post = get_previous_post();
    // Reset our global object
    $post = $oldGlobal;

    if ( '' == $previous_post ) 
        return 0;

    return $previous_post->ID;
}


function get_next_post_id($post_id) {
    // Get a global post reference since get_adjacent_post() references it
    global $post;
   // Store the existing post object for later so we don't lose it
    $oldGlobal = $post;
    // Get the post object for the specified post and place it in the global variable
    $post = get_post($post_id);
    // Get the post object for the previous post
    $previous_post = get_next_post();
    // Reset our global object
    $post = $oldGlobal;

    if ( '' == $previous_post ) 
        return 0;

    return $previous_post->ID;
}

add_action( 'wp_ajax_next_post', 'nextPost' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_next_post', 'nextPost' ); // ajax for not logged in users

add_action( 'wp_ajax_pre_post', 'prePost' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_pre_post', 'prePost' ); // ajax for not logged in users

function nextPost($id){
	$id=$_POST['id'];
	$nextId=get_next_post_id($id);
	$meta = get_post_meta($nextId,'wpcf-user_img',true);
	//$name = get_post_meta($nextId,'wpcf-user_firstname',true);
	//$lastname=get_post_meta($nextId,'wpcf-user_lastname',true);
	$name=get_the_title($nextId);
	$city = get_post_meta($nextId,'wpcf-city',true);
	
	$arg=array('src'=>$meta,'postid'=>$nextId,'writer'=>$name. " - ".$city);
	echo json_encode($arg);
	die();
}

function prePost($id){
	$id=$_POST['id'];
	$preId=get_previous_post_id($id);
	$meta = get_post_meta($preId,'wpcf-user_img',true);
	//$name = get_post_meta($preId,'wpcf-user_firstname',true);
	//$lastname=get_post_meta($preId,'wpcf-user_lastname',true);
	$name=get_the_title($preId);
	$city = get_post_meta($preId,'wpcf-city',true);
	
	$arg=array('src'=>$meta,'postid'=>$preId,'writer'=>$name. " - ".$city);
	echo json_encode($arg);
	die();
}












/*function attchFile($id){	
		$args = array(
		'post_type' => 'attachment',
		'numberposts' => null,
		'post_status' => null,
		'post_parent' => $id// $post->ID
		); 
		
		$attachments = get_posts($args);
		if ($attachments) {
			foreach ($attachments as $attachment) {
				the_attachment_link($attachment->ID, false);
				echo apply_filters('the_title', $attachment->post_title);
			}
		}
	}

function attchImg($id){	
		$args = array(
		'post_type' => 'attachment',
		'numberposts' => null,
		'post_status' => null,
		'post_parent' => $id// $post->ID
		); 
		
		$attachments = get_posts($args);
		if ($attachments) {
			foreach ($attachments as $attachment) {
				echo wp_get_attachment_url($attachment->ID);
			}
		}
	}*/