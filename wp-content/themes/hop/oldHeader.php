<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	
	<?php if (is_search()) { ?>
	   <meta name="robots" content="noindex, nofollow" /> 
	<?php } ?>

	<title>
		   <?php
		      if (function_exists('is_tag') && is_tag()) {
		         single_tag_title("Tag Archive for &quot;"); echo '&quot; - '; }
		      elseif (is_archive()) {
		         wp_title(''); echo ' Archive - '; }
		      elseif (is_search()) {
		         echo 'Search for &quot;'.wp_specialchars($s).'&quot; - '; }
		      elseif (!(is_404()) && (is_single()) || (is_page())) {
		         wp_title(''); echo ' - '; }
		      elseif (is_404()) {
		         echo 'Not Found - '; }
		      if (is_home()) {
		         bloginfo('name'); echo ' - '; bloginfo('description'); }
		      else {
		          bloginfo('name'); }
		      if ($paged>1) {
		         echo ' - page '. $paged; }
		   ?>
	</title>
	
	<link rel="shortcut icon" href="/favicon.ico">
	
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

	<?php if ( is_singular() ) wp_enqueue_script('comment-reply'); ?>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php /*?>	<header class="topheader">
			<nav class="topnav">
				<?php $defaults = array(
					'theme_location'  => '',
					'menu'            => 'top_menu',
					'container'       => 'div',
					'container_class' => '',
					'container_id'    => '',
					'menu_class'      => 'top_menu_r',
					'menu_id'         => '',
					'echo'            => true,
					'fallback_cb'     => 'wp_page_menu',
					'before'          => '',
					'after'           => '',
					'link_before'     => '',
					'link_after'      => '',
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>  ',
					'depth'           => 0,
					'walker'          => ''
				);
				
				wp_nav_menu( $defaults );
			?>
				<ul class="fixLInk">
					<li><a href="#">הופ ילדות ישראלית</a></li>
					<li><a href="#">לולי</a></li>
				</ul>
			
			<?php	 get_search_form( $echo ); ?>	
			<?php $defaults = array(
					'theme_location'  => '',
					'menu'            => 'social_top',
					'container'       => 'div',
					'container_class' => '',
					'container_id'    => '',
					'menu_class'      => 'top_menu_l',
					'menu_id'         => '',
					'echo'            => true,
					'fallback_cb'     => 'wp_page_menu',
					'before'          => '',
					'after'           => '',
					'link_before'     => '',
					'link_after'      => '',
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>  ',
					'depth'           => 0,
					'walker'          => ''
				);
				
				wp_nav_menu( $defaults );
			?>
			
			</nav>
			
		</header><?php */?>
		<!-- wrapper -->
		<div class="wrapper cf">
	
			<!-- header -->
			
			<?php  if(!is_single()):?>
			<header class="header clear" role="banner">
				
					<!-- logo -->
					<?php /*?><div class="logo">
						<a href="<?php echo home_url(); ?>">
							<!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
							<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" class="logo-img">
						</a>
					</div><?php */?>
					<!-- /logo -->
					
					<!-- nav -->
					<nav class="nav" role="navigation">
					<?php // wp_nav_menu(array('menu'=>'תפריט עליון','menu_class'=> 'nav_menu'));?>
					</nav>
					<?php /*?><nav class="mutags_nav">
						<ul class="mutagSlider">
						<?php $args = array(
							'child_of'                 => 0,
							'orderby'                  => 'name',
							'order'                    => 'ASC',
							'hide_empty'               => 0,
							'hierarchical'             =>0,
							'taxonomy'                 => 'category',
							'pad_counts'               => false );
							
							$categories = get_categories( $args );
							
							foreach($categories as $mutag){
							 $category_link = get_category_link($mutag->cat_ID);	
								//echo $category_link;
								?>
							<li data-play="play_<?php echo $mutag->term_id;?>">
							<a href="<?php echo esc_url( $category_link ); ?>" title="Category Name">
							<?php $imgUrl=get_field('mutag_img','category_'.$mutag->term_id);//echo "<pre>".print_r($f,1)."</pre>"; 
								?>
									<img src="<?php echo $imgUrl['url'];?>" title="<?php echo $mutag->name;?>">
							</a>
							<?php $audioUrl=get_field('mutag_sound','category_'.$mutag->term_id);
							//echo "aodio:<pre>".print_r($audioUrl,1)."</pre>"; 
								?>
							<audio preload="auto" id="play_<?php echo $mutag->term_id;?>">
								<source src="<?php echo $audioUrl['url'];?>" type="audio/mpeg">
							</audio>
							<li>
							<?php
									}
							
							?>
							
						</ul>
					</nav><?php */?>
					<!-- /nav -->
			
			</header>
			<?php endif;?>
			<?php  if(is_single()|| is_taxonomy('gallery_cat')):?>
			<header class="galleryHead">
					<div class="logoGallery">
						<a href="<?php echo home_url(); ?>">
							<!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
							<img src="<?php echo get_template_directory_uri(); ?>/images/gallery/logo.png" alt="Logo" class="logo-img">
						</a>
					</div>
					<div class="logosSingel">
						<img src="<?php echo get_template_directory_uri(); ?>/images/gallery/sumsum.png" alt="Logo" class="logo-img-right">
						<img src="<?php echo get_template_directory_uri(); ?>/images/gallery/mrechavim.png" alt="Logo" class="logo-img-right secound">
					</div>
			</header>
			<?php endif;?>
			<!-- /header -->
			<?php //if (function_exists('HAG_Breadcrumbs')) { HAG_Breadcrumbs(); } ?>