<?php 

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
	wp_enqueue_script('dragtoshare-extended', DTSE_ABS_URL . 'js/wp-dragtoshare-extended-packed.js', array('jquery', 'jquery-ui-core', 'jquery-ui-draggable','jquery-ui-droppable'), '1.1');
	return true;
}

// Load CSS into blog header
function dtse_front_init_step_two() {

	echo "\n<!-- Added by WP-DragToShare-eXtended Plugin -->\n";
	echo ' <link href="'. DTSE_ABS_URL .'css/wp-dragtoshare-extended-packed.css" rel="stylesheet" type="text/css" media="screen" />'."\n";
	echo '<script type="text/javascript">
	jQuery(window).load(function(){
		dtsv.root = \''.DTSE_ABS_URL.'\';
		dtsv.targetsLabel = \''.addslashes(get_option('dtse_share')).'\';
		dtsv.tipsLabel = \''.addslashes(get_option('dtse_tooptips')).'\';
		dtsv.targetsPosition = \''.get_option('dtse_position').'\';	
		dtsv.sharePermalink = '.get_option('dtse_permalink').';
		dtsv.networks = \''.dtse_get_social_conf().'\';
		dtsl.front.init();
	}); 
	</script>';
	echo "\n<!-- End of WP-DragToShare-eXtended Plugin -->\n\n";
	return true;
}

// Installing plugin for the first time or upgrading : setting default value
function dtse_install() {

	$dtse_share = get_option('dtse_share');
	$dtse_tooptips = get_option('dtse_tooptips');
	$dtse_position = get_option('dtse_position');
	$dtse_permalink = get_option('dtse_permalink');
	$dtse_auto = get_option('dtse_auto');
	$dtse_network = get_option('dtse_network');	
	$default_network = array(
		'Delicious' => 'on',
		'Facebook' 	=> 'on',
		'Twitter'	=> 'on'
	);
	
	if(empty($dtse_share)) update_option('dtse_tooptips', __("Drag this image to share the page", 'dtse'));
	if(empty($dtse_tooptips)) update_option('dtse_share', __("Share on", 'dtse'));
	if(empty($dtse_position)) update_option('dtse_position', 'top');
	if(empty($dtse_permalink)) update_option('dtse_permalink', 'true');
	if(empty($dtse_auto)) update_option('dtse_auto', 'true');
	if(empty($dtse_network)) update_option('dtse_network', $default_network);
}

// Add classes to every image in a post
function dtse_add_class($content) {
	
	//Is there any images here ?
	$img_pattern = "/<img(.*?)\/>/i";
	preg_match_all($img_pattern, $content, $matches);	
	$img_count = count($matches[1]);
	
	if($img_count > 0) {
		
		//Looking for an existing class="something" in post image
		$img_pattern = "/img class=\"([^\"]*)\"/i";
		preg_match_all($img_pattern, $content, $matches);
		
		if(!empty($matches[1])) {
			
			// Appending a new classname to each images
			foreach($matches[1] as $match) {
				$replace = $match . ' dtse-img dtse-post-'.get_the_ID();
				$content = str_replace($match, $replace, $content);
			}
			
			//Freeing some memory
			unset($match, $matches, $replace, $img_pattern);
		
		//No class found, adding it !
		} else {
		
			$img_pattern = "/<img(.*?)\/>/i";
			preg_match_all($img_pattern, $content, $matches);
			
			// Appending a full class="dtse-img" to each images
			foreach($matches[1] as $match) {
				$replace = ' class="dtse-img dtse-post-'.get_the_ID().'"'. $match;
				$content = str_replace($match, $replace, $content);
			}
			
			//Freeing some memory
			unset($match, $matches, $replace, $img_pattern);
		
		} // end of !empty($matches[1])
	
	} // end of $img_count > 0
	
	return $content;
}

// Full article
function dtse_content_filter($content) {

	$content = dtse_add_class($content);
	$content = dtse_content_script($content);
	
	return $content;
}

// Appending script to post content
function dtse_content_script($content) {

	global $permalink;

	if($permalink == 'true')
	{
		//Sanitizing title
		$title = strip_tags(get_the_title());
		$title = html_entity_decode($title, ENT_QUOTES, get_bloginfo('charset'));
		$title = addslashes($title);

		$content .= "\n\n
		<!-- Added by WP-DragToShare-eXtended Plugin -->
		<script type=\"text/javascript\">
			dtsv.dtse_post_".get_the_ID()."_permalink = '".get_permalink()."';
			dtsv.dtse_post_".get_the_ID()."_title = '".$title."';
		</script>
		<!-- End of WP-DragToShare-eXtended Plugin -->";
	}
	
	return $content;
}

// Handling img between shorcode
function dtse_shortcode($atts, $content = null) {
	return dtse_add_class($content);
}

// Placebo function for handling img between shorcode while automatic mode is enabled
function dtse_no_shortcode($atts, $content = null) {
	return $content;
}

// Just include admin lib
function dtse_load_admin() {
	include_once(DTSE_ABS_PATH .'lib/dtse.admin.php');
}

// Check URL
function dtse_is_valid_URL($url) {
	return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

// Return shortened URL
function dtse_is_gd($longUrl) {

	//Curl is available ?
	if(function_exists("curl_init")) {
	
		//content provided is a valid URL ?
		if((!empty($longUrl)) && (is_string($longUrl)) && (dtse_is_valid_URL($longUrl))){
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://is.gd/api.php?longurl='.$longUrl);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
			$result = curl_exec($ch);
			curl_close($ch);
			
			// Result provided is a valid URL ?
			if((!empty($result)) && (is_string($result)) && (dtse_is_valid_URL($result))) {
				echo $result;
			}
			
		} else {
			header("HTTP/1.1 404 Not Found");
		}
	
	}
}

// Get and Build social networks list
function dtse_get_social_conf() {

	$myConf = get_option('dtse_network');
	$allNetworks = dtse_all_networks();
	
	$html = '<ul id="dtse-targets">';
	
	foreach($myConf as $k => $v) {
		$html .= '<li id="'.$k.'">';
		$html .= '<a href="'.$allNetworks[$k]['url'].'"><!-- --></a>';
		$html .= '</li>';
	}
	
	$html .= '</ul>';
	
	return $html;
}

// Return Supported Social Networks
function dtse_all_networks() {

$known = array(
	
	'Delicious' => array(
		'favicon' 	=> 'delicious.png',
		'url' 		=> 'http://delicious.com/'
	),

	'Digg' => array(
		'favicon' 	=> 'digg.png',
		'url' 		=> 'http://digg.com/'
	),

	'Facebook' => array(
		'favicon' 	=> 'facebook.png',
		'url' 		=> 'http://www.facebook.com/'
	),

	'LinkedIn' => array(
		'favicon' 	=> 'linkedin.png',
		'url' 		=> 'http://www.linkedin.com/'
	),

	'MySpace' => array(
		'favicon' 	=> 'myspace.png',
		'url' 		=> 'http://www.myspace.com/'
	),
	
	'Reddit' => array(
		'favicon' 	=> 'reddit.png',
		'url' 		=> 'http://reddit.com/'
	),

	'StumbleUpon' => array(
		'favicon' 	=> 'stumbleupon.png',
		'url' 		=> 'http://www.stumbleupon.com/'
	),
	
	'Technorati' => array(
		'favicon' 	=> 'technorati.png',
		'url' 		=> 'http://technorati.com/'
	),
	
	'Twitter' => array(
		'favicon' 	=> 'twitter.png',
		'url' 		=> 'http://twitter.com/'
	)

);

return $known;

}

?>