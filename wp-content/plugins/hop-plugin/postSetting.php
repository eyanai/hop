<?php
require_once('hop_setting/excel.php');


add_action('admin_menu',function(){
	if(is_admin()){
		global $wpdb;
			add_menu_page('Menu Add','הגדרות הופ','administrator','hop_setting',function(){
		?>
		<div class="wrap">
			<?php screen_icon();?>
			<h2>ניהול סוגי פוסטים: </h2>
		<?php	
		echo ABSPATH;
			if(isset($_POST['subExcel'])){
				
				$csv= "שם פרטי,שם משפחה,גיל, בית ספר, כיתה, שם ההורה המאשר, תעודת זהות, מספר טלפון, אימייל, עיר, רחוב, דירה, מיקוד, תוכן ההודעה, קישור /r/n";
				
				$datepost=$_POST['date'];
				
				
				$args = array(
				'post_type' => 'user-gallery',
				'tax_query' => array(
								array(
									'taxonomy' => 'gallery_cat',
									'field' => 'slug',
									'terms' => $_POST['galcat']
								)
							)
						);
						// The Query
						$query = new WP_Query( $args );
						//var_dump($query);
						
		//$rows[]= "שם פרטי,שם משפחה,גיל, בית ספר, כיתה, שם ההורה המאשר, תעודת זהות, מספר טלפון, אימייל, עיר, רחוב,דירה, מיקוד, תוכן ההודעה, סוג קובץ";
							$filename=date('now')."_gallery.csv";
							$fileEx=fopen(ABSPATH."wp-content/csv/".$filename,'w');

							$rows=array();
			
							$rows1[]= iconv( 'UTF-8','Windows-1255',"'שם פרטי','שם משפחה','גיל', 'בית ספר', 'כיתה', 'שם ההורה המאשר', 'תעודת זהות',' מספר טלפון, אימייל', 'עיר', 'רחוב','דירה', 'מיקוד', 'תוכן ההודעה', 'סוג קובץ '");
							fputcsv($fileEx, $rows1);
							while( $query->have_posts() ) {
								
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-user_firstname',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-user_lastname',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-user_age',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-school',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-usercalss',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-user_parent',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-parent_id',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-parent_phone',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-parent_email',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-city',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-street',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-apartment',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-zipcode',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-message',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_permalink( $query->post->ID )));
							
							fputcsv($fileEx, $rows);
							$query->next_post();
							
						}
						
						// Restore original Post Data
						wp_reset_postdata();
						
			}
			
				fclose($fileEx);
			?>
		
			
			
			
		<div class="postbox">
	
			<h3 class="hndle">יצוא פוסטים: </h3>
		
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?> " id="excel_post">
				<label for="post_type">בחר סוג :</label>
				<?php 
				$categories = get_categories(array('taxonomy'=>'gallery_cat'));
				echo "<select name=\"galcat\" id=\"galId\" title=\"קטגוריית גלרייה\">";
					foreach($categories as $cat){
					echo 	"<option value=".$cat->slug." data-slug='{$cat->slug}'>{$cat->name}</option>";
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
			});
	}
	
	add_submenu_page( 'hop_setting', 'export excel', 'יצוא לאקסל', 'administrator', 'hop_excel', 'excel_setting');	
});


//global $wpdb;



