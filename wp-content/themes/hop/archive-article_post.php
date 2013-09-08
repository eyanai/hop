<?php get_header(); ?>


<?php
	$type = array('article_post');
	$args=array(
	  'post_type' => $type,
	  'post_status' => 'publish',
	  'posts_per_page' => -1,
	  'caller_get_posts'=> 1,
	);
	$my_query = null;
	$my_query = new WP_Query($args);
	if( $my_query->have_posts() ) {
		
	  while ($my_query->have_posts()) : $my_query->the_post(); ?>
		<h1><?php //echo $post->ID;?></h1>
		<?php $termH=get_the_terms($post->ID, 'aricle-cat' );
		$artiCat=$termH->term_taxonomy_id;
		//echo $artiCat;
		$taxonomyName ="aricle-cat";
		//$tlink=get_term_link($artiCat,$taxonomyName);
		 echo custom_taxonomies_terms_links();
		 ?>
		<a href=""><?php echo $termH->name; ?></a>
		<pre>
		<?php //echo print_r($termH,1) ;?>
		</pre>
		<p><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></p>
		<?php
	  endwhile;
	}


	wp_reset_query();  // Restore global post data stomped by the_post().
?>











<hr>
			<?php if (have_posts()) : ?>

 			<?php while (have_posts()) : the_post(); ?>
			
		<?php	$terms = get_the_terms( $post->ID , 'aricle-cat' );
				$taxonomyName ="aricle-cat";
					if($terms) {
						foreach( $terms as $term ) {
							$termchildren = get_term_children( $term->term_id, $taxonomyName );
					
						echo '<ul>';
						foreach ($termchildren as $child) {
							$term = get_term_by( 'id', $child, $taxonomyName );
					echo '<li><a href="' . get_term_link( $term->name, $taxonomyName ) . '">' . $term->name . '</a></li>';
						}
						echo '</ul>';	
						}
					}
				
				
				?>
				</div>
					<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
					
					
	
				</div>

			<?php endwhile; ?>

			<?php include (TEMPLATEPATH . '/inc/nav.php' ); ?>
			
	<?php else : ?>

		<h2>Nothing found</h2>

	<?php endif; ?>

<?php // get_sidebar(); ?>

<?php get_footer(); ?>