<?php /* Template Name: גלריית משתמש */ get_header(); ?>
single-coloring_post
<!-- section -->
	<section role="main">
		
		
		<?php if (have_posts()): while (have_posts()) : the_post(); ?>
		
		
		
							<pre style='direction:ltr'>
			
                    <?php the_title();;?>    
<?php    
    echo get_post_meta( $post->ID,'wpcf-added_value',true);
        ?>
 <br/>
<?php 
    echo "is new:";
    $isNew = get_post_meta( $post->ID,'wpcf-new_item');
    //echo print_r($isNew);
    echo $isNew[0];
        ?>
        <br/>
        <?php
             echo "wpcf-post_sound:";
        echo get_post_meta( $post->ID,'wpcf-post_sound',true);
        ?>
        <br/>
        <?php
             echo "wpcf-game_permission:";
        echo get_post_meta( $post->ID,'wpcf-game_permission',true);
            ?>
        <br/>
            <?php
            echo get_post_meta( $post->ID,'wpcf-game_hover_image',true);
            ?>
        <br/>
            <?php
            echo get_post_meta( $post->ID,'wpcf-post_tooltip',true);
            ?>
        <br/>
<?php
            echo get_post_meta( $post->ID,'wpcf-post_image',true);
?>
                            <br/>    
<?php                         
echo get_post_meta( $post->ID,'wpcf-about_video',true);
                          ?>
                            <br/>    
<?php                         
echo get_post_meta( $post->ID,'wpcf-video_link',true);
                          ?>
                           
		<?php endwhile;endif;//get_template_part('pagination'); ?>
	    
      

	</section>
	<!-- /section -->
<?php get_footer(); ?>