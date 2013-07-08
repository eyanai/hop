<?php 

/*function authorNotification($post_id) {
    $userMail=get_post_meta($post->ID,'wpcf-parent_email',true);
	$userName=get_post_meta($post->ID,'wpcf-user_parent',true);
	  $message = "
      שלום ".$userName.",
      התמונה, ".$post->post_title." פורסמה לפי שיקולי המערכת ".get_permalink( $post_id ).". בהצלחה!
   ";
   
   if($userMail) {
	   wp_mail($userMail, "התמונה שלך פורסמה !!!", $message);
   }
}

add_action('publish_post', 'function_to_perform');
add_action('publish_{user-gallery}', 'function_to_perform');

function  function_to_perform(){
	add_filter('the_title',function($con){
		return $con." --- pub";
		
	});
}
add_filter('the_title',function($con){
		return $con." --- pub";
		
	});*/