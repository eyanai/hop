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
		         wp_title(''); echo ' - '; }
		      elseif (is_search()) {
		         echo 'Search for &quot;'.wp_specialchars($s).'&quot; - '; }
		      elseif (!(is_404()) && (is_single()) || (is_page())) {
				  $terms = get_the_terms($post->ID, 'gallery_cat');
					foreach($terms as $title){
						$titleCat=$title->name;
					}
		        echo $titleCat.' - '; }
		      elseif (is_404()) {
		         echo 'Not Found - '; }

		      if (is_home()) {
		         bloginfo('name'); echo ' - '; bloginfo('description'); }
		      else {
		        bloginfo('name'); 
                  }
		      if ($paged>1) {
		         echo ' - page '. $paged; 
			}
			
		   ?>
	</title>
	
	<link rel="shortcut icon" href="/favicon.ico">
	
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

	<?php if ( is_singular() ) wp_enqueue_script('comment-reply'); ?>

	<?php wp_head(); ?>
	
	<meta property="og:title" content="<?php bloginfo('name');?>"/>
	<meta property="og:url" content="<?php echo curPageURL();?>"/>
	<meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/images/gallery/logo.png"/>
</head>

<body <?php body_class(); ?>>
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
					
					
			
			</header>
			<?php endif;?>
			<?php  if(is_single()|| is_taxonomy('gallery_cat')):?>
			<header class="galleryHead">
					<div class="logoGallery">
						<?php /*?><a href="<?php echo home_url(); ?>"><?php */?>
						<a href="http://www.hop.co.il/default.aspx">
							<!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
							<img src="<?php echo get_template_directory_uri(); ?>/images/gallery/logo.png" alt="Logo" class="logo-img">
						<?php /*?></a><?php */?>
						</a>
					</div>
					<div class="logosSingel">
						<a href="http://www.hop.co.il/sesameNew/index.html">
							<img src="<?php echo get_template_directory_uri(); ?>/images/gallery/sumsum.png" alt="Logo" class="logo-img-right">
						</a>
						<img src="<?php echo get_template_directory_uri(); ?>/images/gallery/mrechavim.png" alt="Logo" class="logo-img-right secound">
					</div>
			</header>
			<?php endif;?>
			<!-- /header -->
			<?php //if (function_exists('HAG_Breadcrumbs')) { HAG_Breadcrumbs(); } ?>