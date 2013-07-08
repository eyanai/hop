<?php
/*  
Copyright 2010-2013 Arnan de Gans - AJdG Solutions (email : info@ajdg.net)
*/
?>
<h3><?php _e('This groups performance', 'adrotate'); ?></h3>

<?php
	$title		 	= $wpdb->get_var("SELECT `name` FROM `".$wpdb->prefix."adrotate_groups` WHERE `id` = '$group_edit_id';");
	$stats 			= $wpdb->get_row("SELECT SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats` WHERE `group` = '$group_edit_id';");
	$stats_today 	= $wpdb->get_row("SELECT `clicks`, `impressions` FROM `".$wpdb->prefix."adrotate_stats` WHERE `group` = '$group_edit_id' AND `thetime` = '$today';");

	// Get Click Through Rate
	$ctr = adrotate_ctr($stats->clicks, $stats->impressions);						

	// Prevent gaps in display
	if(empty($stats->impressions))		 	$stats->impressions 		= 0;
	if(empty($stats->clicks))	 			$stats->clicks 				= 0;
	if(empty($stats_today->impressions)) 	$stats_today->impressions 	= 0;
	if(empty($stats_today->clicks)) 		$stats_today->clicks 		= 0;

	if($adrotate_debug['stats'] == true) {
		echo "<p><strong>[DEBUG] Group (all time)</strong><pre>";
		$memory = (memory_get_usage() / 1024 / 1024);
		echo "Memory usage: " . round($memory, 2) ." MB <br />"; 
		$peakmemory = (memory_get_peak_usage() / 1024 / 1024);
		echo "Peak memory usage: " . round($peakmemory, 2) ." MB <br />"; 
		print_r($stats); 
		echo "</pre></p>"; 
		echo "<p><strong>[DEBUG] Group (today)</strong><pre>";
		print_r($stats_today); 
		echo "</pre></p>"; 
	}	

?>

<table class="widefat" style="margin-top: .5em">
	<thead>
	<tr>
		<th colspan="5" bgcolor="#DDD"><?php _e('Statistics for', 'adrotate'); ?> '<?php echo $title; ?>'</th>
	</tr>
	</thead>

	<tbody>
  	<tr>
        <td width="20%"><div class="stats_large"><?php _e('Impressions', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats->impressions; ?></div></div></td>
        <td width="20%"><div class="stats_large"><?php _e('Clicks', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats->clicks; ?></div></div></td>
        <td width="20%"><div class="stats_large"><?php _e('Impressions today', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_today->impressions; ?></div></div></td>
        <td width="20%"><div class="stats_large"><?php _e('Clicks today', 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_today->clicks; ?></div></div></td>
        <td width="20%"><div class="stats_large"><?php _e('CTR', 'adrotate'); ?><br /><div class="number_large"><?php echo $ctr.' %'; ?></div></div></td>
  	</tr>

	<thead>
	<tr>
		<th colspan="5" bgcolor="#DDD"><?php _e('Monthly overview of clicks and impressions', 'adrotate'); ?> '<?php echo $title; ?>'</th>
	</tr>
	</thead>

  	<tr>
        <th colspan="5">
        	<div style="text-align:center;"><?php echo adrotate_stats_nav('groups', $group_edit_id, $month, $year); ?></div>
        	<?php echo adrotate_stats_graph('groups', $group_edit_id, 1, $monthstart, $monthend); ?>
        </th>
  	</tr>
	</tbody>
	
	<thead>
	<tr>
		<th colspan="5" bgcolor="#DDD"><?php _e('Export options for', 'adrotate'); ?> '<?php echo $title; ?>'</th>
	</tr>
	</thead>
    <tbody>
    <tr>
		<td colspan="5">
			<p><?php adrotate_pro_notice(); ?></p>
			<p><em>Export these statistics as a CSV file. Download or email them.</em></p>
		</td>
	</tr>
	</tbody>
    <thead>
  	<tr>
		<th colspan="5">
			<b><?php _e('Note:', 'adrotate'); ?></b> <em><?php _e('All statistics are indicative. They do not nessesarily reflect results counted by other parties.', 'adrotate'); ?></em>
		</th>
  	</tr>
	</thead>
</table>
