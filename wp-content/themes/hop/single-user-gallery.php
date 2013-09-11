<?php get_header(); ?>

<!-- section -->
<?php if (have_posts()): while (have_posts()) : the_post(); ?>
<?php 
	$terms = get_the_terms($post->ID, 'gallery_cat');
	foreach($terms as $title){
		$titleCat=$title->name;
	}
	?>
	<?php $meta = get_post_meta($post->ID,'wpcf-user_img',true); ?>
	<section class="singeltopheader">
		<a href="#" class="backSingel"><span class="backArrow"></span>חזור</a>
		<h1 class="singelCat"><?php echo $titleCat?></h1>
	
		<div class="socialSingel">
		<a  href="mailto:someone@example.com?Subject=Hello%20again" class="facebookShare"><span class="letterImg"></span> שלח לחבר</a>
		<a class="facebookShare" href="#" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;">
		<span class="faceImg"></span> שתף בפייסבוק
		</a>
		</div>
	</section>
<section role="main" class="gallery_main_singel">
	
	<div class="imgSingelPostCon" cId="<?php the_ID(); ?>">	
				<div class="mascLoder"><figure class="ajaxLoder"></figure></div>									 
				<img src="<?php echo $meta;?>" id="imgSolo" class="singelImg">
		<div class="circule right"><div class="nextSingel right-triangle"></div></div>
		<div class="circule left"><div class="previousSingel left-triangle"></div></div>
		<div class="writer">
			<?php 
				$name = get_post_meta($post->ID,'wpcf-user_firstname',true);
				//$lastname=get_post_meta($post->ID,'wpcf-user_lastname',true);
				$city = get_post_meta($post->ID,'wpcf-city',true);
				
				echo  get_the_title($post->ID). " - ".$city;
				?>
					
			
		</div>
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
	
	
	<?php endwhile;endif;?>
</section>

<!-- /section -->

<?php get_footer(); ?>
