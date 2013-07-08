<?php
/*
	Template Name:נסיון
*/
?>
<?php
get_header(); ?>

	<div id="primary" class="site-content">
		<?php

$defaults = array(
	'theme_location'  => '',
	'menu'            => '',
	'container'       => 'div',
	'container_class' => '',
	'container_id'    => '',
	'menu_class'      => 'hader',
	'menu_id'         => '',
	'echo'            => true,
	'fallback_cb'     => 'wp_page_menu',
	'before'          => '',
	'after'           => '',
	'link_before'     => '',
	'link_after'      => '',
	'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	'depth'           => 0,
	'walker'          => ''
);

wp_nav_menu( $defaults );

?>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>