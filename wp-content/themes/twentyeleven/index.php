<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 */

get_header(); ?>

<?php 
	if(isset($_POST['mysub'])){
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');

		echo "submit!!!!";
		if(!empty($_FILES['myfile'])){
			$overwit=array('test_form'=>false); 
			$file=wp_handle_upload($_FILES['myfile'],$overwit);
			$fileUrl=$file['uel'];//url is the new location caming throw array
			echo $fileUrl;	
		}else{
			
			//$option['logo']=$this->option['logo'];
			echo "not sesssed!!!!!!!!!!!!!";
		}
	
	}


?>
	<?php $u = array(
		'blog_id'      => $GLOBALS['blog_id'],
		'role'         => '',
		'meta_key'     => '',
		'meta_value'   => '',
		'meta_compare' => '',
		'meta_query'   => array(),
		'include'      => array(),
		'exclude'      => array(),
		'orderby'      => 'login',
		'order'        => 'ASC',
		'offset'       => '',
		'search'       => '',
		'number'       => '',
		'count_total'  => false,
		'fields'       => 'all',
		'who'          => ''
	 ); 
	 
	
	 
	 ?>
	 
	 <ul>
<?php
    $blogusers = get_users($u);
    foreach ($blogusers as $user) {
        echo '<li>' . $user->user_email ;
   	
				if ( is_user_logged_in() ) {
					echo 'Welcome, registered user!';
				} else {
					echo 'Welcome, visitor!';
				}

			
		}
?>
</ul>
	 <?php $args = array(
        'echo' => true,
        'redirect' => site_url( $_SERVER['REQUEST_URI'] ), 
        'form_id' => 'loginform',
        'label_username' => __( 'Username' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in' => __( 'Log In' ),
        'id_username' => 'user_login',
        'id_password' => 'user_pass',
        'id_remember' => 'rememberme',
        'id_submit' => 'wp-submit',
        'remember' => true,
        'value_username' => NULL,
        'value_remember' => false ); ?> 
		<?php wp_login_form( $args ); ?> 
	 
		<div id="primary">
			<div id="content" role="main">
			<ul>
				<?php
				
				global $post;
				
				$args = array(
			'offset'          => 0,
			'mutag'        => 'דורה',
			'orderby'         => 'post_date',
			'order'           => 'DESC',
			'post_type'       => 'hope-test',
			'post_status'     => 'publish',
			'suppress_filters' => true  
			);
				
				$myposts = get_posts( $args );
				
				//print_r($myposts,1);
				
				foreach( $myposts as $post ) : setup_postdata($post); ?>
					<li>
						  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</li>
				<?php endforeach; ?>

			</ul>
			
			
			

			<?php if ( have_posts() ) : ?>

				<?php twentyeleven_content_nav( 'nav-above' ); ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>

				<?php twentyeleven_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #primary -->
		
		<form action="<?php $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
			<input type="file" name="myfile">
			<input type="submit" name="mysub">
		</form>

<?php get_sidebar(); ?>
<?php get_footer(); ?>