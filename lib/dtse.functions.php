﻿<?php 

//Init translation
function dtse_init_locale(){
	$lang = DTSE_REL_URL . '/lang';
	load_plugin_textdomain('dtse', false, $lang);
}

// Load scripts into blog header
function dtse_front_init_step_one() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core', false, array('jquery'));
	wp_enqueue_script('jquery-ui-draggable', false, array('jquery', 'jquery-ui-core'));
	wp_enqueue_script('jquery-ui-droppable', false, array('jquery', 'jquery-ui-core'));
	wp_enqueue_script('dragtoshare-extended', DTSE_ABS_URL . 'js/wp-dragtoshare-extended.js', array('jquery', 'jquery-ui-core', 'jquery-ui-draggable','jquery-ui-droppable'), '1.0');
	return true;
}

// Load CSS into blog header
function dtse_front_init_step_two() {

	echo "\n<!-- Added by WP-DragToShare-eXtended Plugin -->\n";
	echo ' <link href="'. DTSE_ABS_URL .'css/wp-dragtoshare-extended.css" rel="stylesheet" type="text/css" media="screen" />'."\n";
	echo '<script type="text/javascript">
	jQuery(window).load(function(){
		dtsl.front.init();
		$dtsv.targetsLabel = \''.addslashes(get_option('dtse_share')).'\';
		$dtsv.tipsLabel = \''.addslashes(get_option('dtse_tooptips')).'\';
	}); </script>';
	echo "\n<!-- End of WP-DragToShare-eXtended Plugin -->\n\n";
	return true;
}

// Installing plugin for the first time : setting default value
function dtse_install() {

	$dtse_share = get_option('dtse_share');
	$dtse_tooptips = get_option('dtse_tooptips');
	
	if(empty($dtse_share)) update_option('dtse_tooptips', 'Drag this image to share the page');
	if(empty($dtse_tooptips)) update_option('dtse_share', 'Share on');
}

// Add a class to every image in a post
function dtse_add_class($content) {
	
	//Is there any images here ?
	$img_pattern = "/<img(.*?)\/>/";
	preg_match_all($img_pattern, $content, $matches);	
	$img_count = count($matches[1]);
	
	if($img_count > 0) {
		
		//Looking for an existing class="something" in post image
		$img_pattern = "/img class=\"([^\"]*)\"/";
		preg_match_all($img_pattern, $content, $matches);
		
		if(!empty($matches[1])) {
			
			// Appending a new classname to each images
			foreach($matches[1] as $match) {
				$replace = $match . ' dtse-img';
				$content = str_ireplace($match, $replace, $content);
			}
			
			//Freeing some memory
			unset($match, $matches, $replace, $img_pattern);
		
		//No class found, adding it !
		} else {
		
			$img_pattern = "/<img(.*?)\/>/";
			preg_match_all($img_pattern, $content, $matches);
			
			// Appending a full class="dtse-img" to each images
			foreach($matches[1] as $match) {
				$replace = ' class="dtse-img"'. $match;
				$content = str_ireplace($match, $replace, $content);
			}
			
			//Freeing some memory
			unset($match, $matches, $replace, $img_pattern);
		
		} // end of !empty($matches[1])
	
	} // end of $img_count > 0
	
	return $content;
}

// Return known sociable networks
/*
function dtse_known_networks() {

	$known = array(
		
		'del.icio.us' => array(
			'favicon' => 'delicious.png',
			'url' => 'http://delicious.com/post?url=PERMALINK&amp;title=TITLE&amp;notes=EXCERPT',
		),

		'Digg' => array(
			'favicon' => 'digg.png',
			'url' => 'http://digg.com/submit?phase=2&amp;url=PERMALINK&amp;title=TITLE&amp;bodytext=EXCERPT',
			'description' => 'Digg',
		),

		'Facebook' => array(
			'favicon' => 'facebook.png',
			'url' => 'http://www.facebook.com/share.php?u=PERMALINK&amp;t=TITLE',
		),

		'FriendFeed' => array(
			'favicon' => 'friendfeed.png',
			'url' => 'http://www.friendfeed.com/share?title=TITLE&amp;link=PERMALINK',
		),

		'LinkedIn' => array(
			'favicon' => 'linkedin.png',
			'url' => 'http://www.linkedin.com/shareArticle?mini=true&amp;url=PERMALINK&amp;title=TITLE&amp;source=BLOGNAME&amp;summary=EXCERPT',
		),

		'MySpace' => array(
			'favicon' => 'myspace.png',
			'url' => 'http://www.myspace.com/Modules/PostTo/Pages/?u=PERMALINK&amp;t=TITLE',
		),
		
		'Ping.fm' => array(
			'favicon' => 'ping.png',
			'url' => 'http://ping.fm/ref/?link=PERMALINK&amp;title=TITLE&amp;body=EXCERPT',
		),

		'Reddit' => array(
			'favicon' => 'reddit.png',
			'url' => 'http://reddit.com/submit?url=PERMALINK&amp;title=TITLE',
		),

		'StumbleUpon' => array(
			'favicon' => 'stumbleupon.png',
			'url' => 'http://www.stumbleupon.com/submit?url=PERMALINK&amp;title=TITLE',
		),
		
		'Technorati' => array(
			'favicon' => 'technorati.png',
			'url' => 'http://technorati.com/faves?add=PERMALINK',
		),
		
		'TwitThis' => array(
			'favicon' => 'twitter.png',
			'url' => 'http://twitter.com/home?status=PERMALINK',
		),

		'YahooBuzz' => array(
			'favicon' => 'yahoobuzz.png',
			'url' => 'http://buzz.yahoo.com/submit/?submitUrl=PERMALINK&amp;submitHeadline=TITLE&amp;submitSummary=EXCERPT&amp;submitCategory=science&amp;submitAssetType=text',
			'description' => 'Yahoo! Buzz',
		)

	);

return $known;
}
*/

?>