<?php get_header(); ?>


	<!-- section -->
	<section role="main">
		
		
		<?php if (have_posts()): while (have_posts()) : the_post(); ?>
		<h1><?php the_title();;?></h1>
		
		
							<pre style='direction:ltr'>
							
							
							<?php //echo print_r($post,1);?></pre>
							<?php $meta = get_post_meta($post->ID,'wpcf-user_img',true);
								//	echo "ppp: ".$meta;
							 ?>
							<img src="<?php echo $meta;?>">
 
				
				<?php 
				//get category
				$args = array(
					//'type'                     => 'post',
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					//'hide_empty'               => 1,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => 'gallery_cat',
					'pad_counts'               => false );
					
					
										
					?>
				
					
		
		
		<p><?php the_field('gallery_show'); ?></p>
	
		<?php //get_template_part('loop'); ?>
		
		<?php endwhile;endif;//get_template_part('pagination'); ?>
	
	</section>
	<!-- /section -->
	

<?php get_footer(); ?>