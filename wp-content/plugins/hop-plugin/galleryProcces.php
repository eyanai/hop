<?php
add_action( 'i_am_hook', 'galley_form');
add_action( 'i_hook','gm');


function gm($arg){

}


function galley_form($postType='user-gallery',$postCat='gallery_cat'){
	if(!isset($_POST['userSubmit'])){
		gallery_form_add();
	}else{
		
				$all=array();
					$Uname=filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING);
					$Ulastname=filter_input(INPUT_POST,'userlastname',FILTER_SANITIZE_STRING);
					$age=filter_input(INPUT_POST,'userage',FILTER_SANITIZE_NUMBER_INT);
					$Pname=filter_input(INPUT_POST,'parentname',FILTER_SANITIZE_STRING);
					$Plastname=filter_input(INPUT_POST,'parentlastname',FILTER_SANITIZE_STRING);
					$Pid=filter_input(INPUT_POST,'parentid',FILTER_SANITIZE_STRING);
					$phone=filter_input(INPUT_POST,'phone',FILTER_SANITIZE_NUMBER_INT);
					$email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
					$city=filter_input(INPUT_POST,'city',FILTER_SANITIZE_STRING);
					$street=filter_input(INPUT_POST,'street',FILTER_SANITIZE_STRING);
					$apartment=filter_input(INPUT_POST,'apartment',FILTER_SANITIZE_STRING);
					$zipcode=filter_input(INPUT_POST,'zipcode',FILTER_SANITIZE_STRING);
					$filetype=filter_input(INPUT_POST,'filetype',FILTER_SANITIZE_STRING);
					$galcat=filter_input(INPUT_POST,'galcat',FILTER_SANITIZE_STRING);
					$school=filter_input(INPUT_POST,'userSchool',FILTER_SANITIZE_STRING);
					$userCalss=filter_input(INPUT_POST,'userCalss',FILTER_SANITIZE_STRING);
					$message=filter_input(INPUT_POST,'message',FILTER_SANITIZE_STRING);
					$agree=filter_input(INPUT_POST,'agree',FILTER_SANITIZE_STRING);
					
	echo "<hr>".$galcat."<hr>";
	
	
					
				$titel=$Uname." ".$Ulastname;
				if(!empty($_FILES['name'])){
					$titel.=" ".$_FILES['name'];
					}
				
					
				//insert a new post and get the post id
				$my_post = array(
				  'post_type' => $postType,
				  'post_status'=> 'pending',
				  'post_title'  =>$titel
				);
				// Insert the post into the database
				$postid=wp_insert_post( $my_post );
				
				//insert to terms 
				wp_set_object_terms( $postid,$galcat,$postCat );
						
				//insert castoum filde to the post type
				update_post_meta($postid, 'wpcf-user_firstname', $Uname);
				update_post_meta($postid, 'wpcf-user_lastname', $Ulastname);
				update_post_meta($postid, 'wpcf-user_age', $age);
				update_post_meta($postid, 'wpcf-user_parent', $Pname);
				update_post_meta($postid, 'wpcf-user_parent_lastname', $Plastname);
				update_post_meta($postid, 'wpcf-parent_id', $Pid);
				update_post_meta($postid, 'wpcf-parent_phone', $phone);
				update_post_meta($postid, 'wpcf-parent_email', $email);
				update_post_meta($postid, 'wpcf-apartment', $apartment);
				update_post_meta($postid, 'wpcf-city', $city);
				update_post_meta($postid, 'wpcf-street', $street); 
				update_post_meta($postid, 'wpcf-zipcode', $zipcode); 
				update_post_meta($postid, 'wpcf-user_file_type', $filetype); 
				update_post_meta($postid, 'wpcf-usercalss', $userCalss); 
				update_post_meta($postid, 'wpcf-school', $school); 
				update_post_meta($postid, 'wpcf-message', $message); 
				update_post_meta($postid, 'wpcf-agree', $agree);
				
				
				//for email tracikng
				add_post_meta($postid, 'post_report', 'pending');
				
				///file uplode
				
				$images = $_FILES['file'];
					
					echo "<pre id='yanai'>".print_r($images,1)."</pre>";
					echo "yanai --->the titel : ---- : ".$Uname." ".$Ulastname;
					
					//Upload Images
					if($images){
					 require_once(ABSPATH . "wp-admin" . '/includes/image.php');
					 require_once(ABSPATH . "wp-admin" . '/includes/file.php');
					 require_once(ABSPATH . "wp-admin" . '/includes/media.php');
					foreach($_FILES as $field => $file){
						$filename=$_FILES["file"]["name"];
						$uploadedfile = $_FILES['file'];
							$upload_overrides = array( 'test_form' => false );
							$uploaded_file  = wp_handle_upload( $uploadedfile, $upload_overrides );
								if ( $uploaded_file ) {
									$attachment = array(
									'post_title' => $file['name'],
									'post_content' => '',
									'post_type' => 'attachment',
									'post_parent' => $post->ID,
									'post_mime_type' => $file['type'],
									'guid' => $uploaded_file['url']
									);
			
									echo "<pre style='position:absolute;z-index:500;'>".$uploaded_file['file']."    ------------------ move file ---    <br> ";
									echo $uploaded_file['url']."    ------------------ move URl ---   </pre>  ";
									 //Create an Attachment in WordPress
									$id = wp_insert_attachment( $attachment,$uploaded_file['file'], $postid );	
									
									update_post_meta($postid,'wpcf-user_img', $uploaded_file['url']);
			
										//Remove it from the array to avoid duplicating during autosave/revisions.
										unset($_FILES[$field]);
			
									var_dump( $uploaded_file);
								} else {
									echo "<span id='respons' style='position:absolute;z-index:500;'>filed</span>";
								}
						}//end forech
						echo "<span id='respons'>ok</span>";
					}
					echo "<span id='respons'>ok</span>";
			}//end if isset

}
