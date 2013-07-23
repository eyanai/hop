<?php /* Template Name: גלריית משתמש */ get_header(); ?>
single-coloring_post
<!-- section -->
	<section role="main">
		
		
		<?php if (have_posts()): while (have_posts()) : the_post(); ?>
		
		
		
							<pre style='direction:ltr'>
			
                    <?php the_title();;?>    
<?php    
    echo get_post_meta( $post->ID,'wpcf-post_permission',true);
        ?>
        <br/>
        <?php
        echo get_post_meta( $post->ID,'wpcf-user_parent',true);
        ?>
        <br/>
        <?php
        echo get_post_meta( $post->ID,'wpcf-art_url1',true);
            ?>
        <br/>
            <?php
            echo get_post_meta( $post->ID,'wpcf-art_url2',true);
            ?>
        <br/>
            <?php
            echo get_post_meta( $post->ID,'wpcf-art_url5',true);
            ?>
        <br/>
 <?php
            echo get_post_meta( $post->ID,'wpcf-art_url4',true);
            ?>
        <br/>
 <?php
            echo get_post_meta( $post->ID,'wpcf-art_url3',true);
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
echo get_post_meta( $post->ID,'wpcf-post_permission',true);
                          ?>
		<?php endwhile;endif;//get_template_part('pagination'); ?>
	    
      

	</section>
	<!-- /section -->
<?php get_footer(); ?>