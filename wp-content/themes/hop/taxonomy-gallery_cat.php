<?php /* Template Name: גלריית משתמש */ get_header(); ?>
	
		<section role="main" style="direction:rtl;">
	<?php $img=wp_get_attachment_image(61);
			//echo $img;
	?>
	
	
		
		
		<?php 
		$catid=get_query_var( 'gallery_cat' );
	?>
		
		
		<?php //wp_multi_file_uploader(allowed_mime_types); ?>
		
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
						
						// The Loop
						if ( $query->have_posts() ) {
							echo "<ul>";
							while ( $query->have_posts() ):
								$query->the_post();
								?>
							<li><?php echo "name: ". get_post_meta($post->ID,'wpcf-user_firstname',true); ?><br>
							<?php echo "last: ".get_post_meta($post->ID,'wpcf-user_lastname',true); ?><br>
							<?php echo "age: ".get_post_meta($post->ID,'wpcf-user_age',true); ?><br>
							<?php echo "perant: ".get_post_meta($post->ID,'wpcf-user_parent',true); ?><br>
							<?php echo "phone: ".get_post_meta($post->ID,'wpcf-parent_phone',true); ?><br>
							<?php echo "email: ".get_post_meta($post->ID,'wpcf-parent_email',true); ?><br>
							<?php echo "city: ".get_post_meta($post->ID,'wpcf-city',true); ?><br>
							<?php echo get_post_meta($post->ID,'wpcf-street',true); ?><br>
							<?php echo get_post_meta($post->ID,'wpcf-apartment',true); ?><br>
							<?php echo get_post_meta($post->ID,'wpcf-zipcode',true); ?><br>
							<?php  $meta = get_post_meta($post->ID,'wpcf-user_img',true);
								//	echo $meta;
							?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><img src="<?php echo $meta;?>"/></a><br>
</li>
	
						
						
						<?php //$terms = get_the_terms($post->ID, 'gallery_cat');
						
							  /*if ( $terms && !is_wp_error( $terms ) ) : 
								
										$draught_links = array();
									
										foreach ( $terms as $term ) {
											$term->term_id;
											//echo " : ".the_field('gallery_show','gallery_cat_'.$term->term_id)."<br>";
											//echo "---> : ".the_field('gall_regulations','gallery_cat_'.$term->term_id)."<br>";
											$main=get_field('main_hl','gallery_cat_'.$term->term_id);
											switch ($main) {
												case "גיל":
													echo get_post_meta($post->ID,'wpcf-user_age',true)."<br>";
													break;
												case "שם הילד":
													echo get_post_meta($post->ID,'wpcf-user_firstname',true)."<br>";
													break;
												case "בית ספר":
													echo get_post_meta($post->ID,'wpcf-school',true)."<br>";
													break;
												case "שם משפחה":
													echo get_post_meta($post->ID,'wpcf-user_lastname',true)."<br>";
													break;
												default:
      											 echo "לא ניצן להצגה כרגע";
												
											}
											
											$main=get_field('sub_hl','gallery_cat_'.$term->term_id);
											switch ($main) {
												case "גיל":
													echo get_post_meta($post->ID,'wpcf-user_age',true);;
													break;
												case "שם הילד":
													echo get_post_meta($post->ID,'wpcf-user_firstname',true);
													break;
												case "בית ספר":
													echo get_post_meta($post->ID,'wpcf-school',true);
													break;
												case "שם משפחה":
													echo get_post_meta($post->ID,'wpcf-user_lastname',true);
													break;
												default:
      											 echo "לא ניצן להצגה כרגע";
												
											}
											
										}
															
										$on_draught = join( ", ", $draught_links );*/
									?>
									
									<?php /*?><p class="beers draught">
										On draught: <span><?php echo $on_draught; ?></span>
									</p><?php */?>
									
									<?php //endif; ?>
					
						<?php 
							//echo " : ".the_field('gallery_show','gallery_cat_4')."<br>";
//							echo "---> : ".the_field('gall_regulations','gallery_cat_4')."<br>";
//							echo "---> : ".the_field('main_hl','gallery_cat_4');
						
						?>
						<hr>	
						
						<?php
							
						endwhile;
						echo "</ul>";
						} else {
							// no posts found
						}
						/* Restore original Post Data */
						wp_reset_postdata();
						
						
					?>
					
					
					
						<?php /*?><?php $args = array(
								'posts_per_page'  => 5,
								'offset'          => 0,
								'galery_cat'        => 'mesek',
								'orderby'         => '',
								'order'           => 'DESC',
								'include'         => '',
								'exclude'         => '',
								'meta_key'        => '',
								'meta_value'      => '',
								'post_type'       => 'user-gallery',
								'post_mime_type'  => '',
								'post_parent'     => '',
								'post_status'     => 'publish',
								'suppress_filters' => true ); 		
				
					$myposts = get_posts( $args );
						foreach( $myposts as $post ) : setup_postdata($post); ?>
							<pre style='direction:ltr'><?php echo print_r($post,1);?></pre>
							<?php echo get_post_meta($post->ID,'wpcf-user_firstname',true); ?><br>
							<?php echo get_post_meta($post->ID,'wpcf-user_lastname',true); ?><br>
							<?php echo get_post_meta($post->ID,'wpcf-user_age',true); ?><br>
							<?php echo get_post_meta($post->ID,'wpcf-user_parent',true); ?><br>
							<?php echo get_post_meta($post->ID,'wpcf-parent_phone',true); ?><br>
							<?php echo get_post_meta($post->ID,'wpcf-parent_email',true); ?><br>
							<?php echo get_post_meta($post->ID,'wpcf-city',true); ?><br>
							<?php echo get_post_meta($post->ID,'wpcf-street',true); ?><br>
							<?php echo get_post_meta($post->ID,'wpcf-apartment',true); ?><br>
							<?php echo get_post_meta($post->ID,'wpcf-zipcode',true); ?><br>
							<img src="<?php attchImg($post->ID);?>"/><hr>
 						<?php endforeach; ?><?php */?>
				
				<?php 
				//get category
				$args = array(
					//'type'                     => 'post',
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					//'hide_empty'               => 1,
					//'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => 'gallery_cat',
					'pad_counts'               => false );
					
					
										
					?>
				
					
		
		
		<p><?php //echo the_field('gallery_show'); ?></p>
	
		<?php //get_template_part('loop'); ?>
		
		<?php //get_template_part('pagination'); ?>
					<?php next_posts_link(); ?>
					<?php previous_posts_link(); ?>
	</section>
	
<?php get_footer(); ?>