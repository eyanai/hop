<?php get_header(); ?>
	<?php  //get gast the ariti whit the same category
	?>
	<h1>arti cat page</h1>
	
	<?php 
		$catid=get_query_var( 'aricle-cat' );
	?>
	<?php //if (have_posts()) : while (have_posts()) : the_post(); 
	$args = array(
			'post_type' => 'article_post',
			'tax_query' => array(
				array(
					'taxonomy' => 'aricle-cat',
					'field' => 'slug',
					'terms' => $catid
				)
			)
		);
			// The Query
			$query = new WP_Query( $args );
			
			// The Loop
			if ( $query->have_posts() ) :while ( $query->have_posts() ):$query->the_post();
					?>
	
	
	

	
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

		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			
			
			
		
			
		</div>



	<?php endwhile; endif; ?>
	
<?php //get_sidebar(); ?>

<?php get_footer(); ?>