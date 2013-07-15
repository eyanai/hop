<?php

function excel_setting(){
	global $wpdb;
	
	
	?>
	<div class="wrap">
			<?php screen_icon();?>
			<h2>יצוא לאקסל</h2>
			<div class="postbox">
			<?php 
/*$post_types=get_post_types('','names'); 
foreach ($post_types as $post_type ) {
  	$obj=get_post_type_object($post_type);
  	foreach ($obj as $type){
		echo "<pre>".print_r($type,1)."</pre>";
	}
}
*/?>

				<h3 class="hndle">יצוא פוסטים: </h3>
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?> " id="excel_post">
				<label for="post_type">בחר סוג :</label>
				<?php 
				$categories = get_categories(array('taxonomy'=>'gallery_cat'));
				echo "<select name=\"galcat\" id=\"galId\" title=\"קטגוריית גלרייה\">";
					foreach($categories as $cat){
					echo 	"<option value=".$cat->name." data-slug='{$cat->slug}'>{$cat->name}</option>";
					}
				echo    "</select>";?>
				<br>
				<label for="date">מתאריך:</label>
				<input type="date" name="date"><br>
				<input type="submit" name="subExcel">
				</form>
			</div>
	</div>	
<?php
}