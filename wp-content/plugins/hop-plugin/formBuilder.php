<?php
add_action( 'gallery_form', 'gallery_form_add');
add_action( 'gallery_form_cat', 'cat_select');



function cat_select(){
$categories = get_categories(array('taxonomy'=>'gallery_cat'));
	echo "
				<select name=\"galcat\" id=\"galId\" title=\"קטגוריית גלרייה\">
				";
					foreach($categories as $cat){
						//echo "<pre>".print_r($cat,1)."</pre>";
					
	echo 	"<option value=".$cat->name." data-slug='{$cat->slug}'>{$cat->name}</option>";
				}
	echo    "</select>";
	
	gallery_form_add($toxsonomy,$req);

}




function gallery_form_add($toxsonomy='gallery_cat',$req=''){
//get the cange filde titel
		$pageTitle=get_the_title();
		$pageID=get_the_id();
 		$needs=get_post_meta($pageID,'wpcf-required_field',true);
			$req=array();
			$i=0;
		foreach ($needs as $key => $value) {
					$req[$value]=$value;
					$i++;
			}
		
	$meta_values =$terms = wp_get_post_terms($pageID, 'gallery_cat');
	$corrent=$meta_values[0]->slug;
	if($req) extract($req);
	
	
	//echo "the field is:". the_field('gallery_show','gallery_cat_'.$cat->term_id).'<br><br>'; 
	$categories = get_categories(array('taxonomy'=> $toxsonomy));
//	echo $site_url = network_site_url( '/' );
	//echo $_SERVER['REQUEST_URI'];
		
	echo "<fieldset>
			<legend>".$pageTitle."</legend>
			<form  action=\"".$_SERVER['REQUEST_URI']; 
	echo   " \" method=\"post\" enctype=\"multipart/form-data\" target=\"my_frame\">
				";
				
				$categories = get_categories(array('taxonomy'=>'gallery_cat'));
	echo "
				<select name=\"galcat\" id=\"galId\" title=\"קטגוריית גלרייה\">
				";
					foreach($categories as $cat){
						//echo "<pre>".print_r($cat,1)."</pre>";
					
	echo 	"<option value='{$cat->name}' " ;
				if($cat->slug ==$corrent){
					echo "selected='selected' ";
				}
				
	echo     "  data-slug='{$cat->slug}'>{$cat->name} </option>";//
				}
	echo    "</select>";

				
				
		echo    "
						<br>
				<h2>הילד</h2>
				<input type='text' name='username' id='username' placeholder='שם פרטי' ";
				if($userName_fiede){echo " class='req' required ";};
				echo ">
			
				<input type='text' name='userlastname' id='userlastname' placeholder='שם משפחה' ";
				if($userLastNmae__fiede){echo " class='req' required ";};
				echo">
			
				<input type='text' name='userSchool' id='userSchool' placeholder='שם גן ילדים/ בי\"ס' ";
				if($school_field){echo " class='req' required ";};
				echo ">
			
			
				<label for='userage'>תאריך לידה:</label><input type='date'  name='userage' id='userage' ";
					if($age_fiede){echo " class='req' required ";};
					echo "	><br><br>
					
				<h2>ההורה</h2>
			<input type='text'  name='parentname' id='parentname' placeholder='שם הורה' ";
			if($parent_fiede){echo " class='req' required ";};
					echo "	>
						
					 
			
			<input type='text'  name='parentid' id='parentid' pattern='[0-9]{9}' placeholder='תעודת זהות' ";
			if($parentId_fiede){echo " class='req' required ";};
					echo "	>
					<input type='checkbox' required><span class='iam'>אני ההורה</span>
			 
			
					<h2>פרטי הקשר</h2>
						<input type='text'  name='street' id='street'  placeholder='רחוב' ";
			if($street_fiede){echo " class='req' required ";};
					echo "	>
				
				<input type='text' name='userCalss' id='userCalss' placeholder='מספר'  ";
				if($userClass_field){echo " class='req' required ";};
				echo ">
					
				<input type='text'  name='apartment' id='apartment'  placeholder='דירה' ";
			if($apartment_fiede){echo " class='req' required ";};
					echo "	>
				
				<input type='text'  name='city' id='city'  placeholder='עיר' ";
			if($city_fiede){echo " class='req' required ";};
					echo "	>
					
				<input type='text'  name='zipcode' id='zipcode'  placeholder='מיקוד' ";
			if($zipcode_fiede){echo " class='req' required ";};
					echo "	> 
					
			
			<input type='tel'  name='phone' id='phone' placeholder='טלפון' ";
			if($phone_fiede){echo " class='req' required ";};
					echo "	>
						
						<input type='email'  name='email' id='email' placeholder='כתובת מייל' ";
			if($userEmail_fiede){echo " class='req' required ";};
					echo "	>
			
			
						<input type='file' name='file' id='file'";
			if($file_fiede){echo " class='req' required ";};
					echo "	>" 
						
						.types_render_field('change_title', array("output"=>"text"))
						."<input type='text' name='message' id='message'";
			if($message_field){echo " class='req' required ";};
					echo " >
						
					
						<input type='checkbox' required><span class='tarmUse'>אני מסכים לתנאי השימוש</span>
						<label for='agree'>".
						types_render_field('checkbot_title', array("output"=>"html"))
						
						."</label><input type='checkbox' value='yes' name='agree' ";
			if($agree){echo " class='req' required ";};
					echo " >

						<input type='submit' name='userSubmit' id='galSubmit'>
			
			</form>
			<iframe name='my_frame' id='my_frame'>
  			</iframe>
		</fieldset>
		<div class='massegShow'></div>";
}