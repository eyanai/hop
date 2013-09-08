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
			
				<label for='username'>שם משתמש:</label>
				<input type='text' name='username' id='username' placeholder='שם פרטי' ";
				if($userName_fiede){echo " class='req' required ";};
				echo ">
				<label for='userlastname'>שם משפחה:</label>
				<input type='text' name='userlastname' id='userlastname' placeholder='שם משפחה' ";
				if($userLastNmae__fiede){echo " class='req' required ";};
				echo">
				<label for='userSchool'>בית ספר:</label>
				<input type='text' name='userSchool' id='userSchool' placeholder='שם גן ילדים/ בי\"ס' ";
				if($school_field){echo " class='req' required ";};
				echo ">
				<label for='userCalss'>כיתה:</label>
				<input type='text' name='userCalss' id='userCalss'  ";
				if($userClass_field){echo " class='req' required ";};
				echo ">
			  	<br><br>
				<label for='userage'>תאריך לידה:</label><input type='date'  name='userage' id='userage' ";
					if($age_fiede){echo " class='req' required ";};
					echo "	><br><br>
			<label for='parentname'>שם הורה:</label>
			<input type='text'  name='parentname' id='parentname' placeholder='שם הורה' ";
			if($parent_fiede){echo " class='req' required ";};
					echo "	><br><br>
			<label for='parentid'>תעודת זהות הורה:</label>
			<input type='text'  name='parentid' id='parentid' placeholder='תעודת זהות' ";
			if($parentId_fiede){echo " class='req' required ";};
					echo "	><br><br>
			<label for='phone'>נייד:</label>
			<input type='tel'  name='phone' id='phone' placeholder='טלפון' ";
			if($phone_fiede){echo " class='req' required ";};
					echo "	><br><br>
						<label for='email'>email:</label>
						<input type='email'  name='email' id='email' placeholder='כתובת מייל' ";
			if($userEmail_fiede){echo " class='req' required ";};
					echo "	><br><br>
						<label for='city'>city:</label>
						<input type='text'  name='city' id='city'  placeholder='עיר' ";
			if($city_fiede){echo " class='req' required ";};
					echo "	><br><br>
						<label for='street'>street:</label>
						<input type='text'  name='street' id='street'  placeholder='רחוב' ";
			if($street_fiede){echo " class='req' required ";};
					echo "	><br><br>
				<label for='apartment'>apartment:</label>
				<input type='text'  name='apartment' id='apartment'  placeholder='דירה' ";
			if($apartment_fiede){echo " class='req' required ";};
					echo "	><br><br>
				<label for='zipcode'>zipcode:</label>
				<input type='text'  name='zipcode' id='zipcode'  placeholder='מיקוד' ";
			if($zipcode_fiede){echo " class='req' required ";};
					echo "	><br><br>
						<input type='file' name='file' id='file'";
			if($file_fiede){echo " class='req' required ";};
					echo "	><br><br>
						<textarea rows='10' cols='42' name='message' id='message'";
			if($message_field){echo " class='req' required ";};
					echo " >".
						types_render_field('change_title', array("output"=>"text"))
						."</textarea> 
						<select name='filetype' id='filetype' title='file type'";
			if($fileType_fiede){echo " class='req' required ";};
					echo "	>
						<option value='img' selected='selected'>img</option>
						<option value='video'>video</option>
						</select><br>
<br>
						<label for='agree'>".
						types_render_field('checkbot_title', array("output"=>"html"))
						
						."</label><input type='checkbox' value='yes' name='agree' ";
			if($agree){echo " class='req' required ";};
					echo " ><br>
<br>

						<input type='submit' name='userSubmit' id='galSubmit'>
			
			</form>
			<iframe name='my_frame' id='my_frame'>
  			</iframe>
		</fieldset>
		<div class='massegShow'></div>";
}