<?php get_header(); ?>

	<?php 
	?>
	<!-- section -->
	<section class="singeltopheader gallAll">
		
	</section>
	<section role="main" class='mainform'>
		<div class='formup cf'>
			<?php	galley_form(); ?>
			<div class="regu">
			 <?php 
			   $terms = get_the_terms($post->ID, 'gallery_cat');
			   foreach ( $terms as $term ) {
			   $catname=$term->name;
			   $regu=get_field('gall_regulations','gallery_cat_'.$term->term_id);
			   echo $regu; 
			 }?>
			 </div>
			
		
		<h1><?php // _e( 'Latest Posts', 'html5blank' ); ?></h1>
		
		
		
		
		
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
	<?php	
			$terms = get_the_terms($post->ID, 'gallery_cat');
//			foreach ( $terms as $term ) {
//			$catname=$term->name;
//			$regu=get_field('gall_regulations','gallery_cat_'.$term->term_id);
//			echo $regu;	
//		}?>
		<?php 
		$terms = get_the_terms($post->ID, 'gallery_cat');
			foreach ( $terms as $term ) {
			echo '<div class="formCatDetails">';
		//	echo "<pre>".print_r($term,1)."</pre>";
			echo "<h2>".$catname."</h2>";
			echo "<div class='textDescript'>".the_field('gallry_description','gallery_cat_'.$term->term_id)."</div>";
			?>
			
			<a href="<?php echo get_site_url()."/?".$term->taxonomy."=".$term->slug; ?>" class="backgallery" >לגלריית תמונות</a> 
			<?php
			
			echo '</div>';
			}
			
		?>
 		<div class="smartInfo">
			ניתן לשלוח ישירות מהמכשיר הנייד
			
		</div>
	</div>
	</section>
	<!-- /section -->
	
<?php // get_sidebar(); ?>
<script>
Modernizr.load({
  test: Modernizr.inputtypes.date,
  nope: ['http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js', 'jquery-ui.css'],
  complete: function () {
    $('input[type=date]').datepicker({
      dateFormat: 'yy-mm-dd'
    }); 
  }
});
</script>
<?php get_footer(); ?>