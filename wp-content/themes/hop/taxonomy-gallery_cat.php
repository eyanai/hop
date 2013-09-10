<?php /* Template Name: גלריית משתמש */ get_header(); ?>
	
<section class="singeltopheader gallAll">
		<div class="socialSingel">
		<a href="#" class="facebookShare"><span class="letterImg"></span> שלח לחבר</a>
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
			?>
			
			<a href="<?php echo $formlink;?>" class="formlinkgall">להוספת תמונה</a>
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
							echo "<ul class='cat_gallery'> <div class='bigImgCon'>";
							while ( $query->have_posts() ):
								$query->the_post();
								?>
								
								
							
							<?php if($counter==0) echo "<div class='conGall'>";?>
							<?php $counter=$counter<6?++$counter :0; ?>	
							<li>
							
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php  $meta = get_post_meta($post->ID,'wpcf-user_img',true);?>
								<img src="<?php echo $meta;?>" class="mainGallImg"/>
							<span class="subtitle">
							<?php 	
									$name = get_post_meta($post->ID,'wpcf-user_firstname',true);
									$city = get_post_meta($post->ID,'wpcf-city',true);
				
									echo  get_the_title($post->ID). " <br> ".$city;
									
							?>
							</span>
							</a>
							
							</li>
							<?php if($counter==6) {echo "</div>";$counter=0;}?>
						<?php
						
						
						endwhile;
						 if($counter!=6){ echo "</div>"; $counter=0;};
						 
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
				<input type="text" class="inputGall">
				<span class="magnefier"></span>
			</div>
		</div>
		<span class="showAll">הצג הכל</span>
	</section>
	<section class="lang">
		<span class="heb"></span>
		<span class="eng"></span>
		<span class="arb"></span>
		
		<a href="#" class="standart">לתקנון</a>
	</section>
<?php get_footer(); ?>