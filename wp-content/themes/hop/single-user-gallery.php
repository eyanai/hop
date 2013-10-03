<?php get_header(); ?>
<?php ?>
<!-- section -->
<?php if (have_posts()): while (have_posts()) : the_post(); ?>
<?php
////////////

	
 /////////////
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
		<a  href="mailto:?Subject=<?php echo $titleCat?>&body=בואו לראות את 'המשפחה שלי' בגלריית ערוץ הופ! גם אתם יכולים להעלות תמונה משפחתית ולהופיע בגלרייה. כי בכל משפחה יש משהו מיוחד" class="facebookShare" target="new"><span class="letterImg"></span> שלח לחבר</a>
		<!--<a class="facebookShare" href="#" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;">-->
		<a title="שתף בפייסבוק"
      href="http://www.facebook.com/sharer.php?s=100&p[url]=<?php echo curPageURL();?>&p[images][0]=<?php echo $meta; ?>&p[title]=<?php echo $titleCat;?>&p[summary]=בואו לראות את 'המשפחה שלי' בגלריית ערוץ הופ! גם אתם יכולים להעלות תמונה משפחתית ולהופיע בגלרייה. כי בכל משפחה יש משהו מיוחד"
      target="_blank" id='faceshre' class="facebookShare" data-title='<?php echo $titleCat;?>' data-url='<?php  echo curPageURL(); ?>' data-imag='<?php echo $meta; ?>' data-sammery="בואו לראות את 'המשפחה שלי' בגלריית ערוץ הופ! גם אתם יכולים להעלות תמונה משפחתית ולהופיע בגלרייה. כי בכל משפחה יש משהו מיוחד">
		<span class="faceImg"></span> שתף בפייסבוק
		</a>
		</div>
	</section>
<section role="main" class="gallery_main_singel">
	
	<div class="imgSingelPostCon" cId="<?php the_ID(); ?>">	
				<div class="mascLoder"><figure class="ajaxLoder"></figure></div>									 
				
         <div class="image-solo-wrap" style="background-image:url('<?php echo $meta;?>')">
          
         </div>
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
		<div class="gallery_back_singel"><a href="<?php echo get_term_link($title,'gallery_cat');?>" class="backgallery" >לגלריית תמונות</a></div>
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
