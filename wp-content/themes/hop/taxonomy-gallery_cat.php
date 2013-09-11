<?php /* Template Name: גלריית משתמש */ get_header(); ?>
	
<section class="singeltopheader gallAll">
		<div class="socialSingel">
		<a href="mailto:someone@example.com?Subject=Hello%20again" class="facebookShare" id="sendToFriend" ><span class="letterImg"></span> שלח לחבר</a>
		<a class="facebookShare" href="#" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;">
		<span class="faceImg"></span> שתף בפייסבוק
		</a>
		</div>
	</section>
	<section class="topcatdetail cf">
	<?php 
			$terms = get_the_terms($post->ID, 'gallery_cat');
			foreach ( $terms as $term ) {
			$catname=$term->name;
			}
			$post_type = 'forms';
			$tax = 'gallery_cat';
			$tax_terms = get_terms($tax);
			if ($tax_terms) {
			  foreach ($tax_terms  as $tax_term) {
				$args=array(
				  'post_type' => $post_type,
				  "$tax" => $tax_term->slug,
				  'post_status' => 'publish',
				  'posts_per_page' => -1,
				  'caller_get_posts'=> 1
				);
			
				$my_query = null;
				$my_query = new WP_Query($args);
				if( $my_query->have_posts() ) {
								 
				  if($tax_term->name==$catname):
				  while ($my_query->have_posts()) : $my_query->the_post(); ?>
					<?php $formlink=get_permalink() ?>
					<?php
				  endwhile;
				  endif;
				}
				wp_reset_query();
			  }
			}
?>
		<div class="catDescrip">
			
			<?php
			$terms = get_the_terms($post->ID, 'gallery_cat');
			foreach ( $terms as $term ) {
			echo "<h1 class='catH1'>".$term->name."</h1>";
			$cterms=$term;
			?>
			
			<a href="<?php echo $formlink;?>" class="formlinkgall">הוסף תמונה</a>
			<?php
			$term->term_id;
				//echo  get_field('img_icon','gallery_cat_'.$term->term_id);
				echo "<div class='textDescript'>".the_field('gallry_description','gallery_cat_'.$term->term_id)."</div>";
				$a=get_field('img_icon','gallery_cat_'.$term->term_id);
				
				?>
				
				</div>
				 <img src="<?php echo $a['url'];?>" class="imgcat">
			<?php	
				
			}
			$minHedar=get_field('main_hl','gallery_cat_'.$cterms->term_id);
			$subHedar=get_field('sub_hl','gallery_cat_'.$cterms->term_id);
			switch ($minHedar) {
				case 'בית ספר':
					$minH='wpcf-school';
					break;
				case 'עיר':
					$minH='wpcf-city';
					break;
				case 'גיל':
					$minH='wpcf-user_age';
					break;
				 default:
        		$minH='wpcf-user_firstname';
			}
			
			switch ($subHedar) {
				case 'בית ספר':
					$subH='wpcf-school';
					break;
				case 'שם הילד':
					$subH='wpcf-user_firstname';
					break;
				case 'גיל':
					$subH='wpcf-user_age';
					break;
				 default:
        		$subH='wpcf-city';
			}
		?>
		
	</section>	
