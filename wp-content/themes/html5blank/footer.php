			<!-- footer -->
			<footer class="footer" role="contentinfo">
			<nav class="lowmanu">
				<?php $defaults = array(
					'menu'            => 'תפריט תחתון',
					'container'       => 'div',
					'menu_class'      => 'top_menu_r',
					'echo'            => true,
					'fallback_cb'     => 'wp_page_menu',
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>  ',
					'depth'           => 0,
					'walker'          => ''
				);
				
				wp_nav_menu( $defaults );
			?>
			
			</nav>
			<section class="infoBox cf">
				<?php $argsInfo = array(
	'posts_per_page'  => 1,
	'orderby'         => 'post_date',
	'order'           => 'DESC',
	'post_type'       => 'info_box',
	'post_status'     => 'publish',
	'suppress_filters' => true ); 
	
			$infos=get_posts($argsInfo); 
			
			
			
			foreach ($infos as $info):
			?>	
			
			<a href="<?php echo get_post_meta( $info->ID, 'wpcf-info_link', true );?>">
				<div class="info">
					<h2><?php echo get_post_meta( $info->ID, 'wpcf-info_title', true );?></h2>	
					<img src="<?php echo get_post_meta( $info->ID, 'wpcf-img_info', true );?>">
					<p><?php echo get_post_meta( $info->ID, 'wpcf-info_info', true );?></p>
				</div>
			</a>
			<a href="<?php echo get_post_meta( $info->ID, 'wpcf-info_link_2', true );?>">
				<div class="info">
					<h2><?php echo get_post_meta( $info->ID, 'wpcf-info_title_2', true );?></h2>
					<img src="<?php echo get_post_meta( $info->ID, 'wpcf-img_info_2', true );?>">
					<p><?php echo get_post_meta( $info->ID, 'wpcf-info_info_2', true );?></p>
				</div>
			</a>
			<a href="<?php echo get_post_meta( $info->ID, 'wpcf-info_link_3', true );?>">
				<div class="info">
					<h2><?php echo get_post_meta( $info->ID, 'wpcf-info_title_3', true );?></h2>
					<img src="<?php echo get_post_meta( $info->ID, 'wpcf-img_info_3', true );?>">
					<p><?php echo get_post_meta( $info->ID, 'wpcf-info_info_3', true );?></p>
				</div>
			</a>
	
			<?php  endforeach; //end info ?>
			
			</section>
			
			
				<!-- copyright -->
				<p class="copyright">
					&copy; <?php echo date("Y"); ?> Copyright <?php bloginfo('name'); ?>. Powered by Yanai edri 
				</p>
				<!-- /copyright -->
				
			</footer>
			<!-- /footer -->
		
		</div>
		<!-- /wrapper -->

		<?php wp_footer(); ?>
		
		<!-- analytics -->
		<script>
			var _gaq=[['_setAccount','UA-XXXXXXXX-XX'],['_trackPageview']];
			(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
			g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g,s)})(document,'script');
		</script>
	
	</body>
</html>