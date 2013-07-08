<?php
/**
 * Template Name: gallery Template
 */
get_header(); ?>

		<div id="primary" class="showcase">
			<div id="content" role="main">
			<?php wp_multi_file_uploader(allowed_mime_types); ?>
						<?php $args = array(
								'posts_per_page'  => 5,
								'offset'          => 0,
								'galery_cat'        => 'שמיים',
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
							<?php /*?><pre><?php echo print_r($post,1);?></pre>
							<?php $meta = get_post_meta($post->ID,'wpcf-user-img'); ?>
							<img src="<?php echo $meta[0];?>"><?php */?>
 						<?php endforeach; ?>
				
				<?php 
				//get category
				$args = array(
					'type'                     => 'post',
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 1,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => 'cat_gallery',
					'pad_counts'               => false );
					
					$categories = get_categories(array('taxonomy'=> 'cat_gallery'));
					echo "<pre>".print_r($categories,1)."</pre>";
					
					?>
				
					
						
						
			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>