<?php get_header(); ?>
<?php	galley_form(); ?>
	<?php 
	?>
	<!-- section -->
	<section role="main">
		<h1><?php _e( 'Latest Posts', 'html5blank' ); ?></h1>
		
		
		
		
		
		<?php //wp_multi_file_uploader(allowed_mime_types); ?>
						<?php $args = array(
								'posts_per_page'  => 5,
								'offset'          => 0,
								'galery_cat'        => '',
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
							<?php /*?><pre style='direction:ltr'><?php echo print_r($post,1);?></pre>
							<?php $meta = get_post_meta($post->ID,'wpcf-user-img'); ?>
							<img src="<?php echo $meta[0];?>"><?php */?>
 						<?php endforeach; ?>
				
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
				
					
		
		
		<p><?php the_field('gallery_show'); ?></p>
	
		<?php //get_template_part('loop'); ?>
		
		<?php //get_template_part('pagination'); ?>
	
	</section>
	<!-- /section -->
	
<?php // get_sidebar(); ?>

<?php get_footer(); ?>