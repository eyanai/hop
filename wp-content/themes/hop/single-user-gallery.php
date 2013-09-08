<?php get_header(); ?>

<!-- section -->

<section role="main">
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	<h1>
		<?php the_title();;?>
	</h1>
	<pre style='direction:ltr'>
							<?php //echo print_r($post,1);?>
</pre>
	<?php $meta = get_post_meta($post->ID,'wpcf-user_img',true);
								//	echo "ppp: ".$meta;
							 ?>
	<div class="imgSingelPostCon" cId="<?php the_ID(); ?>">						 
	<img src="<?php echo $meta;?>" id="imgSolo">
	</div>
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
	<p>
		<?php the_field('gallery_show'); ?>
	</p>
	
	<span class="next">next</span><br>
	<span class="previous" data-id="<?php the_ID(); ?>">previous</span><br>
	<?php next_post_link('<strong>%link</strong>'); ?>||
	<?php previous_post_link('<strong>%link</strong>'); ?><br>

	
	<?php //get_template_part('loop'); ?>
	<?php endwhile;endif;//get_template_part('pagination'); ?>
	<?php echo $post->ID."<br>";
			echo get_previous_post_id($post->ID)."<br>";
			echo get_next_post_id($post->ID)."<br>";
?>
</section>

<!-- /section -->

<?php get_footer(); ?>