<section role="main" class="gallery_main_singel">
		<div class="circule right gallery"><div class="nextGall right-triangle"></div></div>
		<div class="circule left gallery"><div class="previousGall left-triangle"></div></div>
		<?php 
			$catid=get_query_var( 'gallery_cat' );
		?>
						<?php
						
						$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

							$args = array(
							'post_type' => 'user-gallery',
							'tax_query' => array(
								array(
									'taxonomy' => 'gallery_cat',
									'field' => 'slug',
									'terms' => $catid
								)
							)
						);
						// The Query
						$query = new WP_Query( $args );
						$counter=0;	
						// The Loop
						if ( $query->have_posts() ) {
							echo "<ul class='cat_gallery'> <div class='bigImgCon cf'>";
							while ( $query->have_posts() ):
								$query->the_post();
								?>
								
								
							
							<?php //if($counter==0) echo "<div class='conGall'>";?>
							<?php //$counter=$counter<6?++$counter :0; ?>	
							<li>
							
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php  $meta = get_post_meta($post->ID,'wpcf-user_img',true);?>
								<img src="<?php echo $meta;?>" class="mainGallImg"/>
							<span class="subtitle">
							<?php 	
									$name = get_post_meta($post->ID,$minH,true);
									$city = get_post_meta($post->ID,$subH,true);
									if($minH=='wpcf-user_firstname'){
										$htop=get_the_title($post->ID);
									}else{
										$htop=$name;
									}								
									echo  "<span class='mainNeme'>".$htop."</span> <br> ".$city;
									
							?>
							</span>
							</a>
							
							</li>
							<?php // if($counter==6) {echo "</div>";$counter=0;}?>
						<?php
						
						
						endwhile;
						// if($counter!=6){ echo "</div>"; $counter=0;};
						 
						echo "</div></ul>"; 
						} else {
							// no posts found
						}
						/* Restore original Post Data */
						wp_reset_postdata();
						
						
					?>
					
					
					
	</section>
	
	<section class="searchGallery">
		<div class="soreng">
			<div class="searchInput">
				<input type="text" class="inputGall" placeholder="חיפוש לפי שם">
				<span class="magnefier"></span>
				<div id="searchHE" class="searchLANG">
					<span class="letter">א</span>
					<span class="letter">ב</span>
					<span class="letter">ג</span>
					<span class="letter">ד</span>
					<span class="letter">ה</span>
					<span class="letter">ו</span>
					<span class="letter">ז</span>
					<span class="letter">ח</span>
					<span class="letter">ט</span>
					<span class="letter">י</span>
					<span class="letter">כ</span>
					<span class="letter">ל</span>
					<span class="letter">מ</span>
					<span class="letter">נ</span>
					<span class="letter">ס</span>
					<span class="letter">ע</span>
					<span class="letter">פ</span>
					<span class="letter">צ</span>
					<span class="letter">ק</span>
					<span class="letter">ר</span>
					<span class="letter">ש</span>
					<span class="letter">ת</span>
				</div>
				<div id="searchEN" class="searchLANG">
					<span class="letter">A</span>
					<span class="letter">B</span>
					<span class="letter">C</span>
					<span class="letter">D</span>
					<span class="letter">E</span>
					<span class="letter">F</span>
					<span class="letter">G</span>
					<span class="letter">H</span>
					<span class="letter">I</span>
					<span class="letter">J</span>
					<span class="letter">K</span>
					<span class="letter">L</span>
					<span class="letter">M</span>
					<span class="letter">N</span>
					<span class="letter">O</span>
					<span class="letter">P</span>
					<span class="letter">Q</span>
					<span class="letter">R</span>
					<span class="letter">S</span>
					<span class="letter">T</span>
					<span class="letter">U</span>
					<span class="letter">V</span>
					<span class="letter">W</span>
					<span class="letter">X</span>
					<span class="letter">Y</span>
					<span class="letter">Z</span>
				</div>
				<div id="searchAR" class="searchLANG">
					<span class="letter">ا</span>
					<span class="letter">ﻻ</span>
					<span class="letter">ب</span>
					<span class="letter">ت</span>
					<span class="letter">ث</span>
					<span class="letter">ج</span>
					<span class="letter">ح</span>
					<span class="letter">خ</span>
					<span class="letter">د</span>
					<span class="letter">ذ</span>
					<span class="letter">ر</span>
					<span class="letter">ز</span>
					<span class="letter">س</span>
					<span class="letter">ش</span>
					<span class="letter">ص</span>
					<span class="letter">ض</span>
					<span class="letter">ط</span>
					<span class="letter">ظ</span>
					<span class="letter">ع</span>
					<span class="letter">غ</span>
					<span class="letter">ف</span>
					<span class="letter">ق</span>
					<span class="letter">ك</span>
					<span class="letter">ل</span>
					<span class="letter">م</span>
					<span class="letter">ن</span>
					<span class="letter">ه</span>
					<span class="letter">ة</span>
					<span class="letter">و</span>
					<span class="letter">ى</span>
					<span class="letter">ي</span>
				</div>
			</div>
		</div>
		<span class="showAll">הצג הכל</span>
	</section>
	<section class="lang">
		<span class="heb active"><span class="lang-letter">ע</span></span>
		<span class="eng"><span class="lang-letter">En</span></span>
		<span class="arb"><span class="lang-letter">ي</span></span>
		
		<a href="#" class="standart">תקנון</a>
	</section>
<script>

    $("#file_upload") .change(function(e) {

        var fileName = $(this).val();
        if((/\.(gif|jpg|jpeg|png)$/i).test(fileName)) {

            if(this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    console.log(e.target.result);
                    $("#add-competitor-imgFile").attr("src", e.target.result);
                    smallImgAdded = true;
                };

                reader.readAsDataURL(this.files[0]);
            }
        }
        else {
            alert("noValidFile");
        }
    });
</script>

<?php get_footer(); ?>