<?php
add_action('admin_menu',function(){
	if(is_admin()){
			add_options_page('Menu Add','תוספות לתפריטים','administrator',__file__,function(){
		?>
		<div class="wrap">
			<?php screen_icon();?>
			<h2>ניהול סוגי פוסטים: </h2>
		
		</div>
		<?php
			});
	}
});
