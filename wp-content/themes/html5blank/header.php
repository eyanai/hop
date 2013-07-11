<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">
		<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
		<!-- icons -->
		<link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
		<link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">
			
		<!-- css + javascript -->
		<?php wp_head(); ?>
		<script>
		!function(){
			// configure legacy, retina, touch requirements @ conditionizr.com
			conditionizr()
		}()
		</script>
	</head>
	<body <?php body_class(); ?>>
		<header class="topheader">
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
			
		</header>
		<!-- wrapper -->
		<div class="wrapper cf">
	
			<!-- header -->
			<header class="header clear" role="banner">
				
					<!-- logo -->
					<div class="logo">
						<a href="<?php echo home_url(); ?>">
							<!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
							<img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" alt="Logo" class="logo-img">
						</a>
					</div>
					<!-- /logo -->
					
					<!-- nav -->
					<nav class="nav" role="navigation">
						<?php html5blank_nav(); ?>
					</nav>
					<nav class="mutags_nav">
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
					</nav>
					<!-- /nav -->
			
			</header>
			<!-- /header -->