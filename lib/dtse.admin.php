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
  
  $dtse_position_label = 'dtse_position';
  $dtse_position_value = get_option($dtse_position_label);
  
  $dtse_permalink_label = 'dtse_permalink';
  $dtse_permalink_value = get_option($dtse_permalink_label);
  
  $dtse_auto_label = 'dtse_auto';
  $dtse_auto_value = get_option($dtse_auto_label);
  
  $dtse_network_label = 'dtse_network';
  $dtse_network_value = get_option($dtse_network_label);
  
  $dtse_hidden = 'dtse_hidden';
  
  // Form was posted ?
  if($_POST[$dtse_hidden] == 'Y') {
  
	$dtse_tooptips_value = strip_tags(stripslashes($_POST[$dtse_tooptips_label]));
	$dtse_share_value = strip_tags(stripslashes($_POST[$dtse_share_label]));
	$dtse_position_value = $_POST[$dtse_position_label];
	$dtse_permalink_value = $_POST[$dtse_permalink_label];
	$dtse_auto_value = $_POST[$dtse_auto_label];
	$dtse_network_value = $_POST[$dtse_network_label];
	
	update_option($dtse_tooptips_label, $dtse_tooptips_value);
	update_option($dtse_share_label, $dtse_share_value);
	update_option($dtse_position_label, $dtse_position_value);
	update_option($dtse_permalink_label, $dtse_permalink_value);
	update_option($dtse_auto_label, $dtse_auto_value);
	update_option($dtse_network_label, $dtse_network_value);
	
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
		
		<h3><?php _e("Behaviour", 'dtse'); ?></h3>
		
		<p><?php _e("Mode:", 'dtse'); ?>
			<select name="<?php echo $dtse_auto_label; ?>">
				<option value="true"<?php if($dtse_auto_value == 'true'):?> selected="selected"<?php endif;?>><?php _e("Automatic", 'dtse'); ?></option>
				<option value="false"<?php if($dtse_auto_value == 'false'):?> selected="selected"<?php endif;?>><?php _e("Manual", 'dtse'); ?></option>
			</select><br/>
			<?php _e('<strong>Automatic mode:</strong> Every images in posts become draggable', 'dtse'); ?>.<br/>
			<?php _e('<strong>Manual mode:</strong> Choose which images should be draggable using the <code>[dtse]</code> shortcode', 'dtse'); ?>.<br/>
			<small><?php _e('Manual mode example to use in your post:', 'dtse'); ?> <code>[dtse]&lt;img src="my_img.png" alt="My Img" /&gt;[/dtse]</code></small>
		</p>

		
		<p><?php _e("Social Networks:", 'dtse'); ?>
		
		<?php $known = dtse_all_networks(); ?>
			<ul style="display:block;width:500px;">
			<?php foreach($known as $key => $value): ?>
					<li style="display:inline-block;width:150px;">
						<input <?php if(array_key_exists($key, $dtse_network_value) && $dtse_network_value[$key] == 'on'): ?>checked="checked" <?php endif; ?>type="checkbox" name="<?php echo $dtse_network_label; ?>[<?php echo $key; ?>]" /> 
						<img style="vertical-align:middle;" src="<?php echo DTSE_ABS_URL.'img/admin/'.$known[$key]['favicon']; ?>" alt="<?php echo $key; ?>" /> <?php echo $key; ?>
					</li>
			<?php endforeach; ?>
			</ul>
		</p>


		<p><?php _e("Share posts permalink:", 'dtse'); ?>
			<select name="<?php echo $dtse_permalink_label; ?>">
				<option value="true"<?php if($dtse_permalink_value == 'true'):?> selected="selected"<?php endif;?>><?php _e("Yes", 'dtse'); ?></option>
				<option value="false"<?php if($dtse_permalink_value == 'false'):?> selected="selected"<?php endif;?>><?php _e("No", 'dtse'); ?></option>
			</select>
			<small>(<?php _e('ie. share posts permalinks when several posts are displayed, instead of current page', 'dtse'); ?>.)</small>
		</p>


		
		<h3><?php _e("Look'N'Feel", 'dtse' ); ?></h3>
		
		<p><?php _e("Tooltips Label:", 'dtse' ); ?> 
			<input type="text" name="<?php echo $dtse_tooptips_label; ?>" value="<?php echo $dtse_tooptips_value; ?>" size="50"> <small>(<?php _e('ie. the text displayed while rolling over an image', 'dtse'); ?>).</small>
		</p>
		
		
		<p><?php _e("Sharing label:", 'dtse' ); ?> 
			<input type="text" name="<?php echo $dtse_share_label; ?>" value="<?php echo $dtse_share_value; ?>" size="20"> <small>(<?php _e('ie. the text displayed while dropping on a social icon', 'dtse'); ?>).</small>
		</p>
		
		
		<p><?php _e("Sharing icons vertical positioning:", 'dtse'); ?>
			<select name="<?php echo $dtse_position_label; ?>">
				<option value="top"<?php if($dtse_position_value == 'top'):?> selected="selected"<?php endif;?>><?php _e("Top", 'dtse'); ?></option>
				<option value="middle"<?php if($dtse_position_value == 'middle'):?> selected="selected"<?php endif;?>><?php _e("Middle", 'dtse'); ?></option>
				<option value="bottom"<?php if($dtse_position_value == 'bottom'):?> selected="selected"<?php endif;?>><?php _e("Bottom", 'dtse'); ?></option>
			</select>
			<small>(<?php _e('ie. the vertical position you want social icons to appear', 'dtse'); ?>).</small>
		</p>
		

		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Update Options', 'dtse' ) ?>" />
		</p>

	</form>
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