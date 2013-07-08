<?php
/**
 * The Template for displaying all form posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">
					
				<?php while ( have_posts() ) : the_post(); ?>

					<nav id="nav-single">
						<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentyeleven' ); ?></h3>
						<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'twentyeleven' ) ); ?></span>
						<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></span>
					</nav><!-- #nav-single -->

					<?php get_template_part( 'content-single', get_post_format() ); ?>

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>
<h1>דוד אני פה!!! - הבנת????</h1>
			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?><?php
/**
 * The Template for displaying all form posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="primary">
		
			<div id="content" role="main">
					
				<?php while ( have_posts() ) : the_post(); ?>
					<?php //get_template_part( 'content-single', get_post_format() ); ?>
					<pre style="background-color:rgba(0,102,153,.35);">
					<?php //echo print_r($post,1);
							$allcat=get_terms('tree') ;
							foreach($allcat as $c){
								echo print_r($c,1);
							}
					?>
					<?php comments_template( '', true ); ?>
					</pre>
				<?php endwhile; // end of the loop. ?>
			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>