<?php

//Load options page
function dtse_menu() {
	 add_options_page('DragToShare eXtended', 'DragToShare eXtended', 1, 'WP-DragToShare-eXtended', 'dtse_options');
}

//Display options page
function dtse_options() {

  //Loading options
  $dtse_tooptips_label = 'dtse_tooptips';
  $dtse_tooptips_value = strip_tags(stripslashes(get_option($dtse_tooptips_label)));
  
  $dtse_share_label = 'dtse_share';
  $dtse_share_value = strip_tags(stripslashes(get_option($dtse_share_label)));
  
  $dtse_hidden = 'dtse_hidden';
  
  // Form was posted ?
  if($_POST[$dtse_hidden] == 'Y') {
  
	$dtse_tooptips_value = strip_tags(stripslashes($_POST[$dtse_tooptips_label]));
	$dtse_share_value = strip_tags(stripslashes($_POST[$dtse_share_label]));
	
	update_option($dtse_tooptips_label, $dtse_tooptips_value);
	update_option($dtse_share_label, $dtse_share_value);
	
	?>
	<div class="updated"><p><strong><?php _e('Options saved.', 'dtse' ); ?></strong></p></div>
	<?php
  }
  

//Displaying regular form
 
  echo '<div class="wrap">';
  echo '<h2>'. __('Options &bull; Drag-To-Share eXtended', 'dtse') .'</h2>';
?>
  
	<form name="dtse" method="post" action="">
		<input type="hidden" name="<?php echo $dtse_hidden; ?>" value="Y">

		<p><?php _e("Tooltips Label:", 'dtse' ); ?> 
			<input type="text" name="<?php echo $dtse_tooptips_label; ?>" value="<?php echo $dtse_tooptips_value; ?>" size="50"> <small>(<?php _e('ie. the text displayed while rolling over an image', 'dtse'); ?>).</small>
		</p>
		
		<p><?php _e("Sharing label:", 'dtse' ); ?> 
			<input type="text" name="<?php echo $dtse_share_label; ?>" value="<?php echo $dtse_share_value; ?>" size="20"> <small>(<?php _e('ie. the text displayed while dropping on a social icon', 'dtse'); ?>).</small>
		</p>

		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Update Options', 'dtse' ) ?>" />
		</p>

	</form>
	<br/>
	<hr/>
	<strong><?php _e('Do you mind about our work ? Please support us !', 'dtse'); ?></strong>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="9246512">
		<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
	</form>

  
<?php
  echo '</div>';
}

?>