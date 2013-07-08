<?php 

function authorNotification($post_id) {
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

add_action('publish_post', 'authorNotification');