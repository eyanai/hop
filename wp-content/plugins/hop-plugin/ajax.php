<?php
wp_enqueue_script( 'ajax-script', plugins_url().'/hop-plugin/js/form.js', array('jquery'), 1.0 ); // jQuery will be included automatically
	wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ); // setting ajaxur
	
	add_action( 'wp_ajax_dom_cilck', 'dom' ); // ajax for logged in users
	add_action( 'wp_ajax_nopriv_dom_cilck', 'dom' ); // ajax for not logged in users
	
	
	add_action( 'wp_ajax_cat_sel', 'cat_sel_func' ); // ajax for logged in users
	add_action( 'wp_ajax_nopriv_cat_sel', 'cat_sel_func' ); // ajax for not logged in users
	
	function cat_sel_func(){
		$cat=$_POST['catSel'];
	//	echo $cat." selected<br>";
		
		$args = array(
		'posts_per_page'  => 1,
		'offset'          => 0,
		'gallery_cat'        =>$cat,
		'orderby'         => 'post_date',
		'order'           => 'DESC',
		'include'         => '',
		'exclude'         => '',
		'meta_key'        => '',
		'meta_value'      => '',
		'post_type'       => 'forms',
		'post_mime_type'  => '',
		'post_parent'     => '',
		'post_status'     => 'publish',
		'suppress_filters' => true ); 
	
		$posts_array = get_posts($args);
		foreach($posts_array as $post){
			$meta = get_post_meta($post->ID);
		//	echo print_r($meta,1);
			$needs=get_post_meta($post->ID,'wpcf-required_field',true);
			$req=array();
			$i=0;
			foreach ($needs as $key => $value) {
					$req[$value]=$value;
					$i++;
			}
		//	echo print_r($req,1);
			echo(json_encode($req));
			//gallery_form_add($toxsonomy,$req);  
			//galley_form();   
		};
	
	die;
	}
	
	
	function dom(){
		//gm();
		//galley_form('goProcess');
	}
	