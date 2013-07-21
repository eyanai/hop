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
						
		//$rows[]= "שם פרטי,שם משפחה,גיל, בית ספר, כיתה, שם ההורה המאשר, תעודת זהות, מספר טלפון, אימייל, עיר, רחוב,דירה, מיקוד, תוכן ההודעה, סוג קובץ";
						$filename=date('now')."_gallery.csv";
						if(file_exists(ABSPATH."wp-content/csv/".$filename)){
							$filename=time()."_gallery.csv";
						}
						
						if($_POST['date']<=$query->post->post_date){
							
							$fileEx=fopen(ABSPATH."wp-content/csv/".$filename,'w');
							
							$file_loc= get_bloginfo('url')."/wp-content/csv/".$filename;
							
							$rows=array();
							$titleEx=array('שם פרטי','שם משפחה','גיל', 'בית ספר', 'כיתה', 'שם ההורה המאשר', 'תעודת זהות',' מספר טלפון', 'אימייל', 'עיר', 'רחוב','דירה', 'מיקוד', 'תוכן ההודעה', 'לינק');
							$titelCon=array();
							foreach($titleEx as $val){
								$titelCon[]= iconv( 'UTF-8','Windows-1255',$val);
								}
							/*foreach($titelCon as $val_title){
								fputcsv($fileEx, $val_title);
								}	*/
							fputcsv($fileEx,$titelCon);
							while( $query->have_posts() ) {
								$query->the_post();
								
								 
								//echo "<pre>".print_r($query,1)."</pre>";
							$rows=array();
							
							
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-user_firstname',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-user_lastname',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-user_age',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-school',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-usercalss',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-user_parent',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-parent_id',true)));
							$rows[]=(string) get_post_meta($query->post->ID,'wpcf-parent_phone',true);
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-parent_email',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-city',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-street',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-apartment',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-zipcode',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_post_meta($query->post->ID,'wpcf-message',true)));
							$rows[]=iconv( 'UTF-8','Windows-1255',(get_permalink( $query->post->ID )));
							
							
					/*		echo(get_post_meta($query->post->ID,'wpcf-user_firstname',true))."<br>";
							echo(get_post_meta($query->post->ID,'wpcf-user_lastname',true))."<br>";
							echo(get_post_meta($query->post->ID,'wpcf-user_age',true))."<br>";
							echo(get_post_meta($query->post->ID,'wpcf-school',true))."<br>";
							echo(get_post_meta($query->post->ID,'wpcf-usercalss',true))."<br>";
							echo(get_post_meta($query->post->ID,'wpcf-user_parent',true))."<br>";
							echo(get_post_meta($query->post->ID,'wpcf-parent_id',true))."<br>";
							echo(get_post_meta($query->post->ID,'wpcf-parent_phone',true))."<br>";
							echo(get_post_meta($query->post->ID,'wpcf-parent_email',true))."<br>";
							echo(get_post_meta($query->post->ID,'wpcf-city',true))."<br>";
							echo(get_post_meta($query->post->ID,'wpcf-street',true))."<br>";
							echo(get_post_meta($query->post->ID,'wpcf-apartment',true))."<br>";
							echo(get_post_meta($query->post->ID,'wpcf-zipcode',true))."<br>";
							echo(get_post_meta($query->post->ID,'wpcf-message',true))."<br>";
							echo(get_permalink( $query->post->ID ));*/
							
							fputcsv($fileEx, $rows);
							
						}
						
						// Restore original Post Data
						wp_reset_postdata();
//						echo $file_loc;
				}
			
			
			
				fclose($fileEx);
			}
			?>
		
			
			
			
		<div class="postbox">
	
			<h3 class="hndle">יצוא פוסטים: </h3>
				<span class="hndle">
						<?php if(!empty($file_loc)):?>
							<a href="<?php echo $file_loc;?>">הקובץ ניתן להורדה מכאן</a><?php endif; ?>
				</span>
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



